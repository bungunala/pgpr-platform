<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\EventRegistrationModel;

class AdminController extends BaseController
{
    protected $eventModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->registrationModel = new EventRegistrationModel();
    }

    public function createEvent()
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name_es' => 'required|max_length[200]',
            'registration_start' => 'required|valid_date',
            'registration_end' => 'required|valid_date',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $eventId = $this->eventModel->insert($data);

        if (isset($data['sectors'])) {
            foreach ($data['sectors'] as $sectorId) {
                $this->eventModel->addSector($eventId, $sectorId);
            }
        }

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Event created successfully',
            'event_id' => $eventId
        ]);
    }

    public function updateEvent($id)
    {
        $data = $this->request->getJSON(true);

        $event = $this->eventModel->find($id);
        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Event not found'
            ]);
        }

        $this->eventModel->update($id, $data);

        if (isset($data['sectors'])) {
            $this->db->table('event_sectors')->where('event_id', $id)->delete();
            foreach ($data['sectors'] as $sectorId) {
                $this->eventModel->addSector($id, $sectorId);
            }
        }

        return $this->response->setJSON([
            'message' => 'Event updated successfully'
        ]);
    }

    public function updateEventStatus($id)
    {
        $data = $this->request->getJSON(true);

        $event = $this->eventModel->find($id);
        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Event not found'
            ]);
        }

        if (isset($data['status_registration'])) {
            $this->eventModel->update($id, [
                'status_registration' => $data['status_registration']
            ]);
        }

        if (isset($data['status_schedule'])) {
            if ($data['status_schedule'] === true) {
                $hasSchedule = $this->db->table('event_days')
                    ->where('event_id', $id)
                    ->countAllResults();

                if ($hasSchedule === 0) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'error' => 'Cannot enable scheduling: no schedule loaded for this event'
                    ]);
                }
            }

            $this->eventModel->update($id, [
                'status_schedule' => $data['status_schedule']
            ]);
        }

        if (isset($data['credentials_enabled'])) {
            $this->eventModel->update($id, [
                'credentials_enabled' => $data['credentials_enabled']
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Event status updated successfully'
        ]);
    }

    public function getEventUsers($eventId)
    {
        $role = $this->request->getGet('role');
        
        $registrations = $this->registrationModel->getEventRegistrations($eventId, $role);

        $users = [];
        foreach ($registrations as $reg) {
            $userData = $this->registrationModel->getRegistrationWithUser($reg['id']);
            $users[] = $userData;
        }

        return $this->response->setJSON($users);
    }

    public function approveUser($eventId, $userId)
    {
        $registration = $this->registrationModel
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if (!$registration) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Registration not found'
            ]);
        }

        $adminUser = $this->request->getUser();
        
        $this->registrationModel->approveRegistration($registration['id'], $adminUser['id']);

        return $this->response->setJSON([
            'message' => 'User approved successfully'
        ]);
    }

    public function rejectUser($eventId, $userId)
    {
        $registration = $this->registrationModel
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if (!$registration) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Registration not found'
            ]);
        }

        $adminUser = $this->request->getUser();
        
        $this->registrationModel->rejectRegistration($registration['id'], $adminUser['id']);

        return $this->response->setJSON([
            'message' => 'User rejected successfully'
        ]);
    }

    public function getSellersReport($eventId)
    {
        $sellers = $this->registrationModel
            ->select('event_registrations.*, users.email, user_profiles.*')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('user_profiles', 'user_profiles.user_id = event_registrations.user_id', 'left')
            ->where('event_registrations.event_id', $eventId)
            ->whereIn('event_registrations.role', ['seller', 'both'])
            ->get()
            ->getResultArray();

        return $this->response->setJSON($sellers);
    }

    public function getBuyersReport($eventId)
    {
        $buyers = $this->registrationModel
            ->select('event_registrations.*, users.email, user_profiles.*')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('user_profiles', 'user_profiles.user_id = event_registrations.user_id', 'left')
            ->where('event_registrations.event_id', $eventId)
            ->whereIn('event_registrations.role', ['buyer', 'both'])
            ->get()
            ->getResultArray();

        return $this->response->setJSON($buyers);
    }

    public function getAgendaReport($eventId)
    {
        $meetings = $this->db->table('meetings')
            ->select('meetings.*, 
                seller.email as seller_email, seller_profile.business_name as seller_company,
                buyer.email as buyer_email, buyer_profile.business_name as buyer_company,
                event_days.name_es as day_name, event_schedules.name_es as slot_name,
                event_schedules.start_time, event_schedules.end_time')
            ->join('users as seller', 'seller.id = meetings.seller_user_id')
            ->join('users as buyer', 'buyer.id = meetings.buyer_user_id')
            ->join('user_profiles as seller_profile', 'seller_profile.user_id = meetings.seller_user_id', 'left')
            ->join('user_profiles as buyer_profile', 'buyer_profile.user_id = meetings.buyer_user_id', 'left')
            ->join('event_days', 'event_days.id = meetings.day_id')
            ->join('event_schedules', 'event_schedules.id = meetings.schedule_id')
            ->where('meetings.event_id', $eventId)
            ->where('meetings.status', 'accepted')
            ->orderBy('event_days.date')
            ->orderBy('event_schedules.start_time')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($meetings);
    }

    public function addScheduleDay($eventId)
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'day_number' => 'required|integer',
            'name_es' => 'required|max_length[100]',
            'date' => 'required|valid_date',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $data['event_id'] = $eventId;
        $dayId = $this->db->table('event_days')->insert($data);

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Day added successfully',
            'day_id' => $dayId
        ]);
    }

    public function updateScheduleDay($eventId, $dayId)
    {
        $data = $this->request->getJSON(true);

        $this->db->table('event_days')
            ->where('id', $dayId)
            ->where('event_id', $eventId)
            ->update($data);

        return $this->response->setJSON([
            'message' => 'Day updated successfully'
        ]);
    }

    public function deleteScheduleDay($eventId, $dayId)
    {
        $this->db->table('event_schedules')
            ->where('event_day_id', $dayId)
            ->delete();

        $this->db->table('event_days')
            ->where('id', $dayId)
            ->where('event_id', $eventId)
            ->delete();

        return $this->response->setJSON([
            'message' => 'Day deleted successfully'
        ]);
    }

    public function addScheduleSlot($eventId, $dayId)
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'slot_number' => 'required|integer',
            'name_es' => 'required|max_length[50]',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'permit_empty|integer',
            'is_break' => 'permit_empty|in_list[0,1,true,false]',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $data['event_day_id'] = $dayId;
        $slotId = $this->db->table('event_schedules')->insert($data);

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Slot added successfully',
            'slot_id' => $slotId
        ]);
    }

    public function updateScheduleSlot($eventId, $slotId)
    {
        $data = $this->request->getJSON(true);

        $this->db->table('event_schedules')
            ->where('id', $slotId)
            ->update($data);

        return $this->response->setJSON([
            'message' => 'Slot updated successfully'
        ]);
    }

    public function deleteScheduleSlot($eventId, $slotId)
    {
        $this->db->table('event_schedules')
            ->where('id', $slotId)
            ->delete();

        return $this->response->setJSON([
            'message' => 'Slot deleted successfully'
        ]);
    }
}