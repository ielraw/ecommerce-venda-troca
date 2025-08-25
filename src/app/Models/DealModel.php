<?php

namespace App\Models;

use CodeIgniter\Model;

class DealModel extends Model
{
    protected $table = 'deals';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'type',
        'value',
        'description',
        'trade_for',
        'location_id',
        'urgency_type',
        'urgency_limit_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createDeal(array $dealData, int $locationId)
    {
        $insertData = [
            'type' => $dealData['type'],
            'value' => $dealData['value'],
            'description' => $dealData['description'],
            'trade_for' => $dealData['trade_for'],
            'location_id' => $locationId,
            'urgency_type' => $dealData['urgency']['type'],
            'urgency_limit_date' => $dealData['urgency']['limit_date'] ?? null,
        ];

        $dealId = $this->insert($insertData);
        if ($dealId) {
            return $this->find($dealId);
        }
        return false;
    }

    public function getDealById(int $id)
    {
        $builder = $this->builder();
        $builder->select('deals.*, locations.lat, locations.lng, locations.address, locations.city, locations.state, locations.zip_code');
        $builder->join('locations', 'locations.id = deals.location_id');
        $builder->where('deals.id', $id);
        
        return $builder->get()->getRowArray();
    }

    public function searchDeals(array $filters)
    {
        $builder = $this->builder();
        $builder->select('deals.*, locations.lat, locations.lng, locations.address, locations.city, locations.state, locations.zip_code');
        $builder->join('locations', 'locations.id = deals.location_id');

        if (!empty($filters['type'])) {
            $builder->where('deals.type', $filters['type']);
        }

        if (!empty($filters['value_start']) && !empty($filters['value_end'])) {
            $builder->where('deals.value >=', $filters['value_start']);
            $builder->where('deals.value <=', $filters['value_end']);
        } elseif (!empty($filters['value_start'])) {
            $builder->where('deals.value >=', $filters['value_start']);
        } elseif (!empty($filters['value_end'])) {
            $builder->where('deals.value <=', $filters['value_end']);
        }

        if (!empty($filters['term'])) {
            $builder->groupStart();
            $builder->like('deals.description', $filters['term']);
            $builder->orLike('deals.trade_for', $filters['term']);
            $builder->groupEnd();
        }

        if (!empty($filters['lat']) && !empty($filters['lng'])) {
            $builder->where('locations.lat', $filters['lat']);
            $builder->where('locations.lng', $filters['lng']);
        }

        $builder->orderBy('deals.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
