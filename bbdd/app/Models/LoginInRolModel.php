<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginInRolModel extends Model
{
    protected $table            = 'login_in_rol';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_login_relacio','id_rol_relacio'];

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

    public function addLoginInRol($d1,$d2)
    {
        $this->insert([
            "id_login_relacio" => $d1,
            "id_rol_relacio" => $d2,
        ]);
    }

    public function obtenirRol($id_login) {
        //return $this->select('id_login')->where('login', $nom_login)->first();
        return $this->select('id_rol_relacio')->where("id_login_relacio", $id_login)->first()['id_rol_relacio'];
    }
}
