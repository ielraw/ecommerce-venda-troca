<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDealsTable extends Migration
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
            'type' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
            ],
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => false,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
                'null' => false,
            ],
            'trade_for' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'location_lat' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => false,
            ],
            'location_lng' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => false,
            ],
            'location_address' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => false,
            ],
            'location_city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'location_state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'location_zip_code' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'urgency_type' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
            ],
            'urgency_limit_date' => [
                'type' => 'DATE',
                'null' => true,
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
        $this->forge->addKey('type');
        $this->forge->addKey('urgency_type');
        $this->forge->addKey('created_at');

        $this->forge->createTable('deals');
    }

    public function down()
    {
        $this->forge->dropTable('deals');
    }
}
