<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCatalogTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('code', true);
        $this->forge->createTable('catalog_countries');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('catalog_languages');

        $this->forge->addField([
            'code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('code', true);
        $this->forge->createTable('catalog_greetings');

        $this->forge->addField([
            'code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 50],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('code', true);
        $this->forge->createTable('catalog_units');

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name_es' => ['type' => 'VARCHAR', 'constraint' => 100],
            'name_en' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('catalog_certificates');
    }

    public function down()
    {
        $this->forge->dropTable('catalog_certificates');
        $this->forge->dropTable('catalog_units');
        $this->forge->dropTable('catalog_greetings');
        $this->forge->dropTable('catalog_languages');
        $this->forge->dropTable('catalog_countries');
    }
}