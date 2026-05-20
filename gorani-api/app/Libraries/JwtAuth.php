<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Config\Services;

class JwtAuth
{
    private $secretKey;
    private $algorithm = 'HS256';
    private $ttl = 86400;

    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET') ?: 'change-this-secret-key';
    }

    public function generateToken($userData)
    {
        $issuedAt = time();
        $expiration = $issuedAt + $this->ttl;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'data' => [
                'id' => $userData['id'],
                'email' => $userData['email'],
            ]
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserFromToken($token)
    {
        $decoded = $this->validateToken($token);
        
        if ($decoded && isset($decoded['data'])) {
            return $decoded['data'];
        }
        
        return null;
    }

    public function refreshToken($token)
    {
        $decoded = $this->validateToken($token);
        
        if ($decoded) {
            return $this->generateToken($decoded['data']);
        }
        
        return false;
    }
}