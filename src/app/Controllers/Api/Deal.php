<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

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

            $deal = [
                'type' => (int) $jsonData['type'],
                'value' => (float) $jsonData['value'],
                'description' => $jsonData['description'],
                'trade_for' => $jsonData['trade_for'] ?? null,
                'location' => [
                    'lat' => (float) $jsonData['location']['lat'],
                    'lng' => (float) $jsonData['location']['lng'],
                    'address' => $jsonData['location']['address'],
                    'city' => $jsonData['location']['city'],
                    'state' => $jsonData['location']['state'],
                    'zip_code' => (int) $jsonData['location']['zip_code']
                ],
                'urgency' => [
                    'type' => (int) $jsonData['urgency']['type'],
                    'limit_date' => $jsonData['urgency']['limit_date'] ?? null
                ],
                'photos' => array_map(function ($p) {
                    return ['src' => $p['src']];
                }, $jsonData['photos'] ?? [])
            ];

            return $this->respondCreated(['deal' => $deal]);
        } catch (\Exception $e) {
            return $this->failServerError('Erro interno do servidor: ' . $e->getMessage());
        }
    }
}
