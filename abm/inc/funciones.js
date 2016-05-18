// Chequea solo numeros
function checkNumeros(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        status = "This field accepts numbers only."
        return false
    }
    status = ""
    return true
}

//Chequea email
function checkEmail(strng) {
    var error="";
    if (strng == "") {
        error = "No ha ingresado una dirección de e-mail.\n";
    }

    var emailFilter=/^.+@.+\..{2,3}$/;
    if (!(emailFilter.test(strng))) {
        error = "La dirección de e-mail no es correcta.\n";
    }
    else {
        //test email for illegal characters
        var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/
        if (strng.match(illegalChars)) {
            error = "La dirección de e-mail tiene caracteres no permitidos.\n";
        }
    }
    return error;
}

function borrar(valor){
    if(confirm("Esta seguro que desea eliminar este registro")==true){
        document.lista.opcion.value=valor;
        document.lista.submit()
    }else{
        return false;
    }
}

function isEmpty(s){
    var error="";
    if ((s == null) || (s.length == 0) ){
        error = 'Debe contener un valor';
    }
    return error;
}

function valorCheck(campo){
    if(campo.checked){
        campo.value = 1;
    }else{
        campo.value = 0;
    }
    return campo;
}

function agregaTodos(campo){
   // alert(campo.selectedIndex);
    seleccion=campo.selectedIndex;
    if(seleccion >= 0){
        seleccion++;
    }
    campo.options.length=campo.options.length+1;
    for(pos=campo.options.length-1; pos>0; pos--){
        campo.options[pos].text=campo.options[pos-1].text;
        campo.options[pos].value=campo.options[pos-1].value;
    }
    campo.options[0].text="Todos";
    campo.options[0].value=0;
    campo.selectedIndex=seleccion;
}

function ValidaTipo_prop(formulario){

    formulario.tipo_prop.titulo = 'Tipo de Propiedad';

    formulario.tipo_prop.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}


function ValidaOperacion(formulario){
    return true;
}

function ValidaCartel(formulario){
    return true;
}

function ValidaTasacion(formulario){
    return true;
}

function ValidaRelacion(formulario){
    return true;
}

function ValidaCaracteristica(formulario){

    formulario.titulo.titulo = 'Titulo a Mostrar';

    formulario.titulo.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}


function ValidaCliente(formulario){

    formulario.tipo_doc.titulo = 'Tipo de Documento';
    formulario.nro_doc.titulo = 'Número de Documento';
    formulario.nombres.titulo = 'Nombre';
    formulario.apellido.titulo = 'Apellido';
    formulario.fecha_nac.titulo = 'Fecha de naciomiento';
    formulario.domicilio.titulo = 'Domicilio';
    formulario.localidad.titulo = 'Localidad';
    formulario.telefono.titulo = 'Número de Teléfono';
    formulario.celular.titulo = 'Número de Celular';
    formulario.email.titulo = 'e-mail';
    formulario.observaciones.titulo = 'Observaciones';

    formulario.tipo_doc.optional = false;
    formulario.nro_doc.optional = false;
    formulario.nombres.optional = false;
    formulario.apellido.optional = false;
    formulario.fecha_nac.optional = true;
    formulario.domicilio.optional = false;
    formulario.localidad.optional = true;
    formulario.telefono.optional = true;
    formulario.celular.optional = true;
    formulario.email.optional = true;
    formulario.observaciones.optional = true;

    formulario.email.tipo = 'mail';
    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}

function ValidaPropiedad(formulario){

//    formulario.id_zona.titulo = 'Zona';
//    formulario.id_loca.titulo = 'Localidad';
    formulario.id_ubica.titulo = 'Ubicacion';
    formulario.calle.titulo = 'Calle';
    formulario.id_tipo_prop.titulo = 'Tipo de Propiedad';
    formulario.operacion.titulo = 'Tipo de Operación';

//    formulario.id_zona.optional = false;
//    formulario.id_loca.optional = false;
    formulario.id_ubica.optional = false;
    formulario.calle.optional = false;
    formulario.id_tipo_prop.optional = false;
    formulario.operacion.optional = false;

 //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}

function ValidaEmprendimiento(formulario){

    formulario.id_ubica.titulo = 'Zona';
//    formulario.id_zona.titulo = 'Zona';
 //   formulario.id_loca.titulo = 'Localidad';
    formulario.id_tipo_emp.titulo = 'Tipo de Emprendimiento';
    formulario.nombre.titulo = 'Nombre';

    formulario.id_ubica.optional = false;
//    formulario.id_zona.optional = false;
//    formulario.id_loca.optional = false;
    formulario.id_tipo_emp.optional = false;
    formulario.nombre.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}

function ValidaUbicacionpropiedad(formulario){
    elemento='';
    textoError='';
    retorno = true;
    
    if((!document.getElementById('zonaPrinc').checked && document.getElementById('id_padre').value==0) || document.getElementById('id_padre').value=='' ){
    	textoError='* Si no es una Zona principal, debe indicar la dependencia.\n';
    	elemento=document.getElementById('botonZona');
    	retorno=false;
    }
    if(document.getElementById('nombre_ubicacion').value==''){
    	textoError+='* Descripción es obligatorio';
    	if(elemento==''){
        	elemento=document.getElementById('botonZona');
    	}
    	retorno=false;
    }
    if(!retorno){
    	alert(textoError);
    	elemento.focus();
    }
    
    return retorno;
}

function ValidaZona(formulario){

    formulario.nombre_zona.titulo = 'Nombre de la Zona';

    formulario.nombre_zona.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}
function ValidaTipo_prop(formulario){

    formulario.tipo_prop.titulo = 'Tipo de propiedad';

    formulario.tipo_prop.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}
function ValidaTipo_carac(formulario){

    formulario.tipo_carac.titulo = 'Tipo de característica';

    formulario.tipo_carac.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}
function ValidaCaracteristica(formulario){

    formulario.titulo.titulo = 'Característica';

    formulario.titulo.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}
function ValidaLocalidad(formulario){

    formulario.id_zona.titulo = 'Zona';
    formulario.id_zona.optional = false;
    
    formulario.nombre_loca.titulo = 'Descripción';
    formulario.nombre_loca.optional = false;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1 || elemento.value == 0){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}



function ValidaEvento(formulario){

    formulario.evento.titulo = 'Nombre del Evento';
    formulario.lugar.titulo = 'Lugar del Evento';
    formulario.detalle.titulo = 'Detalle del Evento';
    formulario.fecha_even.titulo = 'Fecha del Evento';

    formulario.evento.optional = false;
    formulario.lugar.optional = true;
    formulario.detalle.optional = true;
    formulario.fecha_even.optional = true;


    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}

function ValidaPersonal(formulario){

    formulario.tipo_doc.titulo = 'Tipo de Documento';
    formulario.nro_doc.titulo = 'Número de Documento';
    formulario.nombres.titulo = 'Nombres';
    formulario.apellido.titulo = 'Apellido';
    formulario.fecha_nac.titulo = 'Fecha de Nacimiento';
    formulario.domicilio.titulo = 'Domicilio';
    formulario.localidad.titulo = 'Localidad';
    formulario.telefono.titulo = 'Teléfono';
    formulario.celular.titulo = 'Celular';
    formulario.email.titulo = 'e-mail';
    formulario.fecha_ing.titulo = 'Fecha de Ingreso';
    formulario.fecha_eg.titulo = 'Fecha de Egreso';
    formulario.id_funcion.titulo = 'Función';
    formulario.observaciones.titulo = 'Observaciones';


    formulario.tipo_doc.optional = false;
    formulario.nro_doc.optional = true;
    formulario.nombres.optional = false;
    formulario.apellido.optional = false;
    formulario.fecha_nac.optional = true;
    formulario.domicilio.optional = false;
    formulario.localidad.optional = true;
    formulario.telefono.optional = true;
    formulario.celular.optional = true;
    formulario.email.optional = true;
    formulario.fecha_ing.optional = true;
    formulario.fecha_eg.optional = true;
    formulario.id_funcion.optional = true;
    formulario.observaciones.optional = true;

    //alert("paso");
    for (i=0 ; i < formulario.elements.length ; i++){
        var elemento = formulario.elements[i];
        if (elemento.optional == false){
            var error = isEmpty(elemento.value);
            if (elemento.value == -1){
                var error = 'Es obligatorio.';
            }
            if (elemento.tipo == 'mail' && elemento.value != ''){
                var error = checkEmail(elemento.value);
            }
            if (error != ''){
                alert(elemento.titulo+'\n'+error);
                elemento.focus();
                return false;
            }
        }
    }
    return true;
}

function  ShowHide(elemento){
    $("#"+elemento+"").animate({
        "height": "toggle"
    }, {
        duration: 1000
    });
}

function ventana(url, titulo, ancho, alto){
    window.open(url, titulo, 'location=0,status=1,scrollbars=1, width='+ancho+',height='+alto+'\'');
}

if (!Array.prototype.indexOf){
    Array.prototype.indexOf = function(elt /*, from*/){
        var len = this.length;

        var from = Number(arguments[1]) || 0;
        from = (from < 0)
        ? Math.ceil(from)
        : Math.floor(from);
        if (from < 0)
            from += len;

        for (; from < len; from++){
            if (from in this &&
                this[from] === elt)
                return from;
        }
        return -1;
    };
}
