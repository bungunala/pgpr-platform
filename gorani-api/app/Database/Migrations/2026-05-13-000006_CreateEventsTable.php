<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 200],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'description_es' => ['type' => 'TEXT', 'null' => true],
            'description_en' => ['type' => 'TEXT', 'null' => true],
            'image_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'registration_start' => ['type' => 'DATE'],
            'registration_end' => ['type' => 'DATE'],
            'schedule_start' => ['type' => 'DATE', 'null' => true],
            'schedule_end' => ['type' => 'DATE', 'null' => true],
            'schedule_mode' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'request'],
            'status_registration' => ['type' => 'BOOLEAN', 'default' => false],
            'status_schedule' => ['type' => 'BOOLEAN', 'default' => false],
            'credentials_enabled' => ['type' => 'BOOLEAN', 'default' => false],
            'has_desk_numbers' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'none'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('events');

        $this->forge->addField([
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sector_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sector_id', 'sectors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['event_id', 'sector_id'], true);
        $this->forge->createTable('event_sectors');
    }

    public function down()
    {
        $this->forge->dropTable('event_sectors');
        $this->forge->dropTable('events');
    }
}