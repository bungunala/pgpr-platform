<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectorsAndSubpartidasTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sectors');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sector_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sector_id', 'sectors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sub_sectors');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20],
            'description_es' => ['type' => 'VARCHAR', 'constraint' => 255],
            'description_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('subpartidas');
    }

    public function down()
    {
        $this->forge->dropTable('subpartidas');
        $this->forge->dropTable('sub_sectors');
        $this->forge->dropTable('sectors');
    }
}