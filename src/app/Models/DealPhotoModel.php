<?php

namespace App\Models;

use CodeIgniter\Model;

class DealPhotoModel extends Model
{
    protected $table = 'deal_photos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'deal_id',
        'src'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function addPhotos(int $dealId, array $photos)
    {
        $inserted = [];
        foreach ($photos as $photo) {
            $data = ['deal_id' => $dealId, 'src' => $photo['src']];
            if ($this->insert($data)) {
                $inserted[] = $this->find($this->getInsertID());
            }
        }
        return $inserted;
    }

    public function getPhotosByDealId(int $dealId)
    {
        return $this->where('deal_id', $dealId)->findAll();
    }
}
