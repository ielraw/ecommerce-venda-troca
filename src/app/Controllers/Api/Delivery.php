<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DeliveryModel;
use App\Models\DeliveryStepModel;
use App\Models\DealModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Delivery extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $deliveryModel;
    protected $deliveryStepModel;
    protected $dealModel;
    protected $userModel;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
        $this->deliveryStepModel = new DeliveryStepModel();
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
            if (!$validation->run($jsonData, 'deliveryCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $deliveryData = [
                'deal_id' => $dealId,
                'user_id' => $jsonData['user_id'],
                'from_lat' => $deal['lat'],
                'from_lng' => $deal['lng'],
                'from_address' => $deal['address'],
                'from_city' => $deal['city'],
                'from_state' => $deal['state'],
                'from_zip_code' => $deal['zip_code'],
                'to_lat' => $user['lat'],
                'to_lng' => $user['lng'],
                'to_address' => $user['address'],
                'to_city' => $user['city'],
                'to_state' => $user['state'],
                'to_zip_code' => $user['zip_code'],
                'value' => $jsonData['value'] ?? 0.0
            ];

            $delivery = $this->deliveryModel->createDelivery($deliveryData);

            if (!$delivery) {
                return $this->failServerError('Erro ao criar delivery');
            }

            $steps = [];
            if (isset($jsonData['steps']) && is_array($jsonData['steps'])) {
                $this->deliveryStepModel->createSteps($delivery['id'], $jsonData['steps']);
                $steps = $this->deliveryStepModel->getStepsByDeliveryId($delivery['id']);
            }

            $response = [
                'delivery' => [
                    'from' => [
                        'lat' => (float)$delivery['from_lat'],
                        'lng' => (float)$delivery['from_lng'],
                        'address' => $delivery['from_address'],
                        'city' => $delivery['from_city'],
                        'state' => $delivery['from_state'],
                        'zip_code' => (int)$delivery['from_zip_code']
                    ],
                    'to' => [
                        'lat' => (float)$delivery['to_lat'],
                        'lng' => (float)$delivery['to_lng'],
                        'address' => $delivery['to_address'],
                        'city' => $delivery['to_city'],
                        'state' => $delivery['to_state'],
                        'zip_code' => (int)$delivery['to_zip_code']
                    ],
                    'value' => (float)$delivery['value']
                ],
                'steps' => array_map(function($step) {
                    return [
                        'location' => $step['location'],
                        'incoming_date' => $step['incoming_date'],
                        'outcoming_date' => $step['outcoming_date']
                    ];
                }, $steps)
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
