$(document).ready(function(){
  /*     
       $.ajaxSetup({
                    'crossDomain' : true,
                    'headers': {
                        'Access-Control-Allow-Headers':'*',
                        //'Access-Control-Allow-Headers':'x-requested-with, x-requested-by',
                        //'Access-Control-Request-Headers':'accept',
                        //'Content-Type':'application/json'
                    },
                    'beforeSend' : function(xhr) {
                        try{xhr.overrideMimeType('text/html; charset=UTF-8');}//xhr.overrideMimeType('text/html; charset=iso-8859-1');
                        catch(e){}
                }}); 
*/

	jQuery.support.cors = true;
	var base_url='http://www.okeefe.com.ar/api/';

	//EVENTS & API ZP
	ApiGetIconsZP();ApiGetIconsAP();ApiGetIconsSP();
	 
	$("a[id*='zonaprop_']").each(function() {
		$(this).click(function(event){
	        event.preventBubble=true;
	        event.preventDefault();

	        var errors=false;
	        var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
	        $('#mensaje-zp').html('');
	        //permisos
	        var urlTo = base_url + 'api/v1/zonaprop/consultausuariozpid/'+id_parts[2];
	        $.get(urlTo,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==404){
	                $('#mensaje-zp').text(obj.description);
	                $('#content-publicacion').hide();
	                OcultarBotonesErrorUsuario();
	                errors=true;
	            }
	        })
	        .fail(function() {
	            $('#mensaje-zp').text("Error al consultar usuario actual");
	        });                     
	                    
	        //chequeo si ya esta publicada en ZP
	        var urlTo2 = base_url + 'api/v1/okeefe/registroenzonaprop/'+id_parts[1];
	        $.get(urlTo2,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==200)
	            {
	                $('#mensaje-zp').html(obj.description);
	              	$('#content-publicacion-zp').hide();
	                OcultarBotonesErrorRegistro();          
	                errors=true;
	            }
	        })
			.fail(function() {
				$('#mensaje-zp').text("Error al consultar registro ZP")
			});  

			//ApiGetResponsables("zp_responsable");
 

	        if(!errors){
	                $('#content-publicacion-zp').show();
	                $('.ui-dialog-buttonset button:nth-child(1)').show();
	                $('.ui-dialog-buttonset button:nth-child(2)').show();                           
	                $('.ui-dialog-buttonset button:nth-child(3)').hide();  
	        }


	        var dialog = $("#dialog-form-zp").dialog({
	            autoOpen: true,
	            height: 360,
	            width: 450,
	            modal: true,
	            close: function( e, ui ) {
	                  e.preventDefault();
	            },
	            buttons: 
	                [
	                    {
	                        text: 'Validar',
	                        click: function(e) {
	                                e.preventDefault();
	                                //alert("Temporalmente desabilitado por cambios en la migración de ZonaProp");return false;
	                                var urlTodebug = base_url + "api/v1/zonaprop/publicaravisodebug/" +id_parts[1]+"/"+id_parts[2]+"/"+$('#zp_destacado').val()+"/"+$('#zp_responsable').val();
	                          		$.get(urlTodebug, null, 'json')
									.done(function(data) {
	                                	var obj = $.parseJSON( data );
	                                    $('#mensaje-zp').text(obj.description);
	                                })
	                                .fail(function() {
	                                	$('#mensaje-zp').text("Error al validar propiedad");
	                                });  
		                        	}
	                    },
	                    {
		                    text: 'Publicar',
		                    click: function(e) {
		                    		e.preventDefault();
		                    		//alert("Temporalmente desabilitado por cambios en la migración de ZonaProp");return false;
		                            var urlTopublicar = base_url + "api/v1/zonaprop/publicaraviso/"+id_parts[1]+"/"+id_parts[2]+"/"+$('#zp_destacado').val()+"/"+$('#zp_responsable').val();
		                          	$.get(urlTopublicar,null, 'json')
		                            .done(function(data) {
		                                var obj = $.parseJSON( data );
		                                $('#mensaje-zp').text(obj.description);
		                            })
		                            .fail(function() {
		                            	$('#mensaje-zp').text("Error al intentar publicar la propiedad");
		                            });
							}
	                    },
						{
	                    	text: 'Retirar',
	                    	click: function(e) {
	                    	    	var urlToretirar = base_url + "api/v1/zonaprop/retiraraviso/"+id_parts[1];
	                    	    	//alert("Temporalmente desabilitado por cambios en la migración de ZonaProp");return false;	                    	    	 
									$.get(urlToretirar,null, 'json')
	                        		.done(function(data) {
	                                	var obj = $.parseJSON( data );
	                                	if(obj.status==0){
	                                		$('#mensaje-zp').html(obj.description);
	                                	}
	                                	if(obj.status==204){
	                                		$('#mensaje-zp').html("La propiedad no existe en ZonaProp.");
	                                	}	                                	
	                                	
	                                })
	                                .fail(function() {$('#mensaje-zp').text("Error al intentar retirar la propiedad");});
	                        }
	                    },
	                    {
	                        text: 'Cancelar',
	                        click: function(e) {
	                              e.preventDefault();
	                              dialog.dialog('close');   
	                               
	                        }
	                    }                                                                                               
	                ],
	                close: function(e) {
	                  e.preventDefault();
	                  dialog.dialog( 'close' );   
	                   
	                }
	        }); 
		});
    });


	//EVENTS & API AP
	$("a[id*='argenprop_']").each(function() {
		$(this).click(function(event){
	        event.preventBubble=true;
	        event.preventDefault();

	        var errors=false;
	        var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
	        $('#mensaje-ap').html('');

	        //permisos
	        var urlTo = base_url + 'api/v1/argenprop/consultausuarioapid/'+id_parts[2];
	        $.get(urlTo,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==404){
	                $('#mensaje-ap').text(obj.description);
	                $('#content-publicacion-ap').hide();
	                OcultarBotonesErrorUsuario();
	                errors=true;
	            }
	        })
	        .fail(function() {
	            $('#mensaje-ap').text("Error al consultar usuario actual");
	        });                     
	                    
	        //chequeo si ya esta publicada en ZP
	        var urlTo2 = base_url + 'api/v1/okeefe/registroenargenprop/'+id_parts[1];
	        $.get(urlTo2,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==200)
	            {
	                $('#mensaje-ap').html(obj.description);
	              	$('#content-publicacion-ap').hide();
					OcultarBotonesErrorRegistro();
	                errors=true;
	            }	            
	        })
			.fail(function() {
				$('#mensaje-ap').text("Error al consultar registro AP")
			});  
	        
	        //ApiGetResponsables("ap_responsable");

	        if(!errors){
	                $('#content-publicacion-ap').show();
	                $('.ui-dialog-buttonset button:nth-child(1)').show();
	                $('.ui-dialog-buttonset button:nth-child(2)').show();                           
	                $('.ui-dialog-buttonset button:nth-child(3)').hide();  
	        }

	        var dialog = $("#dialog-form-ap").dialog({
	            autoOpen: true,
	            height: 360,
	            width: 450,
	            modal: true,
	            close: function( e, ui ) {
	                  e.preventDefault();
	            },
	            buttons: 
	                [
	                    {
	                        text: 'Validar',
	                        click: function(e) {
	                                e.preventDefault();
	                                var urlTodebug = base_url + "api/v1/argenprop/publicaravisodebug";///"+id_parts[1]+"/"+id_parts[2]+"/"+$('#ap_destacado').val()+"/"+$('#ap_responsable').val();
									var request = $.ajax({
									  url: urlTodebug,
									  method: "GET",
									  data: { 
	                          			propiedad :id_parts[1], 
	                          			usuario: id_parts[2], 
	                          			destacado:$('#ap_destacado').val(), 
	                          			responsable:$('#ap_responsable').val() 
	                          			},
									  dataType: "json"//,
									  //contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
									});
									 
									request.done(function( data  ) {
	                                	//var obj = $.parseJSON( data  );
	                                    $('#mensaje-ap').html(data.description);
									});
									 
									request.fail(function( jqXHR, textStatus ) {
									  	$('#mensaje-ap').html("Error al validar propiedad");
									});	                                
	                                /*
	                                //{ prop :id_parts[1], user: id_parts[2], destac:$('#ap_destacado').val(), resp:$('#ap_responsable').val() }
	                          		$.get(urlTodebug, { 
	                          			prop :id_parts[1], 
	                          			user: id_parts[2], 
	                          			destac:$('#ap_destacado').val(), 
	                          			resp:$('#ap_responsable').val() 
	                          			}, "json")                                        
									.done(function(data) {
	                                	var obj = $.parseJSON( data );
	                                    $('#mensaje-ap').text(obj.description);
	                                })
	                                .fail(function() {
	                                	$('#mensaje-ap').text("Error al validar propiedad");
	                                });  
	                                */
		                    }
	                    },
	                    {
		                    text: 'Publicar',
		                    click: function(e) {
	                                e.preventDefault();
	                                var urlTopublicar = base_url + "api/v1/argenprop/publicaraviso";
									var request = $.ajax({
									  url: urlTopublicar,
									  method: "GET",
									  data: { 
	                          			propiedad :id_parts[1], 
	                          			usuario: id_parts[2], 
	                          			destacado:$('#ap_destacado').val(), 
	                          			responsable:$('#ap_responsable').val() 
	                          			},
									  dataType: "json"//,
									  //contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
									});
									 
									request.done(function( data  ) {
	                                	//var obj = $.parseJSON( data  );
	                                	if(data.status!=406){
											$('.ui-dialog-buttonset button:nth-child(1)').hide();
		                					$('.ui-dialog-buttonset button:nth-child(2)').hide();  	                                		                                		
	                                	}
	                                    $('#mensaje-ap').html(data.description);
									});
									 
									request.fail(function( jqXHR, textStatus ) {
									  	$('#mensaje-ap').html("Error al intentar publicar la propiedad");
									});	 

									/*
	                                var urlTopublicar = base_url + "api/v1/argenprop/publicaraviso/"+id_parts[1]+"/"+id_parts[2]+"/"+$('#ap_destacado').val()+"/"+$('#ap_responsable').val();
	                          		$.get(urlTopublicar,null, 'json')            
		                            .done(function(data) {
		                                var obj = $.parseJSON( data );
										$('.ui-dialog-buttonset button:nth-child(1)').hide();
	                					$('.ui-dialog-buttonset button:nth-child(2)').hide();  		                                
		                                $('#mensaje-ap').text(obj.description);
		                            })
		                            .fail(function() {
		                            	$('#mensaje-ap').text("Error al intentar publicar la propiedad");
		                            });
		                            */
							}
	                    },
						{
	                    	text: 'Retirar',
	                    	click: function(e) {
	                    	      	e.preventDefault();
	                    	    	var urlToretirar = base_url + "api/v1/argenprop/retiraraviso/"+id_parts[1];
									$.get(urlToretirar,null, 'json')
	                        		.done(function(data) {
	                                	var obj = $.parseJSON( data );
	                                	$('#mensaje-ap').text(obj.description);
	                                })
	                                .fail(function() {$('#mensaje-ap').text("Error al intentar retirar la propiedad");});
	                        }
	                    },
	                    {
	                        text: 'Cancelar',
	                        click: function(e) {
	                              e.preventDefault();
	                              dialog.dialog('close');   

	                        }
	                    }                                                                                               
	                ],
	                close: function(e) {
	                  e.preventDefault();
	                  dialog.dialog( 'close' );   
	                }
	        }); 
		});
    });


	//EVENTS & API SP
	$("a[id*='sumaprop_']").each(function() {
		$(this).click(function(event){
	        event.preventBubble=true;
	        event.preventDefault();

	        var errors=false;
	        var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
	        $('#mensaje-sp').html('');

	        //permisos
	        var urlTo = base_url + 'api/v1/sumaprop/consultausuarioapid/'+id_parts[2];
	        $.get(urlTo,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==404){
	                $('#mensaje-sp').text(obj.description);
	                $('#content-publicacion-ap').hide();
					OcultarBotonesErrorUsuario();
	                errors=true;
	            }
	        })
	        .fail(function() {
	            $('#mensaje-sp').text("Error al consultar usuario actual");
	        });                     
	                    
	        //chequeo si ya esta publicada en ZP
	        var urlTo2 = base_url + 'api/v1/okeefe/registroensumaprop/'+id_parts[1];
	        $.get(urlTo2,null, 'json')
	        .done(function(data) {
	            var obj = $.parseJSON( data );
	            if(obj.status==200)
	            {
	                $('#mensaje-sp').html(obj.description);
	              	$('#content-publicacion-sp').hide();
					//OcultarBotonesErrorRegistro();              
	                $('.ui-dialog-buttonset button:nth-child(1)').hide();
	                $('.ui-dialog-buttonset button:nth-child(2)').show();      
	                $('.ui-dialog-buttonset button:nth-child(3)').show();   					
	                errors=true;
	            }	
	        })
			.fail(function() {
				$('#mensaje-sp').text("Error al consultar registro SP")
			});  
	        
	        if(!errors){
	                $('#content-publicacion-sp').show();
	                $('.ui-dialog-buttonset button:nth-child(1)').show();
	                $('.ui-dialog-buttonset button:nth-child(2)').show();                           
	                $('.ui-dialog-buttonset button:nth-child(3)').hide();  
	        }

	        ApiGetResponsables("sp_responsable");

	        var dialog = $("#dialog-form-sp").dialog({
	            autoOpen: true,
	            height: 320,
	            width: 450,
	            modal: true,
	            close: function( e, ui ) {
	                  e.preventDefault();
	            },
	            buttons: 
	                [
	                	 
	                    {
		                    text: 'Publicar',
		                    click: function(e) {
	                                e.preventDefault();
	                                var urlTopublicar = base_url + "api/v1/sumaprop/publicaraviso";
									var request = $.ajax({
									  url: urlTopublicar,
									  method: "GET",
									  data: { 
	                          			propiedad :id_parts[1], 
	                          			usuario: id_parts[2], 
	                          			responsable:$('#sp_responsable').val() 
	                          			},
									  dataType: "json" 
									});
									 
									request.done(function( data  ) {
	                                	//var obj = $.parseJSON( data  );
	                                	if(data.status!=406){
											$('.ui-dialog-buttonset button:nth-child(1)').hide();
		                					$('.ui-dialog-buttonset button:nth-child(2)').hide();  	                                		                                		
	                                	}
	                                    $('#mensaje-sp').html(data.description);
									});
									 
									request.fail(function( jqXHR, textStatus ) {
									  	$('#mensaje-sp').html("Error al intentar publicar la propiedad");
									});	 
 
							}
	                    },
						{
	                    	text: 'Retirar',
	                    	click: function(e) {
	                    	      	e.preventDefault();
	                    	      	//alert("Desabilitado mientras se hace test...");return false;
	                    	    	var urlToretirar = base_url + "api/v1/sumaprop/retiraraviso/"+id_parts[1];
									$.get(urlToretirar,null, 'json')
	                        		.done(function(data) {
	                                	var obj = $.parseJSON( data );
	                                	$('#mensaje-sp').text(obj.description);
	                                })
	                                .fail(function() {$('#mensaje-sp').text("Error al intentar retirar la propiedad");});
	                        }
	                    },
	                    {
	                        text: 'Cancelar',
	                        click: function(e) {
	                              e.preventDefault();
	                              dialog.dialog('close');   
	                        }
	                    }                                                                                               
	                ],
	                close: function(e) {
	                  e.preventDefault();
	                  dialog.dialog( 'close' );   
	                }
	        }); 
		});
    });

 
	function OcultarBotonesErrorUsuario(){
	                $('.ui-dialog-buttonset button:nth-child(1)').hide();
	                $('.ui-dialog-buttonset button:nth-child(2)').hide();
	                $('.ui-dialog-buttonset button:nth-child(3)').hide(); 
	                	              			        
	}

	function OcultarBotonesErrorRegistro(){
	                $('.ui-dialog-buttonset button:nth-child(1)').hide();
	                $('.ui-dialog-buttonset button:nth-child(2)').hide();      
	                $('.ui-dialog-buttonset button:nth-child(3)').show();                   		        
	}	

	function ApiGetIconsZP(){
		//headers: {"Access-Control-Allow-Origin": "*"},
		var idsProp="";
		$("a[id*='zonaprop_']").each(function() {
			var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
			idsProp += id_parts[1] +',';
		});
		console.log(idsProp);
		var urlToicons = base_url + "api/v1/zonaprop/propiedadesenzp";
		//var urlToicons = "api/v1/zonaprop/propiedadesenzp";
		var requestIcons = $.ajax({
		  url: urlToicons, method: "GET", data: { ids_prop :idsProp},
		  //dataType: "json",
		  success:function(result)//we got the response
	       {
	        alert('Successfully called');
	       },
		  error:function(data){console.log(data);}
		});
		 
		console.log(requestIcons);
		console.log(window.location.href); 
		requestIcons.done(function( result  ) {
            for (var i = 0; i < result.data.length; i++) {
            	//console.log(result.data[i].id);
            	//console.log(result.data[i].status);            	
            	if(result.data[i].status==1){
            		$("a[id*='zonaprop_"+result.data[i].id+"']").html('<img src="images/api/icon_zp_color.png" alt="Publicada en ZonaProp - Click para operar" title="Publicada en ZonaProp - Click para operar">');
            	}else{
					$("a[id*='zonaprop_"+result.data[i].id+"']").html('<img src="images/api/icon_zp.png" alt="Sin publicar en ZonaProp - Click para operar" title="Sin publicar en ZonaProp - Click para operar">');
            	}
            };
		});
		 
		requestIcons.fail(function( jqXHR, textStatus ) {
		  	console.log("ERROR");
		});	           
	}

	function ApiGetIconsAP(){
		var idsProp="";
		$("a[id*='argenprop_']").each(function() {
			var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
			idsProp += id_parts[1] +',';
		});

		var urlToicons = base_url + "api/v1/argenprop/propiedadesenap";
		var requestIcons = $.ajax({
		  url: urlToicons, method: "GET", data: { ids_prop :idsProp},
		  dataType: "json"//,
		});
		 
		requestIcons.done(function( result  ) {
            for (var i = 0; i < result.data.length; i++) {
            	//console.log(result.data[i].id);
            	//console.log(result.data[i].status);            	
            	if(result.data[i].status==1){
            		$("a[id*='argenprop_"+result.data[i].id+"']").html('<img src="images/api/icon_argenprop_color.png" alt="Publicada en Argenprop - Click para operar" title="Publicada en Argenprop - Click para operar">');
            	}else{
					$("a[id*='argenprop_"+result.data[i].id+"']").html('<img src="images/api/icon_argenprop.png" alt="Sin publicar en Argenprop - Click para operar" title="Sin publicar en Argenprop - Click para operar">');
            	}
            };
		});
		 
		requestIcons.fail(function( jqXHR, textStatus ) {
		  	console.log("ERROR");
		});	         
	}	

	function ApiGetIconsSP(){
		var idsProp="";
		$("a[id*='sumaprop_']").each(function() {
			var id_parts = $(this).attr('id').split('_'); //api_idprop_idusersesion
			idsProp += id_parts[1] +',';
		});

		var urlToicons = base_url + "api/v1/sumaprop/propiedadesensp";
		var requestIcons = $.ajax({
		  url: urlToicons, method: "GET", data: { ids_prop :idsProp},
		  dataType: "json"//,
		});
		 
		requestIcons.done(function( result  ) {
            for (var i = 0; i < result.data.length; i++) {
            	//console.log(result.data[i].id);
            	//console.log(result.data[i].status);            	
            	if(result.data[i].status==1){
            		$("a[id*='sumaprop_"+result.data[i].id+"']").html('<img src="images/api/icon_sumaprop_color.png" style="width:16px;height:16px;" alt="Publicada en Sumaprop - Click para operar" title="Publicada en Sumaprop - Click para operar">');
            	}else{
					$("a[id*='sumaprop_"+result.data[i].id+"']").html('<img src="images/api/icon_sumaprop.png" style="width:16px;height:16px;" alt="Sin publicar en Sumaprop - Click para operar" title="Sin publicar en Sumaprop - Click para operar">');
            	}
            };
		});
		 
		requestIcons.fail(function( jqXHR, textStatus ) {
		  	console.log("ERROR");
		});	         
	}


	function ApiGetResponsables(id_combo_html){
            $("#"+id_combo_html+" option").remove();

            var urlTocombo = base_url + 'api/v1/okeefe/usuariosokeefecombo';
            $.get(urlTocombo,null, 'json')
            .done(function(data) {
                var obj = $.parseJSON( data );
                $("#"+id_combo_html+"").append("<option value='0' SELECTED >Seleccione una opción</option>");
                $.each(obj, function(index, val) {
                    $("#"+id_combo_html+"").append("<option value="+val.key+" >"+val.value+" </option>")
                });
            })
            .fail(function() {alert("Error al cargar responsables");}); 
	}


	//Eliminar codigo repetido por estas funciones
	//llamada a metodos por api
	function CallMethodByApi(api, codAviso, idUser){

	}
	
	//verifica permisos usuario
	function CheckPermsUser(idUser){

	}

	//verifica si la propiedad ya fue publicada
	function CheckExistProp(codAviso){

	}	

	//validacion datos obligatorios propiedad, no publica
	function CheckIsValidProp(codAviso){

	}		

	//elimina publicacion en portal y registro abm
	function RemoveProp(codAviso){

	}			

	//publicacion en portal y registro en abm
	function PostProp(codAviso){

	}	
});