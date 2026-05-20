<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventRegistrationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'role' => ['type' => 'VARCHAR', 'constraint' => 20],
            'desk_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'approved_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'approved_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'registered_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'event_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_registrations');
    }

    public function down()
    {
        $this->forge->dropTable('event_registrations');
    }
}