<?php
include_once("generic_class/class.PGDAO.php");
/*	CREATE TABLE  `achaval`.`propiedad` (  `id_prop` int(10) unsigned NOT NULL auto_increment,  `id_zona` varchar(100) NOT NULL,  `id_loca` varchar(100) NOT NULL,  `calle` varchar(150) NOT NULL,  `entre1` varchar(150) NOT NULL,  `entre2` varchar(150) NOT NULL,  `nro` varchar(45) NOT NULL,  `descripcion` varchar(500) NOT NULL,  `id_tipo_prop` int(10) unsigned default NULL,  	private $intermediacion;	private $id_inmo;	private $operacion;intermediacion,id_inmo,operacion*/
//  id_prop,id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop
class PropiedadPGDAO extends PGDAO {

    protected $INSERT="INSERT INTO achaval.propiedad (id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif, plano1, plano2, plano3, id_parcela, id_comercial, compartir) values ('#id_zona#','#id_loca#','#calle#','#entre1#','#entre2#','#nro#','#descripcion#',#id_tipo_prop#,'#subtipo_prop#','#intermediacion#',#id_inmo#,'#operacion#','#comentario#','#video#','#piso#','#dpto#',#id_cliente#,#goglat#,#goglong#,#activa#,'#id_sucursal#',#id_emp#,'#nomedif#', '#plano1#', '#plano2#', '#plano3#', '#id_parcela#', '#id_comercial#', #compartir#)";

    protected $DELETE="DELETE FROM achaval.propiedad WHERE id_prop=#id_prop#";

    protected $UPDATE="UPDATE achaval.propiedad SET id_zona='#id_zona#',id_loca='#id_loca#',calle='#calle#',entre1='#entre1#',entre2='#entre2#',nro='#nro#',descripcion='#descripcion#',id_tipo_prop=#id_tipo_prop#,subtipo_prop='#subtipo_prop#',intermediacion='#intermediacion#',id_inmo=#id_inmo#,operacion='#operacion#',comentario='#comentario#',video='#video#',piso='#piso#',dpto='#dpto#',id_cliente=#id_cliente#,goglat=#goglat#,goglong=#goglong#,activa=#activa#,id_sucursal='#id_sucursal#',id_emp=#id_emp#,nomedif='#nomedif#',plano1='#plano1#',plano2='#plano2#',plano3='#plano3#',id_parcela='#id_parcela#',id_comercial='#id_comercial#',compartir=#compartir# WHERE id_prop=#id_prop# ";

    protected $FINDBYID="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.entre1,p.entre2,p.nro,p.descripcion,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,o.operacion,p.comentario,p.video,p.piso,p.dpto,p.id_cliente,p.goglat,p.goglong,p.activa,p.id_sucursal,p.id_emp,p.nomedif,p.plano1,p.plano2,p.plano3,p.id_parcela,p.id_comercial,p.compartir FROM achaval.propiedad as p  INNER JOIN (SELECT q.id_prop,q.operacion FROM achaval.propiedad_operacion as q inner join ( select id_prop,max(fecha) as fecha from  achaval.propiedad_operacion group by id_prop) as j on (q.id_prop=j.id_prop and q.fecha=j.fecha)) as o ON p.id_prop=o.id_prop  WHERE p.id_prop=#id_prop#";

    protected $FINDBYCLAVE="SELECT id_prop,id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif,plano1,plano2,plano3,id_parcela,id_comercial,compartir FROM achaval.propiedad WHERE calle LIKE  '#calle#' and nro='#nro#' and piso like '#piso#' and dpto like '#dpto#' and nomedif = '#nomedif#'" ;

    protected $COLECCION="SELECT id_prop,id_zona,id_loca,calle,entre1,entre2,nro,descripcion,id_tipo_prop,subtipo_prop,intermediacion,id_inmo,operacion,comentario,video,piso,dpto,id_cliente,goglat,goglong,activa,id_sucursal,id_emp,nomedif,plano1,plano2,plano3,id_parcela,id_comercial,compartir  FROM achaval.propiedad ORDER BY id_zona,id_loca,calle,nro";

    protected $ACTIVAR="UPDATE achaval.propiedad SET activa=1 WHERE id_prop=#id_prop#";

    protected $DESACTIVAR="UPDATE achaval.propiedad SET activa=0 WHERE id_prop=#id_prop#";

    protected $CANTREGBASE="SELECT COUNT(p.id_prop) as id_prop FROM achaval.propiedad as p ";

    protected $COLECCIONBASE="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.entre1,p.entre2,p.nro,p.descripcion,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,o.operacion,p.comentario,p.video,p.piso,p.dpto,p.id_cliente,p.goglat,p.goglong,p.activa,p.id_sucursal,p.id_emp,p.nomedif, p.plano1, p.plano2, p.plano3, p.id_parcela, p.id_comercial, p.compartir FROM achaval.propiedad as p ";

    protected $JOIN = " INNER JOIN (SELECT q.id_prop,q.operacion FROM achaval.propiedad_operacion as q inner join ( select id_prop,max(fecha) as fecha from  achaval.propiedad_operacion group by id_prop) as j on (q.id_prop=j.id_prop and q.fecha=j.fecha)) as o ON p.id_prop=o.id_prop ";

    protected $COLECCIONBASEBUSCADOR="SELECT p.id_prop,p.id_zona,p.id_loca,p.calle,p.entre1,p.entre2,p.nro,p.descripcion,p.id_tipo_prop,p.subtipo_prop,p.intermediacion,p.id_inmo,o.operacion,p.comentario,p.video,p.piso,p.dpto,p.id_cliente,p.goglat,p.goglong,p.activa,p.id_sucursal,p.id_emp,p.nomedif, p.plano1, p.plano2, p.plano3, p.id_parcela, p.id_comercial, p.compartir,sc.suptot,sc.supcub,sc.cantamb,sc.monalq,sc.valalq,sc.monven,sc.valven FROM achaval.propiedad as p ";

    protected $JOINBUSCADOR = " INNER JOIN (SELECT q.id_prop,q.operacion FROM achaval.propiedad_operacion as q inner join ( select id_prop,max(fecha) as fecha from  achaval.propiedad_operacion group by id_prop) as j on (q.id_prop=j.id_prop and q.fecha=j.fecha)) as o ON p.id_prop=o.id_prop ";
    
    
    protected $JOINCARAC = " INNER JOIN (SELECT st.id_prop,st.suptot,ssc.supcub,sca.cantamb,smal.monalq,sal.valalq,smve.monven,sve.valven from
(SELECT id_prop,contenido as supcub FROM achaval.propiedad_caracteristicas WHERE id_carac=200) as ssc RIGHT JOIN
(SELECT id_prop,contenido as suptot from achaval.propiedad_caracteristicas WHERE id_carac=198) as st ON ssc.id_prop=st.id_prop
LEFT JOIN
(SELECT id_prop,contenido as cantamb FROM achaval.propiedad_caracteristicas WHERE id_carac=208) as sca ON st.id_prop=sca.id_prop
LEFT JOIN
(SELECT id_prop,contenido as monalq FROM achaval.propiedad_caracteristicas WHERE id_carac=166) as smal ON st.id_prop=smal.id_prop
LEFT JOIN
(SELECT id_prop,contenido as valalq FROM achaval.propiedad_caracteristicas WHERE id_carac=164) as sal ON st.id_prop=sal.id_prop
LEFT JOIN
(SELECT id_prop,contenido as monven FROM achaval.propiedad_caracteristicas WHERE id_carac=165) as smve ON st.id_prop=smve.id_prop
LEFT JOIN
(SELECT id_prop,contenido as valven FROM achaval.propiedad_caracteristicas WHERE id_carac=161) as sve ON st.id_prop=sve.id_prop) as sc ON sc.id_prop=p.id_prop ";
    
    protected $ORDENBASE = " ORDER BY id_zona,id_loca,calle,nro";

    public function coleccionByFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal,$pagina=1,$registros=0) {
        $parametro = func_get_args();
        $FILTRO = array();
        $where='';

        if($zona!=0) {
            $FILTRO[]="id_zona='#id_zona#'";
        }

        if($localidad!=0) {
            $FILTRO[]="id_loca='#id_loca#'";
        }
        if($tipo_prop!=0) {
//            $FILTRO[]="id_tipo_prop ='#id_tipo_prop#'";
            $FILTRO[]="id_tipo_prop IN (#id_tipo_prop#)";
            echo $tipo_prop."<br>";
        }
        if($operacion!='Todas') {
            $FILTRO[]="o.operacion='#operacion#'";
        }
        if($publicadas!='Todas') {
            $FILTRO[]="activa=#activa#";
        }
        if($sucursal!='Todas') {
            $FILTRO[]="p.id_sucursal='#id_sucursal#'";
        }
        if(sizeof($FILTRO)>0) {
            for($x=0; $x < sizeof($FILTRO)-1;$x++) {
                $where.= ($FILTRO[$x] . " AND ");

            }
            $where = " WHERE ".$where.$FILTRO[$x];
        }
        if($registros>0 ) {
            $limite = ' LIMIT '.($pagina-1)*$registros.','.$registros;
        }else {
            $limite='';
        }
        //		print_r($parametro);
        $resultado=$this->execSql($this->COLECCIONBASE.$this->JOIN.$where.$this->ORDENBASE.$limite,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
        }
        return $resultado;
    }

    public function coleccionByFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in,$pagina=1,$registros=0,$campo='',$orden=0) {
        $parametro = func_get_args();
		
		$ORDENLOCAL = " ORDER BY id_zona,id_loca,calle,nro ";
		if($campo!=''){
			$ORDENLOCAL = " ORDER BY ".$campo;			
			if($orden!=0){
				$ORDENLOCAL .= " DESC ";
			}
		}
		
		
        
        $FILTRO = array();
//        $where=' WHERE activa=1 ';

        if($zona!=0) {
            $FILTRO[]="id_zona='#id_zona#'";
        }
        if($id_emp!=0) {
            $FILTRO[]="id_emp=#id_emp#";
        }

        if($localidad!=0) {
            $FILTRO[]="id_loca='#id_loca#'";
        }
        if($tipo_prop!=0) {
            $FILTRO[]="id_tipo_prop='#id_tipo_prop#'";
        }
        if($operacion!='Todas') {
            $FILTRO[]="o.operacion='#operacion#'";
        }
        if(sizeof($FILTRO)>0) {
            for($x=0; $x < sizeof($FILTRO)-1;$x++) {
                $auxwhere.= ($FILTRO[$x] . " AND ");
            }
            $where .= (" AND ".$auxwhere.$FILTRO[$x]);
        }
        if($in!='' && $in != '0') {
            if($where!='') {
                $where .= " AND ";
            }//else{
            //	$where = " WHERE ";
            //}
            $where.="p.id_prop IN ($in)";
        }

        if($registros > 0) {
        	if($pagina > 0){
        		$regin =($pagina-1)*$registros;
        	}else{
        		$regin=0;
        	}
            $limite = ' LIMIT '.$regin.','.$registros;
        }else {
            $limite='';
        }

        //		print_r($parametro);
        $resultado=$this->execSql($this->COLECCIONBASEBUSCADOR.$this->JOINBUSCADOR.$this->JOINCARAC.$where.$ORDENLOCAL.$limite,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS x BUSCADOR");
        }
        return $resultado;
    }

    public function cantRegistrosFiltroBuscador($zona,$localidad,$tipo_prop,$operacion,$id_emp,$in) {
        $parametro = func_get_args();
        $FILTRO = array();
//        $where=' WHERE activa=1 ';

        if($zona!=0) {
            $FILTRO[]="id_zona=$zona";
        }
        if($id_emp!=0) {
            $FILTRO[]="id_emp=$id_emp";
        }

        if($localidad!=0) {
            $FILTRO[]="id_loca=$localidad";
        }
        if($tipo_prop!=0) {
            $FILTRO[]="id_tipo_prop=$tipo_prop";

        }
        if($operacion!='Todas') {
            $FILTRO[]="o.operacion='$operacion'";
        }
        if(sizeof($FILTRO)>0) {
            for($x=0; $x < sizeof($FILTRO)-1;$x++) {
                $auxwhere.= ($FILTRO[$x] . " AND ");

            }
            $where .= (" AND ".$auxwhere.$FILTRO[$x]);
        }
        if($in!='' && $in != '0') {
            if($where!='') {
                $where .= " AND ";
            }//else{
            //	$where = " WHERE ";
            //}
            $where.="p.id_prop IN ($in)";
        }
        //		print_r($parametro);
        $resultado=$this->execSql($this->CANTREGBASE.$this->JOINBUSCADOR.$this->JOINCARAC.$where.$this->ORDENBASE,$parametro);
        if (!$resultado) {
//			$resultado=0;
            $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS x BUSCADOR");
        }
        return $resultado;
    }

    public function cantidadRegistrosFiltro($zona,$localidad,$tipo_prop,$operacion,$publicadas,$sucursal) {
        $parametro = func_get_args();
        $FILTRO = array();
        $where='';

        if($zona!=0) {
            $FILTRO[]="id_zona=$zona";
        }

        if($localidad!=0) {
            $FILTRO[]="id_loca=$localidad";
        }
        if($tipo_prop!=0) {
//            $FILTRO[]="id_tipo_prop=$tipo_prop";
            $FILTRO[]="id_tipo_prop IN ($tipo_prop)";

        }
        if($operacion!='Todas') {
            $FILTRO[]="o.operacion='$operacion'";
        }
        if($publicadas!=-1) {
            $FILTRO[]="activa=$publicadas";
        }
        if($sucursal!='Todas') {
            $FILTRO[]="p.id_sucursal='$sucursal'";
        }
        if(sizeof($FILTRO)>0) {
            for($x=0; $x < sizeof($FILTRO)-1;$x++) {
                $where.= ($FILTRO[$x] . " AND ");

            }
            $where = " WHERE ".$where.$FILTRO[$x];
        }
        //		print_r($parametro);
        $resultado=$this->execSql($this->CANTREGBASE.$this->JOIN.$where.$this->ORDENBASE,$parametro);
        if (!$resultado) {
            $this->onError("COD_COLLECION","PROPIEDADES FILTRADAS");
        }
        return $resultado;
    }

    public function activar() {
        $parametro = func_get_args();
        $resultado=$this->execSql($this->ACTIVAR,$parametro);
        if (!$resultado) {
            $this->onError($COD_UPDATE,"ACTIVACION de propiedad");
        }
        return $resultado;
    }

    public function desactivar() {
        $parametro = func_get_args();
        $resultado=$this->execSql($this->DESACTIVAR,$parametro);
        if (!$resultado) {
            $this->onError($COD_UPDATE,"INACTIVACION de propiedad");
        }
        return $resultado;
    }

} // Fin clase DAO
?>