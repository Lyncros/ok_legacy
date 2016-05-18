<?php
include_once("generic_class/class.menu.php");
include_once("clases/class.operacionBSN.php");
include_once("clases/class.operacion.php");
include_once("clases/class.propiedadVW.php");
include_once("inc/funciones.inc");

class OperacionVW {

    private $operacion;
    private $arrayForm;

    public function __construct($_operacion=0) {
        OperacionVW::creaOperacion();
        if($_operacion instanceof Operacion ) {
            OperacionVW::seteaOperacion($_operacion);
        }
        if (is_numeric($_operacion)) {
            if($_operacion!=0) {
                OperacionVW::cargaOperacion($_operacion);
            }
        }
    }


    public function cargaOperacion($_operacion) {
        $operacion=new OperacionBSN($_operacion);
        $this->seteaOperacion($operacion->getObjeto()); //operacion());
    }

    public function getIdOperacion() {
        return $this->operacion->getId_oper();

    }

    protected function creaOperacion() {
        $this->operacion=new operacion();
    }

    protected function seteaOperacion($_operacion) {
        $this->operacion=$_operacion;
        $operacion=new OperacionBSN($_operacion);
        $this->arrayForm=$operacion->getObjetoView();

    }


    public function setIdPropiedad($_prop) {
        $this->operacion->setId_prop($_prop);
        $this->arrayForm['id_prop']=$_prop;
    }

    public function getIdPropiedad() {
        return $this->operacion->getId_prop();
    }


    public function cargaDatosOperacion() {
        $propVW = new PropiedadVW($this->arrayForm['id_prop']);
        $menu=new Menu();
        $menu->dibujaMenu('carga');
        $propVW->muestraDomicilio();
        print "<form action='carga_operacion.php' name='carga' id='carga' method='post' onSubmit='javascript: return ValidaOperacion(this);'>\n";
        print "<table width='100%' align='center' bgcolor='#f1f1f1'>\n";

        print "<tr><td class='cd_celda_titulo'>Carga de estado de la Operacion</td></tr>\n";

        print "<tr><td align='center'>";
        print "<table width='100%' align='center' cellspacing='10'>\n";

        print "<tr><td class='cd_celda_texto' width='50%'>Fecha <span class='obligatorio'>&nbsp;&bull;</span><br />";
        if($this->arrayForm['cfecha'] != ''){
            $fecha = $this->arrayForm['cfecha'];
        }else{
            $fecha = date('d-m-Y');
        }
        print "<input class='campos' type='text' name='cfecha' id='cfecha' value='".	$fecha ."' maxlength='10' size='80' style='width: 90%;'></td>\n";
        print "	<script type=\"text/javascript\">\n";
        print "jQuery(\"#cfecha\").datepicker({showButtonPanel: true,dateFormat: \"dd-mm-yy\", defaultDate: '0w', selectDefaultDate: true });\n";
        print "</script>\n";

        print "<td class='cd_celda_texto' width='50%'>Operacion <span class='obligatorio'>&nbsp;&bull;</span><br />";
        armaTipoOperacion($this->arrayForm['operacion']);
        print "</td></tr>\n";

        print "<tr>";
        print "<td class='cd_celda_texto' colspan='2'>Comentario<br />";
        print "<input class='campos' type='text' name='comentario' id='comentario' value='".	$this->arrayForm['comentario'] ."' maxlength='250' size='80'></td></tr>\n";

        print "<input type='hidden' name='id_oper' id='id_oper' value='".$this->arrayForm['id_oper'] ."'>\n";
        print "<input type='hidden' name='id_prop' id='id_prop' value='".$this->arrayForm['id_prop'] ."'>\n";
        print "<input type='hidden' name='intervino' id='intervino' value='".$_SESSION['UserId']."'>\n";
        print "<input type='hidden' name='opcion' id='opcion' value=''>";
        print "<br>";
        print "<tr><td colspan='3' align='right'><input class='boton_form' type='submit' value='Enviar'><br /><br /><span class='obligatorio'>Los campos marcados con &bull; son obligatorios</span></td></tr>\n</table>\n";
        print "</td></tr>\n</table>\n";
        print "</form>\n";
    }



    /**
     * Lee desde un formulario los datos cargados para el operacion.
     * Los registra en un objeto del tipo operacion operacion de esta clase
     *
     */
    public function leeDatosOperacionVW() {
        $operacion=new OperacionBSN();
        $this->operacion=$operacion->leeDatosForm($_POST);
    }

    /**    OK
     * Muestra una tabla con los datos de los operacions y una barra de herramientas o menu
     * conde se despliegan las opciones ingresables para cada item
     *
     */
    public function vistaTablaOperaciones($id_prop=0) {
        $propVW = new PropiedadVW($id_prop);
        $fila=0;

        if($id_prop==0 || is_nan($id_prop)) {
            echo "Debe seleccionar un Propiedad para poder verificar sus Fotos";
        } else {
            print "<form name='lista' method='POST' action='respondeMenu.php'>";

            print "<script type='text/javascript' language='javascript'>\n";
            print "function envia(opcion,id){\n";
            print "     document.forms.lista.id_oper.value=id;\n";
            print "   	submitform(opcion);\n";
            print "}\n";
            print "</script>\n";

            print "<span class='pg_titulo'>Estados de la Operacion de Propiedades</span><br><br>\n";
            $propVW->muestraDomicilio();


            $menu=new Menu();
            $menu->dibujaMenu('lista','opcion');


            print "  <table class='cd_tabla' width='100%'>\n";
            print "    <tr>\n";
            print "     <td class='cd_lista_titulo'>&nbsp;</td>\n";
            print "     <td class='cd_lista_titulo'>Fecha Operacion</td>\n";
            print "     <td class='cd_lista_titulo'>Estado Operacion</td>\n";
            print "     <td class='cd_lista_titulo'>Intervino</td>\n";
            print "     <td class='cd_lista_titulo'>Comentario</td>\n";
            print "	  </tr>\n";


            $evenBSN=new OperacionBSN();

            $arrayEven=$evenBSN->cargaColeccionFormByPropiedad($id_prop);

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
                    print "	 <td class='row".$fila."'>";
                    print "    <a href='javascript:envia(263,".$Even['id_oper'].");' border=0 onclick=\"return confirm('Esta seguro que quiere eliminar este registro?');\">";
                    print "       <img src='images/delete.png' alt='Borrar' title='Borrar' border=0></a>";
                    print "  </td>\n";
                    print "	 <td class='row".$fila."'>".$Even['cfecha']."</td>\n";
                    print "	 <td class='row".$fila."'>".$Even['operacion']."</td>\n";
                    print "	 <td class='row".$fila."'>".$Even['intervino']."</td>\n";
                    print "	 <td class='row".$fila."'>".$Even['comentario']."</td>\n";
                    print "	</tr>\n";
                }
            }

            print "  </table>\n";
            print "<input type='hidden' name='id_oper' id='id_oper' value=''>";
            print "<input type='hidden' name='id_prop' id='id_prop' value='".$id_prop."'>";
            print "<input type='hidden' name='opcion' id='opcion' value=''>";
            print "</form>";
        }
    }


    public function grabaModificacion() {
        $retorno=false;
        $operacion=new OperacionBSN($this->operacion);
        $retUPre=$operacion->actualizaDB();
        if ($retUPre) {
            echo "Se proceso la grabacion en forma correcta<br>";
            $retorno=true;
        } else {
            echo "Fallo la grabación de los datos<br>";
        }
        return $retorno;
    }

    public function grabaOperacion() {
        $retorno=false;
        $operacion=new OperacionBSN($this->operacion);
        //Control de Duplicidad de los datos cargados, si no hace falta comentar las lineas del if
        //		$existente=$operacion->controlDuplicado($this->operacion->getOperacion());
        //		if($existente){
        //			echo "Ya existe un operacion con ese Titulo";
        //		} else {
        $retIPre=$operacion->insertaDB();
        //			die();
        if ($retIPre) {
            echo "Se proceso la grabacion en forma correcta<br>";
            $retorno=true;
        } else {
            echo "Fallo la grabación de los datos<br>";
        }
        //		} // Fin control de Duplicados
        return $retorno;
    }



} // fin clase


?>