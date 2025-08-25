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
            'location_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('location_id');
        $this->forge->addKey('type');
        $this->forge->addKey('urgency_type');
        $this->forge->addKey('created_at');

        $this->forge->addForeignKey('location_id', 'locations', 'id', 'RESTRICT', 'CASCADE');

        $this->forge->createTable('deals');
    }

    public function down()
    {
        $this->forge->dropTable('deals');
    }
}
