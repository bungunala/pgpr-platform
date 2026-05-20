<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'email',
        'password_hash',
        'email_verified',
        'email_token',
        'token_expires',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getUserWithProfile($userId)
    {
        $user = $this->find($userId);
        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        $user['profile'] = $profile;
        return $user;
    }

    public function getUsersByRole($roleName)
    {
        return $this->select('users.*')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('roles.name', $roleName)
            ->findAll();
    }
}