<?php

include_once("generic_class/class.PGDAO.php");

class PromocionPGDAO extends PGDAO {

    protected $INSERT = "INSERT INTO #dbName#.promocion (id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,fec_ini,fec_fin,visible) values (#id_fpromo#,'#titulo#','#empresa#', '#medio#', '#ubicacion#', '#comentario#', '#imagen1#', '#imagen2#','#imagen3#','#imagen4#',STR_TO_DATE('#fec_ini#', '%d-%m-%Y'),STR_TO_DATE('#fec_fin#', '%d-%m-%Y'),#visible#)";
    protected $DELETE = "DELETE FROM #dbName#.promocion WHERE id_promo=#id_promo#";
    protected $UPDATE = "UPDATE #dbName#.promocion SET id_fpromo=#id_fpromo#,titulo='#titulo#', empresa='#empresa#', medio='#medio#', ubicacion='#ubicacion#', comentario='#comentario#', imagen1='#imagen1#', imagen2='#imagen2#', imagen3='#imagen3#', imagen4='#imagen4#',fec_ini=STR_TO_DATE('#fec_ini#', '%d-%m-%Y'),fec_fin=STR_TO_DATE('#fec_fin#', '%d-%m-%Y'),visible=#visible# WHERE id_promo=#id_promo# ";
    protected $FINDBYCLAVE = "SELECT id_promo,id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,visible FROM #dbName#.promocion WHERE id_promo=#id_promo#";
    protected $FINDBYID = "SELECT id_promo,id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,visible FROM #dbName#.promocion WHERE id_promo=#id_promo#";
    protected $COLECCION = "SELECT id_promo,id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,visible FROM #dbName#.promocion";
    protected $COLECCIONFILTRO = "SELECT id_promo,id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,visible FROM #dbName#.promocion ";
    protected $COLECCIONACTIVAS = "SELECT id_promo,id_fpromo,titulo, empresa, medio, ubicacion, comentario, imagen1, imagen2,imagen3,imagen4,DATE_FORMAT(fec_ini,'%d-%m-%Y') as fec_ini,DATE_FORMAT(fec_fin,'%d-%m-%Y') as fec_fin,visible FROM #dbName#.promocion WHERE visible=1";
    protected $ACTIVAR = "UPDATE #dbName#.promocion SET visible=1 WHERE id_promo=#id_promo# ";
    protected $DESACTIVAR = "UPDATE #dbName#.promocion SET visible=0 WHERE id_promo=#id_promo# ";
    protected $COLECCIONCOMBO = "SELECT id_promo,CONCAT(titulo,'(', empresa,')',DATE_FORMAT(fec_ini,'%d-%m-%Y')) FROM #dbName#.promocion WHERE visible=1";

    public function activar() {
        $parametro = func_get_args();
        $resultado = $this->execSql($this->ACTIVAR,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLECCION", "ACTIVACION DE PROMOCIONES " . $this->ACTIVAR);
        }
        return $resultado;
    }

    public function desactivar() {
        $parametro = func_get_args();
        $resultado = $this->execSql($this->DESACTIVAR,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLECCION", "DESACTIVACION DE PROMOCIONES " . $this->DESACTIVAR);
        }
        return $resultado;
    }
    
    public function coleccionActivas(){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->COLECCIONACTIVAS,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLECCION", "COLECCION DE PROMOCIONES ACTIVAS " . $this->COLECCIONACTIVAS);
        }
        return $resultado;
        
    }

    public function coleccionFiltro($param=''){
        $parametro = func_get_args();
        if($param!=''){
            $where=" where CONCAT(titulo,' ', empresa,' ', medio) like '%".$param."%';";
        }
        $resultado = $this->execSql($this->COLECCIONFILTRO.$where);
        if (!$resultado) {
            $this->onError("COD_FILTRO", "COLECCION DE PROMOCIONES FILTRADAS " . $this->COLECCIONFILTRO.$where);
        }
        return $resultado;
        
    }
    
    public function coleccionCombo(){
        $parametro = func_get_args();
        $resultado = $this->execSql($this->COLECCIONCOMBO,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLECCION", "COLECCION DE PROMOCIONES PARA EL COMBO " . $this->COLECCIONCOMBO);
        }
        return $resultado;
        
    }
    
}

?>
