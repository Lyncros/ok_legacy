<?php

include_once("generic_class/class.VW.php");
include_once("generic_class/class.menu.php");
include_once("clases/class.promocionBSN.php");
include_once("clases/class.promocion.php");
include_once("clases/class.formapromocionBSN.php");
include_once("inc/funciones.inc");
include_once('generic_class/class.upload.php');

class PromocionVW extends VW {

    protected $clase = "Promocion";
    protected $promocion;
    protected $nombreId = "id_promo";

    protected $path;
    protected $pathC;
    protected $anchoG;
    protected $anchoC;
    
    public function __construct($_parametro = 0) {
        PromocionVW::creaObjeto();
        if ($_parametro instanceof Promocion) {
            PromocionVW::seteaVW($_parametro);
        }
        if (is_numeric($_parametro)) {
            if ($_parametro != 0) {
                PromocionVW::cargaVW($_parametro);
            }
        }
        $conf = CargaConfiguracion::getInstance();        
        $this->path = $conf->leeParametro('path_fotos');
        $this->pathC = $conf->leeParametro('path_fotos_chicas');
        $this->anchoC = $conf->leeParametro('ancho_foto_chica');
        $this->anchoG = $conf->leeParametro('ancho_foto_grande');
        
        PromocionVW::cargaDefinicionForm();
    }

    public function cargaDatosVW() {
        print "<div id='cargaData' name='cargaData' >";
        print "<form action='carga_promocion.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaPromocion(this);'>\n";

        print "<div class='pg_titulo'>Carga de Forma de Promocion</div>\n";

        print "<table width='700' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Titulo</td>";
        print "<td width='85%'><input class='campos' type='text' name='titulo' id='titulo' value='" . $this->arrayForm['titulo'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

        print "<tr>";
        print "<td class='cd_celda_texto' width='15%'>Forma de Promocion</td>";
        print "<td width='85%'>";
        $fpromo = FormapromocionBSN::getInstance();
        $fpromo->comboParametros($this->arrayForm ['id_fpromo'], 0, 'id_fpromo');//Formapromocion($valor = $this->arrayForm['id_fpromo']);
        print "</span></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Empresa</td>";
        print "<td width='85%'><input class='campos' type='text' name='empresa' id='empresa' value='" . $this->arrayForm['empresa'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Medio</td>";
        print "<td width='85%'><input class='campos' type='text' name='medio' id='medio' value='" . $this->arrayForm['medio'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Ubicacion</td>";
        print "<td width='85%'><input class='campos' type='text' name='ubicacion' id='ubicacion' value='" . $this->arrayForm['ubicacion'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Comentario</td>";
        print "<td width='85%'><input class='campos' type='text' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "' maxlength='250' size='80'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Imagen 1</td>";
        print "<td width='35%'><input type='hidden' name='imagen1' id='imagen1' value='" . $this->arrayForm['imagen1'] . "'>" . $this->arrayForm['imagen1'] . " <input type='checkbox' name='bimagen1' id='bimagen1' >Marque la casilla para eliminar la Imagen<br />";
        print "	<input type='file' name='himagen1' id='himagen1' maxlength='200'>";
        print "</td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Imagen 2</td>";
        print "<td width='35%'><input type='hidden' name='imagen2' id='imagen2' value='" . $this->arrayForm['imagen2'] . "'>" . $this->arrayForm['imagen2'] . " <input type='checkbox' name='bimagen2' id='bimagen2' >Marque la casilla para eliminar la Imagen<br />";
        print "	<input type='file' name='himagen2' id='himagen2' maxlength='200'>";
        print "</td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Imagen 3</td>";
        print "<td width='35%'><input type='hidden' name='imagen3' id='imagen3' value='" . $this->arrayForm['imagen3'] . "'>" . $this->arrayForm['imagen3'] . " <input type='checkbox' name='bimagen3' id='bimagen3' >Marque la casilla para eliminar la Imagen<br />";
        print "	<input type='file' name='himagen3' id='himagen3' maxlength='200'>";
        print "</td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Imagen 4</td>";
        print "<td width='35%'><input type='hidden' name='imagen4' id='imagen4' value='" . $this->arrayForm['imagen4'] . "'>" . $this->arrayForm['imagen4'] . " <input type='checkbox' name='bimagen4' id='bimagen4' >Marque la casilla para eliminar la Imagen<br />";
        print "	<input type='file' name='himagen4' id='himagen4' maxlength='200'>";
        print "</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Inicio promo</td>";
        print "<td width='85%'><input class='campos' type='text' name='fec_ini' id='fec_ini' value='" . $this->arrayForm['fec_ini'] . "' maxlength='20' size='10'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#fec_ini\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\", defaultDate: '0w', selectDefaultDate: true });\n";
        print "</script>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Fin promo</td>";
        print "<td width='85%'><input class='campos' type='text' name='fec_fin' id='fec_fin' value='" . $this->arrayForm['fec_fin'] . "' maxlength='20' size='10'></td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#fec_fin\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\", defaultDate: '0w', selectDefaultDate: true });\n";
        print "</script>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Visible</td>";
        print "<td width='85%'><input type='checkbox' name='visible' id='visible' ";
        if ($this->arrayForm['visible'] == 1) {
            print "checked>";
        } else {
            print ">";
        }
        print "</td></tr>\n";

        print "<input type='hidden' name='id_promo' id='id_promo' value='" . $this->arrayForm['id_promo'] . "'>\n";

        print "<br>";
        print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
        print "</div>";
    }

    public function leeDatosVW() {
        if(isset($_POST['visible']) && $_POST['visible']=='on'){
            $_POST['visible']=1;
        }else{
            $_POST['visible']=0;
        }
        $prefijo='PR';
        for ($x = 1; $x < 5; $x++) {
            $nombre1='';
            if (isset($_POST['bimagen'.$x]) && $_POST['bimagen'.$x] == "on") {
                $this->borraImagen($_POST['imagen'.$x]);
                $nombre1 = "";
            } else {
                if (trim($_FILES['himagen'.$x]['name'])!='' ){
                    $nombre1 = $this->uploadImagen('himagen'.$x, $_POST['id_promo'],$prefijo);
                }else{
                    if (trim($_POST['imagen'.$x]) != '') {
                        $nombre1 = $_POST['imagen'.$x];
                    }
                }
            }
            $_POST['imagen'.$x] = $nombre1;
        }
        
        parent::leeDatosVW();
    }

    public function vistaTablaVW() {
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_promo.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function filtro(opc){\n";
        print "  if(opc==0){\n";
        print "     param='';\n";
        print "     document.getElementById('valorFiltro').value='';\n";
        print "  }else{\n";
        print "     param=document.getElementById('valorFiltro').value;\n";
        print "  }\n";
        print "  destino='vistaTabla'\n";
        print "  filtraPromocion(param,destino);\n";
        print "}\n";
        print "function muestraDatos(opc){\n";
        print "  if(opc!=0){\n";
        print "     destino='vistaDatosPromo'\n";
        print "     muestraPromo(opc,destino);\n";
        print "  }\n";
        print "}\n";
        print "function showHideDesc(campo,imagen){\n";
        print "  if(document.getElementById(campo).style.display=='none'){\n";
        print "   document.getElementById(campo).style.display='block';\n";
        print "   document.getElementById(imagen).src='images/up.png';\n";
        print "  }else{\n";
        print "   document.getElementById(campo).style.display='none';\n";
        print "   document.getElementById(imagen).src='images/down.png';\n";
        print "  }\n";
        print "}\n";
        print "</script>\n";
        print "<div class='pg_titulo'>Listado de Formas de Promocion</div>\n";
        print "<form name='lista' method='POST' action='respondeMenu.php'>";
        print "<div id='buscador'>\n";   
        print "Filtrar por: <input class='campos' name='valorFiltro' id='valorFiltro' type='text' value='' style='width:300px;' >\n";
        print " <input type='button' value='Borrar filtro' onclick='filtro(0);' /> <input type='button' value='Filtrar' onclick='filtro(1);' />\n";
        print "</div>\n";
        print "<div id='vistaTablaPromo' class='vistaTabla' >\n";
            $objBSN = new PromocionBSN();
            $arrayEven=$objBSN->cargaColeccionForm();
            $this->despliegaTabla($arrayEven);
        print "</div>\n";
        print "<div id='vistaDatosPromo'>\n";   
        print "</div>\n";

        print "<input type='hidden' name='id_promo' id='id_promo' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
    }

    public function despliegaTabla($arrayDatos) {
        $fila=0;
        $formaBSN = FormapromocionBSN::getInstance();
        $arrayForma= $formaBSN->getArrayParametros();
        if (sizeof($arrayDatos) == 0) {
            print "No existen datos para mostrar";
        } else {

            print "    <ul>\n";
            print "     <li class=\"li_lista_titulo\" id='promAcc'  >&nbsp;</li>\n";
            print "     <li class=\"li_lista_titulo\" id='promForma'  >Forma</li>\n";
            print "     <li class=\"li_lista_titulo\" id='promTit'  >Titulo</li>\n";
            print "     <li class=\"li_lista_titulo\" id='promCom'  >Comentario</li>\n";
            print "	  </ul>\n";
            $fila = 0;

            print "<div style='overflow:auto; clear:both; height:520px;'>\n";

            foreach ($arrayDatos as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                $onclick = "onclick=\"javascript: muestraDatos(".$Even ['id_promo'].")\" ";
                
                print "<ul>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='promAcc'  >";
                print "    <a href='javascript:envia(\"lista\",1002,\"" . $Even ['id_promo'] . "\");' border='0'>";
                print "       <img src='images/user--pencil.png' alt='Editar' title='Editar' border=0></a>&nbsp;&nbsp;";
                print "    <a href='javascript:envia(\"lista\",1003,\"" . $Even ['id_promo'] . "\");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                print "       <img src='images/user--minus.png' alt='Borrar' title='Borrar' border=0></a>";
                print "  </li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='promForma'  $onclick>" . $arrayForma[$Even ['id_fpromo']] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='promTit'  $onclick>" . $Even ['titulo'] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\" id='promCom'  $onclick>" . substr($Even ['comentario'],0,150). "</li>\n";
                print "	</ul>\n";
            }
            print "  </div>\n";
        }
    }
    
    public function vistaDatos(){
        $formaBSN = FormapromocionBSN::getInstance();
        $arrayForma= $formaBSN->getArrayFormaPromocion();
        print "<li class='tituloPromo'>" .$this->arrayForm ['titulo']. "</li>\n";

        print "<li class='tituloVistaPromo'>Forma: <span class='detalleVistaPromo'>" . $arrayForma[$this->arrayForm['id_fpromo']] . "</span></li>\n";

        print "<li class='tituloVistaPromo'>Empresa: <span class='detalleVistaPromo'>".$this->arrayForm ['empresa'].' '. $this->arrayForm ['nro_doc'] . "</span></li>\n";

        print "<li class='tituloVistaPromo'>Medio: <span class='detalleVistaPromo'>".$this->arrayForm ['medio']."</li>\n";

        print "<li class='tituloVistaPromo'>Ubicacion: <span class='detalleVistaPromo'>" . $this->arrayForm ['ubicacion'] . "</li>\n";

        print "<li class='tituloVistaPromo'>Comentario:<br> <span class='detalleVistaPromo'>" . $this->arrayForm ['comentario'] . "</li>\n";
        print "<li class='tituloVistaPromo'>Imagenes: </li>\n";
        
        if($this->arrayForm['imagen1']!=''){
            print "<li class='detalleVistaPromo'><img src='".$this->pathC. $this->arrayForm ['imagen1'] . "></li>\n";
        }
        if($this->arrayForm['imagen2']!=''){
            print "<li class='detalleVistaPromo'><img src='".$this->pathC. $this->arrayForm ['imagen2'] . "></li>\n";
        }
        if($this->arrayForm['imagen3']!=''){
            print "<li class='detalleVistaPromo'><img src='".$this->pathC. $this->arrayForm ['imagen3'] . "></li>\n";
        }
        if($this->arrayForm['imagen4']!=''){
            print "<li class='detalleVistaPromo'><img src='".$this->pathC. $this->arrayForm ['imagen4'] . "></li>\n";
        }
        print "<li class='tituloVistaPromo'>Inicio: <span class='detalleVistaPromo'>" . $this->arrayForm ['fec_ini'] . "</li>\n";
        print "<li class='tituloVistaPromo'>Fin: <span class='detalleVistaPromo'>" . $this->arrayForm ['fec_fin'] . "</li>\n";

        
    }
    
}

// fin clase
?>
