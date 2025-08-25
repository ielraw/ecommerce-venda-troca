<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDealPhotosTable extends Migration
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
                'null' => false,
            ],
            'src' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
                'null' => false,
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
        $this->forge->addKey('deal_id');
        $this->forge->addKey('created_at');

        $this->forge->createTable('deal_photos');

        $this->db->query('ALTER TABLE `deal_photos` ADD CONSTRAINT `fk_deal_photos_deal` FOREIGN KEY (`deal_id`) REFERENCES `deals`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `deal_photos` DROP FOREIGN KEY `fk_deal_photos_deal`');
        $this->forge->dropTable('deal_photos');
    }
}
