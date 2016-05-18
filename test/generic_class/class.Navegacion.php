<?php

require_once 'generic_class/class.Template.php';
include_once("generic_class/class.cargaConfiguracion.php");

class Navegacion {

    private $path_temp;
    private $definicion_html_head = 'head_def.html';
    private $definicion_page_header = 'header_page.html';
    private $definicion_app_menu = 'app_menu.html';
    private $modulos;

//    private $definicion_blocks = array('l' => 'div_izquierdo', 'c' => 'div_derecho');

    public function __construct() {
        $conf= CargaConfiguracion::getInstance('');
        clNavegacion::cargaParametros();
    }

    protected function cargaParametros() {
        $conf= CargaConfiguracion::getInstance('');
        $this->path_temp = $params->leeParametro('path_templates');
    }

    /**
     * Genera las paginas para la navegacion de los usuarios
     * @param int $opcionMenu: especifica el elemento del menu presionado
     * @param char $side: determina que lado de la pantalla se va a regenrar  l= Menu izq.  c= Vista de componentes o area de trabajo
     */
    public function navegar($opcionMenu, $side) {
        if ($side == 'l' || $side == 'c') {
            switch ($opcionMenu) {
                case 0:
                    $left_block = "";
                    $cont_block = "";
                    break;
                case 1:
                    $left_block = "filtro_fiscalizaciones.html";
                    $cont_block = "carga_datos.html";
                    break;
                case 2:
                    $left_block = "filtro_aprobaciones.html";
                    $cont_block = "aprobacion.html";
                    break;
                case 3:
                    $left_block = "filtro_cargos_pendientes.html";
                    $cont_block = "generar_cargos.html";
                    break;
                case 4:
                    $left_block = "filtro_planillas.html";
                    $cont_block = "aprobacion_cargos.html";
                    break;
                case 5:
                    $left_block = "";
                    $cont_block = "";
                    break;
                case 6:
                    $left_block = "filtro_fiscalizaciones.html";
                    $cont_block = "carga_datos.html";
                    break;
                case 7:
                    $left_block = "filtro_planillas.html";
                    $cont_block = "seg_planillas.html";
                    break;
                case 8:
                    $left_block = "filtro_inspectores.html";
                    $cont_block = "seg_inspectores.html";
                    break;
                case 9:
                    $left_block = "";
                    $cont_block = "";
                    break;
                case 10:
                    $left_block = "filtro_plan.html";
                    $cont_block = "nuevo_plan.html";
                    break;
                case 11:
                    $left_block = "filtro_operativo.html";
                    $cont_block = "nuevo_operativo.html";
                    break;
                case 12:
                    $left_block = "filtro_planillas_cargos.html";
                    $cont_block = "vista_planillas_cargos.html";
                    break;                    
            }
            if ($side == 'l') {
                $page = $left_block;
            } else {
                $page = $cont_block;
            }
//            $this->renderHtml($page, $this->definicion_blocks[$side]);
            $this->renderHtml($page, $opcionMenu, $side);
        }
    }

    protected function renderHtml($pagina, $opcion, $side) {
        if ($pagina == '') {
            $render = new Template($this->path_temp . "/index.html");
            $render->parsearFile('definicion_html_head', $this->path_temp . "/" . $this->definicion_html_head);
            $render->parsearFile('definicion_page_header', $this->path_temp . "/" . $this->definicion_page_header);

            $render->parsearFile('definicion_app_menu', $this->path_temp . "/" . $this->definicion_app_menu);
            $render->parsear("menu", $this->parseoMenu());
        } else {
            $render = new Template($this->path_temp . "/" . $pagina);
            $render = $this->renderComponentes($render, $opcion, $side);
        }
        $render->imprimir();
    }

    protected function renderComponentes($objRender, $opcion, $side) {
        switch ($opcion) {
            case 1:
                if ($side == 'l') {
                    $objRender=$this->combosFiltroPlanOperativo($objRender);
                    break;
                } else {
                    $fiscal = new clFiscal();
                    $objRender = $fiscal->renderCargaInicial($objRender);
                }
                break;
            case 3:
                if ($side == 'l') {
                    $cargos = new clFiscal();
                    $objRender->parsear('cargos_aprobados', $cargos->listaCargosAprobados(array(0 => array('cuit' => '', 'razon_social' => '', 'operativo_id' => 0, 'plan_id' => 0))));
                } else{
                  $inspectores=new clUsuario();
                  $objRender->parsear('lista_inspectores',$inspectores->listaInspectores(array()));

                } 
                break;
            case 11:
                $plan = new clPlan();
                $objRender->parsear('COMBO_PLAN', $plan->renderCombo('PLAN_ID', 0));
                break;

            case 2:
                if ($side == 'l') {
                    $objRender=$this->combosFiltroPlanOperativo($objRender);
                }
                break;
        }
        return $objRender;
    }

    public function setModulos($modulos) {
        $this->modulos = $modulos;
    }

    private function parseoMenu() {
        $i = 0;
        $vec_modulo = $this->modulos;
        $top = sizeof($vec_modulo);
        $menu .= "";
        $nombre_ant = '';
        $item = '';
        while ($i < $top) {
            if ($vec_modulo[$i]['MODULO_ID'] == $vec_modulo[$i]['padre']) {
                if ($nombre_ant != '') {
                    if ($item != '')
                        $menu .= "<li><a href=\"#\">" . $nombre_ant . "</a><ul>" . $item . "</ul></li>";
                    $item = '';
                }
                $nombre_ant = $vec_modulo[$i]['NOMBRE'];
            }else {
                $item .= "<li><a href='javascript: navegar(" . $vec_modulo[$i]['DESC_PRG'] . ");'>" . $vec_modulo[$i]['NOMBRE'] . "</a></li>";
            }
            $i++;
        }
        return $menu;
    }

    private function combosFiltroPlanOperativo($objRender) {
        $plan = new clPlan();
        $oper = new clOperativo();
        $objRender->parsear('COMBO_PLAN', $plan->renderCombo('PLAN_ID_FILTRO', 0));
        $objRender->parsear('COMBO_OPERATIVO', $oper->renderCombo('OPERATIVO_ID_FILTRO', 0));
        return $objRender;
    }

}

?>
