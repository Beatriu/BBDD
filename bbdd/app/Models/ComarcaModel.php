<?php

namespace App\Models;

use CodeIgniter\Model;

class ComarcaModel extends Model
{
    protected $table            = 'comarca';
    protected $primaryKey       = 'id_comarca';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_comarca','nom_comarca','actiu'];

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

    public function addComarca($d1,$d2)
    {
        $this->insert([
            "id_comarca" => $d1,
            "nom_comarca" => $d2
        ]);
    }

    public function obtenirComarca($d1) {
        return $this->where('id_comarca', $d1)->first();
    }

    public function obtenirComarques()
    {
        return $this->findAll();
    }



    public function obtenirComarcaPerId($id_comarca)
    {
        return $this->where('id_comarca', $id_comarca)->first();
    }

    public function obtenirComarcaPerIdNom($id_comarca, $nom_comarca)
    {
        return $this->where(['id_comarca' => $id_comarca,'nom_comarca' => $nom_comarca])->first();
    }

    public function editarComarcaActiu($id_comarca, $actiu)
    {
        return $this->update($id_comarca, ['actiu' => $actiu]);
    }

    public function esborrarComarca($id_comarca)
    {
        return $this->delete($id_comarca);
    }
}
