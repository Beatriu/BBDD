<?php

namespace App\Models;

use CodeIgniter\Model;

class TiquetModel extends Model
{
    protected $table            = 'tiquet';
    protected $primaryKey       = 'id_tiquet';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tiquet','codi_equip','descripcio_avaria','nom_persona_contacte_centre','correu_persona_contacte_centre','data_alta','data_ultima_modificacio','id_tipus_dispositiu','id_estat','codi_centre_emissor','codi_centre_reparador','preu_total','id_sstt'];

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

    public function addTiquet($id_tiquet,$codi_equip,$descripcio_avaria,$nom_persona_contacte_centre,$correu_persona_contacte_centre,$data_alta,$data_ultima_modificacio,$id_tipus_dispositiu,$id_estat,$codi_centre_emissor,$codi_centre_reparador,$id_sstt)
    {
        $data = [
            "id_tiquet" => $id_tiquet,
            "codi_equip" => $codi_equip,
            "descripcio_avaria" => $descripcio_avaria,
            "nom_persona_contacte_centre" => $nom_persona_contacte_centre,
            "correu_persona_contacte_centre" => $correu_persona_contacte_centre,
            "data_alta" => $data_alta,
            "data_ultima_modificacio" => $data_ultima_modificacio,
            "id_tipus_dispositiu" => $id_tipus_dispositiu,
            "id_estat" => $id_estat,
            "codi_centre_emissor" => $codi_centre_emissor,
            "codi_centre_reparador" => $codi_centre_reparador,
            "id_sstt" => $id_sstt,
        ];

        $this->insert($data);
    }

    public function getTiquets()
    {
        return $this->findAll();
    }

    public function getTiquetById($id_tiquet){
        return $this->where('id_tiquet',$id_tiquet)->first();
    }

    public function getTiquetByCodiCentreReparadorEstat($codi_centre_reparador) 
    {
        return $this->where('codi_centre_reparador', $codi_centre_reparador)->findAll();
    }

    public function deleteTiquetById($id_tiquet)
    {
        return $this->delete(['id_tiquet' => $id_tiquet]);
    }

    public function updateTiquet($id_tiquet, $data) 
    {
        return $this->update($id_tiquet, $data);
    }

}
