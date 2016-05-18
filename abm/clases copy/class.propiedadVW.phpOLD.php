<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.propiedadBSN.php");
include_once("clases/class.propiedad.php");
include_once("clases/class.tipo_propBSN.php");
include_once ("clases/class.zonaBSN.php");
include_once ("clases/class.emprendimientoBSN.php");
include_once ("clases/class.localidadBSN.php");
include_once("inc/funciones.inc");
include_once("inc/funciones_gmap.inc");
include_once("clases/class.sucursal.php");
include_once("clases/class.datospropVW.php");
include_once('generic_class/class.upload.php');
include_once("generic_class/class.cargaConfiguracion.php");
include_once("clases/class.perfilesBSN.php");
include_once("clases/class.grupo_tipoprop.php");


class PropiedadVW {

    private $propiedad;
    private $arrayForm;
    private $path;
    private $pathC;
    private $anchoG;
    private $anchoC;

    public function __construct($_propiedad=0) {
        PropiedadVW::creaPropiedad();
        if($_propiedad instanceof Propiedad ) {
            PropiedadVW::seteaPropiedad($_propiedad);
        }
        if (is_numeric($_propiedad)) {
            if($_propiedad!=0) {
                PropiedadVW::cargaPropiedad($_propiedad);
            }
        }
        $conf=new CargaConfiguracion();
        $this->path =$conf->leeParametro('path_fotos');
        $this->pathC =$conf->leeParametro('path_fotos_chicas');
        $this->anchoC =$conf->leeParametro('ancho_foto_chica');
        $this->anchoG =$conf->leeParametro('ancho_foto_grande');

    }


    public function cargaPropiedad($_propiedad) {
        $propiedad=new PropiedadBSN($_propiedad);
        $this->seteaPropiedad($propiedad->getObjeto()); //propiedad());
    }

    public function getIdPropiedad() {
        return $this->propiedad->getId_prop();

    }

    protected function creaPropiedad() {
        $this->propiedad=new propiedad();
    }

    protected function seteaPropiedad($_propiedad) {
        $this->propiedad=$_propiedad;
        $propiedad=new PropiedadBSN($_propiedad);
        $this->arrayForm=$propiedad->getObjetoView();

    }

    public function cargaDatosPropiedad($id_cli=0) {
        $zonaBSN = new ZonaBSN ();
        $locaBSN = new LocalidadBSN();
        $tipo_propBSN=new Tipo_propBSN();
        $sucursal = new Sucursal();
        $perf = new PerfilesBSN();


        if($this->arrayForm['id_cliente']==0 || $this->arrayForm['id_cliente']=='') {
            $this->arrayForm['id_cliente']=$id_cli;
        }

        print "<script type='text/javascript' language='javascript'>\n";
        print "function validaIntermediacion(){\n";
        print "     if(document.forms.carga.intermediacion.value=='Exclusiva'){;\n";
        print "			desactiva('trint');\n";
        print "		}else{\n";
        print "			activa('trint');\n";
        print "		}\n";
        print "}\n";
        print "function validaOperacion(){\n";
        print "     if(document.forms.carga.operacion.value=='Tasacion'){;\n";
        print "			activa('troper');\n";
        print "		}else{\n";
        print "			desactiva('troper');\n";
        print "		}\n";
        print "}\n";
        print "function activa(campo){\n";
        print "     document.getElementById(campo).style.display='table-row';\n";
        print "}\n";
        print "function desactiva(campo){\n";
        print "     document.getElementById(campo).style.display='none';\n";
        print "}\n";

        print "</script>\n";

        mapaGmap();
        print "<form action='carga_propiedad.php' name='carga' id='carga' enctype='multipart/form-data' method='post' onSubmit='javascript: return ValidaPropiedad(this);'>\n";

        $menu=new Menu();
        $menu->dibujaMenu('carga');

        print "<table width='100%' cellpadding='5' align='center' bgcolor='#EEEEEE'>\n";

        print "<tr><td class='pg_titulo' height='30'>Carga de Propiedades</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='100%' align='center'>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Sucursal<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%' colspan='3'>";
        $sucursal->comboSucursal($this->arrayForm['id_sucursal']);
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Zona<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'>";
        if($this->arrayForm['id_zona']=='') {
            $this->arrayForm['id_zona']=0;
        }
        if($this->arrayForm['id_loca']=='') {
            $this->arrayForm['id_loca']=0;
        }
        $zonaBSN = new ZonaBSN ();
        $zonaBSN->comboZona($this->arrayForm['id_zona'],$this->arrayForm['id_loca'],2,'id_zona','id_loca','id_emp');
        print "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Localidad<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'>";
        print "<div id='localidad'>";
        $loca = new Localidad();
        $loca->setId_loca($this->arrayForm['id_loca']);
        $loca->setId_zona($this->arrayForm['id_zona']);
        $locaBSN = new LocalidadBSN($loca);
        $locaBSN->comboLocalidad($this->arrayForm['id_loca'],$this->arrayForm['id_zona'],2,'id_loca','campos','id_emp');
        print "</div>";
        print "</td></tr>\n";

        print "<td class='cd_celda_texto' width='15%'>Emprendimiento<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'>";
        print "<div id='emprendimiento'>";
        $empre = new Emprendimiento();
        $empre->setId_loca($this->arrayForm['id_loca']);
        $empre->setId_zona($this->arrayForm['id_zona']);
        $empBSN = new EmprendimientoBSN($empre);
        $empBSN->comboEmprendimiento($this->arrayForm['id_emp'],3,$this->arrayForm['id_zona'],$this->arrayForm['id_loca']);
        print "</div>";
        print "</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Calle<span class='obligatorio'>&nbsp;&bull;</span></td>";
        print "<td width='35%'><input class='campos' type='text' name='calle' id='calle' value='". $this->arrayForm['calle'] ."' maxlength='250' size='80'></td>\n";

        print "<td class='cd_celda_texto' width='15%'>Nro</td>";
        print "<td width='35%'><input class='campos' type='text' name='nro' id='nro' value='". $this->arrayForm['nro'] ."' maxlength='250' size='80'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Piso</td>";
        print "<td width='35%'><input class='campos' type='text' name='piso' id='piso' value='". $this->arrayForm['piso'] ."' maxlength='250' size='80'></td>\n";

        print "<td class='cd_celda_texto' width='15%'>Dpto</td>";
        print "<td width='35%'><input class='campos' type='text' name='dpto' id='dpto' value='". $this->arrayForm['dpto'] ."' maxlength='250' size='80'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Entre</td>";
        print "<td width='35%'><input class='campos' type='text' name='entre1' id='entre1' value='". $this->arrayForm['entre1'] ."' maxlength='250'></td>\n";

        print "<td class='cd_celda_texto' width='15%'> y </td>";
        print "<td width='35%'><input class='campos' type='text' name='entre2' id='entre2' value='". $this->arrayForm['entre2'] ."' maxlength='250'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nombre de Edificio </td>";
        print "<td width='85%' colspan='4'><input class='campos' type='text' name='nomedif' id='nomedif' value='". $this->arrayForm['nomedif'] ."' maxlength='250' size='80'></td></tr>\n";

        if($this->arrayForm['id_sucursal'] == "NOR") {
            print "<tr><td class='cd_celda_texto' width='15%'>ID Parcela</td>";
            print "<td width='35%'><input class='campos' type='text' name='id_parcela' id='id_parcela' value='". $this->arrayForm['id_parcela'] ."' maxlength='250' size='80'></td>\n";
            print "<td class='cd_celda_texto' width='15%'>ID Ccomercial </td>";
            print "<td width='35%'><input class='campos' type='text' name='id_comercial' id='id_comercial' value='". $this->arrayForm['id_comercial'] ."' maxlength='250' size='80'></td></tr>\n";
        }
        print "<tr><td class='cd_celda_texto' width='15%'> Descripcion </td>";
        print "<td width='90%' colspan='3'><input class='campos' type='text' name='descripcion' id='descripcion' value='". $this->arrayForm['descripcion'] ."' maxlength='250' size='80'></td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo de Prop.<span class='obligatorio'>&nbsp;&bull;</span></td>\n";
        print "<td width='35%'>";
        $tipo_propBSN->comboTipoProp($this->arrayForm['id_tipo_prop'],2,'id_tipo_prop','campos','subtipo',$this->arrayForm['subtipo_prop']);
        print "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Subtipo Prop.<span class='obligatorio'>&nbsp;&bull;</span></td>\n";
        print "<td width='35%'>";
        print "<div id='subtipo'>";
        $tipo_propBSN->comboSubtipoProp($this->arrayForm['subtipo_prop'],$this->arrayForm['id_tipo_prop'],2);
        print "</div>";
        print "</td></tr>\n";

        print "<td class='cd_celda_texto' width='15%'>Intermediacion</td>\n";
        print "<td width='35%'>";
        armaIntermediacion($this->arrayForm['intermediacion']);
        print "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Compartir</td>\n";
        print "<td width='35%'><input type='checkbox' name='compartir' id='compartir' ";
        if($this->arrayForm['compartir'] == 1) {
            print "checked>";
        }else {
            print ">";
        }
        print "</td></tr>\n";

        print "<tr id='trint' style='display:";
        if($this->arrayForm['intermediacion']!='Exclusiva' && $this->arrayForm['intermediacion']!='') {
            $verMax='table-row';
        }else {
            $verMax='none';
        }
        print "$verMax width='100%'><td class='cd_celda_texto' width='15%'>Inmobiliaria</td>";
        print "<td width='85%' colspan='3'>";
        armaInmo($this->arrayForm['id_inmo']);
        print "</td></tr>";

        if ($this->arrayForm['id_prop']==0) {
            print "<tr><td class='cd_celda_texto' width='15%'>Operacion<span class='obligatorio'>&nbsp;&bull;</span></td>\n";
            print "<td width='85%' colspan='3'>";
            armaTipoOperacion($this->arrayForm['operacion'],2);
            print "</td></tr>\n";

            print "<tr id='troper' style='display:";
            if($this->arrayForm['operacion']=='Tasacion') {
                $verMax='table-row';
            }else {
                $verMax='none';
            }
            print "$verMax' width='100%'>";
            print "<td class='cd_celda_texto' width='15%'>Comentario</td>\n";
            print "<td width='85%' colspan='3'><input class='campos' type='text' name='comentario' id='comentario' value='". $this->arrayForm['comentario'] ."' maxlength='250' size='80'></td></tr>\n";
        }
        print "<tr><td class='cd_celda_texto' width='15%'>Plano 1</td>";
        print "<td width='35%'><input class='campos' type='file' name='plano1' id='plano1' maxlength='250' size='28' ></td>\n";
        print "<td class='cd_celda_texto' width='15%'>Plano 2</td>";
        print "<td width='35%'><input class='campos' type='file' name='plano2' id='plano2' maxlength='250' size='28' ></td></tr>\n";
        print "<tr><td class='cd_celda_texto' width='15%'>Plano3</td>";
        print "<td width='35%'><input class='campos' type='file' name='plano3' id='plano3' maxlength='250' size='28'></td>\n";
        print "<td class='cd_celda_texto' width='15%'>Video</td>";
        print "<td width='35%'><input class='campos' type='text' name='video' id='video' value='".	$this->arrayForm['video'] ."' maxlength='250' size='80'></td></tr>\n";

        /*		print "<tr><td class='cd_celda_texto' width='15%'>Publica en WEB</td>";
		print "<td width='85%' colspan='3'><input class='campos' type='checkbox' name='activa' id='activa'";
		if ($this->arrayForm['activa']==1){
		print "checked ";
		}
		print "></td></tr>\n";
        */
        print "<tr><td class='cd_celda_texto' width='15%'>Muestra mapa ubicacion</td>\n";
        print "<td width='85%' colspan='3'><input type='button' class='campos' value=\"Muestra mapa de ubicacion\" id='ver' name='ver' onclick='javascript: popupMapa(\"p\");'></td></tr>\n";

        print "<input class='campos' type='hidden' name='goglat' id='goglat' value='";
        if($this->arrayForm['goglat']=="") {
            echo "0";
        }else {
            echo $this->arrayForm['goglat'];
        }
        print "' maxlength='250' size='80'>\n";
        print "<input class='campos' type='hidden' name='goglong' id='goglong' value='";
        if($this->arrayForm['goglong']=="") {
            echo "0";
        }else {
            echo $this->arrayForm['goglong'];
        }
        print "' maxlength='250' size='80'>\n";

        print "<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop'] ."'>\n";
        print "<input type='hidden' name='id_cliente' id='id_cliente' value='".$this->arrayForm['id_cliente'] ."'>\n";

        print "<br>";
        print "<tr><td colspan='4' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='txt_obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }



    /**
     * Lee desde un formulario los datos cargados para el propiedad.
     * Los registra en un objeto del tipo propiedad propiedad de esta clase
     *
     */
    public function leeDatosPropiedadVW() {
        $propiedad=new PropiedadBSN();

        $_POST['plano1'] = $this->UploadPlanos('plano1');
        $_POST['plano2'] = $this->UploadPlanos('plano2');
        $_POST['plano3'] = $this->UploadPlanos('plano3');

        $this->propiedad=$propiedad->leeDatosForm($_POST);

        if($this->propiedad->getCompartir()=="on") {
            $this->propiedad->setCompartir(1);
        } else {
            $this->propiedad->setCompartir(0);
        }
        if($this->propiedad->getActiva()=="on") {
            $this->propiedad->setActiva(1);
        } else {
            $this->propiedad->setActiva(0);
        }
        // Si se activa lo de publicacion desde el Menu, quitar el comentario anterior y comentariar la linea siguiente
        //        $this->propiedad->setActiva(0);
    }


    public  function UploadPlanos($plano) {
        if($_FILES[$plano]['type']=='image/jpeg' || $_FILES[$plano]['type']=='image/gif' || $_FILES[$plano]['type']=='image/png') {
            $planoup= new Upload($_FILES[$plano],'es_ES');
            $nom=$_FILES[$plano]['name'];
            $nombre='PL_'.$_POST['id_prop'].'_'.substr($nom,0,strlen($nom)-4);
            if($planoup->uploaded) {
                $planoup->image_resize = true;
                $planoup->image_ratio_y = true;
                $planoup->file_new_name_body = $nombre;

                $planoup->image_x = $this->anchoG;

                $planoup->Process($this->path);

                // we check if everything went OK
                if ($planoup->processed) {
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $planoup->error . '';
                    echo '</fieldset>';
                }

                $planoup->image_resize 	= true;
                $planoup->image_ratio_y = true;

                $planoup->image_x = $this->anchoC;
                $planoup->file_new_name_body = $nombre;

                $planoup->Process($this->pathC);

                // we check if everything went OK
                if ($planoup->processed) {
                } else {
                    // one error occured
                    echo '<fieldset>';
                    echo '  <legend>file not uploaded to the wanted location</legend>';
                    echo '  Error: ' . $planoup->error . '';
                    echo '</fieldset>';
                }

            }else {
                echo '<fieldset>';
                echo '  <legend>file not uploaded on the server</legend>';
                echo '  Error: ' . $planoup->error . '';
                echo '</fieldset>';
            }

            $retorno = $planoup->file_dst_name;
        }else {
            $retorno = 'NULL';
        }
        return $retorno;
    }


    /******************
	 *
	 * Vista datos propiedad
     */
    public function vistaDatosPropiedad($id_cli=0) {
        $zonaBSN = new ZonaBSN ();
        $locaBSN = new LocalidadBSN();
        $tipo_propBSN=new Tipo_propBSN();
        $sucursal = new Sucursal();

        if($this->arrayForm['id_cliente']==0 || $this->arrayForm['id_cliente']=='') {
            $this->arrayForm['id_cliente']=$id_cli;
        }

        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='pg_titulo'>Propiedades</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='100%' align='center' bgcolor='#FFFFFF'>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Sucursal</td>";
        print "<td width='35%' colspan='3'>";
        print $sucursal->nombreSucursal($this->arrayForm['id_sucursal']);
        print "</td></tr>";

        print "<tr><td class='cd_celda_texto' width='15%'>Zona</td>";
        print "<td width='35%'>";
        $zonaBSN = new ZonaBSN ($this->arrayForm['id_zona']);
        echo $zonaBSN->getObjeto()->getNombre_zona();
        print "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Localidad</td>";
        print "<td width='35%'>";
        print "<div id='localidad'>";

        $locaBSN = new LocalidadBSN($this->arrayForm['id_loca']);
        echo $locaBSN->getObjeto()->getNombre_loca();
        print "</div>";
        print "</td></tr>\n";

        print "<td class='cd_celda_texto' width='15%'>Emprendimiento</td>";
        print "<td width='35%'>";
        print "<div id='emprendimiento'>";
        $empBSN = new EmprendimientoBSN($this->arrayForm['id_emp']);
        echo $empBSN->getObjeto()->getNombre();
        //$empBSN->getNombreEmpre($this->arrayForm['id_emp']);
        //        $empBSN->comboEmprendimiento($this->arrayForm['id_emp'],3,$this->arrayForm['id_zona'],$this->arrayForm['id_loca']);
        // print $empBSN->cargaById($this->arrayForm['id_emp']);
        print "</div>";
        print "</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Calle</td>";
        print "<td width='35%'>". $this->arrayForm['calle'] ."</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Nro</td>";
        print "<td width='35%'>". $this->arrayForm['nro'] ."</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Piso</td>";
        print "<td width='35%'>". $this->arrayForm['piso'] ."</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Dpto</td>";
        print "<td width='35%'>". $this->arrayForm['dpto'] ."</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Entre</td>";
        print "<td width='35%'>". $this->arrayForm['entre1'] ."</td>\n";

        print "<td class='cd_celda_texto' width='15%'> y </td>";
        print "<td width='35%'>". $this->arrayForm['entre2'] ."</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Nombre de Edificio </td>";
        print "<td width='90%' colspan='3'>". $this->arrayForm['nomedif'] ."</td></tr>\n";

        if($this->arrayForm['id_sucursal'] == "NOR") {
            print "<tr><td class='cd_celda_texto' width='15%'>ID Parcela</td>";
            print "<td width='35%'>". $this->arrayForm['id_parcela'] ."</td>\n";
            print "<td class='cd_celda_texto' width='15%'>ID Ccomercial </td>";
            print "<td width='35%'>". $this->arrayForm['id_comercial'] ."</td></tr>\n";
        }
        print "<tr><td class='cd_celda_texto' width='15%'> Descripcion </td>";
        print "<td width='90%' colspan='3'>". $this->arrayForm['descripcion'] ."</td></tr>\n";

        print "<tr><td class='cd_celda_texto' width='15%'>Tipo de Prop.</td>\n";
        print "<td width='35%'>";
        $tipo_propBSN = new Tipo_propBSN($this->arrayForm['id_tipo_prop']);
        echo $tipo_propBSN->getObjeto()->getTipo_prop() . "</td>\n";

        print "<td class='cd_celda_texto' width='15%'>Subtipo Prop.</td>\n";
        print "<td width='35%'>";
        print "<div id='subtipo'>";
        echo $this->arrayForm['subtipo_prop'];
        print "</div>";
        print "</td></tr>\n";

        print "<td class='cd_celda_texto' width='15%'>Intermediacion</td>\n";
        print "<td width='85%' colspan='3'>".$this->arrayForm['intermediacion'];
        print "</td>\n";
        print "</tr>\n";

        print "<tr id='trint' style='display:";
        if($this->arrayForm['intermediacion']!='Exclusiva' && $this->arrayForm['intermediacion']!='') {
            $verMax='table-row';
        }else {
            $verMax='none';
        }
        print "$verMax width='100%'><td class='cd_celda_texto' width='15%'>Inmobiliaria</td>";
        print "<td width='85%' colspan='3'>";
        armaInmo($this->arrayForm['id_inmo']);
        print "</td></tr>";

        if ($this->arrayForm['id_prop']==0) {
            print "<tr><td class='cd_celda_texto' width='15%'>Operacion</td>\n";
            print "<td width='85%' colspan='3'>".$this->arrayForm['operacion'];
            //           armaTipoOperacion($this->arrayForm['operacion'],2);
            print "</td></tr>\n";

            print "<tr id='troper' style='display:";
            if($this->arrayForm['operacion']=='Tasacion') {
                $verMax='table-row';
            }else {
                $verMax='none';
            }
            print "$verMax' width='100%'>";
            print "<td class='cd_celda_texto' width='15%'>Comentario</td>\n";
            print "<td width='85%' colspan='3'>". $this->arrayForm['comentario'] ."</td></tr>\n";
        }
        print "<br>";
        print "     </table>\n";
        print "</td></tr>\n</table>\n";
    }


    public function vistaPlanosPropiedad($id_cli=0) {
		$flagP=0;
        print "<table width='90%' align='center' bgcolor='#FFFFFF'>\n";
        print "<tr><td class='cd_celda_titulo' colspan='2'>Planos de la Propiedad</td></tr>\n";

        if($this->arrayForm['plano1']!='' && $this->arrayForm['plano1']!='NULL'){
        	$flagP++;
        	print "<tr><td class='cd_celda_texto' width='15%'>Plano 1</td>";
        	print "<td width='35%'>". $this->arrayForm['plano1']."<br><img src='".$this->pathC."/".$this->arrayForm['plano1']."'></td></tr>\n";
        }
        if($this->arrayForm['plano2']!='' && $this->arrayForm['plano2']!='NULL'){
        	$flagP++;
        	print "<tr><td class='cd_celda_texto' width='15%'>Plano 2</td>";
        	print "<td width='35%'>". $this->arrayForm['plano2'] ."<br><img src='".$this->pathC."/".$this->arrayForm['plano2']."'></td></tr>\n";
        }
        if($this->arrayForm['plano3']!='' && $this->arrayForm['plano3']!='NULL'){
        	$flagP++;
        	print "<tr><tr><td class='cd_celda_texto' width='15%'>Plano3</td>";
        	print "<td width='35%'>". $this->arrayForm['plano3'] ."<br><img src='".$this->pathC."/".$this->arrayForm['plano3']."'></td></tr>\n";
        }
		if($flagP==0){
        	print "<tr><tr><td>No se hay Planos disponibles para mostrar</td>";
		}
        print "     </table>\n";
    }



    /**    OK
     * Muestra una tabla con los datos de los propiedads y una barra de herramientas o menu
     * conde se despliegan las opciones ingresables para cada item
     *
     */
    public function vistaTablaPropiedad($pagina=1) {
        $zona = new Zona ();
        $zonaBSN = new ZonaBSN ();
        $local = new Localidad();
        $localaBSN = new LocalidadBSN();
        $tipopropBSN = new Tipo_propBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal= new Sucursal();
        $config= new CargaConfiguracion();
        $registros=$config->leeParametro('regprod_adm');

        $perf = new PerfilesBSN();
        $perfSuc = $perf->sucursalPerfil($_SESSION['Userrole']);
        $perfGpo = $perf->grupoPerfil($_SESSION['Userrole']);
        $gtp = new Grupo_tipoprop();
        if($perfGpo=='SUPERUSER' || $perfGpo=='LECTURA' || $perfGpo=='admin' || $perfGpo=='GRAL') {
            $listaTPG=0;
        }else {
            $listaTPG=$gtp->listaTipopropGrupo($perfGpo);
        }
//        echo $perfSuc." - ".$perfGpo." - ".$listaTPG."<br>";


        $fila=0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(opcion,id){\n";
        print "     document.forms.lista.id_prop.value=id;\n";
        print "   	submitform(opcion);\n";
        print "}\n";
        print "function filtra(){\n";
        print "   document.lista.action='lista_propiedad.php?i=';\n";
        print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
        print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
        print "   document.forms.lista.fsucursal.value=document.forms.lista.aux_sucursal.value;\n";
        //		print "   document.forms.lista.fsucursal.value=document.forms.lista.aux_sucursal.options[document.getElementById(\"aux_sucursal\").selectedIndex].text;\n";
        print "   document.forms.lista.fpublicadas.value=document.forms.lista.aux_publicadas.value;\n";
        //				print "alert(document.forms.lista.foperacion.value);\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function limpiafiltro(){\n";
        print "   document.lista.action='lista_propiedad.php?i=';\n";
        print "   document.forms.lista.fid_zona.value=0;\n";
        print "   document.forms.lista.fid_loca.value=0;\n";
        print "   document.forms.lista.fid_tipo_prop.value=0;\n";
        print "   document.forms.lista.foperacion.value='Todas';\n";
        print "   document.forms.lista.fsucursal.value='Todas';\n";
        print "   document.forms.lista.fpublicadas.value='Todas';\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function paginar(pagina){\n";
//		print "   document.lista.action='lista_propiedad.php?i=';\n";		
        print "   document.forms.lista.pagina.value=pagina;\n";
        print "   filtra();\n";
//		print "   document.lista.submit();\n";
        print "}\n";
        print "function publicar(id){\n";
        print "   window.open('publicar_propiedad.php?i='+id, 'ventana', 'menubar=1,resizable=1,width=350,height=250');\n";
        print "}\n";
        print "</script>\n";

        print "<span class='pg_titulo'>Listado de Propiedades</span><br><br>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        $menu=new Menu();
        $menu->dibujaMenu('lista');
        //		$menu->dibujaMenu('lista','opcion');

        // Filtro
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td colspan='6' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
        print "	</tr>";
        print "<tr>\n";
        print "		<td class='cd_celda_texto' width='16%'>Sucursal</td>";
        print "		<td class='cd_celda_texto' width='16%'>Zona</td>";
        print "		<td class='cd_celda_texto' width='16%'>Localidad</td>";
        print "		<td class='cd_celda_texto' width='16%'>Tipo de Prop.</td>";
        print "		<td class='cd_celda_texto' width='16%'>Operacion</td>";
        print "		<td class='cd_celda_texto' width='16%'>WEB</td>";  // Si se activa la vista en WEB quitar este comentario
        print "</tr>\n";

        if(isset($_POST['fid_zona']) && $_POST['fid_zona']!=0) {
            $aux_zona=$_POST['fid_zona'];
        }else {
            $aux_zona=0;
        }
        if(isset($_POST['fid_loca']) && $_POST['fid_loca']!=0) {
            $aux_loca=$_POST['fid_loca'];
        }else {
            $aux_loca=0;
        }
        if(isset($_POST['fid_tipo_prop']) && $_POST['fid_tipo_prop']!=0) {
            $aux_prop=$_POST['fid_tipo_prop'];
        }else {
            if($listaTPG==0) {
                $aux_prop=0;
            }else {
                $aux_prop=$listaTPG;
            }
        }
        if(isset($_POST['foperacion']) && $_POST['foperacion']!='Todas') {
            $aux_operacion=$_POST['foperacion'];
        }else {
            $aux_operacion='Todas';
        }
        if(isset($_POST['fsucursal']) && $_POST['fsucursal']!='Todas') {
            $aux_sucursal=$_POST['fsucursal'];
        }else {
            if($perfSuc=='Todas') {
                $aux_sucursal='Todas';
            }else {
                $aux_sucursal=$perfSuc;
            }
        }
        if(isset($_POST['fpublicadas']) && $_POST['fpublicadas']!='Todas') {
            $aux_publicadas=$_POST['fpublicadas'];
        }else {
            $aux_publicadas='Todas';
        }

        print "<tr>\n";
        print "<td>";
        $sucursal->comboSucursal($aux_sucursal,'aux_sucursal',1);
        print "</td>";

        print "	<td>";
        $zona1BSN = new ZonaBSN ();
        $zona1BSN->comboZona($aux_zona,$aux_loca,1,'aux_zona','aux_loca');
        print "</td>\n";

        print "<td>";
        print "<div id='localidad'>";
        $loca = new Localidad();
        $loca->setId_loca($aux_loca);
        $loca->setId_zona($aux_zona);
        $locaBSN = new LocalidadBSN($loca);
        $locaBSN->comboLocalidad($aux_loca,$aux_zona,1,'aux_loca');
        print "</div>";
        print "</td>\n";

        print "<td>";
        $tipo_propBSN->comboTipoProp($aux_prop,1,'aux_prop');
        print "</td>";

        print "<td>";
        armaTipoOperacion($aux_operacion,1,'aux_operacion',0);
        print "</td>\n";

        print "<td><select id='aux_publicadas' name='aux_publicadas' class='campos'>";
        print "<option value='Todas' ";
        if($aux_publicadas=='Todas') {
            print "selected";
        }
        print ">Todas</option>";
        print "<option value='Publicadas' ";
        if($aux_publicadas=='Publicadas') {
            print "selected";
        }
        print ">Publicadas</option>";
        print "<option value='No publicadas' ";
        if($aux_publicadas=='No publicadas') {
            print "selected";
        }
        print ">No publicadas</option>";
        print "</select></td>";

        print "</tr>\n";

        print "<tr>\n";
        print " <td colspan='2'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
        print "</tr>\n";
        print "</table>\n";


        // Fin Filtro

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo' colspan='8'>&nbsp;</td>\n";
        print "     <td class='cd_lista_titulo'>Codigo</td>\n";
        print "     <td class='cd_lista_titulo'>Sucursal</td>\n";
        print "     <td class='cd_lista_titulo'>Zona</td>\n";
        print "     <td class='cd_lista_titulo'>Localidad</td>\n";
        print "     <td class='cd_lista_titulo'>Calle</td>\n";
        print "     <td class='cd_lista_titulo'>Nro</td>\n";
        print "     <td class='cd_lista_titulo'>Piso</td>\n";
        print "     <td class='cd_lista_titulo'>Dpto</td>\n";
        print "     <td class='cd_lista_titulo'>Edificio</td>\n";
        print "     <td class='cd_lista_titulo'>Tipo Prop.</td>\n";
        print "     <td class='cd_lista_titulo'>Intermediacion</td>\n";
        //		print "     <td class='cd_lista_titulo'>WEB</td>\n";
        print "	  </tr>\n";

        $evenBSN=new PropiedadBSN();
        //		$arrayEven=$evenBSN->cargaColeccionForm();
        $arrayEven=$evenBSN->cargaColeccionFiltro($aux_zona,$aux_loca,$aux_prop,$aux_operacion,$aux_publicadas,$aux_sucursal,$pagina);
        $cantreg=$evenBSN->cantidadRegistrosFiltro($aux_zona,$aux_loca,$aux_prop,$aux_operacion,$aux_publicadas,$aux_sucursal);
//		$paginas=intval($cantreg/$registros)+1;
        $cantr=$cantreg/$registros;
        $cante=$cantreg%$registros;
        if($cante !=0 ) {
            $paginas = intval($cantr + 1);
        }else {
            $paginas = $cantr;
        }

//		echo $cantreg."<br>";
        if(sizeof($arrayEven)==0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if($fila==0) {
                    $fila=1;
                } else {
                    $fila=0;
                }

                print "<tr>\n";
                if(($perfSuc == $Even['id_sucursal'] || $perfSuc=='Todas') && ($perfGpo==$gtp->perteneceGrupo($Even['id_tipo_prop']) || $perfGpo=='GRAL' || $perfGpo=='SUPERUSER' || $perfGpo=='LECTURA' || $perfGpo=='admin')) {
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(22,".$Even['id_prop'].");'>";
                    print "       <img src='images/building_edit.png' alt='Editar' title='Editar' border='0'></a></td>";
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(23,".$Even['id_prop'].");' onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                    print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border='0'></a>";
                    print "  </td>\n";
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(24,".$Even['id_prop'].");'>";
                    print "       <img src='images/book_edit.png' alt='Editar Caracteristicas' title='Editar Caracteristicas' border='0'></a>";
                    print "  </td>\n";
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(25,".$Even['id_prop'].");'>";
                    print "       <img src='images/camera_edit.png' alt='Fotografias' title='Fotografías' border='0'></a>";
                    print "  </td>\n";
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(26,".$Even['id_prop'].");'>";
                    print "       <img src='images/operacion.png' alt='Operacion' title='Estado Operacion' border='0'></a>";
                    print "  </td>\n";
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(27,".$Even['id_prop'].");'>";
                    print "       <img src='images/cartel.png' alt='Estado Cartel' title='Estado Cartel' border='0'></a>";
                    print "  </td>\n";

                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:publicar(".$Even['id_prop'].");'>";
                    if($Even['activa']==0) {
                        print "       <img src='images/web.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0'></a>";
                    }else {
                        print "       <img src='images/web_no.png' alt='Publicar la propiedad' title='Publicar la propiedad' border='0'></a>";
                    }
                    print "  </td>\n";

                    /* Si se activa el tema de publicar en WEB quitar estos comentarios y conemtariar el bloque anterior
				if($Even['activa'] == 0){
				print "	 <td class='row".$fila."'>";
				print "    <a href='javascript:envia(281,".$Even['id_prop'].");'>";
				print "       <img src='images/web.png' alt='Publicar en Web' title='Publicar en Web' border='0'></a>";
				print "  </td>\n";
				}else{
				print "	 <td class='row".$fila."'>";
				print "    <a href='javascript:envia(282,".$Even['id_prop'].");'>";
				print "       <img src='images/noweb.png' alt='Quitar de la Web' title='Quitar de la Web' border='0'></a>";
				print "  </td>\n";
				}
                    */
                    print "	 <td class='row".$fila."'>";
                    if(trim($Even['operacion'])=='Tasacion') {
                        print "    <a href='javascript:envia(283,".$Even['id_prop'].");'>";
                        print "       <img src='images/tasar.png' alt='Tasacion' title='Tasacion' border='0'></a>";
                    }
                    print "  </td>\n";
                }else {
                    print "  <td class='row".$fila."' colspan='8'>&nbsp;</td>\n";
                }


                print "  <td class='row".$fila."'>";
                print $Even['id_sucursal'].str_repeat("0",6-strlen(strval($Even['id_prop']))).$Even['id_prop'];
                print "</td>\n";

                print "  <td class='row".$fila."'>";
                print $sucursal->nombreSucursal($Even['id_sucursal']);
                print "</td>\n";

                print "	 <td class='row".$fila."'>";
                $zonaBSN->setId ( $Even ['id_zona'] );
                $zonaBSN->cargaById( $Even['id_zona'] );
                print $zonaBSN->getObjeto()->getNombre_zona();
                print "</td>\n";
                print "	 <td class='row".$fila."'>";
                $localaBSN->setId($Even['id_loca']);
                $localaBSN->cargaById( $Even['id_loca'] );
                print $localaBSN->getObjeto()->getNombre_loca();
                print "</td>\n";
                print "	 <td class='row".$fila."'>".$Even['calle']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['nro']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['piso']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['dpto']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['nomedif']."</td>\n";
                print "	 <td class='row".$fila."'>";
                $tipopropBSN->setId($Even['id_tipo_prop']);
                $tipopropBSN->cargaById($Even['id_tipo_prop']);
                print $tipopropBSN->getObjeto()->getTipo_prop();
                if ($Even['subtipo_prop']!='') {
                    print "(".$Even['subtipo_prop'].")";
                }
                print "</td>\n";
                print "	 <td class='row".$fila."'>".$Even['intermediacion']."</td>\n";
                /* Quitar comentarios si se habilita la vista de publicacion en web
				print "	 <td class='row".$fila."'>";
				if($Even['activa']==1){
				print "<img src=\"images/tilde.png\" border=\"0\" />";
				}
				print "</td>\n";
                */
                print "	</tr>\n";
            }
            print "<tr><td align='center' colspan='19'>";
            if($pagina>1) {
                print "    <a href='javascript:paginar(1);'>";
                print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0'></a>&nbsp;";
                if($pagina>2) {
                    print "    <a href='javascript:paginar(". ($pagina - 1) .");'>";
                    print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0'></a>&nbsp;-&nbsp;";
                }
                for($x=$pagina-5;$x<$pagina;$x++) {
                    if($x>0) {
                        print "<a href='javascript:paginar(". $x .");'>$x</a>&nbsp;-&nbsp;";
                    }
                }
            }
            print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
            if($pagina<$paginas) {
                for($x=$pagina+1;$x<$pagina+5;$x++) {
                    if($x<=$paginas) {
                        print "<a href='javascript:paginar(". $x .");'>".$x."</a>&nbsp;-&nbsp;";
                    }
                }
                if($pagina < $paginas-1) {
                    print "    <a href='javascript:paginar(". ($pagina + 1) .");'>";
                    print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0'></a>&nbsp;";
                }
                print "    <a href='javascript:paginar($paginas);'>";
                print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0'></a>&nbsp;";
            }
            print "</td></tr>";

        }

        print "  </table>\n";
        print "<input type='hidden' name='id_prop' id='id_prop' value=''>";
        //		print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value=''>";
        print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>";
        print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>";
        print "<input type='hidden' name='foperacion' id='foperacion' value=''>";
        print "<input type='hidden' name='fsucursal' id='fsucursal' value=''>";
        print "<input type='hidden' name='fpublicadas' id='fpublicadas' value=''>";
        print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

        print "</form>";
    }


    /**    OK
     * Muestra una tabla con los datos de los propiedads y una barra de herramientas o menu
     * conde se despliegan las opciones ingresables para cada item
     *
     */
    public function vistaTablaBuscador($pagina=1) {
        $zona = new Zona ();
        $zonaBSN = new ZonaBSN ();
        $local = new Localidad();
        $localaBSN = new LocalidadBSN();
        $tipopropBSN = new Tipo_propBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal= new Sucursal();
        $config= new CargaConfiguracion();
        $registros=$config->leeParametro('regprod_adm');

        $fila=0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(opcion,id){\n";
        print "   document.forms.lista.id_prop.value=id;\n";
        print "   document.lista.action='muestra_datosprop.php';\n";
        print "   window.open('muestra_datosprop.php?i='+id);\n";
        print "}\n";

        print "function paginar(pagina){\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
        print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
        print "   document.forms.lista.pagina.value=pagina;\n";
        print "   document.lista.submit();\n";
//        print "   filtra();\n";
        print "}\n";

        print "function filtra(){\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
        print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
        print "   document.forms.lista.pagina.value=1;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function limpiafiltro(){\n";
        print "   for(x=0;x<document.lista.elements.length;x++){\n";
        print "		document.lista.elements[x].value='';\n";
        print "   }\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.fid_zona.value=0;\n";
        print "   document.forms.lista.filtro.value=0;\n";
        print "   document.forms.lista.fid_loca.value=0;\n";
        print "   document.forms.lista.fid_tipo_prop.value=0;\n";
        print "   document.forms.lista.foperacion.value='Todas';\n";
        print "   document.forms.lista.pagina.value=1;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "</script>\n";

        print "<span class='pg_titulo'>Listado de Propiedades Disponibles</span><br><br>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>";

        $menu=new Menu();
        $menu->dibujaMenu('lista');

        // Filtro
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td colspan='6' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
        print "	</tr>";
        print "<tr>\n";
        print "		<td class='cd_celda_texto' width='16%'>Zona</td>";
        print "		<td class='cd_celda_texto' width='16%'>Localidad</td>";
        print "		<td class='cd_celda_texto' width='16%'>Tipo de Prop.</td>";
        print "		<td class='cd_celda_texto' width='16%'>Operacion</td>";
        print "</tr>\n";

        if(isset($_POST['fid_zona']) && $_POST['fid_zona']!=0) {
            $aux_zona=$_POST['fid_zona'];
        }else {
            $aux_zona=0;
        }
        if(isset($_POST['fid_loca']) && $_POST['fid_loca']!=0) {
            $aux_loca=$_POST['fid_loca'];
        }else {
            $aux_loca=0;
        }
        if(isset($_POST['fid_tipo_prop']) && $_POST['fid_tipo_prop']!=0) {
            $aux_prop=$_POST['fid_tipo_prop'];
        }else {
            $aux_prop=0;
        }
        if(isset($_POST['foperacion']) && $_POST['foperacion']!='Todas') {
            $aux_operacion=$_POST['foperacion'];
        }else {
            $aux_operacion='Todas';
        }

        print "<tr>\n";

        print "	<td>";
        $zona1BSN = new ZonaBSN ();
        $zona1BSN->comboZona($aux_zona,$aux_loca,1,'aux_zona','aux_loca');
        print "</td>\n";

        print "<td>";
        print "<div id='localidad'>";
        $loca = new Localidad();
        $loca->setId_loca($aux_loca);
        $loca->setId_zona($aux_zona);
        $locaBSN = new LocalidadBSN($loca);
        $locaBSN->comboLocalidad($aux_loca,$aux_zona,1,'aux_loca');
        print "</div>";
        print "</td>\n";

        print "<td>";
        $tipo_propBSN->comboTipoProp($aux_prop,3,'aux_prop','campos','filtro');
        print "</td>";

        print "<td>";
        armaTipoOperacion($aux_operacion,1,'aux_operacion',0);
        print "</td>\n";

        print "</tr>\n";

        print "<tr id='filtro' name='filtro'><td colspan='4'>";
        //		echo "prop -> $aux_prop <br>";
        $dpropVW = new DatospropVW();
        $arraydp = array();
        $arrayDB = array();
        
        if(isset($_POST['filtro']) && $_POST['filtro']!=0){
	        $arraydp = $dpropVW->leeFiltroDatospropVW();  // Lee los valores desde el filtro del formulario
        }

        $dpropVW->cargaFiltroDatosprop($arraydp,$aux_prop); // Carga nuevamente el filtro con los valores leidos
        $dpropBSN = new DatospropBSN();
        $arrayDB=$arraydp;
        $propsIN = $dpropBSN->armaArrayIN($arrayDB);  // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
        print "</td></tr>";

        print "<tr>\n";
        print " <td colspan='2'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
        print "</tr>\n";
        print "</table>\n";

        
        // Fin Filtro

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
        print "     <td class='cd_lista_titulo'>Codigo</td>\n";
        print "     <td class='cd_lista_titulo'>Sucursal</td>\n";
        print "     <td class='cd_lista_titulo'>Zona</td>\n";
        print "     <td class='cd_lista_titulo'>Localidad</td>\n";
        print "     <td class='cd_lista_titulo'>Calle</td>\n";
        print "     <td class='cd_lista_titulo'>Nro</td>\n";
        print "     <td class='cd_lista_titulo'>Piso</td>\n";
        print "     <td class='cd_lista_titulo'>Dpto</td>\n";
        print "     <td class='cd_lista_titulo'>Edificio</td>\n";
        print "     <td class='cd_lista_titulo'>Tipo Prop.</td>\n";
        print "	  </tr>\n";

        $evenBSN=new PropiedadBSN();
        //		$arrayEven=$evenBSN->cargaColeccionForm();
        $arrayEven=$evenBSN->cargaColeccionFiltroBuscador($aux_zona,$aux_loca,$aux_prop,$aux_operacion,0,$propsIN, $pagina );

        $cantreg=$evenBSN->cantidadRegistrosFiltroBuscador($aux_zona,$aux_loca,$aux_prop,$aux_operacion,0,$propsIN);
//		$paginas=intval($cantreg/$registros)+1;
        $cantr=$cantreg/$registros;
        $cante=$cantreg%$registros;

        if($cante !=0 ) {
            $paginas = intval($cantr + 1);
        }else {
            $paginas = $cantr;
        }
        
        if(sizeof($arrayEven)==0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if($fila==0) {
                    $fila=1;
                } else {
                    $fila=0;
                }

                print "<tr>\n";
                print "	 <td colspan='2' class='row".$fila."'>";
                print "    <a href='muestra_datosprop.php?i=".$Even['id_prop']."&keepThis=true&TB_iframe=true&height=600&width=800' title='Datos de la propiedad' class='thickbox'>";
                print "       <img src='images/magnifier.png' alt='Editar' title='Editar' border='0'></a></td>";


                print "  <td class='row".$fila."'>";
                print $Even['id_sucursal'].str_repeat("0",6-strlen(strval($Even['id_prop']))).$Even['id_prop'];
                print "</td>\n";

                print "  <td class='row".$fila."'>";
                print $sucursal->nombreSucursal($Even['id_sucursal']);
                print "</td>\n";

                print "	 <td class='row".$fila."'>";
                $zonaBSN->setId ( $Even ['id_zona'] );
                $zonaBSN->cargaById( $Even['id_zona'] );
                print $zonaBSN->getObjeto()->getNombre_zona();
                print "</td>\n";
                print "	 <td class='row".$fila."'>";
                $localaBSN->setId($Even['id_loca']);
                $localaBSN->cargaById( $Even['id_loca'] );
                print $localaBSN->getObjeto()->getNombre_loca();
                print "</td>\n";
                print "	 <td class='row".$fila."'>".$Even['calle']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['nro']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['piso']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['dpto']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['nomedif']."</td>\n";
                print "	 <td class='row".$fila."'>";
                $tipopropBSN->setId($Even['id_tipo_prop']);
                $tipopropBSN->cargaById($Even['id_tipo_prop']);
                print $tipopropBSN->getObjeto()->getTipo_prop();
                if ($Even['subtipo_prop']!='') {
                    print "(".$Even['subtipo_prop'].")";
                }
                print "</td>\n";
                print "	</tr>\n";
            }
            //------Paginado--------------------
            print "<tr><td align='center' colspan='19'>";
            if($pagina>1) {
                print "    <a href='javascript:paginar(1);'>";
                print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0'></a>&nbsp;";
                if($pagina>2) {
                    print "    <a href='javascript:paginar(". ($pagina - 1) .");'>";
                    print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0'></a>&nbsp;-&nbsp;";
                }
                for($x=$pagina-5;$x<$pagina;$x++) {
                    if($x>0) {
                        print "<a href='javascript:paginar(". $x .");'>$x</a>&nbsp;-&nbsp;";
                    }
                }
            }
            print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
            if($pagina<$paginas) {
                for($x=$pagina+1;$x<$pagina+5;$x++) {
                    if($x<=$paginas) {
                        print "<a href='javascript:paginar(". $x .");'>".$x."</a>&nbsp;-&nbsp;";
                    }
                }
                if($pagina < $paginas-1) {
                    print "    <a href='javascript:paginar(". ($pagina + 1) .");'>";
                    print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0'></a>&nbsp;";
                }
                print "    <a href='javascript:paginar($paginas);'>";
                print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0'></a>&nbsp;";
            }
            print "</td></tr>";

        }

        print "  </table>\n";
        
        print "<input type='hidden' name='id_prop' id='id_prop' value=''>";
        print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value=''>";
        print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>";
        print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>";
        print "<input type='hidden' name='foperacion' id='foperacion' value=''>";
        print "<input type='hidden' name='filtro' id='filtro' value='0'>";

        print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

        print "</form>";
    }


    public function vistaTablaBuscadorTabs($pagina=1) {
        $zona = new Zona ();
        $zonaBSN = new ZonaBSN ();
        $local = new Localidad();
        $localaBSN = new LocalidadBSN();
        $tipopropBSN = new Tipo_propBSN();
        $tipo_propBSN = new Tipo_propBSN();
        $sucursal= new Sucursal();
        $config= new CargaConfiguracion();
        $registros=$config->leeParametro('regprod_adm');

        $fila=0;
        print "<script type='text/javascript' language='javascript'>\n";
        print "function envia(opcion,id){\n";
        print "   document.forms.lista.id_prop.value=id;\n";
        print "   document.lista.action='muestra_datosprop.php';\n";
        print "   window.open('muestra_datosprop.php?i='+id);\n";
        print "}\n";

        print "function paginar(pagina){\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
        print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
        print "   document.forms.lista.pagina.value=pagina;\n";
        print "   document.lista.submit();\n";
//        print "   filtra();\n";
        print "}\n";

        print "function filtra(){\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.filtro.value=1;\n";
        print "   document.forms.lista.fid_zona.value=document.forms.lista.aux_zona.value;\n";
        print "   document.forms.lista.fid_loca.value=document.forms.lista.aux_loca.value;\n";
        print "   document.forms.lista.fid_tipo_prop.value=document.forms.lista.aux_prop.value;\n";
        print "   document.forms.lista.foperacion.value=document.forms.lista.aux_operacion.options[document.getElementById(\"aux_operacion\").selectedIndex].text;\n";
        print "   document.forms.lista.pagina.value=1;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function limpiafiltro(){\n";
        print "   for(x=0;x<document.lista.elements.length;x++){\n";
        print "		document.lista.elements[x].value='';\n";
        print "   }\n";
        print "   document.lista.action='filtro_datosprop.php?i=';\n";
        print "   document.forms.lista.fid_zona.value=0;\n";
        print "   document.forms.lista.filtro.value=0;\n";
        print "   document.forms.lista.fid_loca.value=0;\n";
        print "   document.forms.lista.fid_tipo_prop.value=0;\n";
        print "   document.forms.lista.foperacion.value='Todas';\n";
        print "   document.forms.lista.pagina.value=1;\n";
        print "   document.lista.submit();\n";
        print "}\n";
        print "function marcar(prop){\n";
        print "   document.forms.lista.aux_id_prop.value=prop;\n";
//        print "alert(document.forms.lista.id_prop.value);\n";
        print "}\n";
        print "</script>\n";

        print "<span class='pg_titulo'>Listado de Propiedades Disponibles</span><br><br>\n";

        print "<form name='lista' method='POST' action='respondeMenu.php'>";

//        $menu=new Menu();
//        $menu->dibujaMenu('lista');

        // Filtro
        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td colspan='6' class='cd_lista_filtro' style='text-align: left; padding-left: 10px;'>Filtrar por </td>";
        print "	</tr>";
        print "<tr>\n";
        print "		<td class='cd_celda_texto' width='16%'>Zona</td>";
        print "		<td class='cd_celda_texto' width='16%'>Localidad</td>";
        print "		<td class='cd_celda_texto' width='16%'>Tipo de Prop.</td>";
        print "		<td class='cd_celda_texto' width='16%'>Operacion</td>";
        print "</tr>\n";

        if(isset($_POST['fid_zona']) && $_POST['fid_zona']!=0) {
            $aux_zona=$_POST['fid_zona'];
        }else {
            $aux_zona=0;
        }
        if(isset($_POST['fid_loca']) && $_POST['fid_loca']!=0) {
            $aux_loca=$_POST['fid_loca'];
        }else {
            $aux_loca=0;
        }
        if(isset($_POST['fid_tipo_prop']) && $_POST['fid_tipo_prop']!=0) {
            $aux_prop=$_POST['fid_tipo_prop'];
        }else {
            $aux_prop=0;
        }
        if(isset($_POST['foperacion']) && $_POST['foperacion']!='Todas') {
            $aux_operacion=$_POST['foperacion'];
        }else {
            $aux_operacion='Todas';
        }

        print "<tr>\n";

        print "	<td>";
        $zona1BSN = new ZonaBSN ();
        $zona1BSN->comboZona($aux_zona,$aux_loca,1,'aux_zona','aux_loca');
        print "</td>\n";

        print "<td>";
        print "<div id='localidad'>";
        $loca = new Localidad();
        $loca->setId_loca($aux_loca);
        $loca->setId_zona($aux_zona);
        $locaBSN = new LocalidadBSN($loca);
        $locaBSN->comboLocalidad($aux_loca,$aux_zona,1,'aux_loca');
        print "</div>";
        print "</td>\n";

        print "<td>";
        $tipo_propBSN->comboTipoProp($aux_prop,3,'aux_prop','campos','filtro');
        print "</td>";

        print "<td>";
        armaTipoOperacion($aux_operacion,1,'aux_operacion',0);
        print "</td>\n";

        print "</tr>\n";

        print "<tr id='filtro' name='filtro'><td colspan='4'>";
        //		echo "prop -> $aux_prop <br>";
        $dpropVW = new DatospropVW();
        $arraydp = array();
        $arrayDB = array();
        
        if(isset($_POST['filtro']) && $_POST['filtro']!=0){
	        $arraydp = $dpropVW->leeFiltroDatospropVW();  // Lee los valores desde el filtro del formulario
        }

        $dpropVW->cargaFiltroDatosprop($arraydp,$aux_prop); // Carga nuevamente el filtro con los valores leidos
        $dpropBSN = new DatospropBSN();
        $arrayDB=$arraydp;
        $propsIN = $dpropBSN->armaArrayIN($arrayDB);  // armo un string con los ID de las propiedades que cumplen con los filtros de caracteristicas
        print "</td></tr>";

        print "<tr>\n";
        print " <td colspan='2'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:filtra();' value='Enviar'></td>\n";
        print "		<td class='row' align='right'><input class='boton_form' type='button' onclick='javascript:limpiafiltro();' value='Limpiar Filtro'></td>\n";
        print "</tr>\n";
        print "</table>\n";

        
        // Fin Filtro

        print "  <table class='cd_tabla' width='100%'>\n";
        print "    <tr>\n";
        print "     <td class='cd_lista_titulo' colspan='2'>&nbsp;</td>\n";
        print "     <td class='cd_lista_titulo'>Codigo</td>\n";
//        print "     <td class='cd_lista_titulo'>Sucursal</td>\n";
        print "     <td class='cd_lista_titulo'>Zona</td>\n";
        print "     <td class='cd_lista_titulo'>Localidad</td>\n";
        print "     <td class='cd_lista_titulo'>Calle</td>\n";
        print "     <td class='cd_lista_titulo'>Nro</td>\n";
        print "     <td class='cd_lista_titulo'>Piso</td>\n";
        print "     <td class='cd_lista_titulo'>Dpto</td>\n";
//        print "     <td class='cd_lista_titulo'>Edificio</td>\n";
        print "     <td class='cd_lista_titulo'>Tipo Prop.</td>\n";
        print "	  </tr>\n";

        $evenBSN=new PropiedadBSN();
        //		$arrayEven=$evenBSN->cargaColeccionForm();
        $arrayEven=$evenBSN->cargaColeccionFiltroBuscador($aux_zona,$aux_loca,$aux_prop,$aux_operacion,0,$propsIN, $pagina );

        $cantreg=$evenBSN->cantidadRegistrosFiltroBuscador($aux_zona,$aux_loca,$aux_prop,$aux_operacion,0,$propsIN);
//		$paginas=intval($cantreg/$registros)+1;
        $cantr=$cantreg/$registros;
        $cante=$cantreg%$registros;

        if($cante !=0 ) {
            $paginas = intval($cantr + 1);
        }else {
            $paginas = $cantr;
        }
        
        if(sizeof($arrayEven)==0) {
            print "No existen datos para mostrar";
        } else {
            foreach ($arrayEven as $Even) {
                if($fila==0) {
                    $fila=1;
                } else {
                    $fila=0;
                }

                print "<tr>\n";
                print "	 <td colspan='2' class='row".$fila."'>";
//                print "    <a href='muestra_datosprop.php?i=".$Even['id_prop']."&keepThis=true&TB_iframe=true&height=600&width=800' title='Datos de la propiedad' class='thickbox'>";
//                print "       <img src='images/magnifier.png' alt='Editar' title='Editar' border='0'></a>";

				print "<input type='radio' name='id_prop' id='id_prop' value='".$Even['id_prop']."' onclick='javascript:marcar(".$Even['id_prop'].");'>";
				print "</td>";


                print "  <td class='row".$fila."'>";
                print $Even['id_sucursal'].str_repeat("0",6-strlen(strval($Even['id_prop']))).$Even['id_prop'];
                print "</td>\n";

//                print "  <td class='row".$fila."'>";
//                print $sucursal->nombreSucursal($Even['id_sucursal']);
//                print "</td>\n";

                print "	 <td class='row".$fila."'>";
                $zonaBSN->setId ( $Even ['id_zona'] );
                $zonaBSN->cargaById( $Even['id_zona'] );
                print $zonaBSN->getObjeto()->getNombre_zona();
                print "</td>\n";
                print "	 <td class='row".$fila."'>";
                $localaBSN->setId($Even['id_loca']);
                $localaBSN->cargaById( $Even['id_loca'] );
                print $localaBSN->getObjeto()->getNombre_loca();
                print "</td>\n";
                print "	 <td class='row".$fila."'>".$Even['calle']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['nro']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['piso']."</td>\n";
                print "	 <td class='row".$fila."'>".$Even['dpto']."</td>\n";
//                print "	 <td class='row".$fila."'>".$Even['nomedif']."</td>\n";
                print "	 <td class='row".$fila."'>";
                $tipopropBSN->setId($Even['id_tipo_prop']);
                $tipopropBSN->cargaById($Even['id_tipo_prop']);
                print $tipopropBSN->getObjeto()->getTipo_prop();
                if ($Even['subtipo_prop']!='') {
                    print "(".$Even['subtipo_prop'].")";
                }
                print "</td>\n";
                print "	</tr>\n";
            }
            //------Paginado--------------------
            print "<tr><td align='center' colspan='19'>";
            if($pagina>1) {
                print "    <a href='javascript:paginar(1);'>";
                print "       <img src='images/resultset_first.png' alt='Inicio' title='Inicio' border='0'></a>&nbsp;";
                if($pagina>2) {
                    print "    <a href='javascript:paginar(". ($pagina - 1) .");'>";
                    print "       <img src='images/resultset_previous.png' alt='Anterior' title='Anterior' border='0'></a>&nbsp;-&nbsp;";
                }
                for($x=$pagina-5;$x<$pagina;$x++) {
                    if($x>0) {
                        print "<a href='javascript:paginar(". $x .");'>$x</a>&nbsp;-&nbsp;";
                    }
                }
            }
            print "<span style='font-weight: bold; color:#FFF; background-color:#99BBEA;'>&nbsp;$pagina&nbsp;</span>-&nbsp;";
            if($pagina<$paginas) {
                for($x=$pagina+1;$x<$pagina+5;$x++) {
                    if($x<=$paginas) {
                        print "<a href='javascript:paginar(". $x .");'>".$x."</a>&nbsp;-&nbsp;";
                    }
                }
                if($pagina < $paginas-1) {
                    print "    <a href='javascript:paginar(". ($pagina + 1) .");'>";
                    print "       <img src='images/resultset_next.png' alt='Siguiente' title='Siguiente' border='0'></a>&nbsp;";
                }
                print "    <a href='javascript:paginar($paginas);'>";
                print "       <img src='images/resultset_last.png' alt='Ultima' title='Ultima' border='0'></a>&nbsp;";
            }
            print "</td></tr>";

        }

        print "  </table>\n";
        
        print "<input type='hidden' name='id_prop' id='id_prop' value=''>";
        print "<input type='hidden' name='aux_id_prop' id='aux_id_prop' value=''>";
        print "<input type='hidden' name='fid_tipo_prop' id='fid_tipo_prop' value=''>";
        print "<input type='hidden' name='fid_zona' id='fid_zona' value=''>";
        print "<input type='hidden' name='fid_loca' id='fid_loca' value=''>";
        print "<input type='hidden' name='foperacion' id='foperacion' value=''>";
        print "<input type='hidden' name='filtro' id='filtro' value='0'>";

        print "<input type='hidden' name='pagina' id='pagina' value='$pagina'>";

        print "</form>";
    }
    
    
    
    public function grabaModificacion() {
        $retorno=false;
        $propiedad=new PropiedadBSN($this->propiedad);
        $retUPre=$propiedad->actualizaDB();
        if ($retUPre) {
            echo "Se proceso la grabacion en forma correcta<br>";
            $retorno=true;
        } else {
            echo "Fallo la grabación de los datos<br>";
        }
        return $retorno;
    }


    public function grabaPropiedad() {
        $retorno=false;
        $propiedad=new PropiedadBSN($this->propiedad);
        //Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
        $existente=$propiedad->controlDuplicado();//$this->propiedad->getPropiedad());
        if($existente) {
            echo "Ya existe un propiedad con esos datos de ubicaci�n verifique los mismos.";
        } else {
            $retIPre=$propiedad->insertaDB();
            //			die();
            if ($retIPre) {
                $this->propiedad->setId_prop($propiedad->buscaID());
                echo "Se proceso la grabacion en forma correcta<br>";
                $retorno=true;
            } else {
                echo "Fallo la grabación de los datos<br>";
            }
        } // Fin control de Duplicados
        return $retorno;
    }

    public function muestraDomicilio() {
        $local = new Localidad();
        $localaBSN = new LocalidadBSN();

        $localaBSN->setId($this->arrayForm['id_loca']);
        $localaBSN->cargaById( $this->arrayForm['id_loca'] );

        print "<span class='pg_titulo'>Propiedad:".$this->arrayForm['calle']." ".$this->arrayForm['nro']." ".$this->arrayForm['piso']." ".$this->arrayForm['dpto']." - ";
        print $localaBSN->getObjeto()->getNombre_loca();
        print "</span><br><br>\n";
    }

} // fin clase


?>
