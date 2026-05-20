<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMeetingsAndCredentialsTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'seller_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'buyer_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'schedule_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'day_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'requested_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'requested_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'responded_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'responded_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'alternative_day_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'alternative_schedule_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'accepted_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'accepted_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'rejected_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'rejected_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'cancellation_reason' => ['type' => 'TEXT', 'null' => true],
            'cancelled_by' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'cancelled_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seller_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('buyer_user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'event_schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('day_id', 'event_days', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('meetings');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'contact_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => 20],
            'desk_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'qr_code' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'printed_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('contact_id', 'contacts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('credentials');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 50],
            'subject_es' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'subject_en' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'body_es' => ['type' => 'TEXT', 'null' => true],
            'body_en' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['event_id', 'code']);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('email_templates');
    }

    public function down()
    {
        $this->forge->dropTable('email_templates');
        $this->forge->dropTable('credentials');
        $this->forge->dropTable('meetings');
    }
}