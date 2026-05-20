<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return response()->setJSON([
            'message' => 'PGPR Platform API',
            'version' => '1.0.0',
            'status' => 'running'
        ]);
    }
}