<?php

include_once ('generic_class/class.VW.php');
include_once("generic_class/class.menu.php");
include_once("clases/class.emprendimientoBSN.php");
include_once("clases/class.emprendimiento.php");
include_once("clases/class.tipo_empBSN.php");
include_once ("clases/class.ubicacionpropiedadBSN.php");
include_once("inc/funciones.inc");
include_once("inc/funciones_gmap.inc");
include_once('generic_class/class.upload.php');

class EmprendimientoVW extends VW {

    protected $clase = "Emprendimiento";
    protected $emprendimiento;
    protected $nombreId = "Id_emp";
//	private $arrayForm;
    private $path;
    private $pathC;
    private $anchoG;
    private $anchoC;

    public function __construct($_emprendimiento = 0) {
        EmprendimientoVW::creaObjeto();
        if ($_emprendimiento instanceof Emprendimiento) {
            EmprendimientoVW::seteaVW($_emprendimiento);
        }
        if (is_numeric($_emprendimiento)) {
            if ($_emprendimiento != 0) {
                EmprendimientoVW::cargaVW($_emprendimiento);
            }
        }
        $conf = CargaConfiguracion::getInstance();
        $this->path = $conf->leeParametro('path_fotos');
        $this->pathC = $conf->leeParametro('path_fotos_chicas');
        $this->anchoC = $conf->leeParametro('ancho_foto_chica');
        $this->anchoG = $conf->leeParametro('ancho_foto_grande');
        EmprendimientoVW::cargaDefinicionForm();
    }

    public function cargaDatosVW() {
        $ubiBSN = new UbicacionpropiedadBSN();
        $tipo_empBSN = new Tipo_empBSN();

        mapaGmap();

        print "<form action='carga_emprendimiento.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaEmprendimiento(this);'>\n";

        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='pg_titulo'>Carga de Emprendimientos</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr>";
        print "<td class='cd_celda_texto' >Zona <span class='obligatorio'>&nbsp;&bull;</span><br />";
        $ubiBSN = new UbicacionpropiedadBSN();
        if ($this->arrayForm['id_ubica'] == '') {
            $this->arrayForm['id_ubica'] = 0;
            $textoUbica = 'Seleccione una Zona';
        } else {
            $textoUbica = $ubiBSN->armaNombreZona($this->arrayForm['id_ubica']);
        }
        $id_padre = $ubiBSN->definePrincipalByHijo($this->arrayForm['id_ubica']);
        $ubiBSN->comboUbicacionpropiedadPrincipal($id_padre, 0);
        print "<input type='hidden' id='id_ubica' name='id_ubica' value='" . $this->arrayForm['id_ubica'] . "'>";
        print "<input type='button' value='Despliegue Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('id_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&t=r', 'ventanaDom', 'menubar=1,resizable=1,width=950,height=550');\">";
        print "<div id='txtUbica'>$textoUbica</div>";


        print "</td>\n";
        print "</tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'>";
        $tipo_empBSN->comboTipoEmp($this->arrayForm['id_tipo_emp'], 2);
        print "</td>";

        print "<td class='cd_celda_texto' width='15%'>Estado</td>";
        print "<td width='35%'>";
        armaEstadoEmprendimiento($this->arrayForm['estado']);
        print "</td><td><span class='obligatorio'>&nbsp;&bull;</span></td></tr>\n";


        print "<tr><td class='cd_celda_texto' width='15%'>Nombre<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'><input class='campos' type='text' name='nombre' id='nombre' value='" . $this->arrayForm['nombre'] . "' maxlength='250'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Logo</td>";
        print "<td width='35%'><input type='hidden' name='logo' id='logo' value='" . $this->arrayForm['logo'] . "'>" . $this->arrayForm['logo'] . " <input type='checkbox' name='blogo' id='blogo' > Marque la casilla para eliminar el Logo";
        print "	<input type='file' name='hlogo' id='hlogo' maxlength='200'>";
        print "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Foto</td>";
        print "<td width='35%'><input type='hidden' name='foto' id='foto' value='" . $this->arrayForm['foto'] . "'>" . $this->arrayForm['foto'] . " <input type='checkbox' name='bfoto' id='bfoto' >Marque la casilla para eliminar la Foto";
        print "	<input type='file' name='hfoto' id='hfoto' maxlength='200'>";
        print "</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Ubicacion</td>";
        print "<td width='85%' colspan='3'><input class='campos' type='text' name='ubicacion' id='ubicacion' value='" . $this->arrayForm['ubicacion'] . "' maxlength='250'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'> Descripcion </td>";
        print "<td width='85%' colspan='3'><input class='campos' type='text' name='descripcion' id='descripcion' value='" . $this->arrayForm['descripcion'] . "' maxlength='250'></td></tr>\n";


        print "<td class='cd_celda_texto' width='15%'>Comentario</td>";
        print "<td width='85%' colspan='3'><input class='campos' type='text' name='comentario' id='comentario' value='" . $this->arrayForm['comentario'] . "' maxlength='250'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Publica en WEB</td>";
        print "<td width='85%' colspan='3'><input class='campos' type='checkbox' name='activa' id='activa'";
        if ($this->arrayForm['activa'] == 1) {
            print "checked ";
        }
        print "></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Muestra mapa ubicacion</td>";
        print "<td width='85%' colspan='3'><input type='button' class='campos' id='ver' name='ver' value=\"Muestra mapa de ubicacion\" onclick='javascript: popupMapa(\"e\");'></td></tr>\n";

        print "<input class='campos' type='hidden' name='goglat' id='goglat'  value='";
        if ($this->arrayForm['goglat'] == "") {
            echo "0";
        } else {
            echo $this->arrayForm['goglat'];
        }
        print "' maxlength='250' size='80'>\n";
        print "<input class='campos' type='hidden' name='goglong' id='goglong' value='";
        if ($this->arrayForm['goglong'] == "") {
            echo "0";
        } else {
            echo $this->arrayForm['goglong'];
        }
        print "' maxlength='250' size='80'>\n";

        print "<input type='hidden' name='id_emp' id='id_emp' value='" . $this->arrayForm['id_emp'] . "'>\n";

        print "<br>";
        print "<tr><td colspan='4' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }

    /**
     * Lee desde un formulario los datos cargados para el emprendimiento.
     * Los registra en un objeto del tipo emprendimiento emprendimiento de esta clase
     *
     */
    public function leeDatosEmprendimientoVW() {
        $emprendimiento = new EmprendimientoBSN();

        $imglogo = false;
        $imgfoto = false;
        if ($_POST['blogo'] == "on") {
            $this->borraFoto($_POST['logo']);
            $nombre1 = "";
        } else {
            if ($_FILES['hlogo']['type'] == 'image/jpeg' || $_FILES['hlogo']['type'] == 'image/gif' || $_FILES['hlogo']['type'] == 'image/png') {
                $imglogo = true;
            }
            if (trim($_FILES['hlogo']['name']) == '' || !isset($_FILES['hlogo']['name']) || !$imglogo) {
                $nombre1 = $_POST['logo'];
            } else {
                $nombre1 = $this->uploadImagen('hlogo', $_POST['id']);
            }
        }
        $_POST['logo'] = $nombre1;


        if ($_POST['bfoto'] == "on") {
            $this->borraFoto($_POST['foto']);
            $nombre2 = "";
        } else {
            if ($_FILES['hfoto']['type'] == 'image/jpeg' || $_FILES['hfoto']['type'] == 'image/gif' || $_FILES['hfoto']['type'] == 'image/png') {
                $imgfoto = true;
            }
            if (trim($_FILES['hfoto']['name']) == '' || !isset($_FILES['hfoto']['name']) || !$imgfoto) {
                $nombre2 = $_POST['foto'];
            } else {
                $nombre2 = $this->uploadImagen('hfoto', $_POST['id']);
            }
        }
        $_POST['foto'] = $nombre2;

        $this->emprendimiento = $emprendimiento->leeDatosForm($_POST);

        if ($this->emprendimiento->getActiva() == "on") {
            $this->emprendimiento->setActiva(1);
        } else {
            $this->emprendimiento->setActiva(0);
        }
    }

    protected function borraFoto($_nombre) {
        $nombre = $this->path . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
        $nombre = $this->pathC . "/" . $_nombre;
        if (file_exists($nombre)) {
            unlink($nombre);
        }
    }

    protected function uploadImagen($campo, $id) {
        $retorno = '';
        if ($_FILES[$campo]['type'] == 'image/jpeg' || $_FILES[$campo]['type'] == 'image/gif' || $_FILES[$campo]['type'] == 'image/png') {
            $fotoup = new Upload($_FILES[$campo], 'es_ES');
            $nom = $_FILES[$campo]['name'];
            if ($campo == 'hlogo') {
                $pre = 'L_';
            } else {
                $pre = 'E_';
            }
            $nombre = $pre . $_POST[$id] . '_' . substr($nom, 0, strlen($nom) - 4);
            if ($fotoup->uploaded) {
                $fotoup->image_resize = true;
                $fotoup->image_ratio_y = true;
                $fotoup->file_new_name_body = $nombre;

                $fotoup->image_x = $this->anchoG;

                $fotoup->Process($this->path);

                // we check if everything went OK
                if ($fotoup->processed) {
                    
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $fotoup->error . '';
                    echo '</fieldset>';
                }

                $fotoup->image_x = $this->anchoC;
                $fotoup->file_new_name_body = $nombre;

                $fotoup->Process($this->pathC);

                // we check if everything went OK
                if ($fotoup->processed) {
                    
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $fotoup->error . '';
                    echo '</fieldset>';
                }
            } else {
                echo '<fieldset>';
                echo '  <legend>file not uploaded on the server</legend>';
                echo '  Error: ' . $fotoup->error . '';
                echo '</fieldset>';
            }
            $retorno = $fotoup->file_dst_name;
        }
        return $retorno;
    }

    /**    OK
     * Muestra una tabla con los datos de los emprendimientos y una barra de herramientas o menu
     * conde se despliegan las opciones ingresables para cada item
     *
     */
    public function vistaTablaVW($pagina = 1) {
        $ubiBSN = new UbicacionpropiedadBSN();
        $tipopropBSN = new Tipo_empBSN();
        $tipo_empBSN = new Tipo_empBSN();
        $config = CargaConfiguracion::getInstance();
        $registros = $config->leeParametro('regemp_adm');

        $fila = 0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(nameForm,opcion,id){\n";
        print "     document.forms.lista.id_emp.value=id;\n";
        print "   	submitform(nameForm,opcion);\n";
        print "}\n";
        print "function filtra(origen){\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.lista.action='lista_emprendimiento.php?i=';\n";
        print "   document.forms.lista.fid_tipo_emp.value=document.forms.lista.aux_emp.value;\n";
        print "   document.forms.lista.festado.value=document.forms.lista.estado.value;\n";
        print "   if(origen=='f'){\n";
        print "     document.forms.lista.pagina.value=1;\n";
        print "   }\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function limpiafiltro(){\n";
        print "   document.forms.lista.filtro.value=0;\n";
        print "   document.lista.action='lista_emprendimiento.php?i=';\n";
        print "   document.forms.lista.aux_ubica.value=0;\n";
        print "   document.forms.lista.fid_tipo_emp.value=0;\n";
        print "   document.forms.lista.festado.value='';\n";
        print "   document.forms.lista.id_ubicaPrincipal.value=0;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function paginar(pagina){\n";
        print "   document.forms.lista.pagina.value=pagina;\n";
        print "   filtra('p');\n";
        print "}\n";

        print "</script>\n";

        print "<span class='pg_titulo'>Listado de Emprendimientos</span><br><br>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        // Filtro
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td colspan='4' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
        print "	</tr>";

        print "<tr><td class='cd_celda_texto' width='33%'>Zona</td>";
        print "<td class='cd_celda_texto' width='25%'>Tipo de Emprendimiento</td>";
        print "<td class='cd_celda_texto' width='25%'>Estado</td></tr>";

        $_POST=$_SESSION['filtroEmp'];
        if ($_POST['filtro'] == 1 ) {
            if (isset($_POST['aux_ubica']) && $_POST['aux_ubica'] != 0){
                $aux_ubica = $_POST['aux_ubica'];
            } else {
                if (isset($_POST['id_ubicaPrincipal']) && $_POST['id_ubicaPrincipal'] != 0) {
                    $aux_ubica = $_POST['id_ubicaPrincipal'];
                } else {
                    $aux_ubica = 0;
                    $idsZonas = 0;
                }
            }
        } else {
            $aux_ubica = 0;
            $idsZonas = 0;
        }
        if (isset($_POST['fid_tipo_emp']) && $_POST['fid_tipo_emp'] != 0) {
            $aux_emp = $_POST['fid_tipo_emp'];
        } else {
            $aux_emp = 0;
        }
        if (isset($_POST['festado']) && $_POST['festado'] != '') {
            $aux_est = $_POST['festado'];
        } else {
            $aux_est = '';
        }


        $ubi1BSN = new UbicacionpropiedadBSN();
        if ($aux_ubica == 0) {
            $textoUbica = 'Seleccione una Zona para filtrar';
        } else {
            $textoUbica = $ubi1BSN->armaNombreZona($aux_ubica);
            $idsZonas = $aux_ubica . ', ' . $ubi1BSN->armaListaSeleciones($aux_ubica, '');
        }
        $id_padre = $ubi1BSN->definePrincipalByHijo($aux_ubica);
        print "<td width='33%'>";
        $ubi1BSN->comboUbicacionpropiedadPrincipal($id_padre, 3, 'id_ubicaPrincipal', 'aux_ubica');
        print "<input type='button' value='Seleccione Subzonas' onclick=\"window.open('seleccionaZona.php?v='+document.getElementById('aux_ubica').value+'&z='+document.getElementById('id_ubicaPrincipal').value+'&ncu=aux_ubica', 'ventanaEmp', 'menubar=1,resizable=1,width=950,height=550');\">";
        print "<div id='txtUbica'>$textoUbica</div>";
        print "</td>";


        print "<td width='25%'>";
        $tipo_empBSN->comboTipoEmp($aux_emp, 1, 'aux_emp');
        print "</td>";
        print "<td width='25%'>";
        armaEstadoEmprendimiento($aux_est, 3);
        print "</td></tr>";

        print "<tr><td></td><td class='row' align='right'><input class='boton_form' type='button' onclick=\"javascript:filtra('f');\" value='Filtrar'></td>\n";
        print "<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
        print "</tr>\n";
        print "</table>\n";


        // Fin Filtro
        $evenBSN = new EmprendimientoBSN();
        $arrayEven = $evenBSN->cargaColeccionFiltro($idsZonas, $aux_emp, $aux_est, -1, $pagina);
        $cantreg = $evenBSN->cantidadRegistrosFiltro($idsZonas, $aux_emp, $aux_est, -1);

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo' colspan='4'>$cantreg Emp.</td>\n";
        print "     <td class='cd_lista_titulo'>Nombre</td>\n";
        print "     <td class='cd_lista_titulo'>Zona</td>\n";
        print "     <td class='cd_lista_titulo'>Ubicacion</td>\n";
        print "     <td class='cd_lista_titulo'>Logo</td>\n";
        print "     <td class='cd_lista_titulo'>Tipo Emp.</td>\n";
        print "     <td class='cd_lista_titulo'>Estado</td>\n";
        print "	  </tr>\n";

        $cantr = $cantreg / $registros;
        $cante = $cantreg % $registros;
        if ($cante != 0) {
            $paginas = intval($cantr + 1);
        } else {
            $paginas = $cantr;
        }
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
                print "	 <td class='row" . $fila . "'>";
                print "    <a href='javascript:envia(\"lista\",32," . $Even['id_emp'] . ");' border='0'>";
                print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border=0></a></td>";
                print "	 <td class='row" . $fila . "'>";
                print "    <a href='javascript:envia(\"lista\",33," . $Even['id_emp'] . ");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a></td>";
                print "	 <td class='row" . $fila . "'>";
                print "    <a href='javascript:envia(\"lista\",34," . $Even['id_emp'] . ");' border=0>";
                print "       <img src='images/book_edit.png' alt='Editar Caracteristicas' title='Editar Caracteristicas' border=0></a>";
                print "  </td>\n";
                print "	 <td class='row" . $fila . "'>";
                if ($Even['activa'] == 1) {
//                    print "    <a href='javascript:envia(\"lista\",36," . $Even['id_emp'] . ");' border=0>";
                    print "    <a href='javascript:window.open(\"retirar_emprendimiento.php?i=" . $Even['id_emp'] . "&pag=$pagina\", \"ventana\", \"menubar=1,resizable=1,width=350,height=250\");' border=0>";
                    print "       <img src='images/web.png' alt='No Publicar en Web' title='No Publicar en Web' border=0></a>";
                } else {
//                    print "    <a href='javascript:envia(\"lista\",35," . $Even['id_emp'] . ");' border=0>";
                    print "    <a href='javascript:window.open(\"publicar_emprendimiento.php?i=" . $Even['id_emp'] . "&pag=$pagina\", \"ventana\", \"menubar=1,resizable=1,width=350,height=250\");' border=0>";
                    print "       <img src='images/web_no.png' alt='Publicar en web' title='Publicar en Web' border=0></a>";
                }
                print "  </td>\n";

                print "	 <td class='row" . $fila . "'>" . $Even['nombre'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>";
                $ubiBSN->setId($Even ['id_ubica']);
                $ubiBSN->cargaById($Even['id_ubica']);
                print $ubiBSN->armaNombreZona($Even['id_ubica']);
                print "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['ubicacion'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['logo'] . "</td>\n";
                print "	 <td class='row" . $fila . "'>";
                $tipopropBSN->setId($Even['id_tipo_emp']);
                $tipopropBSN->cargaById($Even['id_tipo_emp']);
                print $tipopropBSN->getObjeto()->getTipo_emp();
                print "</td>\n";
                print "	 <td class='row" . $fila . "'>" . $Even['estado'] . "</td>\n";
                print "	</tr>\n";
            }
            print "<tr><td align='center' colspan='19'>";
            if ($pagina > 1) {
                print "    <a href='javascript:paginar(1);'>";
                print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0'></a>&nbsp;";
                if ($pagina > 2) {
                    print "    <a href='javascript:paginar(" . ($pagina - 1) . ");'>";
                    print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0'></a>&nbsp;-&nbsp;";
                }
                for ($x = $pagina - 5; $x < $pagina; $x++) {
                    if ($x > 0) {
                        print "<a href='javascript:paginar(" . $x . ");'>$x</a>&nbsp;-&nbsp;";
                    }
                }
            }
            print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
            if ($pagina < $paginas) {
                for ($x = $pagina + 1; $x < $pagina + 5; $x++) {
                    if ($x <= $paginas) {
                        print "<a href='javascript:paginar(" . $x . ");'>" . $x . "</a>&nbsp;-&nbsp;";
                    }
                }
                if ($pagina < $paginas - 1) {
                    print "    <a href='javascript:paginar(" . ($pagina + 1) . ");'>";
                    print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0'></a>&nbsp;";
                }
                print "    <a href='javascript:paginar($paginas);'>";
                print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0'></a>&nbsp;";
            }
            print "</td></tr>";
        }

        print "  </table>\n";
        print "<input type='hidden' name='id_emp' id='id_emp' value=''>";
        print "<input type='hidden' name='filtro' id='filtro' value=0>";
        print "<input type='hidden' name='fid_tipo_emp' id='fid_tipo_emp' value=''>";
        print "<input type='hidden' name='festado' id='festado' value=''>";
        print "<input type='hidden' name='aux_ubica' id='aux_ubica' value='$aux_ubica'>";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

        print "</form>";
    }

    public function grabaModificacion() {
        $retorno = false;
        $emprendimiento = new EmprendimientoBSN($this->emprendimiento);
        $retUPre = $emprendimiento->actualizaDB();
        if ($retUPre) {
            echo "Se proceso la grabacion en forma correcta<br>";
            $retorno = true;
        } else {
            echo "Fallo la grabación de los datos<br>";
        }
        return $retorno;
    }

    public function grabaDatosVW() {
        $retorno = false;
        $emprendimiento = new EmprendimientoBSN($this->emprendimiento);
        //Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
        //		$existente=$emprendimiento->controlDuplicado($this->emprendimiento->getEmprendimiento());
        //		if($existente){
        //			echo "Ya existe un emprendimiento con ese Titulo";
        //		} else {
        $retIPre = $emprendimiento->insertaDB();
        //			die();
        if ($retIPre) {
            $this->emprendimiento->setId_emp($emprendimiento->buscaID());
            echo "Se proceso la grabacion en forma correcta<br>";
            $retorno = true;
        } else {
            echo "Fallo la grabación de los datos<br>";
        }
        //		} // Fin control de Duplicados
        return $retorno;
    }

    public function muestraNombre() {

        print "<span class='pg_titulo'>Emprendimiento:" . $this->arrayForm['nombre'] . " " . $this->arrayForm['logo'];
        print "</span><br><br>\n";
    }

}

// fin clase
?>
