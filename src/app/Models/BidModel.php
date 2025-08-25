<?php

namespace App\Models;

use CodeIgniter\Model;

class BidModel extends Model
{
    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'deal_id',
        'user_id',
        'accepted',
        'value',
        'description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createBid(array $bidData)
    {
        $insertData = [
            'deal_id' => $bidData['deal_id'],
            'user_id' => $bidData['user_id'],
            'accepted' => $bidData['accepted'],
            'value' => $bidData['value'],
            'description' => $bidData['description']
        ];

        $bidId = $this->insert($insertData);
        if ($bidId) {
            return $this->find($bidId);
        }
        return false;
    }

    public function getBidById(int $id, int $dealId)
    {
        return $this->where('id', $id)
                   ->where('deal_id', $dealId)
                   ->first();
    }

    public function getBidsByDealId(int $dealId)
    {
        return $this->where('deal_id', $dealId)
                   ->where('deleted_at IS NULL')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function updateBid(int $id, int $dealId, array $bidData)
    {
        $updateData = [
            'user_id' => $bidData['user_id'],
            'accepted' => $bidData['accepted'],
            'value' => $bidData['value'],
            'description' => $bidData['description']
        ];

        $result = $this->where('id', $id)
                      ->where('deal_id', $dealId)
                      ->set($updateData)
                      ->update();

        if ($result) {
            return $this->find($id);
        }
        return false;
    }
}
