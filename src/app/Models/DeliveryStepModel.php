<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryStepModel extends Model
{
    protected $table = 'delivery_steps';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'delivery_id',
        'location',
        'incoming_date',
        'outcoming_date',
        'step_order'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createSteps(int $deliveryId, array $steps)
    {
        $insertData = [];
        foreach ($steps as $index => $step) {
            $insertData[] = [
                'delivery_id' => $deliveryId,
                'location' => $step['location'],
                'incoming_date' => $step['incoming_date'] ?? null,
                'outcoming_date' => $step['outcoming_date'] ?? null,
                'step_order' => $index
            ];
        }

        if (!empty($insertData)) {
            return $this->insertBatch($insertData);
        }
        return false;
    }

    public function getStepsByDeliveryId(int $deliveryId)
    {
        return $this->where('delivery_id', $deliveryId)
                   ->where('deleted_at IS NULL')
                   ->orderBy('step_order', 'ASC')
                   ->findAll();
    }
}
