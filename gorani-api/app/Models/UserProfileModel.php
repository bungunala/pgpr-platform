<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table = 'user_profiles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id',
        'identification',
        'business_name',
        'phone',
        'mobile',
        'address',
        'website',
        'foundation_year',
        'employees_number',
        'country_code',
        'city',
        'description_es',
        'description_en',
        'logo_url',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getProfileWithUser($profileId)
    {
        $profile = $this->find($profileId);
        if ($profile) {
            $userModel = new UserModel();
            $user = $userModel->find($profile['user_id']);
            $profile['user'] = $user;
        }
        return $profile;
    }

    public function getProfileByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }

    public function updateProfile($userId, $data)
    {
        return $this->where('user_id', $userId)->update(null, $data);
    }
}