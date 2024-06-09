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

    public function getTiquetByCodiCentreEmissor($codi_centre){
        return $this->where('codi_centre_emissor', $codi_centre)->findAll();
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
    public function countTiquetsSenseTipusPOBLACIO()
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->groupBy('centre.id_poblacio, poblacio.nom_poblacio')
                        ->findAll();
    }
    public function countTiquetsSenseTipusCOMARCA()
    {
        return $this->select('centre.id_poblacio, comarca.id_comarca, comarca.nom_comarca')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                        ->groupBy('comarca.id_comarca')
                        ->findAll();
    }
    public function countTiquetsSenseTipusSSTT()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->groupBy('sstt.id_sstt')
                        ->findAll();
    }
    public function countTiquetsSenseTipusTOTAL()
    {
        return $this->selectCount('id_tiquet','num_tiquets')->findAll();
    }
    public function sumTiquetsSenseTipusCentreEmissor()
    {
        return $this->select('tiquet.codi_centre_emissor, centre.id_poblacio, centre.nom_centre')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->groupBy('tiquet.codi_centre_emissor')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusCentreReparador()
    {
        return $this->select('tiquet.codi_centre_reparador, centre.id_poblacio, centre.nom_centre')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->groupBy('tiquet.codi_centre_reparador')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusPoblacio()
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->groupBy('centre.id_poblacio')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusComarca()
    {
        return $this->select('centre.id_comarca, comarca.nom_comarca')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                    ->groupBy('centre.id_comarca')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusSSTTEmissor()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->groupBy('sstt.id_sstt')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusSSTTReparador()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->groupBy('sstt.id_sstt')
                    ->findAll();
    }
    public function sumTiquetsSenseTipusTOTAL()
    {
        return $this->selectSum('tiquet.preu_total', 'total_preu')->findAll();
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
    public function countTiquetsTotsTipusPOBLACIO()
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('centre.id_poblacio, poblacio.nom_poblacio, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTotsTipusCOMARCA()
    {
        return $this->select('centre.id_poblacio, comarca.id_comarca, comarca.nom_comarca, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('comarca.id_comarca, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTotsTipusSSTT()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTotsTipusTOTAL()
    {
        return $this->select('tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function sumTiquetsTotsTipusCentreEmissor()
    {
        return $this->select('tiquet.codi_centre_emissor, centre.id_poblacio, centre.nom_centre, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('tiquet.codi_centre_emissor, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusCentreReparador()
    {
        return $this->select('tiquet.codi_centre_reparador, centre.id_poblacio, centre.nom_centre, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('tiquet.codi_centre_reparador, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusPoblacio()
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('poblacio.id_poblacio, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusComarca()
    {
        return $this->select('centre.id_poblacio, comarca.id_comarca, comarca.nom_comarca, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('comarca.id_comarca, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusSSTTEmissor()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusSSTTReparador()
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTotsTipusTOTAL()
    {
        return $this->select('tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectSum('tiquet.preu_total', 'total_preu')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('tiquet.id_tipus_dispositiu')
                        ->findAll();
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
    public function countTiquetsTipusPOBLACIO($id_tipus_dispositiu)
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('centre.id_poblacio, poblacio.nom_poblacio, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTipusCOMARCA($id_tipus_dispositiu)
    {
        return $this->select('centre.id_poblacio, comarca.id_comarca, comarca.nom_comarca, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('comarca.id_comarca, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTipusSSTT($id_tipus_dispositiu)
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function countTiquetsTipusTOTAL($id_tipus_dispositiu)
    {
        return $this->select('tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('tiquet.id_tipus_dispositiu')
                        ->findAll();
    }
    public function sumTiquetsTipusCentreEmissor($id_tipus_dispositiu)
    {
        return $this->select('tiquet.codi_centre_emissor, centre.id_poblacio, centre.nom_centre, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('tiquet.codi_centre_emissor, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusCentreReparador($id_tipus_dispositiu)
    {
        return $this->select('tiquet.codi_centre_reparador, centre.id_poblacio, centre.nom_centre, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('tiquet.codi_centre_reparador, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusPoblacio($id_tipus_dispositiu)
    {
        return $this->select('centre.id_poblacio, poblacio.nom_poblacio, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('poblacio.id_poblacio, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusComarca($id_tipus_dispositiu)
    {
        return $this->select('centre.id_poblacio, comarca.id_comarca, comarca.nom_comarca, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('comarca', 'centre.id_comarca = comarca.id_comarca')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('comarca.id_comarca, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusSSTTEmissor($id_tipus_dispositiu)
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusSSTTReparador($id_tipus_dispositiu)
    {
        return $this->select('sstt.id_sstt, sstt.nom_sstt, tipus_dispositiu.nom_tipus_dispositiu')
                    ->selectSum('tiquet.preu_total', 'total_preu')
                    ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                    ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                    ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                    ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                    ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                    ->groupBy('sstt.id_sstt, tiquet.id_tipus_dispositiu')
                    ->findAll();
    }
    public function sumTiquetsTipusTOTAL($id_tipus_dispositiu)
    {
        return $this->select('tipus_dispositiu.nom_tipus_dispositiu')
                        ->selectSum('tiquet.preu_total', 'total_preu')
                        ->where('tiquet.id_tipus_dispositiu', $id_tipus_dispositiu)
                        ->join('tipus_dispositiu', 'tiquet.id_tipus_dispositiu = tipus_dispositiu.id_tipus_dispositiu')
                        ->groupBy('tiquet.id_tipus_dispositiu')
                        ->findAll();
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




    public function countNombreDispositiusTempsCentreReparador()
    {
        return $this->select('tiquet.codi_centre_reparador as codi_centre_reparador, centre.nom_centre, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->whereIn('tiquet.id_estat', [9, 10, 11])
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->groupBy('tiquet.codi_centre_reparador, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('tiquet.codi_centre_reparador', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countNombreDispositiusTempsCentreReparadorEstat($id_estat)
    {
        return $this->select('tiquet.codi_centre_reparador as codi_centre_reparador, centre.nom_centre, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_estat', $id_estat)
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->groupBy('tiquet.codi_centre_reparador, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('tiquet.codi_centre_reparador', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countNombreDispositiusTempsSSTT()
    {
        return $this->select('centre.id_sstt, sstt.nom_sstt, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->whereIn('tiquet.id_estat', [9, 10, 11])
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->groupBy('centre.id_sstt, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('centre.id_sstt', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countNombreDispositiusTempsSSTTEstat($id_estat)
    {
        return $this->select('centre.id_sstt, sstt.nom_sstt, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_estat', $id_estat)
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->groupBy('centre.id_sstt, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('centre.id_sstt', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countNombreDispositiusTempsTOTAL()
    {
        return $this->select('YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->whereIn('tiquet.id_estat', [9, 10, 11])
                        ->groupBy('YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countNombreDispositiusTempsTOTALEstat($id_estat)
    {
        return $this->select('YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->where('tiquet.id_estat', $id_estat)
                        ->groupBy('YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }


    public function countTiquetsTempsCentreEmissor()
    {
        return $this->select('tiquet.codi_centre_emissor, centre.nom_centre, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->groupBy('tiquet.codi_centre_emissor, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('tiquet.codi_centre_emissor', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countTiquetsTempsSSTT()
    {
        return $this->select('centre.id_sstt, sstt.nom_sstt, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->join('centre', 'tiquet.codi_centre_emissor = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->groupBy('centre.id_sstt, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('centre.id_sstt', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function countTiquetsTempsTOTAL()
    {
        return $this->select('YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes')
                        ->selectCount('tiquet.id_tiquet', 'num_tiquets')
                        ->groupBy('YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }





    public function sumTiquetsTempsCentreReparador()
    {
        return $this->select('tiquet.codi_centre_reparador, centre.nom_centre, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectSum('tiquet.preu_total', 'total_preu')
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->groupBy('tiquet.codi_centre_reparador, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('tiquet.codi_centre_reparador', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function sumTiquetsTempsSSTT()
    {
        return $this->select('centre.id_sstt, sstt.nom_sstt, YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes, centre.id_poblacio')
                        ->selectSum('tiquet.preu_total', 'total_preu')
                        ->join('centre', 'tiquet.codi_centre_reparador = centre.codi_centre')
                        ->join('poblacio', 'centre.id_poblacio = poblacio.id_poblacio')
                        ->join('sstt', 'centre.id_sstt = sstt.id_sstt')
                        ->groupBy('centre.id_sstt, YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
                        ->orderBy('centre.id_sstt', 'ASC')
                        ->orderBy('any', 'ASC')
                        ->orderBy('mes', 'ASC')
                        ->findAll();
    }
    public function sumTiquetsTempsTOTAL()
    {
        return $this->select('YEAR(tiquet.data_alta) as any, MONTH(tiquet.data_alta) as mes')
        ->selectSum('tiquet.preu_total', 'total_preu')
        ->groupBy('YEAR(tiquet.data_alta), MONTH(tiquet.data_alta)')
        ->orderBy('any', 'ASC')
        ->orderBy('mes', 'ASC')
        ->findAll();
    }

}
