<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfessorModel extends Model
{
    protected $table            = 'professor';
    protected $primaryKey       = 'id_xtec';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_xtec','nom_professor','cognoms_professor','correu_professor','codi_centre'];

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

    public function addProfessor($d1,$d2,$d3,$d4,$d5)
    {
        $this->insert([
            "id_xtec" => $d1,
            "nom_professor" => $d2,
            "cognoms_professor" => $d3,
            "correu_professor" => $d4,
            "codi_centre" => $d5
        ]);
    }

    public function obtenirProfessor($correu_professor) {
        return $this->where('correu_professor', $correu_professor)->first();
    }

    public function obtenirCodiCentre($correu_professor) {
        return $this->select('codi_centre')->where('correu_professor', $correu_professor)->first();
    }

    public function editarProfessorNomCognomsCodiCentre($id_xtec, $nom, $cognoms, $codi_centre)
    {
        return $this->update($id_xtec, ['codi_centre' => $codi_centre, 'nom' => $nom, 'cognoms' => $cognoms]);
    }

    public function editarProfessorCodiCentre($id_xtec, $codi_centre)
    {
        return $this->update($id_xtec, ['codi_centre' => $codi_centre]);
    }
}
