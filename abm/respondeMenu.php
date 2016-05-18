<?php
session_start();
ob_start();

include_once("generic_class/class.cargaConfiguracion.php");
include_once("generic_class/class.menu.php");

//echo $_POST['opcion'] . " - " . $_POST['dia'] . " - " . $_POST['mes'] . " - " . $_POST['anio'];

$conf=CargaConfiguracion::getInstance('');
$menu=new Menu();

$URL=$conf->leeParametro("particular"); // lee URL del Sitio

//print_r($_POST);
//die();

// lee opcion del menu donde registro la accion seleccionada del menu
if (isset($_POST['opcion']) && is_numeric($_POST['opcion'])){
	$_SESSION['opcionMenu']=$_POST['opcion'];
}
$seteo=0;
$op=array();
if (isset($_POST['id'])){
	$op[]='i=' . $_POST['id'];
	$seteo=1;
}
if (isset($_POST['id_tipo_prop'])){
	$op[]='i=' . $_POST['id_tipo_prop'];
	$seteo=1;
}
if (isset($_POST['id_tipo_emp'])){
	$op[]='i=' . $_POST['id_tipo_emp'];
	$seteo=1;
}
if (isset($_POST['id_prop'])){
	$op[]='i=' . $_POST['id_prop'];
	$seteo=1;
}
if (isset($_POST['id_emp'])){
	$op[]='i=' . $_POST['id_emp'];
	$seteo=1;
}
if (isset($_POST['id_tipo_carac'])){
	$op[]='i=' . $_POST['id_tipo_carac'];
	$seteo=1;
}
if (isset($_POST['id_carac'])){
	$op[]='i=' . $_POST['id_carac'];
	$seteo=1;
}
if (isset($_POST['id_emp_carac'])){

	$op[]='o=' . $_POST['id_emp_carac'];
	$seteo=1;
}
if (isset($_POST['id_ubica']) && $_POST['id_ubica']!=''){
	$op[]='i=' . $_POST['id_ubica'];
	$seteo=1;
}
/*
 if (isset($_POST['id_zona'])){
 $op[]='i=' . $_POST['id_zona'];
 $seteo=1;
 }
 if (isset($_POST['id_loca'])){
 $op[]='i=' . $_POST['id_loca'];
 $seteo=1;
 }
 */
if(isset($_POST['id_foto'])){
	$op[]='f='.$_POST['id_foto'];
	$seteo=1;
}
if(isset($_POST['id_oper'])){
	$op[]='o='.$_POST['id_oper'];
	$seteo=1;
}
if(isset($_POST['id_cartel'])){
	$op[]='o='.$_POST['id_cartel'];
	$seteo=1;
}
if(isset($_POST['id_tasacion'])){
	$op[]='o='.$_POST['id_tasacion'];
	$seteo=1;
}
if(isset($_POST['comparacion'])){
	$op[]='comp='.$_POST['comparacion'];
	$seteo=1;
}
if(isset($_POST['id_relacion'])){
	$op[]='r='.$_POST['id_relacion'];
	$seteo=1;
}
if(isset($_POST['id_tiporel'])){
	$op[]='r='.$_POST['id_tiporel'];
	$seteo=1;
}
if(isset($_POST['id_user'])){
	$op[]='i=' . $_POST['id_user'];
	$seteo=1;
}
if(isset($_POST['perfil'])){
	$op[]='l='.$_POST['perfil'];
	$seteo=1;
}
if(isset($_POST['id_cli'])){
	$op[]='c='.$_POST['id_cli'];
	$seteo=1;
}
if(isset($_POST['id_cont'])){
	$op[]='c='.$_POST['id_cont'];
	$seteo=1;
}
if(isset($_POST['id_rubro'])){
	$op[]='u='.$_POST['id_rubro'];
	$seteo=1;
}
if(isset($_POST['id_impuesto'])){
	$op[]='imp='.$_POST['id_impuesto'];
	$seteo=1;
}
if(isset($_POST['id_contrato'])){
    $op[]='cnt='.$_POST['id_contrato'];
        $seteo=1;
}
if(isset($_POST['id_promo'])){
    $op[]='promo='.$_POST['id_promo'];
        $seteo=1;
}


if($seteo==0){
	$parametro='i=0';
}else{
    $parametro='';
	foreach ($op as $opcion){
		$parametro.=($opcion."&");
	}
	$parametro=substr($parametro, 0,-1);
}

tomarAccion($_SESSION['opcionMenu'],$parametro);

function tomarAccion($accion, $op){
	$menu=new Menu();

	switch ($accion){
		case 0:
			die();
		case 1:
			$menu->redireccionURL("index.php?$op");
			break;
		case 2:
		case 100000:
			$menu->redireccionURL("lista_propiedad.php?$op");
			break;
		case 3:
		case 100001:
			$menu->redireccionURL("lista_emprendimiento.php?$op");
			break;
		case 4:
			$menu->redireccionURL("lista_propiedad.php?$op");
			break;
		case 5:
		case 100002:
			$menu->redireccionURL("lista_contactos.php?c=0");
			break;
		case 7:
			$menu->redireccionURL("lista_log.php?$op");
			//$menu->redireccionURL("filtro_datosprop.php?i=");
			break;
		case 10:
		case 100004:
			$menu->redireccionURL("lista_tipo_prop.php?$op");
			break;
		case 11:
		case 100005:
			$menu->redireccionURL("lista_tipo_carac.php?$op");
			break;
		case 12:
		case 100006:
			$menu->redireccionURL("lista_caracteristica.php?$op");
			break;
		case 21:
			$menu->redireccionURL("carga_propiedad.php?i=");
			break;
		case 22:			// Edita Propiedad	2
			$menu->redireccionURL("carga_propiedad.php?$op");
			break;
		case 23:			// Borra Propiedades  2
			$menu->redireccionURL("confirmaborra_propiedad.php?$op");
			//			$menu->redireccionURL("borra_propiedad.php?$op");
			break;
		case 24:			// Borra Propiedades  2
			$menu->redireccionURL("carga_datosprop.php?$op");
			break;
		case 25:			// Borra Propiedades  2
		case 100012:
			if($op=='i=' && isset($_SESSION['id_prop']) && is_numeric($_SESSION['id_prop'])){
				$op='i='.$_SESSION['id_prop'];
			}
			$menu->redireccionURL("lista_fotos.php?$op");
			session_unregister('id_prop');
			break;
		case 26:			// Borra Propiedades  2
			if($op=='i=' && isset($_SESSION['id_prop']) && is_numeric($_SESSION['id_prop'])){
				$op='i='.$_SESSION['id_prop'];
			}
			$menu->redireccionURL("lista_operaciones.php?$op");
			session_unregister('id_prop');
			break;
		case 27:			// Borra Propiedades  2
			if($op=='i=' && isset($_SESSION['id_prop']) && is_numeric($_SESSION['id_prop'])){
				$op='i='.$_SESSION['id_prop'];
			}
			$menu->redireccionURL("lista_carteles.php?$op");
			session_unregister('id_prop');
			break;
		case 20:			// Clons Propiedades  2
			$menu->redireccionURL("clona_propiedad.php?$op&rep=0");
			break;

		case 28:			// Borra Propiedades  2
			$menu->redireccionURL("carga_contactoZP.php?$op");
			break;
		case 281:			// Nueva Propiedad	2
			$menu->redireccionURL("publicar_propiedad.php?$op");
			break;
		case 282:			// Nueva Propiedad	2
			$menu->redireccionURL("retirar_propiedad.php?$op");
			break;
		case 283:			// Nueva Propiedad	2
		case 100016:
			$menu->redireccionURL("lista_tasaciones.php?$op");
			break;
		case 31:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_emprendimiento.php?i=");
			break;
		case 32:			// Edita Propiedad	2
			$menu->redireccionURL("carga_emprendimiento.php?$op");
			break;
		case 33:			// Borra Propiedades  2
			$menu->redireccionURL("borra_emprendimiento.php?$op");
			break;
		case 34:			// Borra Propiedades  2
		case 100013:
			if($op=='i=' && isset($_SESSION['id_emp']) && is_numeric($_SESSION['id_emp'])){
				$op='i='.$_SESSION['id_emp'];
			}
			$menu->redireccionURL("lista_datosemp.php?$op");
			session_unregister('id_emp');
			break;
		case 35:			// Edita Propiedad	2
			$menu->redireccionURL("publicar_emprendimiento.php?$op");
			break;
		case 36:			// Borra Propiedades  2
			$menu->redireccionURL("retirar_emprendimiento.php?$op");
			break;
		case 51:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_contacto.php?c=0");
			break;
		case 52:			// Edita Propiedad	2
			$menu->redireccionURL("carga_contacto.php?$op");
			break;
		case 53:			// Borra Propiedades  2
			$menu->redireccionURL("borra_contacto.php?$op");
			break;
		case 101:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tipo_prop.php?i=");
			break;
		case 102:			// Edita Propiedad	2
			$menu->redireccionURL("carga_tipo_prop.php?$op");
			break;
		case 103:			// Borra Propiedades  2
			$menu->redireccionURL("borra_tipo_prop.php?$op");
			break;
		case 111:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tipo_carac.php?i=");
			break;
		case 112:			// Edita Propiedad	2
			$menu->redireccionURL("carga_tipo_carac.php?$op");
			break;
		case 113:			// Borra Propiedades  2
			$menu->redireccionURL("borra_tipo_carac.php?$op");
			break;
		case 114:			// Edita Propiedad	2
			$menu->redireccionURL("subir_tipocarac.php?$op");
			break;
		case 115:			// Borra Propiedades  2
			$menu->redireccionURL("bajar_tipocarac.php?$op");
			break;
		case 121:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_caracteristica.php?i=");
			break;
		case 122:			// Edita Propiedad	2
			$menu->redireccionURL("carga_caracteristica.php?$op");
			break;
		case 123:			// Borra Propiedades  2
			$menu->redireccionURL("borra_caracteristica.php?$op");
			break;
		case 124:			// Edita Propiedad	2
			$menu->redireccionURL("subir_carac.php?$op");
			break;
		case 125:			// Borra Propiedades  2
			$menu->redireccionURL("bajar_carac.php?$op");
			break;
		case 126:
			$menu->redireccionURL("lista_buscadorcarac.php?$op");
			break;
		case 1261:
			$menu->redireccionURL("habilitar_carac.php?$op");
			break;
		case 1262:
			$menu->redireccionURL("quitar_carac.php?$op");
			break;

		case 13:		//Zona
		case 100007:
			$menu->redireccionURL("lista_ubicacionpropiedad.php?i=");
			break;
		case 131:		// nueva Zona
			$menu->redireccionURL("carga_ubicacionpropiedad.php?$op");
			break;
		case 132:		//editar Zona
			$menu->redireccionURL("carga_ubicacionpropiedad.php?$op");
			break;
		case 133:		//borrar Zona
			$menu->redireccionURL("borra_ubicacionpropiedad.php?$op");
			break;
		case 18:		//Rubros
		case 100011:
			$menu->redireccionURL("lista_rubros.php?u=");
			break;
		case 181:		// nueva
			$menu->redireccionURL("carga_rubro.php?$op");
			break;
		case 182:		//editar Zona
			$menu->redireccionURL("carga_rubro.php?$op");
			break;
		case 183:		//borrar Zona
			$menu->redireccionURL("borra_rubro.php?$op");
			break;

			/*
			 case 13:		//Zona
			 $menu->redireccionURL("lista_zona.php?i=");
			 break;
			 case 131:		// nueva Zona
			 $menu->redireccionURL("carga_zona.php?$op");
			 break;
			 case 132:		//editar Zona
			 $menu->redireccionURL("carga_zona.php?$op");
			 break;
			 case 133:		//borrar Zona
			 $menu->redireccionURL("borra_zona.php?$op");
			 break;
			 case 14:		//localidad
			 $menu->redireccionURL("lista_localidad.php?i=");
			 break;
			 case 141:
			 $menu->redireccionURL("carga_localidad.php?$op");
			 break;
			 case 142:
			 $menu->redireccionURL("carga_localidad.php?$op");
			 break;
			 */
		case 15:
		case 100008:
			$menu->redireccionURL("lista_tipo_emp.php?$op");
			break;
		case 151:
			$menu->redireccionURL("carga_tipo_emp.php?i=");
			break;
		case 152:
			$menu->redireccionURL("carga_tipo_emp.php?$op");
			break;
		case 153:
			$menu->redireccionURL("borra_tipo_emp.php?$op");
			break;
		case 16:
		case 100009:
			$menu->redireccionURL("lista_caracteristicaemp.php?$op");
			break;
		case 161:
			$menu->redireccionURL("carga_caracteristicaemp.php?i=");
			break;
		case 162:
			$menu->redireccionURL("carga_caracteristicaemp.php?$op");
			break;
		case 163:
			$menu->redireccionURL("borra_caracteristicaemp.php?$op");
			break;
		case 164:
			$menu->redireccionURL("subir_caracemp.php?$op");
			break;
		case 165:
			$menu->redireccionURL("bajar_caracemp.php?$op");
			break;
		case 17:
		case 100010:
			$menu->redireccionURL("lista_tiporelacion.php?$op");
			break;
		case 171:
			$menu->redireccionURL("carga_tiporelacion.php?r=");
			break;
		case 172:
			$menu->redireccionURL("carga_tiporelacion.php?$op");
			break;
		case 173:
			$menu->redireccionURL("borra_tiporelacion.php?$op");
			break;

		case 251:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_foto.php?".$op."&f=0");
			break;
		case 253:			// Borra Propiedades  2
			$menu->redireccionURL("borra_foto.php?$op");
			break;
		case 254:			// Edita Propiedad	2
			$menu->redireccionURL("subir_foto.php?$op");
			break;
		case 255:			// Borra Propiedades  2
			$menu->redireccionURL("bajar_foto.php?$op");
			break;
		case 261:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_operacion.php?".$op."&f=0");
			break;
		case 263:			// Borra Propiedades  2
			$menu->redireccionURL("borra_operacion.php?$op");
			break;
		case 271:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_cartel.php?".$op."&f=0");
			break;
		case 273:			// Borra Propiedades  2
			$menu->redireccionURL("borra_cartel.php?$op");
			break;
		case 2831:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tasacion.php?".$op."&f=0");
			break;
		case 2832:			// Nueva Propiedad	2
			$menu->redireccionURL("carga_tasacion.php?".$op."&f=0");
			break;
		case 2833:			// Borra Propiedades  2
			$menu->redireccionURL("borra_tasacion.php?$op");
			break;

		case 341:
			$menu->redireccionURL("carga_datosemp.php?$op");
			break;
		case 342:
			$menu->redireccionURL("carga_datosemp.php?$op");
			break;
		case 343:
			$menu->redireccionURL("borra_datosemp.php?$op");
			break;
		case 344:
		case 100017:
			if($op=='o=' && isset($_SESSION['id_emp_carac']) && is_numeric($_SESSION['id_emp_carac'])){
				$op='o='.$_SESSION['id_emp_carac'];
			}
			$menu->redireccionURL("lista_fotosemp.php?$op");
			session_unregister('id_emp_carac');
			break;
		case 345:
			$menu->redireccionURL("publicar_datosemp.php?$op");
			break;
		case 346:
			$menu->redireccionURL("retirar_datosemp.php?$op");
			break;
		case 3441:
			$menu->redireccionURL("carga_fotoemp.php?$op");
			break;
		case 3442:
			$menu->redireccionURL("carga_fotoemp.php?$op");
			break;
		case 3443:
			$menu->redireccionURL("borra_fotoemp.php?$op");
			break;
		case 3444:
			$menu->redireccionURL("subir_fotoemp.php?$op");
			break;
		case 3445:
			$menu->redireccionURL("bajar_fotoemp.php?$op");
			break;
		case 143:		//borrar localidad
			$menu->redireccionURL("borra_localidad.php?$op");
			break;

		case 6:
		case 100003:
			$menu->redireccionURL("lista_clientes.php?$op");
			break;
		case 61:
			$menu->redireccionURL("carga_cliente.php?c=0");
			break;
		case 62:
			$menu->redireccionURL("carga_cliente.php?$op");
			break;
		case 63:
			$menu->redireccionURL("borra_cliente.php?$op");
			break;


		case 8:
			$menu->redireccionURL("lista_perfil.php");
			break;
		case 81:
		case 100014:
			$menu->redireccionURL("lista_usuarios.php");
			break;
		case 82:
		case 100015:
			$menu->redireccionURL("lista_perfil.php");
			break;
		case 811:
			$menu->redireccionURL("cargadatosLogon.php?i=0");
			break;
		case 812:
			$menu->redireccionURL("cargadatosLogon.php?$op");
			break;
		case 813:
			$menu->redireccionURL("borra_usuario.php?$op");
			break;
		case 814:
			$menu->redireccionURL("activa_usuario.php?$op");
			break;
		case 815:
			$menu->redireccionURL("desactiva_usuario.php?$op");
			break;
		case 816:
			$menu->redireccionURL("asignaPerfil.php?$op");
			break;
		case 817:
			$menu->redireccionURL("cambio_claveUser.php?i=".$_SESSION['UserId']);
			break;
		case 818:
			$menu->redireccionURL("desbloquea_usuario.php?$op");
			break;
		case 821:
			$menu->redireccionURL("carga_perfil.php?$op");
			break;
		case 822:
			$menu->redireccionURL("carga_perfil.php?$op");
			break;
		case 823:
			$menu->redireccionURL("borra_perfil.php?$op");
			break;
		case 824:
		case 100018:
			$menu->redireccionURL("lista_perfilWU.php?$op");
			break;
		case 8241:
			$menu->redireccionURL("carga_perfilWU.php?$op");
			break;
		case 8242:
			$menu->redireccionURL("carga_perfilWU.php?$op");
			break;
		case 8243:
			$menu->redireccionURL("borra_perfilWU.php?$op");
			break;
/*
 *                 case 1000:

                case 10004:
                        $menu->redireccionURL("lista_impuesto.php?imp=0");
                    break;
                case 10001:
                    $menu->redireccionURL("carga_impuesto.php?imp=0");
                    break;
               case 10002:
                   $menu->redireccionURL("carga_impuesto.php?$op");
                   break;
               case 10003:
                   $menu->redireccionURL("borra_impuesto.php?$op");
                   break;
*/
                case 1000:
                case 100019:
                        $menu->redireccionURL("lista_promocion.php?promo=0");
                    break;
                case 1001:
                    $menu->redireccionURL("carga_promocion.php?promo=0");
                    break;
               case 1002:
                   $menu->redireccionURL("carga_promocion.php?$op");
                   break;
               case 1003:
                   $menu->redireccionURL("borra_promocion.php?$op");
                   break;
               
		case 29:
			unset ($_SESSION['filtro']);
		case 59:
		case 519:
		case 529:
		case 69:
		case 209:
		case 619:
		case 629:
		case 139:
		case 1269:
		case 219:
		case 229:
		case 159:
		case 169:
		case 179:
		case 1719:
		case 1729:
		case 179:
		case 79:
		case 3449:
		case 39:
		case 269:
		case 119:
		case 1119:
		case 1129:
		case 129:
		case 249:
		case 1219:
		case 1229:
		case 279:
		case 109:
		case 1019:
		case 1029:
		case 259:
		case 2519:
		case 2529:
		case 2619:
		case 2719:
		case 2839:
		case 28319:
		case 28329:
		case 149:
		case 89:
		case 819:
		case 829:
		case 8119:
		case 8129:
		case 8219:
		case 8229:
		case 8249:
		case 82419:
		case 82429:
		case 189:
		case 1819:
		case 1829:
			irMenuAnterior();
			break;

			//*************************   HASTA ACAC  *********************************************************


		case 9:		// Salir	Salir de la Aplicacion	0
			session_unset();
			session_destroy();
			die("Gracias por utilizar esta aplicaci&oacute;n.");

		default:
			if($_SESSION['opcionMenu']!=0){
				irMenuAnterior();
				break;
			} else {
				echo "Sale por default";
				$_SESSION['opcionMenu']=0;
				$menu->redireccionURL("index.php");
			}
			break;
	}
}

function controlSeleccion($_id){
	$retorno=true;
	if($_id == ""){
		$retorno=false;
	}
	return $retorno;
}

function irMenuAnterior(){
	$menu=new Menu();
	$opcion=$menu->menuAnterior($_SESSION['opcionMenu']);
	$_SESSION['opcionMenu']=$opcion;
	if($opcion!=0){
		$menu->redireccionURL("respondeMenu.php");
	} else {
		$menu->redireccionURL("index.php");
	}

}

?>