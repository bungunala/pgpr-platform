<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'name_es',
        'name_en',
        'description_es',
        'description_en',
        'image_url',
        'registration_start',
        'registration_end',
        'schedule_start',
        'schedule_end',
        'schedule_mode',
        'status_registration',
        'status_schedule',
        'credentials_enabled',
        'has_desk_numbers',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getEventWithSectors($eventId)
    {
        $event = $this->find($eventId);
        if ($event) {
            $sectors = $this->db->table('event_sectors')
                ->select('sectors.*')
                ->join('sectors', 'sectors.id = event_sectors.sector_id')
                ->where('event_sectors.event_id', $eventId)
                ->get()
                ->getResultArray();
            $event['sectors'] = $sectors;
        }
        return $event;
    }

    public function getActiveEvents()
    {
        return $this->where('status_registration', true)->findAll();
    }

    public function getEventsInSchedule()
    {
        return $this->where('status_schedule', true)->findAll();
    }

    public function addSector($eventId, $sectorId)
    {
        return $this->db->table('event_sectors')->insert([
            'event_id' => $eventId,
            'sector_id' => $sectorId,
        ]);
    }

    public function removeSector($eventId, $sectorId)
    {
        return $this->db->table('event_sectors')
            ->where('event_id', $eventId)
            ->where('sector_id', $sectorId)
            ->delete();
    }
}