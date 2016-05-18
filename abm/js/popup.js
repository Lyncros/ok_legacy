/* test */ 
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
                        document.getElementById('fid_codigo').value = ui.item.id;
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
                    $.get("ajaxFormRelacionCP.php?cli=" + $('#clienteCRM #ID_CLI').val(), function(data) {
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
                    $.get("ajaxFormRelacionCC.php?cli=" + $('#clienteCRM #ID_CLI').val(), function(data) {
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
//                    $.get("ajaxFormTareasCli.php?cli=" + $('.buscador #ID_CLI').val() + "&asto=" + asuntoAct, function(data) {
                    $.get("ajaxFormTareasCli.php?cli=" + arrCli.id_cli + "&asto=" + asuntoAct, function(data) {
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
        /*
         * ******************************************************************************************************************************
         *                                 C A R G A    D E    C O M E N T A R I O     D E    C I E R R E                                              *
         * ******************************************************************************************************************************
         */

        var cierreAsunto = {
            message: null,
            init: function() {
                $('#cerrarAstoButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
//                    $.get("ajaxFormComentarioCierre.php?cli=" + $('.buscador #ID_CLI').val() + "&asto=" + asuntoSel + "&prop=0", function(data) {
                    $.get("ajaxFormComentarioCierre.php?cli=" + arrCli.id_cli + "&asto=" + asuntoSel + "&prop=0", function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'cierreAsunto-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: cierreAsunto.open,
                            onShow: cierreAsunto.show,
                            onClose: cierreAsunto.close
                        });
//                        $('.relacionCC-form #relacionCC #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .cierreAsunto-title').html();
                $('#popup-conteiner .cierreAsunto-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .cierreAsunto-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .cierreAsunto-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #cierreAsunto-name').focus();
                                    $('.cierreAsunto-form #cierreAsunto #id_cli_evt').val(arrCli.id_cli);
                                    $('.cierreAsunto-form #cierreAsunto #id_prop_evt').val(propSel);
                                });
                            });
                        });
                    });
                });
            },
            show: function(dialog) {
//                $('#popup-conteiner .cierreAsunto-send').click(function(e) {
                $('#popup-conteiner #bt_cierrecom').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (tareasCli.validate()) {
                        var msg = $('#popup-conteiner .cierreAsunto-message');
                        msg.fadeOut(function() {
                            msg.removeClass('cierreAsunto-error').empty();
                        });
                        $('#popup-conteiner .cierreAsunto-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .cierreAsunto-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .cierreAsunto-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .cierreAsunto-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .cierreAsunto-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: tareasCli.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .cierreAsunto-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .cierreAsunto-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                cierreAsunto.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .cierreAsunto-message').animate({
                                height: '30px'
                            }, cierreAsunto.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .cierreAsunto-message').fadeOut();
                $('#popup-conteiner .cierreAsunto-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .cierreAsunto-content').animate({
                    height: 40
                }, function() {
                    $.modal.close();
                });
                $.modal.close();
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                cierreAsunto.message = '';
                if (cierreAsunto.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            showError: function() {
                $('#popup-conteiner .cierreAsunto-message')
                        .html($('<div class="cierreAsunto-error"></div>').append(cierreAsunto.message))
                        .fadeIn(200);
            }
        };
        /*
         * ******************************************************************************************************************************
         *                                 C A R G A    D E    C O M E N T A R I O     D E    C I E R R E                                              *
         * ******************************************************************************************************************************
         */

        var cierreProducto = {
            message: null,
            init: function() {
                $('#cerrarProductoButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
                    $.get("ajaxFormComentarioCierre.php?cli=" + $('.buscador #ID_CLI').val() + "&asto=" + asuntoAct + "&prop=" + propSel, function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'cierreProducto-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: cierreProducto.open,
                            onShow: cierreProducto.show,
                            onClose: cierreProducto.close
                        });
//                        $('.relacionCC-form #relacionCC #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .cierreProducto-title').html();
                $('#popup-conteiner .cierreProducto-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .cierreProducto-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .cierreProducto-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #cierreProducto-name').focus();
                                    $('.cierreAsunto-form #cierreProducto #id_cli_evt').val(arrCli.id_cli);
                                    $('.cierreAsunto-form #cierreProducto #id_prop_evt').val(propSel);
                                });
                            });
                        });
                    });
                });
            },
            show: function(dialog) {
                $('#popup-conteiner .cierreProducto-send').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (tareasCli.validate()) {
                        var msg = $('#popup-conteiner .cierreProducto-message');
                        msg.fadeOut(function() {
                            msg.removeClass('cierreProducto-error').empty();
                        });
                        $('#popup-conteiner .cierreProducto-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .cierreProducto-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .cierreProducto-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .cierreProducto-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .cierreProducto-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: tareasCli.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .cierreProducto-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .cierreProducto-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                tareasCli.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .cierreProducto-message').animate({
                                height: '30px'
                            }, tareasCli.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .cierreProducto-message').fadeOut();
                $('#popup-conteiner .cierreProducto-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .cierreProducto-content').animate({
                    height: 40
                }, function() {
                    $.modal.close();
                });
                $.modal.close();
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                cierreProducto.message = '';
                if (cierreProducto.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            showError: function() {
                $('#popup-conteiner .cierreProducto-message')
                        .html($('<div class="cierreProducto-error"></div>').append(cierreProducto.message))
                        .fadeIn(200);
            }
        };

        buscador.init();
        relacionCP.init();
        relacionCC.init();
        tareasCli.init();
        cierreAsunto.init();
        cierreProducto.init();
    });
}

        /*
         * ******************************************************************************************************************************
         *                                 C A R G A    D E    C O M E N T A R I O     D E    C I E R R E                                              *
         * ******************************************************************************************************************************
         */

        var cierreAsunto = {
            message: null,
            init: function() {
                $('#cerrarAstoButton').click(function(e) {
                    e.preventDefault();
                    // load the contact form using ajax
//                    $.get("ajaxFormComentarioCierre.php?cli=" + $('.buscador #ID_CLI').val() + "&asto=" + asuntoSel + "&prop=0", function(data) {
                    $.get("ajaxFormComentarioCierre.php?cli=" + arrCli.id_cli + "&asto=" + asuntoSel + "&prop=0", function(data) {
                        // create a modal dialog with the data
                        $(data).modal({
                            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                            position: ["15%", ],
                            overlayId: 'cierreAsunto-overlay',
                            containerId: 'popup-conteiner',
                            onOpen: cierreAsunto.open,
                            onShow: cierreAsunto.show,
                            onClose: cierreAsunto.close
                        });
//                        $('.relacionCC-form #relacionCC #id_cli').val($('.buscador #ID_CLI').val());
                    });
                });
            },
            open: function(dialog) {
                // dynamically determine height
                var h = 20;

                var title = $('#popup-conteiner .cierreAsunto-title').html();
                $('#popup-conteiner .cierreAsunto-title').html('Cargando...');
                dialog.overlay.fadeIn(200, function() {
                    dialog.container.fadeIn(200, function() {
                        dialog.data.fadeIn(200, function() {
                            $('#popup-conteiner .cierreAsunto-title').animate({
                                height: h
                            }, function() {
                                $('#popup-conteiner .cierreAsunto-title').html(title);
                                $('#popup-conteiner form').fadeIn(200, function() {
                                    $('#popup-conteiner #cierreAsunto-name').focus();
                                    $('.cierreAsunto-form #cierreAsunto #id_cli_evt').val(arrCli.id_cli);
                                    $('.cierreAsunto-form #cierreAsunto #id_prop_evt').val(propSel);
                                });
                            });
                        });
                    });
                });
            },
            show: function(dialog) {
//                $('#popup-conteiner .cierreAsunto-send').click(function(e) {
                $('#popup-conteiner #bt_cierrecom').click(function(e) {
                    e.preventDefault();
                    // validate form
                    if (tareasCli.validate()) {
                        var msg = $('#popup-conteiner .cierreAsunto-message');
                        msg.fadeOut(function() {
                            msg.removeClass('cierreAsunto-error').empty();
                        });
                        $('#popup-conteiner .cierreAsunto-title').html('Sending...');
                        $('#popup-conteiner form').fadeOut(200);
                        $('#popup-conteiner .cierreAsunto-content').animate({
                            height: '80px'
                        }, function() {
                            $('#popup-conteiner .cierreAsunto-loading').fadeIn(200, function() {
                                $.ajax({
                                    url: 'data/contact.php',
                                    data: $('#popup-conteiner form').serialize() + '&action=send',
                                    type: 'post',
                                    cache: false,
                                    dataType: 'html',
                                    success: function(data) {
                                        $('#popup-conteiner .cierreAsunto-loading').fadeOut(200, function() {
                                            $('#popup-conteiner .cierreAsunto-title').html('Thank you!');
                                            msg.html(data).fadeIn(200);
                                        });
                                    },
                                    error: tareasCli.error
                                });
                            });
                        });
                    }
                    else {
                        if ($('#popup-conteiner .cierreAsunto-message:visible').length > 0) {
                            var msg = $('#popup-conteiner .cierreAsunto-message div');
                            msg.fadeOut(200, function() {
                                msg.empty();
                                cierreAsunto.showError();
                                msg.fadeIn(200);
                            });
                        }
                        else {
                            $('#popup-conteiner .cierreAsunto-message').animate({
                                height: '30px'
                            }, cierreAsunto.showError);
                        }

                    }
                });

            },
            close: function(dialog) {
                $('#popup-conteiner .cierreAsunto-message').fadeOut();
                $('#popup-conteiner .cierreAsunto-title').html('Goodbye...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .cierreAsunto-content').animate({
                    height: 40
                }, function() {
                    $.modal.close();
                });
                $.modal.close();
            },
            error: function(xhr) {
                alert(xhr.statusText);
            },
            validate: function() {
                cierreAsunto.message = '';
                if (cierreAsunto.message.length > 0) {
                    return false;
                }
                else {
                    return true;
                }
            },
            showError: function() {
                $('#popup-conteiner .cierreAsunto-message')
                        .html($('<div class="cierreAsunto-error"></div>').append(cierreAsunto.message))
                        .fadeIn(200);
            }
        };



/*
 * ******************************************************************************************************************************
 *                                 S E L E C C I O N    D E    C L I E N T E     O C A R G A     D E      N U E V O             *
 * ******************************************************************************************************************************
 */

var seleccionEnvioCliente = {
    message: null,
    init: function() {
        // load the contact form using ajax
//                    $.get("ajaxFormEnvioSeleccion.php?cli=" + $('.buscador #ID_CLI').val()+"&asto="+asuntoAct+"&prop="+propSel, function(data) {
        $.get("ajaxFormEnvioSeleccion.php", function(data) {
            // create a modal dialog with the data
            $(data).modal({
                closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                position: ["15%", ],
                overlayId: 'seleccionEnvioCliente-overlay',
                containerId: 'popup-conteiner',
                onOpen: seleccionEnvioCliente.open,
                onShow: seleccionEnvioCliente.show,
                onClose: seleccionEnvioCliente.close
            });
        });
        $(".seleccionEnvioCliente-form #buscaCli").autocomplete({
            source: "autocompletarClientes.php",
            minLength: 2,
            select: function(event, ui) {
                document.getElementById('aux_cli').value = ui.item.id;
                //                        document.getElementById('id_cli').value = ui.item.id;
            }
        });


//                });
    },
    open: function(dialog) {
        // dynamically determine height
        var h = 20;

        var title = $('#popup-conteiner .seleccionEnvioCliente-title').html();
        $('#popup-conteiner .seleccionEnvioCliente-title').html('Cargando...');
        dialog.overlay.fadeIn(200, function() {
            dialog.container.fadeIn(200, function() {
                dialog.data.fadeIn(200, function() {
                    $('#popup-conteiner .seleccionEnvioCliente-title').animate({
                        height: h
                    }, function() {
                        $('#popup-conteiner .seleccionEnvioCliente-title').html(title);
                        $('#popup-conteiner form').fadeIn(200, function() {
                            $('#popup-conteiner #seleccionEnvioCliente-name').focus();
//                                    $('.cierreAsunto-form #seleccionEnvioCliente #id_cli_evt').val(arrCli.id_cli);
//                                    $('.cierreAsunto-form #seleccionEnvioCliente #id_prop_evt').val(propSel);
                        });


                    });
                });
            });
        });
    },
    show: function(dialog) {
                        $("#seleccionEnvioCliente #buscaCli").autocomplete({
                            source: "autocompletarClientes.php",
                            minLength: 2,
                            select: function(event, ui) {
                                document.getElementById('aux_cli').value = ui.item.id;
                                //                        document.getElementById('id_cli').value = ui.item.id;
                            }
                        });
        $('#popup-conteiner .seleccionEnvioCliente-send').click(function(e) {
            e.preventDefault();
            // validate form
            if (tareasCli.validate()) {
                var msg = $('#popup-conteiner .seleccionEnvioCliente-message');
                msg.fadeOut(function() {
                    msg.removeClass('seleccionEnvioCliente-error').empty();
                });
                $('#popup-conteiner .seleccionEnvioCliente-title').html('Sending...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .seleccionEnvioCliente-content').animate({
                    height: '80px'
                }, function() {
                    $('#popup-conteiner .seleccionEnvioCliente-loading').fadeIn(200, function() {
                    });
                });
            }
            else {
                if ($('#popup-conteiner .seleccionEnvioCliente-message:visible').length > 0) {
                    var msg = $('#popup-conteiner .seleccionEnvioCliente-message div');
                    msg.fadeOut(200, function() {
                        msg.empty();
                        tareasCli.showError();
                        msg.fadeIn(200);
                    });
                }
                else {
                    $('#popup-conteiner .seleccionEnvioCliente-message').animate({
                        height: '30px'
                    }, tareasCli.showError);
                }

            }
        });

    },
    close: function(dialog) {
        $('#popup-conteiner .seleccionEnvioCliente-message').fadeOut();
        $('#popup-conteiner .seleccionEnvioCliente-title').html('Goodbye...');
        $('#popup-conteiner form').fadeOut(200);
        $('#popup-conteiner .seleccionEnvioCliente-content').animate({
            height: 40
        }, function() {
            $.modal.close();
        });
        $.modal.close();
    },
    error: function(xhr) {
        alert(xhr.statusText);
    },
    validate: function() {
        cierreProducto.message = '';
        if (cierreProducto.message.length > 0) {
            return false;
        }
        else {
            return true;
        }
    },
    showError: function() {
        $('#popup-conteiner .seleccionEnvioCliente-message')
                .html($('<div class="seleccionEnvioCliente-error"></div>').append(seleccionEnvioCliente.message))
                .fadeIn(200);
    }
};

/*
 * ******************************************************************************************************************************************************
 *            S E L E C C I O N    D E    C L I E N T E     O    C A R G A     D E      N U E V O    P A R A   R E G I S T R O   E N    C R M           *
 * ******************************************************************************************************************************************************
 */

var seleccionClienteGrabacion = {
    message: null,
    init: function() {
        // load the contact form using ajax
        $.get("ajaxFormSeleccionCliente.php", function(data) {
            // create a modal dialog with the data
            $(data).modal({
                closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
                position: ["15%", ],
                overlayId: 'seleccionClienteGrabacion-overlay',
                containerId: 'popup-conteiner',
                onOpen: seleccionClienteGrabacion.open,
                onShow: seleccionClienteGrabacion.show,
                onClose: seleccionClienteGrabacion.close
            });
        });
        $(".seleccionClienteGrabacion-form #buscaCli").autocomplete({
            source: "autocompletarClientes.php",
            minLength: 2,
            select: function(event, ui) {
                document.getElementById('aux_cli').value = ui.item.id;
                //                        document.getElementById('id_cli').value = ui.item.id;
            }
        });


//                });
    },
    open: function(dialog) {
        // dynamically determine height
        var h = 20;

        var title = $('#popup-conteiner .seleccionClienteGrabacion-title').html();
        $('#popup-conteiner .seleccionClienteGrabacion-title').html('Cargando...');
        dialog.overlay.fadeIn(200, function() {
            dialog.container.fadeIn(200, function() {
                dialog.data.fadeIn(200, function() {
                    $('#popup-conteiner .seleccionClienteGrabacion-title').animate({
                        height: h
                    }, function() {
                        $('#popup-conteiner .seleccionClienteGrabacion-title').html(title);
                        $('#popup-conteiner form').fadeIn(200, function() {
                            $('#popup-conteiner #seleccionClienteGrabacion-name').focus();
//                                    $('.cierreAsunto-form #seleccionClienteGrabacion #id_cli_evt').val(arrCli.id_cli);
//                                    $('.cierreAsunto-form #seleccionClienteGrabacion #id_prop_evt').val(propSel);
                        });


                    });
                });
            });
        });
    },
    show: function(dialog) {
                        $("#seleccionClienteGrabacion #buscaCli").autocomplete({
                            source: "autocompletarClientes.php",
                            minLength: 2,
                            select: function(event, ui) {
                                document.getElementById('aux_cli').value = ui.item.id;
                                //                        document.getElementById('id_cli').value = ui.item.id;
                            }
                        });
        $('#popup-conteiner .seleccionClienteGrabacion-send').click(function(e) {
            e.preventDefault();
            // validate form
            if (tareasCli.validate()) {
                var msg = $('#popup-conteiner .seleccionClienteGrabacion-message');
                msg.fadeOut(function() {
                    msg.removeClass('seleccionClienteGrabacion-error').empty();
                });
                $('#popup-conteiner .seleccionClienteGrabacion-title').html('Sending...');
                $('#popup-conteiner form').fadeOut(200);
                $('#popup-conteiner .seleccionClienteGrabacion-content').animate({
                    height: '80px'
                }, function() {
                    $('#popup-conteiner .seleccionClienteGrabacion-loading').fadeIn(200, function() {
                    });
                });
            }
            else {
                if ($('#popup-conteiner .seleccionClienteGrabacion-message:visible').length > 0) {
                    var msg = $('#popup-conteiner .seleccionClienteGrabacion-message div');
                    msg.fadeOut(200, function() {
                        msg.empty();
                        tareasCli.showError();
                        msg.fadeIn(200);
                    });
                }
                else {
                    $('#popup-conteiner .seleccionClienteGrabacion-message').animate({
                        height: '30px'
                    }, tareasCli.showError);
                }

            }
        });

    },
    close: function(dialog) {
        $('#popup-conteiner .seleccionClienteGrabacion-message').fadeOut();
        $('#popup-conteiner .seleccionClienteGrabacion-title').html('Goodbye...');
        $('#popup-conteiner form').fadeOut(200);
        $('#popup-conteiner .seleccionClienteGrabacion-content').animate({
            height: 40
        }, function() {
            $.modal.close();
        });
        $.modal.close();
    },
    error: function(xhr) {
        alert(xhr.statusText);
    },
    validate: function() {
        cierreProducto.message = '';
        if (cierreProducto.message.length > 0) {
            return false;
        }
        else {
            return true;
        }
    },
    showError: function() {
        $('#popup-conteiner .seleccionClienteGrabacion-message')
                .html($('<div class="seleccionClienteGrabacion-error"></div>').append(seleccionClienteGrabacion.message))
                .fadeIn(200);
    }
};
