<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MessageModel;
use App\Models\DealModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Message extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $messageModel;
    protected $dealModel;
    protected $userModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->dealModel = new DealModel();
        $this->userModel = new UserModel();
    }

    public function create($dealId = 0)
    {
        try {
            $deal = $this->dealModel->getDealById($dealId);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $jsonData = $this->request->getJSON(true);
            $jsonData['deal_id'] = $dealId;

            $user = $this->userModel->getUserById($jsonData['user_id']);
            if (!$user) {
                return $this->failNotFound('Usuário não encontrado');
            }

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'messageCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $message = $this->messageModel->createMessage($jsonData);

            if (!$message) {
                return $this->failServerError('Erro ao criar mensagem');
            }

            $response = [
                'message' => [
                    'user_id' => (int)$message['user_id'],
                    'title' => $message['title'],
                    'message' => $message['message']
                ]
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function show($dealId = 0, $messageId = 0)
    {
        try {
            if (!$dealId || !$messageId) {
                return $this->failValidationError('ID do deal e ID da mensagem são obrigatórios');
            }

            $deal = $this->dealModel->getDealById($dealId);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $message = $this->messageModel->getMessageById($messageId, $dealId);
            if (!$message) {
                return $this->failNotFound('Mensagem não encontrada');
            }

            $response = [
                'message' => [
                    'user_id' => (int)$message['user_id'],
                    'title' => $message['title'],
                    'message' => $message['message']
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function listMessages($dealId = 0)
    {
        try {
            if (!$dealId) {
                return $this->failValidationError('ID do deal é obrigatório');
            }

            $deal = $this->dealModel->getDealById($dealId);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $messages = $this->messageModel->getMessagesByDealId($dealId);

            $response = [];
            foreach ($messages as $message) {
                $response[] = [
                    'message' => [
                        'user_id' => (int)$message['user_id'],
                        'title' => $message['title'],
                        'message' => $message['message']
                    ]
                ];
            }

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function update($dealId = 0, $messageId = 0)
    {
        try {
            if (!$dealId || !$messageId) {
                return $this->failValidationError('ID do deal e ID da mensagem são obrigatórios');
            }

            $deal = $this->dealModel->getDealById($dealId);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $existingMessage = $this->messageModel->getMessageById($messageId, $dealId);
            if (!$existingMessage) {
                return $this->failNotFound('Mensagem não encontrada');
            }

            $jsonData = $this->request->getJSON(true);

            $user = $this->userModel->getUserById($jsonData['user_id']);
            if (!$user) {
                return $this->failNotFound('Usuário não encontrado');
            }

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'messageCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $updatedMessage = $this->messageModel->updateMessage($messageId, $dealId, $jsonData);

            if (!$updatedMessage) {
                return $this->failServerError('Erro ao atualizar mensagem');
            }

            $response = [
                'message' => [
                    'user_id' => (int)$updatedMessage['user_id'],
                    'title' => $updatedMessage['title'],
                    'message' => $updatedMessage['message']
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
