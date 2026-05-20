<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class CatalogsController extends BaseController
{
    public function countries()
    {
        $countries = $this->db->table('catalog_countries')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($countries);
    }

    public function languages()
    {
        $languages = $this->db->table('catalog_languages')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($languages);
    }

    public function certificates()
    {
        $certificates = $this->db->table('catalog_certificates')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($certificates);
    }

    public function greetings()
    {
        $greetings = $this->db->table('catalog_greetings')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($greetings);
    }

    public function units()
    {
        $units = $this->db->table('catalog_units')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($units);
    }

    public function sectors()
    {
        $sectors = $this->db->table('sectors')
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($sectors);
    }

    public function subSectors($sectorId = null)
    {
        if (!$sectorId) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Sector ID is required'
            ]);
        }

        $subSectors = $this->db->table('sub_sectors')
            ->where('sector_id', $sectorId)
            ->orderBy('name_es')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($subSectors);
    }

    public function subpartidas()
    {
        $query = $this->request->getGet('q');

        $builder = $this->db->table('subpartidas');

        if ($query) {
            $builder->like('code', $query, 'both')
                ->orLike('description_es', $query, 'both')
                ->orLike('description_en', $query, 'both');
        }

        $subpartidas = $builder->limit(20)->get()->getResultArray();

        return $this->response->setJSON($subpartidas);
    }
}