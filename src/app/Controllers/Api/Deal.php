<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DealModel;
use App\Models\LocationModel;
use App\Models\DealPhotoModel;
use CodeIgniter\API\ResponseTrait;

class Deal extends BaseController
{
    use ResponseTrait;
    protected $format = 'json';
    protected $dealModel;
    protected $locationModel;
    protected $dealPhotoModel;

    public function __construct()
    {
        $this->dealModel = new DealModel();
        $this->locationModel = new LocationModel();
        $this->dealPhotoModel = new DealPhotoModel();
    }

    public function create()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();

            if (!$validation->run($jsonData, 'dealCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $locationData = [
                'lat' => $jsonData['location']['lat'],
                'lng' => $jsonData['location']['lng'],
                'address' => $jsonData['location']['address'],
                'city' => $jsonData['location']['city'],
                'state' => $jsonData['location']['state'],
                'zip_code' => $jsonData['location']['zip_code']
            ];

            $location = $this->locationModel->createLocation($locationData);

            if (!$location) {
                return $this->failServerError('Erro ao criar localização');
            }

            $deal = $this->dealModel->createDeal($jsonData, $location['id']);

            if (!$deal) {
                return $this->failServerError('Erro ao criar deal');
            }

            $photos = [];
            if (isset($jsonData['photos']) && is_array($jsonData['photos'])) {
                $photos = $this->dealPhotoModel->addPhotos($deal['id'], $jsonData['photos']);
            }

            $response = [
                'deal' => [
                    'type' => (int)$deal['type'],
                    'value' => (float)$deal['value'],
                    'description' => $deal['description'],
                    'trade_for' => $deal['trade_for'],
                    'location' => [
                        'lat' => (float)$location['lat'],
                        'lng' => (float)$location['lng'],
                        'address' => $location['address'],
                        'city' => $location['city'],
                        'state' => $location['state'],
                        'zip_code' => (int)$location['zip_code']
                    ],
                    'urgency' => [
                        'type' => (int)$deal['urgency_type'],
                        'limit_date' => $deal['urgency_limit_date']
                    ],
                    'photos' => array_map(function($photo) {
                        return ['src' => $photo['src']];
                    }, $photos)
                ]
            ];

            return $this->respondCreated($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function show($id = null)
    {
        try {
            $deal = $this->dealModel->getDealById($id);

            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $location = $this->locationModel->getLocationById($deal['location_id']);
            if (!$location) {
                return $this->failServerError('Localização do deal não encontrada');
            }

            $photos = $this->dealPhotoModel->getPhotosByDealId($deal['id']);

            $response = [
                'deal' => [
                    'type' => (int)$deal['type'],
                    'value' => (float)$deal['value'],
                    'description' => $deal['description'],
                    'trade_for' => $deal['trade_for'],
                    'location' => [
                        'lat' => (float)$location['lat'],
                        'lng' => (float)$location['lng'],
                        'address' => $location['address'],
                        'city' => $location['city'],
                        'state' => $location['state'],
                        'zip_code' => (int)$location['zip_code']
                    ],
                    'urgency' => [
                        'type' => (int)$deal['urgency_type'],
                        'limit_date' => $deal['urgency_limit_date']
                    ],
                    'photos' => array_map(function($photo) {
                        return ['src' => $photo['src']];
                    }, $photos)
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function search()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();

            if (!$validation->run($jsonData, 'dealSearch')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $deals = $this->dealModel->searchDeals($jsonData);

            $response = [];
            foreach ($deals as $deal) {
                $photos = $this->dealPhotoModel->getPhotosByDealId($deal['id']);

                $response[] = [
                    'deal' => [
                        'type' => (int)$deal['type'],
                        'value' => (float)$deal['value'],
                        'description' => $deal['description'],
                        'trade_for' => $deal['trade_for'],
                        'location' => [
                            'lat' => (float)$deal['lat'],
                            'lng' => (float)$deal['lng'],
                            'address' => $deal['address'],
                            'city' => $deal['city'],
                            'state' => $deal['state'],
                            'zip_code' => (int)$deal['zip_code']
                        ],
                        'urgency' => [
                            'type' => (int)$deal['urgency_type'],
                            'limit_date' => $deal['urgency_limit_date']
                        ],
                        'photos' => array_map(function($photo) {
                            return ['src' => $photo['src']];
                        }, $photos)
                    ]
                ];
            }

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }

    public function update($id = null)
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();

            if (!$validation->run($jsonData, 'dealCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $deal = $this->dealModel->getDealById($id);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado para atualização');
            }

            $this->locationModel->update($deal['location_id'], $jsonData['location']);

            $dealData = [
                'type' => $jsonData['type'],
                'value' => $jsonData['value'],
                'description' => $jsonData['description'],
                'trade_for' => $jsonData['trade_for'],
                'urgency_type' => $jsonData['urgency']['type'],
                'urgency_limit_date' => $jsonData['urgency']['limit_date'] ?? null,
            ];

            $this->dealModel->update($id, $dealData);

            if (isset($jsonData['photos']) && is_array($jsonData['photos'])) {
                $this->dealPhotoModel->where('deal_id', $id)->delete();
                $this->dealPhotoModel->addPhotos($id, $jsonData['photos']);
            }

            $updatedDeal = $this->dealModel->getDealById($id);
            $updatedLocation = $this->locationModel->getLocationById($updatedDeal['location_id']);
            $updatedPhotos = $this->dealPhotoModel->getPhotosByDealId($id);

            $response = [
                'deal' => [
                    'type' => (int)$updatedDeal['type'],
                    'value' => (float)$updatedDeal['value'],
                    'description' => $updatedDeal['description'],
                    'trade_for' => $updatedDeal['trade_for'],
                    'location' => [
                        'lat' => (float)$updatedLocation['lat'],
                        'lng' => (float)$updatedLocation['lng'],
                        'address' => $updatedLocation['address'],
                        'city' => $updatedLocation['city'],
                        'state' => $updatedLocation['state'],
                        'zip_code' => (int)$updatedLocation['zip_code']
                    ],
                    'urgency' => [
                        'type' => (int)$updatedDeal['urgency_type'],
                        'limit_date' => $updatedDeal['urgency_limit_date']
                    ],
                    'photos' => array_map(function($photo) {
                        return ['src' => $photo['src']];
                    }, $updatedPhotos)
                ]
            ];

            return $this->respond($response);

        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
