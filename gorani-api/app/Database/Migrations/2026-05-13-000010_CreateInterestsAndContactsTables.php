<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInterestsAndContactsTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sector_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('buyer_interests');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'greeting_code' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'last_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'position' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'mobile' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'skype' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contacts');

        $this->forge->addField([
            'contact_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'language_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('contact_id', 'contacts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('language_id', 'catalog_languages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['contact_id', 'language_id'], true);
        $this->forge->createTable('contact_languages');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'contact_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['contact_id', 'event_id']);
        $this->forge->addForeignKey('contact_id', 'contacts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('event_contacts');
    }

    public function down()
    {
        $this->forge->dropTable('event_contacts');
        $this->forge->dropTable('contact_languages');
        $this->forge->dropTable('contacts');
        $this->forge->dropTable('buyer_interests');
    }
}