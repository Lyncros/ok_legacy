<?php
header('Content-type: text/html; charset=utf-8');
require_once('lib-nusoap/nusoap.php');

//$wsdl="http://localhost/okeefe/webservice/servicioweb.php?wsdl";
$wsdl="http://abm.okeefe.com.ar/webservice/servicioweb.php?wsdl"; //url del webservice que invocaremos
$client=new nusoap_client($wsdl,'wsdl'); //instanciando un nuevo objeto cliente para consumir el webservice

//¿ocurrio error al llamar al web service?
if ($client->fault) { // si
    echo 'No se pudo completar la operación';
    die();
}else { // no
    $error = $client->getError();
    if ($error) { // Hubo algun error
        echo 'Error:' . $error;
    }
}

$param=array('id_padre' => $_GET['zona']);
$loca = $client->call('ListarZonasDependientesActivas',$param,'');

if(isset($_GET['loca'])){
	$getloca = explode(',', $_GET['loca']);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Listado de Localidades</title>
	<script type="text/javascript" language="JavaScript">
	

	function expandirLista(elem){
		if(document.getElementById('dd_'+elem).style.display=='none'){
			document.getElementById('dd_'+elem).style.display='block';
			document.getElementById('sp_'+elem).src='images/fabajo.gif';
		}else{
			document.getElementById('dd_'+elem).style.display='none';
			document.getElementById('sp_'+elem).src='images/fderecha.gif';

		}
	}
	function enviarSeleccion(f){
		retorno=leeCheckbox(f);
		window.opener.document.buscador.opcLocalidad.value=retorno[0];
		//window.opener.document.getElementById('txtUbica').innerHTML=retorno[1];
		window.close();
	}
	
	function leeCheckbox(f) {
		seleccion='';
		campos='';
		retorno=new Array(2);
		elementos=f.elements.length;
		for ( var n= 0 ; n < elementos; n++ ) {
			largo=f.elements[n].name.length;
			pref=f.elements[n].name.substring(0,2);
			pid=f.elements[n].name.substring(3,largo);
		    if(f.elements[n].checked && pref=='sz'){
				if(seleccion.length>0){
					seleccion+=',';
					campos+=', ';
				}
				seleccion+=pid;
				campos+=f.elements[n].title;
			}
		}
 		retorno[0]=seleccion;
		retorno[1]=campos;
		return retorno;
	}

	</script>
	<link href="css/okeefeVentanas.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
    <form name="listaSubZonas" id="listaSubZonas">
      <div style="width: 350px; height: 320px;" id="contenedor">
        <div style="position:fixed; left: 10px; top: 10px; width: 320px; height: 300px; overflow: auto;" id="left">
          <dl>
            <?php foreach($loca as $id){ 
					if($id['id_padre'] == $_GET['zona']){
			?>
            <dt>
              <input type="checkbox" id="sz_<?php echo $id['id_ubica']; ?>" name="sz_<?php echo $id['id_ubica']; ?>"  title='<?php echo $id['nombre_ubicacion']; ?>' <?php if(in_array($id['id_ubica'], $getloca)){ echo "checked"; } ?> />
              <label id="lz_<?php echo $id['id_ubica']; ?>" title="<?php echo $id['nombre_ubicacion']; ?>"><?php echo $id['nombre_ubicacion']; ?></label>
              <img src="images/fderecha.gif" width="8" height="9" id="sp_sz_<?php echo $id['id_ubica']; ?>" onclick="expandirLista('sz_<?php echo $id["id_ubica"]; ?>');" style="cursor:pointer;" /> </dt>
            <dd id='dd_sz_<?php echo $id["id_ubica"]; ?>' style="display: none;">
              <?php
			   $z=0;
			   foreach($loca as $idsub){ 
				   		if($idsub["id_padre"] == $id["id_ubica"]){ ?>
              <ul>
                <input type="checkbox" id="sz_<?php echo $idsub['id_ubica']; ?>" name="sz_<?php echo $idsub['id_ubica']; ?>" title="<?php echo $idsub['nombre_ubicacion']; ?>" <?php if(in_array($idsub['id_ubica'], $getloca)){ echo "checked"; } ?> />
                <label for="sz_<?php echo $idsub['id_ubica']; ?>" title="<?php echo $idsub['nombre_ubicacion']; ?>"><?php echo $idsub['nombre_ubicacion']; ?></label>
              </ul>
              <?php if(in_array($idsub['id_ubica'], $getloca) && $z==0){
				  		$z++;
				   		echo "<script type=\"text/javascript\">\n"; 
				   		echo "expandirLista('sz_". $id['id_ubica']."');\n";
						echo "document.getElementById('lz_". $id['id_ubica']."').style.fontWeight='bold';\n";
				   		echo "</script>\n"; 
				   } ?>
              
              <?php 	}
			 		 } ?>
            </dd>
            <?php } 
			}?>
          </dl>
        </div>
      </div>
      <div style="text-align:right; margin-right:20px;">
        <input type='button' value='Seleccionar...' onclick='enviarSeleccion(this.form);' /></div>
    </form>
</body>
</html>
