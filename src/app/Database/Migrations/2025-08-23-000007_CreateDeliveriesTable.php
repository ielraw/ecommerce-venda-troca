<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'deal_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'from_lat' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
            ],
            'from_lng' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
            ],
            'from_address' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'from_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'from_state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'from_zip_code' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'to_lat' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
            ],
            'to_lng' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
            ],
            'to_address' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'to_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'to_state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'to_zip_code' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('deal_id', 'deals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('deliveries');
    }

    public function down()
    {
        $this->forge->dropTable('deliveries');
    }
}
