<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'type' => ['type' => 'VARCHAR', 'constraint' => 20],
            'subpartida_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'subpartida_code' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'subpartida_desc' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sector_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'characteristics_es' => ['type' => 'TEXT', 'null' => true],
            'characteristics_en' => ['type' => 'TEXT', 'null' => true],
            'offer_capacity' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'unit_code' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'other_certificates' => ['type' => 'TEXT', 'null' => true],
            'photo_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'datasheet_url' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');

        $this->forge->addField([
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'certificate_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('certificate_id', 'catalog_certificates', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['product_id', 'certificate_id'], true);
        $this->forge->createTable('product_certificates');

        $this->forge->addField([
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'country_code' => ['type' => 'VARCHAR', 'constraint' => 10],
        ]);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('country_code', 'catalog_countries', 'code', 'CASCADE', 'CASCADE');
        $this->forge->addKey(['product_id', 'country_code'], true);
        $this->forge->createTable('product_export_countries');
    }

    public function down()
    {
        $this->forge->dropTable('product_export_countries');
        $this->forge->dropTable('product_certificates');
        $this->forge->dropTable('products');
    }
}