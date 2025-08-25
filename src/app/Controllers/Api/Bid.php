<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BidModel;
use App\Models\DealModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Bid extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $bidModel;
    protected $dealModel;
    protected $userModel;

    public function __construct()
    {
        $this->bidModel = new BidModel();
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

            if (!$validation->run($jsonData, 'bidCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $bid = $this->bidModel->createBid($jsonData);

            if (!$bid) {
                return $this->failServerError('Erro ao criar lance');
            }

            $response = [
                'bid' => [
                    'user_id' => (int)$bid['user_id'],
                    'accepted' => (bool)$bid['accepted'],
                    'value' => (float)$bid['value'],
                    'description' => $bid['description']
                ]
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function show($dealId = null, $bidId = null)
    {
        try {
            if (!$dealId || !$bidId) {
                return $this->failValidationError('ID do deal e ID do lance são obrigatórios');
            }

            $bid = $this->bidModel->getBidById($bidId, $dealId);
            if (!$bid) {
                return $this->failNotFound('Lance não encontrado');
            }

            $response = [
                'bid' => [
                    'user_id' => (int)$bid['user_id'],
                    'accepted' => (bool)$bid['accepted'],
                    'value' => (float)$bid['value'],
                    'description' => $bid['description']
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function listBids($dealId = null)
    {
        try {
            if (!$dealId) {
                return $this->failValidationError('ID do deal é obrigatório');
            }

            $deal = $this->dealModel->getDealById($dealId);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $bids = $this->bidModel->getBidsByDealId($dealId);

            $response = [];
            foreach ($bids as $bid) {
                $response[] = [
                    'bid' => [
                        'user_id' => (int)$bid['user_id'],
                        'accepted' => (bool)$bid['accepted'],
                        'value' => (float)$bid['value'],
                        'description' => $bid['description']
                    ]
                ];
            }

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
