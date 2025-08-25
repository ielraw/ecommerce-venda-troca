<?php

namespace App\Models;

use CodeIgniter\Model;

class InviteModel extends Model
{
    protected $table = 'invites';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'name',
        'email',
        'user',
        'user_invited'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function createInvite(array $inviteData)
    {
        $insertData = [
            'name' => $inviteData['name'],
            'email' => $inviteData['email'],
            'user' => $inviteData['user'],
            'user_invited' => $inviteData['user_invited']
        ];

        $inviteId = $this->insert($insertData);
        if ($inviteId) {
            return $this->find($inviteId);
        }
        return false;
    }

    public function getInvitesByUserId(int $userId)
    {
        return $this->where('user', $userId)
                   ->where('deleted_at IS NULL')
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    public function getInviteById(int $id, int $userId)
    {
        return $this->where('id', $id)
                   ->where('user', $userId)
                   ->first();
    }

    public function updateInvite(int $id, int $userId, array $inviteData)
    {
        $updateData = [
            'name' => $inviteData['name'],
            'email' => $inviteData['email'],
            'user_invited' => $inviteData['user_invited']
        ];

        $result = $this->where('id', $id)
                      ->where('user', $userId)
                      ->set($updateData)
                      ->update();

        if ($result) {
            return $this->find($id);
        }
        return false;
    }
}
