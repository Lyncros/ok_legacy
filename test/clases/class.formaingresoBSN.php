<?php

include_once("generic_class/class.parametricosBSN.php");

class FormaingresoBSN extends ParametricosBSN {
	
	private static $_instance;

    public function __construct() {
            parent::__construct('XML', 'formaIngreso.xml');
        }

    static public function getInstance() {
        if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
    

    public function selectorFormaIngreso($valor = 0, $campo = 'id_fingreso', $clase = 'datoCampo', $div = 'divComboPromocion') {
        print "        <div class=\"col3\">\n";
        print "          <div class=\"nombreCampo\">C&oacute;mo se entera de O'Keefe</div>\n";
        print "          <div>\n";
        $promo = 0;
        if ($valor != '' || $valor != 0) {
            $promo = $valor;
}
        $this->comboParametros($valor, 0, $campo, $clase, 'comboPromociones(\'' . $campo . '\',' . $promo . ',\'' . $div . '\')');
        print "          </div>\n";
        print "       </div>\n";
        print "        <div class=\"col3\">\n";
        print "             <div id='divComboPromocion' name='divComboPromocion'></div>\n";
        print "             <div id='divPromoCli' name='divPromoCli' style='display:none'>\n";
        $titulo = 'Indique el Cliente';
        print "             <div class=\"nombreCampo\">$titulo</div>";
        print "             <div><input class=\"datoCampo\" type='text' size='50' name='buscaPromoCli' id='buscaPromoCli' />\n";
        print "                 <input type='hidden' size='50' name='aux_promo' id='aux_promo' />\n";
        print "             </div>\n";
        print "                 * Debe ingresar al menos 3 caracteres.";
        print "             </div>\n";
        print "         </div>\n";
//        print "        <div id=\"clearfix\"></div>\n";
//        print "      </div>\n";
    }

}

?>
