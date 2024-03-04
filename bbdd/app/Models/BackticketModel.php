<?php

namespace App\Models;

use CodeIgniter\Model;

class BackticketModel extends Model
{
    protected $table            = 'backtickets';
    protected $primaryKey       = 'id_back';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tipus_alerta','data_backticket','informacio'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function addBackTickets($tipus_alerta, $data_backticket, $informacio)
    {
        $data = [
            'tipus_alerta' =>  $tipus_alerta,
            'data_backticket' =>  $data_backticket,
            'informacio' => $informacio
        ];

        $this->insert($data);
    }

    public function getBackTickets ($id=null) {
        if ($id===null) {
            return $this->findAll();
        }
        return $this->where('id',$id)->first();
    }


}
