<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeader('Authorization');
        
        if (!$header) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token de autorização não fornecido']);
        }

        $token = str_replace('Bearer ', '', $header->getValue());

        if (empty($token)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token de autorização inválido']);
        }

        try {
            $key = getenv('JWT_SECRET') ?: 'default_secret_key';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            $request->user_id = $decoded->user_id;
            $request->user_login = $decoded->login;
            
        } catch (Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token de autorização inválido ou expirado']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não é necessário fazer nada após a requisição
    }
}
