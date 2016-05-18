/*
 function cargarRequest(pagina_requerida, id_contenedor, tipo) {
 if (pagina_requerida.readyState == 4 && (pagina_requerida.status == 200 || window.location.href.indexOf("http") == -1)) {
 if (id_contenedor != "" && tipo == "c") {
 document.getElementById(id_contenedor).innerHTML = pagina_requerida.responseText;
 regeneraComponentes(id_contenedor);
 } else {
 recargaDatos(pagina_requerida.responseText, id_contenedor);
 }
 } else {
 return '';//		document.getElementById(id_contenedor).innerHTML="Fallo la carga."
 }
 }
 */
function cargarRequest(pagina_requerida, id_contenedor, tipo) {
    if (pagina_requerida.readyState === 4) {
        if (pagina_requerida.status === 200 || window.location.href.indexOf("http") === -1) {
            if (id_contenedor !== "" && tipo === "c") {
                document.getElementById(id_contenedor).innerHTML = pagina_requerida.responseText;
                regeneraComponentes(id_contenedor);
            } else {
                recargaDatos(pagina_requerida.responseText, id_contenedor);
            }
        } else {
            retorno = jQuery.parseJSON(pagina_requerida.responseText);
            /*            if(retorno.message=="TOKEN EXPIRO" || retorno.message=="SESSION NO INICIADA"){
             alert("La sesion a expirado.");
             window.location.href = "http://www.agip.gob.ar";
             }else{
             alert("Se produjo un error al intentar obtener un dato externo. Por favor reintente mÃ¡s tarde o comuniquese.");
             }
             */
        }
    } else {
        return '';
    }
}


/*
 function sendRequest(request, url, data, nombre, tipo) {
 request.onreadystatechange = function() { // funci????????????????n de respuesta
 if (nombre == "" || tipo == "v") {
 return cargarRequest(request, nombre, tipo);
 } else {
 cargarRequest(request, nombre, tipo);
 }
 }
 request.open('POST', url, true); // asignamos los m????????????????todos open y send
 request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
 request.send(data);
 }
 */

function sendRequest(request, url, data, nombre, tipo, metodo, contenido, sincronico) {
    request.onreadystatechange = function() {
        if (nombre === "" || tipo === "v") {
            return cargarRequest(request, nombre, tipo);
        } else {
            cargarRequest(request, nombre, tipo);
        }
    };
    request.open(metodo, url, false);
    if (contenido == 'JSON') {
        contentType = 'application/json;charset=UTF-8';
    } else {
        contentType = 'application/x-www-form-urlencoded';
    }
    request.setRequestHeader("Content-type", contentType);
    request.send(data);
}

function sendRequestAjax(url, data, eventOk, eventError) {

    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        data: 'r=' + Math.random() + '&' + data,
        success: function(data) {
            if (data.CodRet == 0) {
                //todo ok...
                eventOk(data);
            } else if (data.CodRet == 999) {
                //fin de session
                //eventError();
            } else if (data.CodRet > 0 && data.CodRet < 999) {
                //error
                eventError(data);
            } else {

                alert(data);
            }
        },
        error: function(data) {
            alert(data.responseText);
        }
    });

}


function sendRequestAjaxFile(url, data, eventOk, eventError) {

    $.ajax({
        url: url,
        data: data,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        //data: data,
        success: function(data) {

            if (data.CodRet == 0) {
                //todo ok...
                eventOk(data);
            } else if (data.CodRet == 999) {
                //fin de session
                //eventError();
            } else if (data.CodRet > 0 && data.CodRet < 999) {
                //error
                eventError(data);
            } else {
                alert(data);
            }
        },
        error: function(data) {
            alert(data.responseText);
        }
    });

}


function createRequest() {
    var request = false
    if (window.XMLHttpRequest) {// Si es Mozilla, Safari etc
        request = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // pero si es IE
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) { // en caso que sea una versi????????????????n antigua
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
            }
        }
    }
    if (request == null) {
        alert("Se produjo un error en la pagina. Por favor recargue la misma.");
    } else {
        return request;
    }
}

reqTipoCont = createRequest();
reqTipoAsig = createRequest();
reqTipoCat = createRequest();
reqTipoDoc = createRequest();
reqEstCiv = createRequest();
reqTipoTel = createRequest();
reqTipoMed = createRequest();
reqTipoDom = createRequest();
reqTipoPar = createRequest();
reqTipoRel = createRequest();
reqRelUsr = createRequest();
reqCapCom = createRequest();
reqPromo = createRequest();
reqFIng = createRequest();
reqZona = createRequest();
reqUEv = createRequest();
reqUCli = createRequest();
reqCli = createRequest();
reqTels = createRequest();
reqDoms = createRequest();
reqMeds = createRequest();
reqRels = createRequest();
reqIngs = createRequest();
reqAstA = createRequest();
reqAstC = createRequest();
reqAstPA = createRequest();
reqAstPC = createRequest();

reqGral= createRequest();

opc = 0;                          // variable global para obtener la opcion de menu presionada


function regeneraComponentes(div_contenedor) {

    /*
     jQuery("#accordion_1").accordion();
     jQuery("#accordion_2").accordion();
     jQuery("#accordion_DDJJs").accordion();     // Usada en la pestaña de DDJJ's
     jQuery("#button").button();
     jQuery("#tabs").tabs();
     
     regenerarPicker();
     redimensionarDivs(opc);
     //    habilitoComponentesModulo(recursos);
     
     */
    regenerarPicker();
    regeneraMenu();
}

function regeneraMenu() {
    $(" #nav ul ").css({display: "none"}); // Opera Fix
    $(" #nav li").hover(function() {
        $(this).find('ul:first').css({visibility: "visible", display: "none"}).show(400);
    }, function() {
        $(this).find('ul:first').css({visibility: "hidden"});
    });

}

function regenerarPicker() {
    //$.datepicker();

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<',
        nextText: '>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $(".tb-date").datepicker({
        //     showOn: 'both',
        //      buttonImage: 'images/calendar.gif',
        //      buttonImageOnly: false,
        changeYear: true,
        numberOfMonths: 1,
        onSelect: function(textoFecha, objDatepicker) {
            //$("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
        }
    });

    $.datepicker.setDefaults($.datepicker.regional['es']);

    $(".tb-date").datepicker("option", "minDate", new Date(1999, 6, 1));
    $(".tb-date").datepicker("option", "maxDate", new Date(2030, 6, 1));

}

function recargaDatos(contenido, dato) {
    switch (dato) {
        case "tt":
            arrTel = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "tm":
            arrMed = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "td":
            arrDom = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "tp":
            arrPar = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "tr":
            arrRel = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "tco":
            arrCont = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('TIPOCONT')!==null){
                populaCombo('TIPOCONT', arrCont, 0, 1);
            }
            break;
        case "tca":
            arrCat = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('CATEGORIA')!==null){
                populaCombo('CATEGORIA', arrCat, 0, 1);
            }
            break;
        case "ta":
            arrAsig = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('ASIGNACION')!==null){
                populaCombo('ASIGNACION', arrAsig, 0, 1);
            }
            break;
        case "tdoc":
            arrDoc = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('TIPO_DOC')!==null){
                populaCombo('TIPO_DOC', arrDoc, 0, 1);
            }
            break;
        case "tec":
            arrECiv = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('ID_ESTCIV')!==null){
                populaCombo('ID_ESTCIV', arrECiv, 0, 1);
            }
            break;
        case "cc":
            arrCComp = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('CAPCOMPRA')!==null){
                populaCombo('CAPCOMPRA', arrCComp, 0, 1);
            }
            break;
        case "fi":
            arrFIng = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('ID_FINGRESO')!==null){
                populaCombo('ID_FINGRESO', arrFIng, 0, 1);
            }
            //            populaCombo('ID_FINGRESO2', arrFIng, 0, 1);
            break;
        case "fc":
            arrFCont = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('ID_FCONTACTO')!==null){
                populaCombo('ID_FCONTACTO', arrFCont, 0, 1);
            }
            //            populaCombo('ID_FINGRESO2', arrFIng, 0, 1);
            break;
        case "zo":
            arrZona = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if(document.getElementById('ID_UBICAPRINCIPAL')!==null){
                populaCombo('ID_UBICAPRINCIPAL', arrZona, 0, 0);
            }
            break;
        case "clim":
            arrCli = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            break;
        case "cliv":
            arrCli = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            recargaDatosVista(arrCli);
            recargaDatosReferidos(arrCli);
            break;
        case "clie":
            arrCli = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            precargaData(0);
            recargaDatosForm('clienteCRM', arrCli);
            break;
        case 'du':
            arrRUsr = jQuery.parseJSON(contenido);
            if(document.getElementById('EJECUTIVO')!==null){
                populaCombo('EJECUTIVO', arrRUsr, 0, 1);    
            }
            break;
        case "ctel":
            arrDatosTel = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            cargaDatosTelefonos();
            break;
        case "cdom":
            arrDatosDom = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            cargaDatosDomicilios();
            break;
        case "cmed":
            arrDatosMed = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            cargaDatosMedios();
            break;
        case "crel":
            arrDatosRel = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            cargaDatosRelaciones();
            break;
        case "cing":
            arrDatosIng = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            cargaIngreso();
            break;
        case "gcli":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet === '0') {
                alert(ret.msg);
                document.getElementById('buscaProp').focus;
                document.getElementById('id_cli').value = ret.idCli;
            } else {
                alert(ret.msg);
                document.getElementById('NOMBRE').focus;
            }
            break;
        case "actcrm":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            alert(ret.msg);
            break;
        case "gcrm":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet == '0') {
//                alert(ret.msg);
//                window.location = "lista_propiedad.php?b=" + ret.idCrm + "&i=";
                abreVistaListaProps(ret.idCrm);
//                cargaListaPropiedades();
            } else if (ret.codRet === '-1') {
                alert(ret.msg);
                document.getElementById('buscaProp').focus;
            } else if (ret.codRet === '2'){
                abreVistaListaPropsSinCrm(ret.params);
            }else{
                alert(ret.msg);
                window.location = "logoff.php";
            }
            break;
        case "grela":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet == '0') {
                alert(ret.msg);
                params = 'cli=' + ret.cli + '&modo=v';
                $.modal.close();
                cargaContactosCliente(params);
                cargaInmueblesCliente(params);
            } else {
                alert(ret.msg);
                document.getElementById('buscaProp').focus;
            }
            break;
        case "dbv":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet == '0') {
                seteaArrayCrmParams(ret.msg.params);
                seteaArrayCrmAdjuntos(ret.msg.adjuntos);
                seteaFiltro();
                seteaAdjuntos();
            } else {
                alert(ret.msg);
            }
            break;
        case "grabado":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            alert(ret.msg);
            break;
        case "email":
            ret=jQuery.parseJSON(contenido);
            if(ret.codRet==0){
                regeneraIconosEstadoProp();
            }
            alert(ret.msg);
            break;
        case "ncli":
            ret=jQuery.parseJSON(contenido);
            if(ret.codRet==0 && ret.idCrm!=0){
                $('#buscador #id_cli').val(ret.idCli);
                $('#buscador #id_crm').val(ret.idCrm);
                $.modal.close();
//                cargaCliente(data, 'm');
                cargaInfoCliente('m');
//                cargaTelsCliente(data, modo);
//                cargaMedsCliente(data, modo);

                cargaClienteAcotado();
//                  abreVistaListaProps(ret.idCrm);
            }else{
                alert(ret.msg);
            }
            break;
        case "gecrm":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet == '0') {
                $.modal.close();
                cargaDataPropiedad(asuntoAct,propSel);
            } else {
                alert(ret.msg);
            }
            break;
        case "gclose":
            ret = jQuery.parseJSON(contenido);//JSON.parse(contenido);
            if (ret.codRet == '0') {
                $.modal.close();
                var dataCli='cli=' + arrCli.id_cli + '&modo=v';
                cargaAsuntosAbiertos(dataCli);
                cargaAsuntosCerrados(dataCli);
//                cargaEventosAsuntosAbiertos(dataCli);

                cargaDataAsuntos(asuntoAct);
                cargaDataPropiedad(asuntoAct,propSel);
            } else {
                alert(ret.msg);
            }
            break;
    }
}
//abre las vistas de datos de cliente , filtro y propiedades
function abreVistaListaProps(idCrm) {
    url = 'ajaxVistaListaProps.php';
    var data = '';
    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);

    cargaClienteAcotado();
    cargaContenedorBarraHerramientas();
    document.getElementById('cuantos').innerHTML=0;
    cargaBuscadorVertical(idCrm);
    cargaContenedorListaProps();
    cargaListaPropiedades(idCrm);
    popupLoadingOff();
}


//  REEMPlAZAR LAS FUNCIONES POR OTRAS QUE RECIBAN LOS PARAMETROS DEL FILTRO.
function abreVistaListaPropsSinCrm(paramFiltro) {
    url = 'ajaxVistaListaProps.php';
    var data = '';
    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);

    cargaClienteAcotado();
    cargaContenedorBarraHerramientas();
    cargaBuscadorVertical(0);  
    
    seteaArrayCrmParams(paramFiltro);
    seteaFiltro();

    cargaContenedorListaProps();
    cargaListaPropiedades();
    popupLoadingOff();
}


function cargaClienteAcotado() {
    if(typeof(arrCli)!=="undefined" && "id_cli" in arrCli){
        document.getElementById('div_contacto').style.display='block';
        url = 'ajaxDatosCliAcotado.php';
        var data = '';
        sendRequest(reqUEv, url, data, 'div_contacto', 'c', 'POST', 'TXT', false);

        document.getElementById('apeCli').innerHTML = arrCli['apellido'];
        document.getElementById('nomCli').innerHTML = arrCli['nombre'];
        document.getElementById('telCli').innerHTML = reqTels.responseText;
        document.getElementById('mailCli').innerHTML = reqMeds.responseText;
        redimensionarDivClienteAcotado();
    }else{
        document.getElementById('div_contacto').style.display='none';
    }
}

function cargaBuscadorVertical(idCrm) {
    url = 'ajaxFormBuscadorVertical.php';
    var data = '';
    sendRequest(reqUEv, url, data, 'div_buscador_vert', 'c', 'POST', 'TXT', false);
    populaCombo('ID_UBICAPRINCIPAL', arrZona, 0, 0);
    $(function() {
        $("#buscaProp").autocomplete({
            source: "autocompletarPropiedades.php",
            minLength: 2,
            select: function(event, ui) {
                document.getElementById('aux_prop').value = ui.item.id;
//                document.getElementById('fid_calle').value = ui.item.id;
//                cargaListaPropiedades();
                buscadorCRM("buscador",1);
            }
        });

    });
    if(idCrm!=0){
        populaBuscadorVertical(idCrm);
    }
}

function populaBuscadorVertical(idCrm){
    document.getElementById('id_crm').value = idCrm;
    url = 'ajaxDataBuscadorVertical.php';
    var data = 'idCrm='+idCrm;
    sendRequest(reqUEv, url, data, 'dbv', 'v', 'POST', 'TXT', false);
}

function cargaContenedorListaProps(){
     url = 'ajaxContenedorListaProps.php';
     var data='';
     sendRequest(reqUEv, url, data,'div_lista_prop', 'c', 'POST', 'TXT', false);
}

function cargaContenedorBarraHerramientas(){
     url = 'ajaxContenedorBarraHerramientas.php';
     var data='';
     sendRequest(reqUEv, url, data,'div_barra_herramientas', 'c', 'POST', 'TXT', false);
}

function cargaListaPropiedades() {
     url = 'ajaxListaPropiedad.php';
     var data=$("#buscador").serialize()+'&adjuntos='+adjuntos2string();
     sendRequest(reqUEv, url, data,'div_lista_inmuebles', 'c', 'POST', 'TXT', false);
     document.getElementById('cuantos').innerHTML=calculaTotalAdjuntos();
}

function cargaUsuarios() {
    url = 'ajaxUsuarios.php';
    data = '';
    sendRequest(reqRelUsr, url, data, 'du', 'v', 'POST', 'TXT', true);
}

function cargaTipoTelefono() {
    url = 'ajaxTipoTelefono.php';
    data = '';
    sendRequest(reqTipoTel, url, data, 'tt', 'v', 'POST', 'TXT', true);
}

function cargaTipoMedio() {
    url = 'ajaxTipoMedio.php';
    data = '';
    sendRequest(reqTipoMed, url, data, 'tm', 'v', 'POST', 'TXT', true);
}

function cargarTipoDomicilio() {
    url = 'ajaxTipoDomicilio.php';
    data = '';
    sendRequest(reqTipoDom, url, data, 'td', 'v', 'POST', 'TXT', true);
}


function cargaTipoPariente() {
    url = 'ajaxTipoPariente.php';
    data = '';
    sendRequest(reqTipoPar, url, data, 'tp', 'v', 'POST', 'TXT', true);
}

function cargaTipoRelacion() {
    url = 'ajaxTipoRelacion.php';
    data = '';
    sendRequest(reqTipoRel, url, data, 'tr', 'v', 'POST', 'TXT', true);
}

function cargaTipoContacto() {
    url = 'ajaxTipoContacto.php';
    data = '';
    sendRequest(reqTipoCont, url, data, 'tco', 'v', 'POST', 'TXT', true);
}

function cargaTipoAsignacion() {
    url = 'ajaxTipoAsignacion.php';
    data = '';
    sendRequest(reqTipoAsig, url, data, 'ta', 'v', 'POST', 'TXT', true);
}

function cargaTipoCategoria() {
    url = 'ajaxTipoCategoria.php';
    data = '';
    sendRequest(reqTipoCat, url, data, 'tca', 'v', 'POST', 'TXT', true);
}

function cargaTipoDocumento() {
    url = 'ajaxTipoDocumento.php';
    data = '';
    sendRequest(reqTipoDoc, url, data, 'tdoc', 'v', 'POST', 'TXT', true);
}

function cargaEstadoCivil() {
    url = 'ajaxEstadoCivil.php';
    data = '';
    sendRequest(reqEstCiv, url, data, 'tec', 'v', 'POST', 'TXT', true);
}

function cargaCapacidadCompra() {
    url = 'ajaxCapacidadCompra.php';
    data = '';
    sendRequest(reqCapCom, url, data, 'cc', 'v', 'POST', 'TXT', true);
}

function cargaFormaIngreso() {
    url = 'ajaxFormaIngreso.php';
    data = '';
    sendRequest(reqFIng, url, data, 'fi', 'v', 'POST', 'TXT', true);
}

function cargaFormaContacto() {
    url = 'ajaxFormaContacto.php';
    data = '';
    sendRequest(reqFIng, url, data, 'fc', 'v', 'POST', 'TXT', true);
}

function cargaZona() {
    url = 'ajaxZona.php';
    data = '';
    sendRequest(reqZona, url, data, 'zo', 'v', 'POST', 'TXT', false);
}

function cargaUltimosEventos() {
    url = 'ajaxUltimosEventos.php';
    data = '';
//    sendRequest(reqUEv, url, data, 'ultimosEventos', 'c', 'POST', 'TXT', true);
    sendRequest(reqUEv, url, data, 'div_lista_inmuebles', 'c', 'POST', 'TXT', false);
}

function cargaUltimosClientes() {
    url = 'ajaxUltimosClientes.php';
    data = '';
    sendRequest(reqUCli, url, data, 'ultimosClientes', 'c', 'POST', 'TXT', true);
}


function cargaModoEdicion(cli){
        cargaPantallaEdicionCliente();

        $(function() {
            $("#buscaCli").autocomplete({
                source: "autocompletarClientes.php",
                minLength: 2,
                dataType: "jsonp",
                select: function(event, ui) {
                    document.getElementById('ID_CLI').value = ui.item.id;
                    document.getElementById('id_cli').value = ui.item.id;
                    document.getElementById('OPERACION').value = 'm';
                    cargaInfoCliente('v');
                }
            });
            $("#buscaPromoCli").autocomplete({
                source: "autocompletarClientes.php",
                minLength: 2,
                select: function(event, ui) {
                    //                                window.location.href='cargarapida_cliente.php?c='+ui.item.id;
                    document.getElementById('ID_PROMO').value = ui.item.id;
                }
            });
            $("#buscaPromoCli2").autocomplete({
                source: "autocompletarClientes.php",
                minLength: 2,
                select: function(event, ui) {
                    //                                window.location.href='cargarapida_cliente.php?c='+ui.item.id;
                    document.getElementById('ID_PROMO2').value = ui.item.id;
                }
            });
            $("#buscaProp").autocomplete({
                source: "autocompletarPropiedades.php",
                minLength: 2,
                select: function(event, ui) {
                    document.getElementById('aux_prop').value = ui.item.id;
                    buscadorCRM("buscador",0);

                }
            });

        });
        if(document.getElementById('ID_CLI')!= null){
            document.getElementById('ID_CLI').value = cli;
        }
        if(document.getElementById('id_cli')!= null){
            document.getElementById('id_cli').value = cli;
        }
}

function cargaModoVista(){
        cargaPantallaCliente();
        $(function() {
            $("#buscaCli").autocomplete({
                source: "autocompletarClientes.php",
                minLength: 2,
                dataType: "jsonp",
                select: function(event, ui) {
                    if (document.getElementById('ID_CLI')) {
                        document.getElementById('ID_CLI').value = ui.item.id;
                    }
                    if (document.getElementById('id_cli')) {
                        document.getElementById('id_cli').value = ui.item.id;
                    }
                    if (document.getElementById('OPERACION')) {
                        document.getElementById('OPERACION').value = 'm';
                    }
                    cargaInfoCliente('v');
                }
            });
        });
        inicializaFormularioModal();
}

function cargaInfoCliente(modo) {
//    inicializaFormularioModal();
    if(document.getElementById('ID_CLI')!= null){
        var data = 'cli=' + document.getElementById('ID_CLI').value + '&modo=' + modo;
        var cli = document.getElementById('ID_CLI').value;
    }else{
        var data = 'cli=' + document.getElementById('id_cli').value + '&modo=' + modo;
        var cli = document.getElementById('id_cli').value;
    }
    switch(modo){
        case 'e':
        case 'r':
            cargaModoEdicion(cli);
            break;
        case 'v':
            cargaModoVista();
            break;
    }

    if(modo!='r'){
        cargaCliente(data, modo);
        cargaTelsCliente(data, modo);
        cargaMedsCliente(data, modo);
        if (modo != 'v') {
            cargaDomsCliente(data, modo);
        } else {
            asuntoSel=0;
            cierreAsunto.init();
            cargaContactosCliente(data);
            cargaInmueblesCliente(data);
            cargaAsuntosAbiertos(data);
            cargaDefinicionesCombos(); // cargo lo que son combos en ediciones dentro de sus campos
            cargaAsuntosCerrados(data);
    //  AGREGAR CARGA DE CONSULTAS Y ACTIVIDADES
        }
        cargaRelsCliente(data, modo);
        cargaIngsCliente(data, modo);
    }else{
        document.getElementById('OPERACION').value = 'n';
        precargaData(0);
        redimensionarDivCliente();
    }
    if (modo == 'e') {
        document.getElementById('OPERACION').value = 'm';
    }
    
}

function cargaDefinicionesCombos(){
    document.getElementById('DESC_CATEGORIA').innerHTML=buscaElementoCombo(arrCli.categoria,arrCat);
    document.getElementById('DESC_TIPOCONT').innerHTML=buscaElementoCombo(arrCli.tipocont,arrCont);
    document.getElementById('DESC_ASIGNACION').innerHTML=buscaElementoCombo(arrCli.asignacion,arrAsig);
//    document.getElementById('DESC_FCONTACTO').innerHTML=buscaElementoCombo(arrCli.categoria,arrCat);
//    document.getElementById('DESC_FINGRESO').innerHTML=buscaElementoCombo(arrDatosIng.categoria,arrCat);
    document.getElementById('DESC_CAPCOMPRA').innerHTML=buscaElementoCombo(arrCli.capcompra,arrCComp);
    document.getElementById('DESC_TIPODOC').innerHTML=buscaElementoCombo(arrCli.tipo_doc,arrDoc);
    document.getElementById('DESC_ESTCIV').innerHTML=buscaElementoCombo(arrCli.id_estcil,arrECiv);
    document.getElementById('DESC_GRUPOFAM').innerHTML=arrCli.grupofam;
    
}

function buscaElementoCombo(indice, arrayCombo){
    var def='';
    for(var x=0; x < arrayCombo.length; x++){
        if(indice == arrayCombo[x][0]){
            def=arrayCombo[x][1];
            break;
        }
    }
    return def;
}

function cargaDataAsuntos(id_asto) {
    propAnt=-1;
    propSel=0;
    var params = "idAst=" + id_asto;
    cargaEventosAsuntosAbiertos(params);
    cargaPropiedadesAsuntosAbiertos(params);
    cargaPropiedadesAsuntosCerrados(params);
}

function cargaDataPropiedad(id_asto, id_prop) {
    var params = "idAst=" + id_asto + "&idProp=" + id_prop;
    cargaEventosAsuntosAbiertos(params);
}


function cargaAsuntosAbiertos(params) {
    url = 'ajaxAsuntosAbiertos.php';
    data = params;
    destino = 'div_AsuntoAbierto';
    tipo = 'c';
    sendRequest(reqAstA, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaAsuntosCerrados(params) {
    url = 'ajaxAsuntosCerrados.php';
    data = params;
    destino = 'div_AsuntoCerrado';
    tipo = 'c';
    sendRequest(reqAstC, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaEventosAsuntosAbiertos(params) {
    url = 'ajaxEventosAsuntosAbiertos.php';
    data = params;
    destino = 'div_EventosAbierto';
    tipo = 'c';
    sendRequest(reqAstA, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaPropiedadesAsuntosAbiertos(params) {
    url = 'ajaxPropiedadesAsuntosAbiertos.php';
    data = params;
    destino = 'div_PropiedadesAbierto';
    tipo = 'c';
    sendRequest(reqAstPA, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaPropiedadesAsuntosCerrados(params) {
    url = 'ajaxPropiedadesAsuntosCerrados.php';
    data = params;
    destino = 'div_PropiedadesCerrados';
    tipo = 'c';
    sendRequest(reqAstPC, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaPantallaCliente() {
    url = 'ajaxPantallaCliente.php';
    data = '';
    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);
    precargaData(0);
    inicializaFormularioModal();
}

function cargaPantallaEdicionCliente() {
    url = 'ajaxPantallaEdicionCliente.php';
    data = '';
    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);
    precargaData(0);
    asignarDatepicker('FECHA_NAC');
    $("#UBICA").autocomplete({
        source: "autocompletarUbicacion.php",
        minLength: 2,
        select: function(event, ui) {
            //                                window.location.href='cargarapida_cliente.php?c='+ui.item.id;
            document.getElementById('ID_UBICA').value = ui.item.id;
        }
    });
    inicializaFormularioModal();
}

function cargaIngsCliente(params, modo) {
    url = 'ajaxIngresoCliente.php';
    data = params;
    sendRequest(reqIngs, url, data, 'cing' + modo, 'v', 'POST', 'TXT', false);
}

function cargaCliente(params, modo) {
    propSel=0;
    asuntoAct=0;
    propAnt=-1;
    asuntoAnt=-1;
    url = 'ajaxDatosCliente.php';
    data = params;
    sendRequest(reqUCli, url, data, 'cli' + modo, 'v', 'POST', 'TXT', false);
}

function cargaTelsCliente(params, modo) {
    var destino = 'ctel';
    var tipo = 'v';
    url = 'ajaxTelefonosCliente.php';
    data = params;
    if (modo == 'v') {
        destino = 'divTablaTels';
        tipo = 'c';
    }
    sendRequest(reqTels, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaDomsCliente(params, modo) {
    url = 'ajaxDomiciliosCliente.php';
    data = params;
    sendRequest(reqDoms, url, data, 'cdom', 'v', 'POST', 'TXT', true);
}

function cargaMedsCliente(params, modo) {
    var destino = 'cmed';
    var tipo = 'v';
    url = 'ajaxMediosElecCliente.php';
    data = params;
    if (modo == 'v') {
        destino = 'divTablaMedios';
        tipo = 'c';
    }
    sendRequest(reqMeds, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaRelsCliente(params, modo) {
    var destino = 'crel';
    var tipo = 'v';
    url = 'ajaxRelacionesCliente.php';
    data = params;
    if (modo == 'v') {
        destino = 'divTablaEjec';
        tipo = 'c';
    }
    sendRequest(reqRels, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaContactosCliente(params) {
    url = 'ajaxRelacionesCliCli.php';
    data = params;
    destino = 'divTablaContactosAsoc';
    tipo = 'c';
    sendRequest(reqRels, url, data, destino, tipo, 'POST', 'TXT', true);
}

function cargaInmueblesCliente(params) {
    url = 'ajaxRelacionesCliPro.php';
    data = params;
    destino = 'divTablaPropsAsoc';
    tipo = 'c';
    sendRequest(reqRels, url, data, destino, tipo, 'POST', 'TXT', true);
}

function comboPromociones(campo, valor, destino) {
    tipo = document.getElementById(campo).value;
    if (tipo == 3 || tipo == null) {
        document.getElementById('divPromoCli').style.display = 'block';
        elemPromo = document.getElementById('AUX_PROMO');
        if (elemPromo != null) {
            document.getElementById('AUX_PROMO').name = 'ID_PROMO';
            document.getElementById('AUX_PROMO').id = 'ID_PROMO';
        }
    } else {
        elemPromo = document.getElementById('ID_PROMO');
        if (elemPromo != null) {
            document.getElementById('ID_PROMO').name = 'AUX_PROMO';
            document.getElementById('ID_PROMO').id = 'AUX_PROMO';
        }
        document.getElementById('divPromoCli').style.display = 'none';
    }
    url = 'llenaComboPromocion.php';
    data = 't=' + tipo + '&v=' + valor;
    sendRequest(reqPromo, url, data, destino, 'c', 'POST', 'TXT', false);
}

function cargaListaEventos() {
//    url = 'ajaxUltimosEventos.php';
//    data = '';
//    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);
    
       url = 'ajaxVistaListaProps.php';
    var data = '';
    sendRequest(reqUEv, url, data, 'contPrincipal', 'c', 'POST', 'TXT', false);

    cargaClienteAcotado();
    cargaBuscadorVertical(idCrm);
    cargaContenedorListaProps();
//    cargaListaPropiedades(idCrm);
    popupLoadingOff();

}

function cargaMenu() {
    url = 'ajaxFormularioMenu.php';
    data = '';
    sendRequest(reqUEv, url, data, 'menu', 'c', 'POST', 'TXT', true);
}

function submitFormMenu(nameForm, valor) {
    switch(valor){
        case 2:
        case 100000:
            popupLoadingOn();
            arrCli=Array();
            arrCrmAdjuntos=Array();
            arrCrmParam=Array();
            abreVistaListaProps(0);
            break;
        case 19:
        case 100019:
            popupLoadingOn();
            impuestos.init();
            impuestos.listaColeccion();
            break;
        case 29:
        case 100021:
            popupLoadingOn();
            contratos.init();
            contratos.listaColeccion();
            break;
        default:
            if (nameForm == '') {
                nombre = 'formMenu';
            } else {
                nombre = nameForm;
            }
            document.forms[nombre].action = 'respondeMenu.php';
            document.forms[nombre].opcion.value = valor;
            document.forms[nombre].submit();
    }
}

function popupLoadingOn(){
//    cargando.show();
}

function popupLoadingOff(){
//    cargando.closa();
}