function navegar(opc) {
    var izq = '';
    var der = '';
    switch (opc) {
        case 3:
            izq = 'div_izquierdo_apr';
            der = 'div_derecho_apr';
            break;
        default:
            izq = 'div_izquierdo';
            der = 'div_derecho';
            break;
    }
    /*cargaLeftMenu(opc, izq);
     if(opc !=2){
     cargaContenido(opc, der);
     }*/
    fiscal.buscarPagina();
    // si existe el componente lo cargo


// cargaLeftMenu(opc,'div_izquierdo');
// cargaContenido(opc, 'div_derecho');


}

function redimensionarDivs(opc) {
    switch (opc) {
        case 3:
            addClass('open', document.getElementById('div_izquierdo_apr'));
            removeClass('close', document.getElementById('div_izquierdo_apr'));

            addClass('close', document.getElementById('div_izquierdo'));
            removeClass('open', document.getElementById('div_izquierdo'));

            addClass('open', document.getElementById('div_derecho_apr'));
            removeClass('close', document.getElementById('div_derecho_apr'));

            addClass('close', document.getElementById('div_derecho'));
            removeClass('open', document.getElementById('div_derecho'));

            break;
        default:
            removeClass('open', document.getElementById('div_izquierdo_apr'));
            addClass('close', document.getElementById('div_izquierdo_apr'));

            addClass('open', document.getElementById('div_izquierdo'));
            removeClass('close', document.getElementById('div_izquierdo'));

            removeClass('open', document.getElementById('div_derecho_apr'));
            addClass('close', document.getElementById('div_derecho_apr'));

            addClass('open', document.getElementById('div_derecho'));
            removeClass('close', document.getElementById('div_derecho'));

            break;
    }

}

/*
 function addClass(classname, element) {
 var cn = element.className;
 // test for existance
 if (cn.indexOf(classname) != -1) {
 return;
 }
 // add a space if the element already has class
 if (cn != '') {
 classname = ' ' + classname;
 }
 element.className = cn + classname;
 }
 
 function removeClass(classname, element) {
 var cn = element.className;
 var rxp = new RegExp("\\s?\\b" + classname + "\\b", "g");
 cn = cn.replace(rxp, '');
 element.className = cn;
 }
 */

function addClass(classname, element) {
    var elemento;
    var pasa = 1;
    if (typeof element === 'object') {
        elemento = element;
        pasa = 0;
    } else {
        if (document.getElementById(element)) {
            elemento = document.getElementById(element);
            pasa = 0;
        }
    }
    if (pasa === 0) {
        var cn = elemento.className;
        // test for existance
        if (cn.indexOf(classname) != -1) {
            return;
        }
        // add a space if the element already has class
        if (cn != '') {
            classname = ' ' + classname;
        }
        elemento.className = cn + classname;
    }
}

function removeClass(classname, element) {
    var elemento;
    var pasa = 1;
    if (typeof element === 'object') {
        elemento = element;
        pasa = 0;
    } else {
        if (document.getElementById(element)) {
            elemento = document.getElementById(element);
            pasa = 0;
        }
    }
    if (pasa === 0) {
        var cn = elemento.className;
        var rxp = new RegExp("\\s?\\b" + classname + "\\b", "g");
        cn = cn.replace(rxp, '');
        elemento.className = cn;
    }
}

function limpiarTabla(tableID) {
    try {
        var table = document.getElementById(tableID);
        var rowCount = table.tBodies[0].rows.length;//table.rows.length;
        for (var i = rowCount; i > 0; i--) {
            table.deleteRow(i);
        }
    } catch (e) {
        alert(e);
    }
}

/*
 function deleteRow(tableID) {
 try {
 var table = document.getElementById(tableID);
 var rowCount = table.rows.length;
 for (var i = 0; i < rowCount; i++) {
 table.deleteRow(i);
 rowCount--;
 i--;
 }
 } catch (e) {
 alert(e);
 }
 }
 */

function deleteRow(tableID, row) {
    try {
        var table = document.getElementById(tableID);
        var rowCount = table.tBodies[0].rows.length;//table.rows.length;
        if (row < rowCount) {
            table.deleteRow(i);
        }
    } catch (e) {
        alert(e);
    }
}


// Agrega filas a una tabla
// Columnas indica la cantidad de columnas de la tabla
// Datos contien los elementos a agregar en la tabla, ordenados posicionalmente
function addRow(tableID, columnas, datos) {
    var table = document.getElementById(tableID);
    var rowCount = table.tBodies[0].rows.length;
    var row = table.tBodies[0].insertRow(rowCount);
    var idTR = tableID + "_tr_" + rowCount;
    var idTD = tableID + "_td_" + rowCount;
    for (i = 0; i < columnas; i++) {
        var cellX = row.insertCell(i);
        cellX.scope = "row";
        cellX.innerHTML = datos[i];
        cellX.id = idTD + '_' + i;
    }
    row.id = idTR;

}

/*
 Agrega una fila a una tabla segun el contenido de un array multidimensional que contiene
 en cada fila un componente de la fila y en cada columna las especificaciones del mismo, basado en 
 el siguiente esquema:
 Columna  -> Contenido
 0 : id de la columna
 1 : tipo de contenido (t-texto,c-checkbox,l-label,i-i)
 2 : contenido del mismo
 3 : accesible ( 0 - readonly o dissabled )
 4 : clase
 5 : javascript ( tipo {evento,javascript} )
 */
function addRowComponentesNUEVA(tableID, columnas, datos) {
    var table = document.getElementById(tableID);
    var rowCount = table.tBodies[0].rows.length;
    var row = table.tBodies[0].insertRow(rowCount);
    for (i = 0; i < columnas; i++) {
        var cellX = row.insertCell(i);
        cellX.scope = "row";
        tipo = '';
        elemento = '';
        switch (datos[i][1]) {
            case 't':
                elemento = 'input';
                tipo = 'text';
                break;
            case 'c':
                elemento = 'input';
                tipo = 'checkbox';
                break;
            case 'l':
                elemento = 'label';
                tipo = '';
                break;
            case 'i':
                elemento = 'i';
                tipo = '';
                break;

        }
        var element1 = document.createElement(elemento);
        id = datos[i][0] + "_" + rowCount;
        element1.name = datos[i][0] + "_" + rowCount;
        element1.id = datos[i][0] + "_" + rowCount;
        if (tipo === 'checkbox') {
            if (datos[i][2] === 1) {
                element1.type = tipo;
                element1.checked = true;
            }
        } else {
            switch (datos[i][1]) {
                case 'l':
                case 'i':
                    element1.innerHTML = datos[i][2];
                    break;
                default:
                    element1.value = datos[i][2];
                    element1.type = tipo;
                    break;
            }
        }
        if (datos[i][3] === 0) {
            element1.readOnly = true;
        }
        if (datos[i][4] !== '') {
            element1.className = datos[i][4];
        }

        cellX.appendChild(element1);

        if (datos[i][5].evento === 'click') {
            $(elemento + "#" + id).on("click", {funcion: datos[i][5].funcion, dato: id}, operaElemento);
        }
    }
}

function operaElemento(event) {
    funcion = event.data.funcion;
    parametro = event.data.dato;
    window[funcion](parametro);
}

function addRowComponentes(tableID, columnas, datos) {
    var table = document.getElementById(tableID);
    var rowCount = table.tBodies[0].rows.length;
    var row = table.tBodies[0].insertRow(rowCount);
    for (i = 0; i < columnas; i++) {
        var cellX = row.insertCell(i);
        cellX.scope = "row";
        tipo = '';
        elemento = '';
        switch (datos[i][1]) {
            case 't':
                elemento = 'input';
                tipo = 'text';
                break;
            case 'c':
                elemento = 'input';
                tipo = 'checkbox';
                break;
            case 'l':
                elemento = 'label';
                tipo = '';
                break;
        }
        var element1 = document.createElement(elemento);
        element1.type = tipo;
        element1.name = datos[i][0] + "_" + rowCount;
        element1.id = datos[i][0] + "_" + rowCount;
        if (tipo === 'checkbox') {
            if (datos[i][2] === 1) {
                element1.checked = true;
            }
        } else {
            element1.value = datos[i][2];
        }
        if (datos[i][3] === 0) {
            element1.readOnly = true;
        }

        cellX.appendChild(element1);


    }
}


// Borra las opctiones de un combo
function limpiarCombo(comboId) {
    var selElement = document.getElementById(comboId);
    selElement.options.length = 0;
    addOption2Combo(comboId, Array(0, 'Seleccione una opcion'));
}

// Carga nuevas opciones a un combo
// ArrayDatos contiene los datos a agregar al combo, el primer elemento contiene
// el ID el segundo el TEXTO
function addOption2Combo(comboId, arrayDatos) {
    var selElement = document.getElementById(comboId);
    selElement.options[selElement.options.length] = new Option(arrayDatos[1], arrayDatos[0]);
}

function deshabilitarOptionById(comboId, id)
{
    var selElement = document.getElementById(comboId);
    for (i = (selElement.length - 1); i >= 0; i--)
    {
        des = selElement.options[i];
        if (des.value === id.toString()) {
            des.disabled = true;
        }
    }
}

function habilitarOptionById(comboId, id)
{
    var selElement = document.getElementById(comboId);
    for (i = (selElement.length - 1); i >= 0; i--)
    {
        des = selElement.options[i];
        if (des.value === id.toString()) {
            des.disabled = false;
        }
    }
}

function rehabilitarAllOptionsCombo(comboId) {
    var selElement = document.getElementById(comboId);
    for (i = (selElement.length - 1); i >= 0; i--)
    {
        des = selElement.options[i];
        des.disabled = false;
    }
}

function deshabilitarAllOptionsCombo(comboId) {
    var selElement = document.getElementById(comboId);
    for (i = (selElement.length - 1); i >= 0; i--)
    {
        des = selElement.options[i];
        des.disabled = true;
    }
}

// Agrega eventos a un elemento del HTML
function addEvent(elementId, evnt, funct) {
    element = document.getElementById(elementId);
    if (element) {
        if (element.attachEvent) {
            return element.attachEvent('on' + evnt, funct);
        } else {
            return element.addEventListener(evnt, funct, false);
        }
    }
}

function trim(myString)
{
    return myString.replace(/^\s+/g, '').replace(/\s+$/g, '');
}


function denotaErrores(arrayError) {
    var msg = '';
    for (i = 0; i < arrayError.length; i++)
    {
        addClass('error', arrayError[i][0]);
        msg += (arrayError[i][1] + '\n');
    }
    if (document.getElementById(arrayError[0][0])) {
        document.getElementById(arrayError[0][0]).focus();
    }
    ventanaMessages(msg);
//    alert(msg);
}

function habilitarBoton(boton, sino) {
    if (sino) {
        document.getElementById(boton).disabled = false;
    } else {
        document.getElementById(boton).disabled = true;
    }
}

function validarEmail(email) {
    var valido = true;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!expr.test(email)) {
        valido = false;
    }
    return valido;
}

function validarNumerico(numero) {
    var valido = true;
    expr = /^([\-|\+])?([0-9])+(\.(0-9)+)?$/;
    if (!expr.test(numero)) {
        valido = false;
    }
    return valido;
}

function prevenirString(texto) {
    var valido = texto;
    expr = /[\'\")(;|`,<>]/;
    while (expr.test(valido)) {
        valido = valido.replace(expr, '');
    }
    return valido;
}

function validaControldeEdicion(keyCode) {
    var retorno = false;
    if (keyCode == 8 || keyCode == 9 || keyCode == 35 || keyCode == 36 || keyCode == 37 || keyCode == 39 || keyCode == 46) {
        retorno = true;
    }
    return retorno;
}

function validarInputNumerico(numero, tipo) {
// onkeypress="validate(event, 'i')"
    var theEvent = numero || window.event;
    var keyCode = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(keyCode);
    var regex = 0;
    if (tipo === "i") {
        regex = /[0-9]/;
    } else {
        regex = /[0-9]|\./;
    }
    if (!regex.test(key) && !validaControldeEdicion(keyCode)) {//keyCode != 8) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}


function validarInputString(texto) {
// onkeypress="validate(event, 'i')"
    var theEvent = texto || window.event;
    var keyCode = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(keyCode);
    var regex = 0;
    regex = /[^\'\")(;|`,<>]/;
    if (!regex.test(key) && keyCode !== 8) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

function ventanaMessages(msg) {
    document.getElementById("basic-modal-content").innerHTML = msg;
    $("#basic-modal-content").modal();
}

function armaMsgError(arrMsg) {
    var msgError = '';
    for (x = 0; x < arrMsg.length; x++) {
        msgError += (arrMsg[x] + '\n');
    }
    return msgError;
}

function marcaErroresEnCampos(arrCampos) {
    document.getElementById(arrCampos[0]).focus();
    for (x = 0; x < arrCampos.length; x++) {
        marcaCamposError(arrCampos[x]);
    }
}

function marcaCamposError(campo) {
    addClass('error', campo);
}

function desmarcaCamposError(campo) {
    removeClass('error', campo);
}


$.fn.serializeObject = function(returnEmpty, dateFormat) {
    if (!dateFormat) {
        dateFormat = 'dd/MM/yyyy';
    }
    var o = {};
    $(this)
            .find('input[type="hidden"], input[type="text"], input[type="password"], input[type="date"], input[type="number"], input[type="checkbox"]:checked, input[type="radio"]:checked, select,textarea')
            .each(function() {
                if ($(this).attr('type') == 'hidden') { // if checkbox is checked do not
                    // take the hidden field
                    var $parent = $(this).parent();
                    var $chb = $parent.find('input[type="checkbox"][name="' + this.name.replace(/\[/g, '\[').replace(/\]/g, '\]') + '"]');
                    if ($chb != null) {
                        if ($chb.prop('checked'))
                            return;
                    }
                }
                if (this.name === null || this.name === undefined || this.name === '')
                    return;
                var elemValue = null;
                if ($(this).is('select')) {
                    option = $(this).find('option:selected');
                    if ($(this).data().tipo) {
                        elemValue = {
                            codigo: option.val(),
                            descripcion: option.text()
                        };
                    } else {
                        elemValue = option.val();
                    }
                } else {
                    elemValue = this.value;
                }

                if (elemValue === '' || elemValue === undefined || elemValue === null) {
                    if (!returnEmpty)
                        return;
                }

                if (elemValue == 'true') {
                    elemValue = true;
                } else if (elemValue == 'false') {
                    elemValue = false;
                }

                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(elemValue || '');
                } else {
                    o[this.name] = elemValue || '';
                }
            });
    return o;
}

$.fn.serializeJson = function() {
    return JSON.stringify($(this).serializeObject());
}

// Retorna la posicion de un elemento dentro de la ventana
function getPosition(elemento) {
    var xPosition = 0;
    var yPosition = 0;

    if (document.getElementById(elemento)) {
        var element = document.getElementById(elemento);
        xPosition = (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition = (element.offsetTop - element.scrollTop + element.clientTop);
//        element = element.offsetParent;
    }
    return {x: xPosition, y: yPosition};
}

function parserJS(str, patron, reemplazo) {
    var strPat = "{" + patron + "}";
    var res=str;
    while (res.indexOf(strPat) !== -1) {
        res = res.replace(strPat, reemplazo);
    }
    return res;
}

function armaConjuntoRegistros(strHtml, contenido) {
    var retStr = '';
    var str = strHtml;
    if (contenido.length > 0) {
        for (i in contenido) {
            retPart = str;
            for (j in contenido[i]) {
                retPart = parserJS(retPart, j.toUpperCase(), contenido[i][j]);
            }
            retStr += retPart;
        }
    } else {
        retStr = '';
    }
    return retStr;
}

function fillFormulario(strHtml, contenido) {
    var retPart = strHtml;
    for (i in contenido) {
        retPart = parserJS(retPart, i.toUpperCase(), contenido[i]);
    }
    return retPart;
}

function fillTabla(tabla, strHtml) {
    $('#' + tabla + ' tbody').append(strHtml)
}
