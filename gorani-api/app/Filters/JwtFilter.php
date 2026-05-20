<?php

namespace App\Filters;

use CodeIgniter\Filters\BaseFilter;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JwtAuth;

class JwtFilter extends BaseFilter
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtAuth = new JwtAuth();
        
        $authHeader = $request->getHeader('Authorization');
        
        if (!$authHeader) {
            return response()->setStatusCode(401)->setJSON(['error' => 'No token provided']);
        }
        
        $token = str_replace('Bearer ', '', $authHeader->getValue());
        
        $decoded = $jwtAuth->validateToken($token);
        
        if (!$decoded) {
            return response()->setStatusCode(401)->setJSON(['error' => 'Invalid token']);
        }
        
        $request->setUserData((array) $decoded);
        
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}