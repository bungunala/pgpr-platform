<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserProfileModel;

class ProfileController extends BaseController
{
    protected $profileModel;

    public function __construct()
    {
        $this->profileModel = new UserProfileModel();
    }

    public function show()
    {
        $userData = $this->request->getUser();
        $profile = $this->profileModel->getProfileByUserId($userData['id']);

        if (!$profile) {
            $this->profileModel->insert([
                'user_id' => $userData['id'],
            ]);
            $profile = $this->profileModel->getProfileByUserId($userData['id']);
        }

        return $this->response->setJSON($profile);
    }

    public function update()
    {
        $userData = $this->request->getUser();
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'identification' => 'permit_empty|max_length[20]',
            'business_name' => 'permit_empty|max_length[200]',
            'phone' => 'permit_empty|max_length[20]',
            'mobile' => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
            'website' => 'permit_empty|max_length[200]',
            'foundation_year' => 'permit_empty|max_length[4]',
            'employees_number' => 'permit_empty|max_length[20]',
            'country_code' => 'permit_empty|max_length[10]',
            'city' => 'permit_empty|max_length[100]',
            'description_es' => 'permit_empty',
            'description_en' => 'permit_empty',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $profile = $this->profileModel->getProfileByUserId($userData['id']);

        if (!$profile) {
            $data['user_id'] = $userData['id'];
            $this->profileModel->insert($data);
        } else {
            $this->profileModel->update($profile['id'], $data);
        }

        $updatedProfile = $this->profileModel->getProfileByUserId($userData['id']);

        return $this->response->setJSON([
            'message' => 'Profile updated successfully',
            'profile' => $updatedProfile
        ]);
    }

    public function uploadLogo()
    {
        $userData = $this->request->getUser();
        
        $file = $this->request->getFile('logo');
        
        if (!$file->isValid()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => $file->getErrorString()
            ]);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Only JPG and PNG files are allowed'
            ]);
        }

        $newName = $userData['id'] . '_logo_' . time() . '.' . $file->getExtension();
        
        $uploadPath = WRITEPATH . 'uploads/logos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $newName);

        $logoUrl = '/uploads/logos/' . $newName;

        $profile = $this->profileModel->getProfileByUserId($userData['id']);
        
        if (!$profile) {
            $this->profileModel->insert([
                'user_id' => $userData['id'],
                'logo_url' => $logoUrl,
            ]);
        } else {
            $this->profileModel->update($profile['id'], [
                'logo_url' => $logoUrl
            ]);
        }

        return $this->response->setJSON([
            'message' => 'Logo uploaded successfully',
            'logo_url' => $logoUrl
        ]);
    }
}