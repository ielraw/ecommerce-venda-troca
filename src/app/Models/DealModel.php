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
        'location_lat',
        'location_lng',
        'location_address',
        'location_city',
        'location_state',
        'location_zip_code',
        'urgency_type',
        'urgency_limit_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}
