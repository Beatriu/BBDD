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

    public function obtenirTiquetTipusDispositiu($id_tipus_dispositiu)
    {
        return $this->where('id_tipus_dispositiu', $id_tipus_dispositiu)->findAll();
    }



    public function countNombreDispositiusTotsEstatsSenseTipus()
    {
        return $this->select('codi_centre_reparador')->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->groupBy('codi_centre_reparador')->findAll();
    }
    public function countNombreDispositiusTotsEstatsSenseTipusSSTT()
    {
        return $this->select('id_sstt')->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->groupBy('id_sstt')->findAll();
    }
    public function countNombreDispositiusTotsEstatsSenseTipusTOTAL()
    {
        return $this->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->findAll();
    }
    public function countTiquetsSenseTipus()
    {
        return $this->select('codi_centre_emissor')->selectCount('id_tiquet','num_tiquets')->groupBy('codi_centre_emissor')->findAll();
    }

    public function countNombreDispositiusEstatSenseTipus($id_estat)
    {
        return $this->select('codi_centre_reparador')->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->groupBy('codi_centre_reparador')->findAll();
    }
    public function countNombreDispositiusEstatSenseTipusSSTT($id_estat)
    {
        return $this->select('id_sstt')->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->groupBy('id_sstt')->findAll();
    }
    public function countNombreDispositiusEstatSenseTipusTOTAL($id_estat)
    {
        return $this->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->findAll();
    }

    public function countNombreDispositiusTotsEstatsTotsTipus()
    {
        return $this->select('codi_centre_reparador')->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->groupBy('codi_centre_reparador')->groupBy('id_tipus_dispositiu')->findAll();
    }
    public function countNombreDispositiusTotsEstatsTotsTipusSSTT()
    {
        return $this->select('id_sstt')->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->groupBy('id_sstt')->groupBy('id_tipus_dispositiu')->findAll();
    }
    public function countNombreDispositiusTotsEstatsTotsTipusTOTAL()
    {
        return $this->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',9)->orWhere('id_estat',10)->orWhere('id_estat',11)->groupBy('id_tipus_dispositiu')->findAll();
    }
    public function countTiquetsTotsTipus()
    {
        return $this->select('codi_centre_emissor')->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->groupBy('codi_centre_emissor')->groupBy('id_tipus_dispositiu')->findAll();
    }

    public function countNombreDispositiusEstatTotsTipus($id_estat) {
        return $this->select('codi_centre_reparador')->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->groupBy('codi_centre_reparador')->groupBy('id_tipus_dispositiu')->findAll();
    }
    public function countNombreDispositiusEstatTotsTipusSSTT($id_estat) {
        return $this->select('id_sstt')->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->groupBy('id_sstt')->groupBy('id_tipus_dispositiu')->findAll();
    }
    public function countNombreDispositiusEstatTotsTipusTOTAL($id_estat) {
        return $this->select('id_tipus_dispositiu')->selectCount('id_tiquet','num_tiquets')->where('id_estat',$id_estat)->groupBy('id_tipus_dispositiu')->findAll();
    }

    public function countNombreDispositiusTotsEstatsTipus($id_tipus_dispositiu)
    {
        return $this->select('codi_centre_reparador')->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->groupStart()->where('id_estat', 9)->orWhere('id_estat', 10)->orWhere('id_estat', 11)->groupEnd()->groupBy('codi_centre_reparador')->findAll();
    }
    public function countNombreDispositiusTotsEstatsTipusSSTT($id_tipus_dispositiu)
    {
        return $this->select('id_sstt')->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->groupStart()->where('id_estat', 9)->orWhere('id_estat', 10)->orWhere('id_estat', 11)->groupEnd()->groupBy('id_sstt')->findAll();
    }
    public function countNombreDispositiusTotsEstatsTipusTOTAL($id_tipus_dispositiu)
    {
        return $this->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->groupStart()->where('id_estat', 9)->orWhere('id_estat', 10)->orWhere('id_estat', 11)->groupEnd()->findAll();
    }
    public function countTiquetsTipus($id_tipus_dispositiu)
    {
        return $this->select('codi_centre_emissor')->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->groupBy('codi_centre_emissor')->findAll();
    }

    public function countNombreDispositiusEstatTipus($id_estat, $id_tipus_dispositiu)
    {
        return $this->select('codi_centre_reparador')->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->where('id_estat', $id_estat)->groupBy('codi_centre_reparador')->findAll();
    }
    public function countNombreDispositiusEstatTipusSSTT($id_estat, $id_tipus_dispositiu)
    {
        return $this->select('id_sstt')->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->where('id_estat', $id_estat)->groupBy('id_sstt')->findAll();
    }
    public function countNombreDispositiusEstatTipusTOTAL($id_estat, $id_tipus_dispositiu)
    {
        return $this->select('id_tipus_dispositiu')->selectCount('id_tiquet', 'num_tiquets')->where('id_tipus_dispositiu', $id_tipus_dispositiu)->where('id_estat', $id_estat)->findAll();
    }

}
