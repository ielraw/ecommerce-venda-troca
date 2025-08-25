<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table = 'deliveries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'deal_id',
        'user_id',
        'from_lat',
        'from_lng',
        'from_address',
        'from_city',
        'from_state',
        'from_zip_code',
        'to_lat',
        'to_lng',
        'to_address',
        'to_city',
        'to_state',
        'to_zip_code',
        'value'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createDelivery(array $deliveryData)
    {
        $insertData = [
            'deal_id' => $deliveryData['deal_id'],
            'user_id' => $deliveryData['user_id'],
            'from_lat' => $deliveryData['from_lat'],
            'from_lng' => $deliveryData['from_lng'],
            'from_address' => $deliveryData['from_address'],
            'from_city' => $deliveryData['from_city'],
            'from_state' => $deliveryData['from_state'],
            'from_zip_code' => $deliveryData['from_zip_code'],
            'to_lat' => $deliveryData['to_lat'],
            'to_lng' => $deliveryData['to_lng'],
            'to_address' => $deliveryData['to_address'],
            'to_city' => $deliveryData['to_city'],
            'to_state' => $deliveryData['to_state'],
            'to_zip_code' => $deliveryData['to_zip_code'],
            'value' => $deliveryData['value']
        ];

        $deliveryId = $this->insert($insertData);
        if ($deliveryId) {
            return $this->find($deliveryId);
        }
        return false;
    }

    public function getDeliveryByDealId(int $dealId)
    {
        return $this->where('deal_id', $dealId)
                   ->where('deleted_at IS NULL')
                   ->first();
    }
}
