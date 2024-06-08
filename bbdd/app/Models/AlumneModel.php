<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumneModel extends Model
{
    protected $table            = 'alumne';
    protected $primaryKey       = 'correu_alumne';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['correu_alumne', 'codi_centre', 'actiu', 'nom', 'cognoms'];

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

    public function addAlumne($d1,$d2, $d3, $d4)
    {
        $this->insert([
            "correu_alumne" => $d1,
            "codi_centre" => $d2,
            "nom" => $d3,
            "cognoms" => $d4
        ]);
    }

    public function getAlumneByCorreu($correu_alumne) 
    {
        return $this->where('correu_alumne', $correu_alumne)->first();
    }

    public function deleteAlumneByCorreu($correu_alumne) 
    {
        return $this->delete(['correu_alumne' => $correu_alumne]);
    }

    public function editarAlumneCorreu($correu_alumne_original, $correu_alumne_nova) {
        return $this->update($correu_alumne_original,["correu_alumne" => $correu_alumne_nova]);
    }

    public function editarAlumneCorreuCentre($correu_alumne_original, $correu_alumne_nova, $codi_centre_nou) {
        return $this->update($correu_alumne_original,["correu_alumne" => $correu_alumne_nova, "codi_centre" => $codi_centre_nou]);
    }

    public function editarAlumneCodiCentre($correu_alumne_original, $codi_centre_nou) {
        return $this->update($correu_alumne_original,["codi_centre" => $codi_centre_nou]);
    }

    public function editarAlumneActiu($correu_alumne, $actiu)
    {
        return $this->update($correu_alumne, ["actiu" => $actiu]);
    }
}
