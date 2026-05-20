<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Libraries\JwtAuth;

class AuthController extends BaseController
{
    protected $userModel;
    protected $profileModel;
    protected $jwt;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profileModel = new UserProfileModel();
        $this->jwt = new JwtAuth();
    }

    public function register()
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[seller,buyer,both]',
            'identification' => 'permit_empty|max_length[20]',
            'business_name' => 'permit_empty|max_length[200]',
            'country_code' => 'permit_empty|max_length[10]',
            'accept_terms' => 'required|in_list[1,true]',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $userId = $this->userModel->insert([
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email_verified' => false,
            'email_token' => bin2hex(random_bytes(32)),
            'token_expires' => date('Y-m-d H:i:s', strtotime('+24 hours')),
        ]);

        $this->profileModel->insert([
            'user_id' => $userId,
            'identification' => $data['identification'] ?? null,
            'business_name' => $data['business_name'] ?? null,
            'country_code' => $data['country_code'] ?? null,
        ]);

        $token = $this->jwt->generateToken(['id' => $userId, 'email' => $data['email']]);

        return $this->response->setStatusCode(201)->setJSON([
            'message' => 'User registered successfully',
            'token' => $token,
            'user_id' => $userId,
        ]);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $user = $this->userModel->where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'error' => 'Invalid credentials'
            ]);
        }

        if (!$user['email_verified']) {
            return $this->response->setStatusCode(403)->setJSON([
                'error' => 'Email not verified. Please verify your email.',
                'resend_verification' => true
            ]);
        }

        $profile = $this->profileModel->where('user_id', $user['id'])->first();

        $token = $this->jwt->generateToken([
            'id' => $user['id'],
            'email' => $user['email'],
        ]);

        return $this->response->setJSON([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'email_verified' => $user['email_verified'],
                'profile' => $profile,
            ]
        ]);
    }

    public function me()
    {
        $userData = $this->request->getUser();
        
        $user = $this->userModel->find($userData['id']);
        $profile = $this->profileModel->where('user_id', $userData['id'])->first();

        return $this->response->setJSON([
            'id' => $user['id'],
            'email' => $user['email'],
            'email_verified' => $user['email_verified'],
            'profile' => $profile,
        ]);
    }

    public function verifyEmail($token = null)
    {
        if (!$token) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Token is required'
            ]);
        }

        $user = $this->userModel->where('email_token', $token)->first();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'Invalid token'
            ]);
        }

        if (strtotime($user['token_expires']) < time()) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Token expired'
            ]);
        }

        $this->userModel->update($user['id'], [
            'email_verified' => true,
            'email_token' => null,
            'token_expires' => null,
        ]);

        return $this->response->setJSON([
            'message' => 'Email verified successfully'
        ]);
    }

    public function resendVerification()
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Email is required'
            ]);
        }

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => 'User not found'
            ]);
        }

        if ($user['email_verified']) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Email already verified'
            ]);
        }

        $this->userModel->update($user['id'], [
            'email_token' => bin2hex(random_bytes(32)),
            'token_expires' => date('Y-m-d H:i:s', strtotime('+24 hours')),
        ]);

        return $this->response->setJSON([
            'message' => 'Verification email resent'
        ]);
    }

    public function changePassword()
    {
        $userData = $this->request->getUser();
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
        ]);

        if (!$validation->run($data)) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }

        $user = $this->userModel->find($userData['id']);

        if (!password_verify($data['current_password'], $user['password_hash'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Current password is incorrect'
            ]);
        }

        $this->userModel->update($user['id'], [
            'password_hash' => password_hash($data['new_password'], PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON([
            'message' => 'Password changed successfully'
        ]);
    }
}