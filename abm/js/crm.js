function carga() {
   jQuery("#buscaCli").autocomplete({
        source: "autocompletarClientes.php",
        minLength: 2,
        select: function(event, ui) {
            alert("ENTRA");
//            defineActividad(ui.item.id, ui.item.value);
        }
    });

}



function addTelefono() {
    var opciones = new Array();
    opciones[0] = new Array(1, 'Particular');
    opciones[1] = new Array(2, 'Laboral');
    opciones[2] = new Array(3, 'Contacto');
    var divTel = document.getElementById('div_telefono');
    var elemento = document.createElement('div');
    elemento.className = "celda_min div_select";
    elemento.appendChild(creaSelect("tipoTel", opciones, 2, 0));
    divTel.appendChild(elemento);

    // Agrego Div de Campos
    var divCampos = document.createElement('div');
    divCampos.appendChild(creaCampo("CODPAIS", "input", "text", '054', 0, 3));
    divCampos.appendChild(creaCampo("CODAREA", "input", "text", '011', 0, 3));
    divCampos.appendChild(creaCampo("NUMERO", "input", "text", '', 0, 15));
    divTel.appendChild(divCampos);

    // Agrego div clear all
    creaDivClear(divTel);
}

function creaCampo(name, campo, tipo, valor, estado, size) {
    var element = document.createElement(campo);
    element.type = tipo;
    element.name = name;
    element.id = name;
    element.value = valor;
    element.size = size;
    if (estado !== 0) {
        element.readOnly = true;
    }
    return element;
}

function creaSelect(name, opciones, valor, estado) {
    var element = document.createElement("select");
    element.name = name;
    element.id = name;
    for (x = 0; x < opciones.length; x++) {
        var option = document.createElement("option");
        option.text = opciones[x][1];
        option.value = opciones[x][0];
        if (valor === opciones[x][0]) {
            option.selected = true;
        }
        element.add(option, element.options[null]);
    }

//    element.value=valor;
    if (estado !== 0) {
        element.disabled = true;
    }
    return element;
}

function creaDivClear(destino){
    var elemento = document.createElement('br');
//    elemento.className = "corte";
    destino.appendChild(elemento);
}