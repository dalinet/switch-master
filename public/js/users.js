$(document).ready(function() {

	/**
	 * USUARIOS
	 */

	 /**
	 * Registro de compañias
	 */

    /**
	 * VALIDACIÓN DEL FORMULARIO DE REGISTRO
	 */
	 $('#formUser').validate({
        ignore: [],
        rules :{
            selectCompanies: {
                required  : true
            },
            name: {
				required : true
            },
            username: {
                required  : true,
                remote: {
                    url: "checkUserAvailability",
                    type: "post",
                    data: {
                        username: function() {
                            return $( "#username" ).val();
                        }
                    }
                }
            },
            email: {
                required  : true,
                email     : true
            },
            password: {
                required  : true,
                minlength : 6
            },
            confirm_password: {
                required  : true,
                equalTo: "#password"
            }
        },
        messages : {
            selectCompanies: {
                required  : "Se requiere que escribas el nombre del cliente."
            },
            name: {
				required : "Se requiere un nombre para mostrar."
            },
            username: {
                required  : "Se requiere un nombre de usuario.",
                remote  : "El nombre de usuario ya se encuentra registrado."
            },
            email: {
                required  : "Se requiere un correo electr&oacute;nico.",
                email     : "El correo electr&oacute;nico no es v&aacute;lido."
            },
            password: {
                required  : "Es necesario que escribas una contrase&ntilde;a.",
                minlength : "La contraseña debe contener como m&iacute;nimo 6 caracteres."
            },
            confirm_password: {
                required  : "Es necesario que confirme su contrase&ntilde;a.",
                equalTo: "Las contraseñas no coinciden."
            }
        }
    });
	
	/**
	 * Eliminar una Compañia
	 */
	
	// Se coloca en el modal la información según 
	// la campaña pulsada
	$('#deleteUserModal').on('show.bs.modal', function (event) {
        var button  = $( event.relatedTarget ); 
        var costumer_id = button.data('costumer_id');

		var modal     = $(this)
		modal.find( '#btnDeleteUser' ).attr( 'costumer_id', costumer_id );

		// Nombre de la pantalla
		var companyName = $( "#tdUserName" + costumer_id ).html();

		$( "#modalUserName" ).html( companyName );

	});

	// Se elimina una campaña
	$( "#btnDeleteUser" ).on('click', function(event) {
		event.preventDefault();
		var costumer_id = $( this ).attr( 'costumer_id' );

        $.ajax({
        	url: 'deleteUser',
        	type: 'POST',
        	dataType: 'json',
        	data: { costumer_id: costumer_id },
        })
        .done(function( data ) {
        	
        	if( data == true ){
        		$( "deleteUserModal" ).modal( "hide" );
        		window.location.reload();
        	}
        })
        .fail(function() {
        	console.log("error");
        });

	});

    /**
     * Se envia un correo al usuario con su contraseña
     */
        
    $('#sendPasswordModal').on('show.bs.modal', function (event) {
        var button  = $( event.relatedTarget ); 
        var costumer_id = button.data('costumer_id');

        var modal     = $(this)
        modal.find( '#btnSendPassword' ).attr( 'costumer_id', costumer_id );

        // Nombre de la pantalla
        var companyName = $( "#tdName" + costumer_id ).html();

        $( "#modalSPUserName" ).html( companyName );

    });

    // Se envia el correo electrónico
    $( "#btnSendPassword" ).on('click', function(event) {
        event.preventDefault();
        var costumer_id = $( this ).attr( 'costumer_id' );

        $.ajax({
            url: 'sendUserPassword',
            type: 'POST',
            dataType: 'json',
            data: { costumer_id: costumer_id },
        })
        .done(function( data ) {


            if( data == 1 ){
                $( "#sendPasswordModal" ).modal( "hide" );
                $( "#alertSendEmailError" ).hide();
                $( "#alertSendEmailSuccess" ).show();
            }else{
                $( "#alertSendEmailSuccess" ).hide();
                $( "#alertSendEmailError" ).show();
            }
        })
        .fail(function() {
            console.log("error");
        });

    });

    /**
     * DataTable
     */
    $('#dataTables-example').DataTable({
                                responsive: true
                            });

    // Se edita un usuario
    $( ".dataTable_wrapper" ).on('click', '.btn-edit-user', function(event) {
        event.preventDefault();
        
        var costumer_id = $( this ).attr( 'costumer_id' );

        $.ajax({
            url: 'getUserInfo',
            type: 'POST',
            dataType: 'json',
            data: { costumer_id: costumer_id },
        })
        .done(function( data ) {

            $( "#divUsersList" ).hide();
            $( "#divEditUser" ).show();

            $( "#selectCompanies" ).val( data.company_id ).attr('disabled', 'disabled');
            $( "#costumer_id" ).val( data.costumer_id );
            $( "#name" ).val( data.name );
            $( "#username" ).val( data.user_name );
            $( "#email" ).val( data.email );

        })
        .fail(function() {
            console.log("error");
        });

    });

    $( "#btnCancelUpdtUser" ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $( "#divUsersList" ).show();
        $( "#divEditUser" ).hide();

    });

    /**
     * VALIDACIÓN DEL FORMULARIO DE REGISTRO
     */
     $('#formUpdateUser').validate({
        ignore: [],
        rules :{
            selectCompanies: {
                required  : true
            },
            name: {
                required : true
            },
            email: {
                required  : true,
                email     : true
            }
        },
        messages : {
            selectCompanies: {
                required  : "Se requiere que escribas el nombre del cliente."
            },
            name: {
                required : "Se requiere un nombre para mostrar."
            },
            email: {
                required  : "Se requiere un correo electr&oacute;nico.",
                email     : "El correo electr&oacute;nico no es v&aacute;lido."
            }
        }
    });

});