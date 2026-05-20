<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\EventRegistrationModel;

class EventsController extends BaseController
{
    protected $eventModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->registrationModel = new EventRegistrationModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        if ($status === 'registration') {
            $events = $this->eventModel->getActiveEvents();
        } elseif ($status === 'schedule') {
            $events = $this->eventModel->getEventsInSchedule();
        } else {
            $events = $this->eventModel->findAll();
        }

        return $this->response->setJSON($events);
    }

    public function show($id = null)
    {
        $event = $this->eventModel->getEventWithSectors($id);

        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Event not found'
            ]);
        }

        return $this->response->setJSON($event);
    }

    public function myRegistrations()
    {
        $userData = $this->request->getUser();
        
        $registrations = $this->registrationModel->getUserRegistrations($userData['id']);
        
        $events = [];
        foreach ($registrations as $reg) {
            $event = $this->eventModel->getEventWithSectors($reg['event_id']);
            $event['registration'] = $reg;
            $events[] = $event;
        }

        return $this->response->setJSON($events);
    }

    public function register($eventId)
    {
        $userData = $this->request->getUser();
        $data = $this->request->getJSON(true);

        $event = $this->eventModel->find($eventId);
        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Event not found'
            ]);
        }

        if (!$event['status_registration']) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Registration is closed for this event'
            ]);
        }

        $today = date('Y-m-d');
        if ($today < $event['registration_start'] || $today > $event['registration_end']) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Registration period has ended'
            ]);
        }

        $existingReg = $this->registrationModel->where('user_id', $userData['id'])
            ->where('event_id', $eventId)
            ->first();

        if ($existingReg) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Already registered in this event'
            ]);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'role' => 'required|in_list[seller,buyer,both]',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $registrationId = $this->registrationModel->insert([
            'user_id' => $userData['id'],
            'event_id' => $eventId,
            'role' => $data['role'],
            'status' => 'pending',
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'Registered successfully',
            'registration_id' => $registrationId,
            'status' => 'pending'
        ]);
    }

    public function getSchedule($eventId)
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Event not found'
            ]);
        }

        $days = $this->db->table('event_days')
            ->where('event_id', $eventId)
            ->orderBy('day_number')
            ->get()
            ->getResultArray();

        foreach ($days as &$day) {
            $schedules = $this->db->table('event_schedules')
                ->where('event_day_id', $day['id'])
                ->orderBy('slot_number')
                ->get()
                ->getResultArray();
            $day['schedules'] = $schedules;
        }

        return $this->response->setJSON([
            'event' => $event,
            'days' => $days
        ]);
    }

    public function getStatistics($eventId)
    {
        $totalSellers = $this->registrationModel->where('event_id', $eventId)
            ->whereIn('role', ['seller', 'both'])
            ->countAllResults();
        
        $totalBuyers = $this->registrationModel->where('event_id', $eventId)
            ->whereIn('role', ['buyer', 'both'])
            ->countAllResults();

        $approvedSellers = $this->registrationModel->where('event_id', $eventId)
            ->whereIn('role', ['seller', 'both'])
            ->where('status', 'approved')
            ->countAllResults();

        $approvedBuyers = $this->registrationModel->where('event_id', $eventId)
            ->whereIn('role', ['buyer', 'both'])
            ->where('status', 'approved')
            ->countAllResults();

        return $this->response->setJSON([
            'sellers' => [
                'total' => $totalSellers,
                'approved' => $approvedSellers,
            ],
            'buyers' => [
                'total' => $totalBuyers,
                'approved' => $approvedBuyers,
            ]
        ]);
    }
}