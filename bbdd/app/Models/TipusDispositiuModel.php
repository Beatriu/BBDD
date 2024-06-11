<?php

namespace App\Models;

use CodeIgniter\Model;

class TipusDispositiuModel extends Model
{
    protected $table            = 'tipus_dispositiu';
    protected $primaryKey       = 'id_tipus_dispositiu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom_tipus_dispositiu', 'actiu'];

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
    
    public function addTipusDispositiu($d1)
    {
        $this->insert([
            "nom_tipus_dispositiu" => $d1
        ]);
    }

    public function getTipusDispositius() {
        return $this->findAll();
    }

    public function obtenirTipusDispositiuActiu() {
        return $this->select('id_tipus_dispositiu, nom_tipus_dispositiu')->where('actiu', "1")->findAll();
    }

    public function getNomTipusDispositiu($id_tipus_dispositiu)
    {
        return $this->where('id_tipus_dispositiu', $id_tipus_dispositiu)->first();
    }

    public function obtenirTipusDispositiuPerId($id_tipus_dispositiu)
    {
        return $this->where('id_tipus_dispositiu', $id_tipus_dispositiu)->first();
    }

    public function obtenirTipusDispositiuPerNom($nom_tipus_dispositiu)
    {
        return $this->where('nom_tipus_dispositiu', $nom_tipus_dispositiu)->first();
    }

    public function editarTipusDispositiuActiu($id_tipus_dispositiu, $actiu)
    {
        return $this->update($id_tipus_dispositiu, ['actiu' => $actiu]);
    }

    public function esborrarTipusDispositiu($id_tipus_dispositiu)
    {
        return $this->delete($id_tipus_dispositiu);
    }
}
