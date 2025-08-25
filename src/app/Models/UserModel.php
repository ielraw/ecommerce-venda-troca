<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'login',
        'password',
        'location_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name'        => 'required|min_length[2]|max_length[255]',
        'email'       => 'required|valid_email|max_length[255]',
        'login'       => 'required|min_length[3]|max_length[100]|is_unique[users.login,id,{id}]',
        'password'    => 'required|min_length[6]|max_length[255]',
        'location_id' => 'required|integer|greater_than[0]'
    ];
    
    protected $validationMessages   = [
        'name' => [
            'required' => 'O nome é obrigatório',
            'min_length' => 'O nome deve ter pelo menos 2 caracteres',
            'max_length' => 'O nome não pode exceder 255 caracteres'
        ],
        'email' => [
            'required' => 'O email é obrigatório',
            'valid_email' => 'O email deve ser válido',
            'max_length' => 'O email não pode exceder 255 caracteres'
        ],
        'login' => [
            'required' => 'O login é obrigatório',
            'min_length' => 'O login deve ter pelo menos 3 caracteres',
            'max_length' => 'O login não pode exceder 100 caracteres',
            'is_unique' => 'Este login já está em uso'
        ],
        'password' => [
            'required' => 'A senha é obrigatória',
            'min_length' => 'A senha deve ter pelo menos 6 caracteres',
            'max_length' => 'A senha não pode exceder 255 caracteres'
        ],
        'location_id' => [
            'required' => 'O ID da localização é obrigatório',
            'integer' => 'O ID da localização deve ser um número inteiro',
            'greater_than' => 'O ID da localização deve ser maior que zero'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function createUser($userData, $locationId)
    {
        $insertData = [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'login' => $userData['login'],
            'password' => $userData['password'],
            'location_id' => $locationId
        ];

        $userId = $this->insert($insertData);
        
        if ($userId) {
            return $this->find($userId);
        }
        
        return false;
    }

    public function getUserById($id)
    {
        $builder = $this->builder();
        $builder->select('users.*, locations.lat, locations.lng, locations.address, locations.city, locations.state, locations.zip_code');
        $builder->join('locations', 'locations.id = users.location_id');
        $builder->where('users.id', $id);
        
        $user = $builder->get()->getRowArray();
        
        if ($user) {
            return [
                'name' => $user['name'],
                'email' => $user['email'],
                'login' => $user['login'],
                'password' => $user['password'],
                'location_id' => (int)$user['location_id'],
                'lat' => $user['lat'],
                'lng' => $user['lng'],
                'address' => $user['address'],
                'city' => $user['city'],
                'state' => $user['state'],
                'zip_code' => $user['zip_code']
            ];
        }
        
        return false;
    }
}
