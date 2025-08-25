<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\InviteModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Invite extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $inviteModel;
    protected $userModel;

    public function __construct()
    {
        $this->inviteModel = new InviteModel();
        $this->userModel = new UserModel();
    }

    public function create($userId = 0)
    {
        try {
            
            $user = $this->userModel->getUserById($userId);
            if (!$user) {
                return $this->failNotFound('Usuário não encontrado');
            }

            $jsonData = $this->request->getJSON(true);
            $jsonData['user'] = $userId;

            $userInvited = $this->userModel->getUserById($jsonData['user_invited']);
            if (!$userInvited) {
                return $this->failNotFound('Usuário convidado não encontrado');
            }

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'inviteCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $invite = $this->inviteModel->createInvite($jsonData);

            if (!$invite) {
                return $this->failServerError('Erro ao criar convite');
            }

            $response = [
                'invite' => [
                    'name' => $invite['name'],
                    'email' => $invite['email'],
                    'user' => (int)$invite['user'],
                    'user_invited' => (int)$invite['user_invited']
                ]
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
