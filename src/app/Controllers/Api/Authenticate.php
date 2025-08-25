<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authenticate extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'authenticate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $user = $this->userModel->getUserByLogin($jsonData['login']);
            if (!$user) {
                return $this->failUnauthorized('Credenciais inválidas');
            }

            if (!password_verify($jsonData['password'], $user['password'])) {
                return $this->failUnauthorized('Credenciais inválidas');
            }

            $key = getenv('JWT_SECRET') ?: 'default_secret_key';
            $payload = [
                'iss' => 'ecommerce-api',
                'aud' => 'ecommerce-users',
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24), // 24 horas
                'user_id' => $user['id'],
                'login' => $user['login']
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            $response = [
                'token' => $token,
                'user' => [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'login' => $user['login'],
                    'password' => $user['password'],
                    'location' => [
                        'lat' => (float)$user['lat'],
                        'lng' => (float)$user['lng'],
                        'address' => $user['address'],
                        'city' => $user['city'],
                        'state' => $user['state'],
                        'zip_code' => (int)$user['zip_code']
                    ]
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function sso()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'authenticateSSO')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $user = $this->userModel->getUserByLogin($jsonData['login']);
            $appToken = getenv('APP_TOKEN') ?: 'default_app_token';
            if (!$user || $jsonData['app_token'] !== $appToken) {
                return $this->failUnauthorized('Credenciais inválidas');
            }

            $key = getenv('JWT_SECRET') ?: 'default_secret_key';
            $payload = [
                'iss' => 'ecommerce-api',
                'aud' => 'ecommerce-users',
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24), // 24 horas
                'user_id' => $user['id'],
                'login' => $user['login']
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            $response = [
                'token' => $token,
                'user' => [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'login' => $user['login'],
                    'password' => $user['password'],
                    'location' => [
                        'lat' => (float)$user['lat'],
                        'lng' => (float)$user['lng'],
                        'address' => $user['address'],
                        'city' => $user['city'],
                        'state' => $user['state'],
                        'zip_code' => (int)$user['zip_code']
                    ]
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
