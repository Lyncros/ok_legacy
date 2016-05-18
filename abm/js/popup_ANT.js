function inicializaFormularioModal() {
    jQuery(function($) {
        var buscador = {
            message: null,
            init: function() {
//                $('#contact-form input.contact, #contact-form a.contact').click(function (e) {
                $('#buscadorButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
//                    $.get("templates/contact.php", function(data){
                    $.get("ajaxFormBuscador.php", function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'buscador-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: buscador.open,
                            onShow: buscador.show,
                            onClose: buscador.close
                        });
                        $('.buscador-form #buscador #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 30;

                var title = $('#popup-conteiner .buscador-title').html();
                $('#popup-conteiner .buscador-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .buscador-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .buscador-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #buscador-name').focus();
                                    $('.buscador-form #buscador #id_cli').val($('.buscador #ID_CLI').val());
                                });
                            });
                        });
                    });
                });
                $("#buscaProp").autocomplete({
                    source: "autocompletarPropiedades.php",
                    minLength: 2,
                    select: function(event, ui) {
                        document.getElementById('aux_prop').value = ui.item.id;
                        buscadorCRM("buscador", 1);

                    }
                });
                populaCombo('ID_UBICAPRINCIPAL', arrZona, 0, 0);
            },
            show: function(dialog) {
                $('#popup-conteiner #bt_buscador').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (buscador.validate()) {
                        var msg = $('#popup-conteiner .buscador-message');
                        msg.fadeOut(function() {
                            msg.removeClass('buscador-error').empty();
                        });
                        $('#popup-conteiner .buscador-title').html('Enviando...');
                        buscador.close();
                        buscadorCRM("buscador", 1);

                    }
                    else {
                        if ($('#popup-conteiner .buscador-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .buscador-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                buscador.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .buscador-message').animate({
                                height: '30px'
                            }, buscador.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .buscador-message').fadeOut();
                $('#popup-conteiner .buscador-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .buscador-content').animate({
                    height: 40
                }, function() {
//                    dialog.data.fadeOut(200, function() {
//                        dialog.container.fadeOut(200, function() {
//                            dialog.overlay.fadeOut(200, function() {
                    $.modal.close();
//                            });
//                        });
//                    });
                });
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                buscador.message = '';
                if ($('#aux_Tprop').val() == 0 && $('#ID_UBICAPRINCIPAL').val() == 0 && $('#aux_emp').val() == 0 && $('#aux_operacion').val() == 0 && $('#fid_ubica').val() == 0 && $('#DORMITORIOS').val() == 0) {
                    buscador.message += 'Debe especificar algun criterio para efectuar una busqueda. ';
                }


                if (buscador.message.length > 0) {
                    return false;
                } else {
                    return true;
                }
            },
            showError: function() {
                $('#popup-conteiner .buscador-message')
                        .html($('<div class="buscador-error"></div>').append(buscador.message))
                        .fadeIn(200);
            }
        };
        /*
         * ******************************************************************************************************************************
         *                                 R E L A C I O N   C L I E N T E   P R O P I E D A D                                          *
         * ******************************************************************************************************************************
         */

        var relacionCP = {
            message: null,
            init: function() {
                $('#relacionCPButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
                    $.get("ajaxFormRelacionCP.php?cli=" + $('.buscador #ID_CLI').val(), function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'relacionCP-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: relacionCP.open,
                            onShow: relacionCP.show,
                            onClose: relacionCP.close
                        });
//                        $('.relacionCP-form #relacionCP #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .relacionCP-title').html();
                $('#popup-conteiner .relacionCP-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .relacionCP-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .relacionCP-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #buscaProp').focus();
//                                    $('.relacionCP-form #relacionCP #id_cli').val($('.relacionCP #ID_CLI').val());
                                });
                            });
                        });
                    });
                });
                $("#buscaProp").autocomplete({
                    source: "autocompletarPropiedades.php",
                    minLength: 2,
                    select: function(event, ui) {
                        document.getElementById('aux_prop').value = ui.item.id;
                    }
                });
            },
            show: function(dialog) {
                $('#popup-conteiner .relacionCP-send').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (relacionCP.validate()) {
                        var msg = $('#popup-conteiner .relacionCP-message');
                        msg.fadeOut(function() {
                            msg.removeClass('relacionCP-error').empty();
                        });
                        $('#popup-conteiner .relacionCP-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .relacionCP-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .relacionCP-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .relacionCP-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .relacionCP-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: relacionCP.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .relacionCP-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .relacionCP-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                relacionCP.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .relacionCP-message').animate({
                                height: '30px'
                            }, relacionCP.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .relacionCP-message').fadeOut();
                $('#popup-conteiner .relacionCP-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .relacionCP-content').animate({
                    height: 40
                }, function() {
                    dialog.data.fadeOut(200, function() {
                        dialog.container.fadeOut(200, function() {
                            dialog.overlay.fadeOut(200, function() {
                                $.modal.close();
                            });
                        });
                    });
                });
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                relacionCP.message = '';
                if (!$('#popup-conteiner #relacionCP-name').val()) {
                    relacionCP.message += 'Name is required. ';
                }

                var email = $('#popup-conteiner #relacionCP-email').val();
                if (!email) {
                    relacionCP.message += 'Email is required. ';
                }
                else {
                    if (!relacionCP.validateEmail(email)) {
                        relacionCP.message += 'Email is invalid. ';
                    }
                }

                if (!$('#popup-conteiner #relacionCP-message').val()) {
                    relacionCP.message += 'Message is required.';
                }

                if (relacionCP.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            validateEmail: function(email) {
                var at = email.lastIndexOf("@");

                // Make sure the at (@) sybmol exists and  
                // it is not the first or last character
                if (at < 1 || (at + 1) === email.length)
                    return false;

                // Make sure there aren't multiple periods together
                if (/(\.{2,})/.test(email))
                    return false;

                // Break up the local and domain portions
                var local = email.substring(0, at);
                var domain = email.substring(at + 1);

                // Check lengths
                if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
                    return false;

                // Make sure local and domain don't start with or end with a period
                if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
                    return false;

                // Check for quoted-string addresses
                // Since almost anything is allowed in a quoted-string address,
                // we're just going to let them go through
                if (!/^"(.+)"$/.test(local)) {
                    // It's a dot-string address...check for valid characters
                    if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
                        return false;
                }

                // Make sure domain contains only valid characters and at least one period
                if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
                    return false;

                return true;
            },
            showError: function() {
                $('#popup-conteiner .relacionCP-message')
                        .html($('<div class="relacionCP-error"></div>').append(relacionCP.message))
                        .fadeIn(200);
            }
        };

        /*
         * ******************************************************************************************************************************
         *                                 R E L A C I O N   C L I E N T E   C L I E N T E                                              *
         * ******************************************************************************************************************************
         */

        var relacionCC = {
            message: null,
            init: function() {
                $('#relacionCCButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
                    $.get("ajaxFormRelacionCC.php?cli=" + $('.buscador #ID_CLI').val(), function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'relacionCC-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: relacionCC.open,
                            onShow: relacionCC.show,
                            onClose: relacionCC.close
                        });
//                        $('.relacionCC-form #relacionCC #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .relacionCC-title').html();
                $('#popup-conteiner .relacionCC-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .relacionCC-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .relacionCC-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #relacionCC-name').focus();
//                                    $('.relacionCC-form #relacionCC #id_cli').val($('.relacionCC #ID_CLI').val());
                                });
                            });
                        });
                    });
                });
                $(".relacionCC-form #buscaCli").autocomplete({
                    source: "autocompletarClientes.php",
                    minLength: 2,
                    select: function(event, ui) {
                        document.getElementById('aux_cli').value = ui.item.id;
//                        document.getElementById('id_cli').value = ui.item.id;
                    }
                });
            },
            show: function(dialog) {
                $('#popup-conteiner .relacionCC-send').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (relacionCC.validate()) {
                        var msg = $('#popup-conteiner .relacionCC-message');
                        msg.fadeOut(function() {
                            msg.removeClass('relacionCC-error').empty();
                        });
                        $('#popup-conteiner .relacionCC-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .relacionCC-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .relacionCC-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .relacionCC-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .relacionCC-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: relacionCC.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .relacionCC-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .relacionCC-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                relacionCC.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .relacionCC-message').animate({
                                height: '30px'
                            }, relacionCC.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .relacionCC-message').fadeOut();
                $('#popup-conteiner .relacionCC-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .relacionCC-content').animate({
                    height: 40
                }, function() {
                    dialog.data.fadeOut(200, function() {
                        dialog.container.fadeOut(200, function() {
                            dialog.overlay.fadeOut(200, function() {
                                $.modal.close();
                            });
                        });
                    });
                });
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                relacionCC.message = '';
                if (!$('#popup-conteiner #relacionCC-name').val()) {
                    relacionCC.message += 'Name is required. ';
                }

                var email = $('#popup-conteiner #relacionCC-email').val();
                if (!email) {
                    relacionCC.message += 'Email is required. ';
                }
                else {
                    if (!relacionCC.validateEmail(email)) {
                        relacionCC.message += 'Email is invalid. ';
                    }
                }

                if (!$('#popup-conteiner #relacionCC-message').val()) {
                    relacionCC.message += 'Message is required.';
                }

                if (relacionCC.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            validateEmail: function(email) {
                var at = email.lastIndexOf("@");

                // Make sure the at (@) sybmol exists and  
                // it is not the first or last character
                if (at < 1 || (at + 1) === email.length)
                    return false;

                // Make sure there aren't multiple periods together
                if (/(\.{2,})/.test(email))
                    return false;

                // Break up the local and domain portions
                var local = email.substring(0, at);
                var domain = email.substring(at + 1);

                // Check lengths
                if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
                    return false;

                // Make sure local and domain don't start with or end with a period
                if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
                    return false;

                // Check for quoted-string addresses
                // Since almost anything is allowed in a quoted-string address,
                // we're just going to let them go through
                if (!/^"(.+)"$/.test(local)) {
                    // It's a dot-string address...check for valid characters
                    if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
                        return false;
                }

                // Make sure domain contains only valid characters and at least one period
                if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
                    return false;

                return true;
            },
            showError: function() {
                $('#popup-conteiner .relacionCC-message')
                        .html($('<div class="relacionCC-error"></div>').append(relacionCC.message))
                        .fadeIn(200);
            }
        };

        /*        buscador.init();
         relacionCP.init();
         relacionCC.init();
         });*/
        /*
         * ******************************************************************************************************************************
         *                                 C A R G A    D E    T A R E A S   D E    C L I E N T E                                              *
         * ******************************************************************************************************************************
         */

        var tareasCli = {
            message: null,
            init: function() {
                $('#eventoButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
                    $.get("ajaxFormTareasCli.php?cli=" + $('.formTarea #ID_CLI').val(), function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'tareasCli-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: tareasCli.open,
                            onShow: tareasCli.show,
                            onClose: tareasCli.close
                        });
//                        $('.relacionCC-form #relacionCC #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .tareasCli-title').html();
                $('#popup-conteiner .tareasCli-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .tareasCli-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .tareasCli-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #tareasCli-name').focus();
                                    $('.tareasCli-form #tareasCli #id_cli_evt').val(arrCli.id_cli);
                                    $('.tareasCli-form #tareasCli #id_prop_evt').val(propSel);
                                });
                            });
                        });
                    });
                });
                /*            $(".tareasCli-form #buscaCli").autocomplete({
                 source: "autocompletarClientes.php",
                 minLength: 2,
                 select: function(event, ui) {
                 document.getElementById('aux_cli').value = ui.item.id;
                 //                        document.getElementById('id_cli').value = ui.item.id;
                 }
                 });   */
            },
            show: function(dialog) {
                $('#popup-conteiner .tareasCli-send').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (tareasCli.validate()) {
                        var msg = $('#popup-conteiner .tareasCli-message');
                        msg.fadeOut(function() {
                            msg.removeClass('tareasCli-error').empty();
                        });
                        $('#popup-conteiner .tareasCli-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .tareasCli-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .tareasCli-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .tareasCli-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .tareasCli-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: tareasCli.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .tareasCli-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .tareasCli-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                tareasCli.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .tareasCli-message').animate({
                                height: '30px'
                            }, tareasCli.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .tareasCli-message').fadeOut();
                $('#popup-conteiner .tareasCli-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .tareasCli-content').animate({
                    height: 40
                }, function() {
                    $.modal.close();
                });
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                tareasCli.message = '';
  /*
                if (!$('#popup-conteiner #tareasCli-name').val()) {
                    tareasCli.message += 'Name is required. ';
                }

                var email = $('#popup-conteiner #tareasCli-email').val();
                if (!email) {
                    tareasCli.message += 'Email is required. ';
                }
                else {
                    if (!tareasCli.validateEmail(email)) {
                        tareasCli.message += 'Email is invalid. ';
                    }
                }

                if (!$('#popup-conteiner #tareasCli-message').val()) {
                    tareasCli.message += 'Message is required.';
                }
*/
                if (tareasCli.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            showError: function() {
                $('#popup-conteiner .tareasCli-message')
                        .html($('<div class="tareasCli-error"></div>').append(tareasCli.message))
                        .fadeIn(200);
            }
        };

        buscador.init();
        relacionCP.init();
        relacionCC.init();
        tareasCli.init();
    });
}