<?php

include_once ('generic_class/class.VW.php');
include_once ("generic_class/class.menu.php");
include_once ("clases/class.caracteristicaBSN.php");
include_once ("clases/class.caracteristica.php");
include_once ("clases/class.tipo_caracBSN.php");
include_once ("clases/class.tipo_propBSN.php");
include_once ("inc/funciones.inc");

class CaracteristicaVW extends VW {

    protected $clase = "Caracteristica";
    protected $caracteristica;
    protected $nombreId = "Id_carac";
//	private $arrayForm;
    protected $arrayTipoProp;

    public function __construct($_caracteristica = 0) {
//		CaracteristicaVW::creaCaracteristica ();
        CaracteristicaVW::creaObjeto();
        if ($_caracteristica instanceof Caracteristica) {
//			CaracteristicaVW::seteaCaracteristica ( $_caracteristica );
            CaracteristicaVW::seteaVW($_caracteristica);
        }
        if (is_numeric($_caracteristica)) {
            if ($_caracteristica != 0) {
//				CaracteristicaVW::cargaCaracteristica ( $_caracteristica );
                CaracteristicaVW::cargaVW($_caracteristica);
            }
        }
        CaracteristicaVW::cargaDefinicionForm();
    }

    public function cargaDatosVW() {
        $tipo_carBSN = new Tipo_caracBSN ( );

        if ($this->arrayForm['id_carac'] == 0 || $this->arrayForm['id_carac'] == '') {
//			$objaux=new Caracteristica();
//			$objaux->setId_tipo_carac($this->arrayForm['id_tipo_carac']);
//			$objBSN= new CaracteristicaBSN($objaux);
//			$orden=$objBSN->proximaPosicion();
            $orden = 0;
        } else {
            $orden = $this->arrayForm['orden'];
        }


        print "<script type='text/javascript' language='javascript'>\n";
        print "function validaTipo(){\n";
        print "     if(document.forms.carga.tipo.value=='Numerico'){;\n";
        print "			activaMaximo();\n";
        print "		}else{\n";
        print "			desactivaMaximo();\n";
        print "		}\n";
        print "     if(document.forms.carga.tipo.value=='Lista'){;\n";
        print "			activaLista();\n";
        print "		}else{\n";
        print "			desactivaLista();\n";
        print "		}\n";
        print "}\n";
        print "function activaMaximo(){\n";
        print "     document.getElementById(\"trmax\").style.display='table-row';\n";
        print "}\n";
        print "function desactivaMaximo(){\n";
        print "     document.getElementById(\"trmax\").style.display='none';\n";
        print "}\n";
        print "function activaLista(){\n";
        print "     document.getElementById(\"trlista\").style.display='table-row';\n";
        print "}\n";
        print "function desactivaLista(){\n";
        print "     document.getElementById(\"trlista\").style.display='none';\n";
        print "}\n";
        print "</script>\n";
        print "<form action='carga_caracteristica.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaCaracteristica(this);'>\n";
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo'>Carga de Caracteristicas de Propiedades</td></tr>\n";
        print "<tr><td align='center'>";
        print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_texto' width='180'>Pertenece a</td>";
        print "<td colspan='5'>";
        $tipo_carBSN->comboTipoCarac($this->arrayForm ['id_tipo_carac'], 'id_tipo_carac', 'divorden');
        print "</td></tr>";
        print "<tr><td class='cd_celda_texto' width='180'>Característica<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td colspan='5'><input class='campos' type='text' name='titulo' id='titulo' value='" . $this->arrayForm ['titulo'] . "' maxlength='100' /></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='180'>Tipo</td>";
        print "<td width='300'>";
        armaTipoCampo($this->arrayForm ['tipo']);
        print "</td>\n";
        print "<td class='cd_celda_texto' width='100'>Inc. Comentario</td>";
        print "<td>";
        armaComentarioCampo($this->arrayForm ['comentario']);
        print "</td>\n";
        print "<td class='cd_celda_texto' width='100'>Orden</td>";
        print "<td><div  id='divorden'>";
        print "<input class='campos' type='text' name='orden' id='orden' value='" . $orden . "' maxlength='3' size='10' />";
        print "</div></td></tr>\n";

        print "<tr id='trmax' style='display:";
        if ($this->arrayForm ['tipo'] == 'Numerico') {
            $verMax = 'table-row';
        } else {
            $verMax = 'none';
        }
        print "$verMax' width='90%'><td class='cd_celda_texto' width='180'>Valor Maximo</td>";
        print "<td colspan='5'><input class='campos' type='text' name='maximo' id='maximo' value='";
        if (!is_numeric($this->arrayForm ['maximo'])) {
            print "0";
        } else {
            print $this->arrayForm ['maximo'];
        }
        print "' maxlength='2' size='10' /></td></tr>\n";
        print "<tr id='trlista' style='display:";
        if ($this->arrayForm ['tipo'] == 'Lista') {
            $verLis = 'table-row';
        } else {
            $verLis = 'none';
        }
        print "$verLis' width='90%'><td class='cd_celda_texto' width='180'>Lista de valores</td>";
        print "<td colspan='5'><input class='campos' type='text' name='lista' id='lista' value='";
        print $this->arrayForm ['lista'];
        print "' maxlength='300' size='80' /></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='180' valign='top'>Aplica a</td><td colspan='5'>";
        $tprop = new Tipo_propBSN ( );
        $tprop->checkboxTipoProp($this->arrayForm ['id_carac']);
        print "</td></tr>";
        print "<tr><td class='cd_celda_texto' width='180'>Vista Publica</td>";
        print "<td colspan='5' align='left'><input class='campos' type='checkbox' name='publica' id='publica' ";
        if ($this->arrayForm['publica'] == 1) {
            print " checked ";
        }
        print " />";

        print "<input type='hidden' name='opcion' id='opcion' value='' />";
        print "<input type='hidden' name='id_carac' id='id_carac' value='" . $this->arrayForm ['id_carac'] . "' />\n";
        print "</td></tr>";
        print "<tr><td class='cd_celda_texto' width='180'>Publica en </ br>Ficha Tasacion</td>";
        print "<td colspan='5' align='left'><input class='campos' type='checkbox' name='tasacion' id='tasacion' ";
        if ($this->arrayForm['tasacion'] == 1) {
            print " checked ";
        }
        print " />";
        print "<br /></td></tr>";


        print "<tr><td colspan='6' align='right'><input class='boton_form' type='submit' value='Enviar' /><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }

    /**     * Lee desde un formulario los datos cargados para el caracteristica. * Los registra en un objeto del tipo caracteristica caracteristica de esta clase * */
    public function leeDatosVW() {
        $caracteristica = new CaracteristicaBSN ( );
        if ($_POST['publica'] == 'on') {
            $_POST['publica'] = 1;
        } else {
            $_POST['publica'] = 0;
        }
        if ($_POST['tasacion'] == 'on') {
            $_POST['tasacion'] = 1;
        } else {
            $_POST['tasacion'] = 0;
        }

        $this->caracteristica = $caracteristica->leeDatosForm($_POST);
        $tipoProp = new Tipo_propBSN ( );
        $this->arrayTipoProp = $tipoProp->leechkTipoProp($_POST);
    }

    /**    OK * Muestra una tabla con los datos de los caracteristicas y una barra de herramientas o menu * conde se despliegan las opciones ingresables para cada item * */
    public function vistaTablaVW() {
        $tipo_car = new Tipo_carac();
        $tipo_carBSN = new Tipo_caracBSN();
        $filtro_tipo_car = new Tipo_caracBSN();
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_carac.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function enviabuscador(opcion,id,tipo){\n";
        print "     document.forms.lista.id_carac.value=id;\n";
        print "		if(tipo=='Numerico'){\n";
        print "		      compara = null;\n";
        print "           while (compara != '<' && compara != '>' && compara != 'eq' && compara != '<>' && compara != '><'){\n";
        print "               compara = prompt(\"Ingrese el tipo comparacion \\n<    Menor\\n>    Mayor \\neq    Igual\\n<>   Distinto\\n><  Entre\",\"<\");\n";
        print "           }\n";
		print "      //alert('Cargaste '+compara);\n";
        print "			document.forms.lista.comparacion.value=compara;\n";
        print " 	}\n	";
        print "   	envia('lista',opcion,id);\n";
        print "}\n";
        print "function filtra(){\n";
        print "   document.lista.action='lista_caracteristica.php?i=';\n";
        print "   document.forms.lista.id_tipo_carac.value=document.forms.lista.aux_tipo_carac.value;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function limpiafiltro(){\n";
        print "   document.lista.action='lista_caracteristica.php?i=';\n";
        print "     document.forms.lista.id_tipo_carac.value=0;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "</script>\n";
        print "<div class='pg_titulo'>Listado de Caracteristicas de Propiedades</div>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>\n";

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td colspan='3' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por Tipo de Característica</td>";
        print "	</tr>";
        print "    <tr>\n";
        print "     <td class='row'>";
        $_POST=$_SESSION['filtroCar'];
        if (isset($_POST['id_tipo_carac']) && $_POST['id_tipo_carac'] != 0) {
            $auxtipo_carac = $_POST['id_tipo_carac'];
        } else {
            $auxtipo_carac = 0;
        }

        $filtro_tipo_car->comboTipoCarac($auxtipo_carac, 'aux_tipo_carac');
        print "</td>\n";
        print "     <td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
        print "     <td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
        print "</tr>\n";
        print "</table>\n";


        $evenBSN = new CaracteristicaBSN();
        if (isset($_POST['id_tipo_carac']) && $_POST['id_tipo_carac'] != 0) {
            $arrayEven = $evenBSN->cargaColeccionTipoCarac($_POST['id_tipo_carac']);
        } else {
            $arrayEven = $evenBSN->cargaColeccionForm();
        }
       
        print "<div id='vistaTabla' class='vistaTabla'>\n";

        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            print "<ul>\n";
            print "     <li class=\"li_lista_titulo\" id='caracAcc' >&nbsp;</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracTCar' >Tipo de Caracteristica</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracTitu' >Titulo</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracIng' >T. de Ingreso</li>\n";
            //print "     <li class=\"li_lista_titulo\" id='caracMax' >Valor Max</li>\n";
           // print "     <li class=\"li_lista_titulo\" id='caracCom' >Comentario</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracVal' >Lista de Valores</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracOrd' >Orden</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracPub' >Publ.</li>\n";
            print "     <li class=\"li_lista_titulo\" id='caracTas' >Tas.</li>\n";
            print "</ul>\n";
            $arrayBuscador = $evenBSN->cargaColeccionBuscador();
            print "<div style='overflow:auto; clear:both; height:600px;width:980px;'>\n";

            foreach ($arrayEven as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<ul>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracAcc'>";
                print "    <a href='javascript:envia(\"lista\",122," . $Even ['id_carac'] . ");' border='0'>";
                print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a>";
                print "    <a href='javascript:envia(\"lista\",123," . $Even ['id_carac'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";

                if (array_search($Even['id_carac'], $arrayBuscador) === false) {
                    print "    <a href='javascript:enviabuscador(1261," . $Even ['id_carac'] . ",\"" . $Even ['tipo'] . "\");' border='0'>";
                    print "       <img src='images/buscador.png' alt='Buscador' title='Buscador' border=0></a>";
                } else {
                    print "    <a href='javascript:envia(\"lista\",1262," . $Even ['id_carac'] . ");' border='0'>";
                    print "       <img src='images/nobuscador.png' alt='Quitar del Buscador' title='Quitar del Buscador' border=0></a>";
                }

                print "    <a href='javascript:envia(\"lista\",124," . $Even ['id_carac'] . ");' border='0'>";
                print "       <img src='images/up.png' alt='Subir' border=0></a>";
                print "    <a href='javascript:envia(\"lista\",125," . $Even ['id_carac'] . ");' border=0>";
                print "       <img src='images/down.png' alt='Bajar' border=0></a>";
                print "	 </li>\n";
                $tipo_carBSN->setId($Even ['id_tipo_carac']);
                $tipo_carBSN->cargaById($Even ['id_tipo_carac']);
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracTCar'>" . $tipo_carBSN->getObjeto()->getTipo_carac() . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracTitu'>" . $Even ['titulo'] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracIng'>" . $Even ['tipo'] . "</li>\n";
                //print "	 <li class=\"li_lista_" . $fila . "\"  id='caracMax'>" . $Even ['maximo'] . "</li>\n";
                //print "	 <li class=\"li_lista_" . $fila . "\"  id='caracCom'>" . $Even ['comentario'] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracVal'>" . substr($Even ['lista'],0,42) . "...</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracOrd'>" . $Even ['orden'] . "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracPub'>";
                if ($Even['publica'] == 1) {
                    print "SI";
                }
                print "</li>\n";
                print "	 <li class=\"li_lista_" . $fila . "\"  id='caracTas'>";
                if ($Even['tasacion'] == 1) {
                    print "SI";
                }
                print "</li>\n";

                print "	</ul>\n";
            }
            print "  </div>\n";
        }
        /*  `id_carac` int(10) unsigned NOT NULL auto_increment,  `id_tipo_carac` decimal(10,0) NOT NULL,  `titulo` varchar(150) NOT NULL,  `tipo` char(1) NOT NULL,  `maximo` decimal(10,0) NOT NULL,  `comentario` char(1) NOT NULL, */
        print "  </div>\n";
        print "<input type='hidden' name='id_tipo_carac' id='id_tipo_carac' value=''>";
        print "<input type='hidden' name='id_carac' id='id_carac' value=''>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='comparacion' id='comparacion' value=''>";
        print "</form>";
    }

    /*
      Arma una taba para mostrar los datos y habilitar o quitar las caracteristicas del filtro
     */

    public function tablaBuscadorCaracteristica() {
        $tipo_car = new Tipo_carac();
        $tipo_carBSN = new Tipo_caracBSN();
        $filtro_tipo_car = new Tipo_caracBSN();
        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_carac.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "</script>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>\n";

//		$menu = new Menu ( );
//		$menu->dibujaMenu ( 'lista');

        print "<span class='pg_titulo'>Listado de Caracteristicas a mostrar en el Buscador</span><br /><br />\n";

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr class='cd_lista_titulo'>\n";
        print "     <td colspan='2'>Habilita Buscador</td>\n";
        print "     <td>Tipo de Caracteristica</td>\n";
        print "     <td>Titulo</td>\n";
        print "     <td>Tipo de Ingreso</td>\n";
        print "     <td>Lista de Valores</td>\n";
        print "		<td>Habilitado</td>\n";
        print "	  </tr>\n";

        $evenBSN = new CaracteristicaBSN();
        $arrayEven = $evenBSN->cargaColeccionForm();
        $arrayBuscador = $evenBSN->cargaColeccionBuscador();
        if (sizeof($arrayEven) == 0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if ($fila == 0) {
                    $fila = 1;
                } else {
                    $fila = 0;
                }
                print "<tr>\n";
                print "	 <td  align='center' width='25' class='row" . $fila . "'>";
                print "    <a href='javascript:envia(\"lista\",1261," . $Even ['id_carac'] . ");' border='0'>";
                print "       <img src='images/bucador.png' alt='Buscador' title='Buscador' border=0></a></td>";
                print "	 <td  align='center' width='25' class='row" . $fila . "'>";
                print "    <a href='javascript:envia(\"lista\",1262," . $Even ['id_carac'] . ");' border='0'>";
                print "       <img src='images/nobuscador.png' alt='Quitar del Buscador' title='Quitar del Buscador' border=0></a></td>";
                $tipo_carBSN->setId($Even ['id_tipo_carac']);
                $tipo_carBSN->cargaById($Even ['id_tipo_carac']);
                print "	 <td class='row" . $fila . "'>" . $tipo_carBSN->getObjeto()->getTipo_carac() . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even ['titulo'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even ['tipo'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even ['lista'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>";
                if (array_search($Even['id_carac'], $arrayBuscador) === false) {
                    print "";
                } else {
                    print "Habilitada";
                }
                print "	 </td>\n";
            }
        }
        print "  </table>\n";
        print "<input type='hidden' name='id_tipo_carac' id='id_tipo_carac' value=''>";
        print "<input type='hidden' name='id_carac' id='id_carac' value=''>";
//		print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "</form>";
    }

    public function grabaModificacion() {
        $retorno = false;
        $caracteristica = new CaracteristicaBSN($this->caracteristica);
        $retUPre = $caracteristica->actualizaDB();
        if ($retUPre) {
            echo "Se proceso la grabacion en forma correcta<br />";
            $retorno = true;
            $this->grabaCaracteristica_Tipoprop();
        } else {
            echo "Fallo la grabación de los datos<br />";
        }
        return $retorno;
    }

    public function grabaDatosVW() {
        $retorno = false;
        $caracteristica = new CaracteristicaBSN($this->caracteristica);
        //Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if//
        //$existente = $caracteristica->controlDuplicado ( $this->caracteristica->getCaracteristica () );
        //		if($existente){//			echo "Ya existe un caracteristica con ese Titulo";
        //		} else {
        $retIPre = $caracteristica->insertaDB();
        //			die();
        if ($retIPre) {
            echo "Se proceso la grabacion en forma correcta<br />";
            $retorno = true;
            $this->caracteristica->setId_carac($caracteristica->buscaID());
            $this->grabaCaracteristica_Tipoprop();
        } else {
            echo "Fallo la grabación de los datos<br />";
        }
        //		}
        // Fin control de Duplicados
        return $retorno;
    }

    public function grabaCaracteristica_Tipoprop() {
        $tipoProp = new Tipo_propBSN ( );
        $tipoProp->grabaCaracteristica_TipoProp($this->getId(), $this->arrayTipoProp);
    }

}

// fin clase
?>