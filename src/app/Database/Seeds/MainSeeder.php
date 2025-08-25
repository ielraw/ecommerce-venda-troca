<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('DefaultUserSeeder');
        
        echo "\nTodos os seeders foram executados com sucesso!\n";
    }
}
