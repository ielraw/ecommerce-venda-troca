<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        $locationData = [
            'lat' => -23.5505,
            'lng' => -46.6333,
            'address' => 'Av. Paulista, 1000',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => 01310,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('locations')->insert($locationData);
        $locationId = $db->insertID();
        
        $userData = [
            'name' => 'Usuário Padrão',
            'email' => 'admin@exemplo.com',
            'login' => 'admin',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'location_id' => $locationId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $db->table('users')->insert($userData);
        
        echo "Usuário padrão criado com sucesso!\n";
        echo "Login: admin\n";
        echo "Senha: 123456\n";
        echo "Email: admin@exemplo.com\n";
    }
}
