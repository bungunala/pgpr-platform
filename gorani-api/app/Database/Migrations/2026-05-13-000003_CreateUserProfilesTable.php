<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'identification' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'business_name' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'mobile' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'address' => ['type' => 'TEXT', 'null' => true],
            'website' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'foundation_year' => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'employees_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'country_code' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'description_es' => ['type' => 'TEXT', 'null' => true],
            'description_en' => ['type' => 'TEXT', 'null' => true],
            'logo_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('user_profiles');
    }
}