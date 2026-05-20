<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table = 'event_registrations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'event_id',
        'role',
        'desk_number',
        'status',
        'approved_by',
        'approved_at',
        'registered_at',
    ];
    protected $useTimestamps = false;
    protected $createdField = 'registered_at';

    public function getUserRegistrations($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getEventRegistrations($eventId, $role = null)
    {
        $builder = $this->where('event_id', $eventId);
        
        if ($role) {
            $builder->where('role', $role);
        }
        
        return $builder->findAll();
    }

    public function getRegistrationWithUser($registrationId)
    {
        $reg = $this->find($registrationId);
        if ($reg) {
            $userModel = new UserModel();
            $profileModel = new UserProfileModel();
            
            $user = $userModel->find($reg['user_id']);
            $profile = $profileModel->getProfileByUserId($reg['user_id']);
            
            $reg['user'] = $user;
            $reg['profile'] = $profile;
        }
        return $reg;
    }

    public function approveRegistration($registrationId, $adminUserId)
    {
        return $this->update($registrationId, [
            'status' => 'approved',
            'approved_by' => $adminUserId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function rejectRegistration($registrationId, $adminUserId)
    {
        return $this->update($registrationId, [
            'status' => 'rejected',
            'approved_by' => $adminUserId,
            'approved_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getApprovedRegistrations($eventId)
    {
        return $this->where('event_id', $eventId)
            ->where('status', 'approved')
            ->findAll();
    }
}