<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'deal_id',
        'user_id',
        'title',
        'message'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createMessage(array $messageData)
    {
        $insertData = [
            'deal_id' => $messageData['deal_id'],
            'user_id' => $messageData['user_id'],
            'title' => $messageData['title'],
            'message' => $messageData['message']
        ];

        $messageId = $this->insert($insertData);
        if ($messageId) {
            return $this->find($messageId);
        }
        return false;
    }

    public function getMessagesByDealId(int $dealId)
    {
        return $this->where('deal_id', $dealId)
                   ->where('deleted_at IS NULL')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getMessageById(int $id, int $dealId)
    {
        return $this->where('id', $id)
                   ->where('deal_id', $dealId)
                   ->first();
    }
}
