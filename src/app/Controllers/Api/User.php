<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LocationModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $userModel;
    protected $locationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->locationModel = new LocationModel();
    }

    public function create()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();

            if (!$validation->run($jsonData, 'userCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $location = $this->locationModel->createLocation($jsonData['location']);

            if (!$location) {
                return $this->failServerError('Erro ao criar localização');
            }

            $user = $this->userModel->createUser($jsonData, $location['id']);

            if (!$user) {
                return $this->failServerError('Erro ao criar usuário');
            }

            $response = [
                'user' => [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'login' => $user['login'],
                    'password' => $jsonData['password'],
                    'location' => [
                        'lat' => (float) $location['lat'],
                        'lng' => (float) $location['lng'],
                        'address' => $location['address'],
                        'city' => $location['city'],
                        'state' => $location['state'],
                        'zip_code' => (int) $location['zip_code']
                    ]
                ]
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userModel->find($id);
            if (!$user) {
                return $this->failNotFound('Usuário não encontrado');
            }

            $location = $this->locationModel->find($user['location_id']);

            $response = [
                'user' => [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'login' => $user['login'],
                    'password' => $user['password'],
                    'location' => $location
                ]
            ];

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();

            if (!$validation->run($jsonData, 'userCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $user = $this->userModel->find($id);
            if (!$user) {
                return $this->failNotFound('Usuário não encontrado');
            }

            $locationId = $user['location_id'];

            $this->locationModel->update($locationId, $jsonData['location']);

            $userData = [
                'name' => $jsonData['name'],
                'email' => $jsonData['email'],
                'login' => $jsonData['login'],
                'password' => $jsonData['password'],
            ];

            $this->userModel->update($id, $userData);

            $updatedUser = $this->userModel->find($id);
            $updatedLocation = $this->locationModel->find($locationId);

            $response = [
                'user' => [
                    'name' => $updatedUser['name'],
                    'email' => $updatedUser['email'],
                    'login' => $updatedUser['login'],
                    'password' => $jsonData['password'],
                    'location' => [
                        'lat' => (float) $updatedLocation['lat'],
                        'lng' => (float) $updatedLocation['lng'],
                        'address' => $updatedLocation['address'],
                        'city' => $updatedLocation['city'],
                        'state' => $updatedLocation['state'],
                        'zip_code' => (int) $updatedLocation['zip_code']
                    ]
                ]
            ];

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
