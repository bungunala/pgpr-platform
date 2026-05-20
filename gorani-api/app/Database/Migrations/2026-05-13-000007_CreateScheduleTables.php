<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateScheduleTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'day_number' => ['type' => 'INT', 'constraint' => 11],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'date' => ['type' => 'DATE'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_days');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_day_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'slot_number' => ['type' => 'INT', 'constraint' => 11],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'start_time' => ['type' => 'TIME'],
            'end_time' => ['type' => 'TIME'],
            'duration_minutes' => ['type' => 'INT', 'constraint' => 11, 'default' => 30],
            'is_break' => ['type' => 'BOOLEAN', 'default' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_day_id', 'event_days', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_schedules');
    }

    public function down()
    {
        $this->forge->dropTable('event_schedules');
        $this->forge->dropTable('event_days');
    }
}