<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table            = 'login';
    protected $primaryKey       = 'id_login';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_login', 'login', 'contrasenya'];

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

    public function addLogin($d1,$d2)
    {
        $this->insert([
            "login" => $d1,
            "contrasenya" => $d2,
        ]);
    }

    public function addLoginCSV($d1,$d2,$d3)
    {
        $this->insert([
            "login" => $d1,
            "contrasenya" => $d2,
            "id_sstt" => $d3
        ]);
    }

    public function obtenirId($nom_login) {
        return $this->select('id_login')->where('login', $nom_login)->first()['id_login'];
    }

    public function obtenirLogin($nom_login) {
        return $this->where('login', $nom_login)->first();
    }

    public function insertarLogin($nom_login) {
        $this->insert([
            "login" => $nom_login,
        ]);
    }

    public function obtenirIdSSTT($nom_login) {
        return $this->where('login', $nom_login)->first();
    }

    public function deleteLogin($id_login) 
    {
        return $this->delete(['id_login' => $id_login]);
    }

}
