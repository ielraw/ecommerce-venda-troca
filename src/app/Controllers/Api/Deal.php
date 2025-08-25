<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\DealModel;
use App\Models\DealPhotoModel;

class Deal extends BaseController
{
    use ResponseTrait;

    public function create()
    {
        try {
            $jsonData = $this->request->getJSON(true);

            $validation = \Config\Services::validation();
            if (!$validation->run($jsonData, 'dealCreate')) {
                return $this->failValidationErrors($validation->getErrors());
            }

            $dealModel = new DealModel();
            $dealPhotoModel = new DealPhotoModel();

            $dealData = [
                'type' => (int) $jsonData['type'],
                'value' => (float) $jsonData['value'],
                'description' => $jsonData['description'],
                'trade_for' => $jsonData['trade_for'] ?? null,
                'location_lat' => (float) $jsonData['location']['lat'],
                'location_lng' => (float) $jsonData['location']['lng'],
                'location_address' => $jsonData['location']['address'],
                'location_city' => $jsonData['location']['city'],
                'location_state' => $jsonData['location']['state'],
                'location_zip_code' => (int) $jsonData['location']['zip_code'],
                'urgency_type' => (int) $jsonData['urgency']['type'],
                'urgency_limit_date' => $jsonData['urgency']['limit_date'] ?? null
            ];

            $dealId = $dealModel->insert($dealData);
            if (!$dealId) {
                return $this->failServerError('Erro ao criar deal');
            }

            $photos = $jsonData['photos'] ?? [];
            foreach ($photos as $p) {
                if (isset($p['src']) && $p['src'] !== '') {
                    $dealPhotoModel->insert([
                        'deal_id' => $dealId,
                        'src' => $p['src']
                    ]);
                }
            }

            $created = $dealModel->find($dealId);
            $createdPhotos = $dealPhotoModel->where('deal_id', $dealId)->findAll();

            $response = [
                'deal' => [
                    'type' => (int) $created['type'],
                    'value' => (float) $created['value'],
                    'description' => $created['description'],
                    'trade_for' => $created['trade_for'],
                    'location' => [
                        'lat' => (float) $created['location_lat'],
                        'lng' => (float) $created['location_lng'],
                        'address' => $created['location_address'],
                        'city' => $created['location_city'],
                        'state' => $created['location_state'],
                        'zip_code' => (int) $created['location_zip_code']
                    ],
                    'urgency' => [
                        'type' => (int) $created['urgency_type'],
                        'limit_date' => $created['urgency_limit_date']
                    ],
                    'photos' => array_map(function ($p) { return ['src' => $p['src']]; }, $createdPhotos)
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
            $dealModel = new DealModel();
            $dealPhotoModel = new DealPhotoModel();

            $deal = $dealModel->find($id);
            if (!$deal) {
                return $this->failNotFound('Deal não encontrado');
            }

            $photos = $dealPhotoModel->where('deal_id', $id)->findAll();

            $response = [
                'deal' => [
                    'type' => (int) $deal['type'],
                    'value' => (float) $deal['value'],
                    'description' => $deal['description'],
                    'trade_for' => $deal['trade_for'],
                    'location' => [
                        'lat' => (float) $deal['location_lat'],
                        'lng' => (float) $deal['location_lng'],
                        'address' => $deal['location_address'],
                        'city' => $deal['location_city'],
                        'state' => $deal['location_state'],
                        'zip_code' => (int) $deal['location_zip_code']
                    ],
                    'urgency' => [
                        'type' => (int) $deal['urgency_type'],
                        'limit_date' => $deal['urgency_limit_date']
                    ],
                    'photos' => array_map(function ($p) { return ['src' => $p['src']]; }, $photos)
                ]
            ];

            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
