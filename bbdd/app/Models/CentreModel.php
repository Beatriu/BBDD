<?php

namespace App\Models;

use CodeIgniter\Model;

class CentreModel extends Model
{
    protected $table            = 'centre';
    protected $primaryKey       = 'codi_centre';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['codi_centre','nom_centre','actiu','taller','telefon_centre','adreca_fisica_centre','nom_persona_contacte_centre','correu_persona_contacte_centre','id_sstt','id_poblacio','login'];

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

    public function addCentre($d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11)
    {
        $this->insert([
            "codi_centre" => $d1,
            "nom_centre" => $d2,
            "actiu" => $d3,
            "taller" => $d4,
            "telefon_centre" => $d5,
            "adreca_fisica_centre" => $d6,
            "nom_persona_contacte_centre" => $d7,
            "correu_persona_contacte_centre" => $d8,
            "id_sstt" => $d9,
            "id_poblacio" => $d10,
            "login" => $d11,
        ]);
    }

    public function obtenirNomResponsable($codi_centre) {
        $centre = $this->where('codi_centre', $codi_centre)->first();
        return $centre['nom_persona_contacte_centre'];
    }

    public function obtenirCorreuResponsable($codi_centre) {
        $centre = $this->where('codi_centre', $codi_centre)->first();
        return $centre['correu_persona_contacte_centre'];
    }

    public function obtenirCentres() {
        return $this->findAll();
    }

    public function obtenirCentresReparadors(){
        return $this->where('taller', 1)->findAll();
    }

    public function obtenirCentre($codi_centre) {
        return $this->where('codi_centre', $codi_centre)->first();
    }

    public function obtenirCentrePerCorreu($correu) {
        return $this->where('login', $correu)->first();
    }

}
