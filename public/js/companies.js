$(document).ready(function() {

	/**
	 * COMPAÑIAS
	 */

     $('#dataTables-example').DataTable({
                                 responsive: true
                             });
     
	 /**
	 * Registro de compañias
	 */
     
    // Se muestra una vista previa de la imagen de la campaña
    $( '#fileLogoCompany' ).on('change', function(event) {
        event.preventDefault();

		var fileName      = this.files[0].name
		var fileExtension = fileName.split('.').pop();
		var allowedFiles  = [ "jpg", "jpeg", "png", "gift", "bmp" ];
        
        var allowFile = $.inArray( fileExtension, allowedFiles );

        var fileContent = $( '#fileLogoCompany' );

        if( allowFile != -1 ){
        	if (this.files && this.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (e) {
                    var img = new Image;

                    img.onload = function(){

                        fileContent.attr({
                            originalWidth: img.width,
                            originalHeight: img.height
                        });
                    }

                    img.src = reader.result;

	                $('#imgLogoCompany').attr('src', e.target.result);
	            }
	            reader.readAsDataURL( this.files[0] );
	        }
        }

    });

    // Se crea un método para validar las dimensiones de la imagen
    $.validator.addMethod( 'imageDimentions', function(value, element, params) {
        console.log( element );
        if( value != "" ){
            var width = $( element ).attr( 'originalWidth' );
            var height = $( element ).attr( 'originalHeight' );

            var proportion = width/height;
            
            console.log( proportion );

            if( proportion == 1 ){
                return true;
            }else{
                return false;
            }

        }else{
            return true;
        }
        
    }, 'Se requiere que la imagen sea cuadrada.');


    /**
	 * VALIDACIÓN DEL FORMULARIO DE REGISTRO
	 */
	 $('#formCompany').validate({
        ignore: [],
        rules :{
            company_name: {
                required  : true
            },
            fileLogoCompany: {
				required  : true,
				extension: "png|jpeg|jpg|gift|bmp",
                imageDimentions : true
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
                required  : true
            },
            confirm_password: {
                required  : true,
                equalTo: "#password"
            }
        },
        messages : {
            company_name: {
                required  : "Se requiere que escribas el nombre del cliente."
            },
            fileLogoCompany: {
                required  : "Es necesario que agreges una imagen.",
                extension  : "Se requiere una imagen png, jpeg, jpg, gift o bmp."
            },
            name: {
				required : "Se requiere un nombre para mostrar."
            },
            username: {
                required  : "Se requiere un nombre de usuario.",
                remote  : "El nombre de usuario no se encuentra registrado."
            },
            email: {
                required  : "Se requiere un correo electr&oacute;nico.",
                email     : "El correo electr&oacute;nico no es v&aacute;lido."
            },
            password: {
                required  : "Es necesario que escribas una contrase&ntilde;a."
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
	$('#deleteCompanyModal').on('show.bs.modal', function (event) {
		var button      = $( event.relatedTarget ); 
		var company_id  = button.data('company_id');

		var modal     = $(this)
		modal.find( '#btnDeleteCompany' ).attr( 'company_id', company_id );

		// Nombre de la pantalla
		var companyName = $( "#tdCompanyName" + company_id ).html();

		$( "#modalCompanyName" ).html( companyName );

	});

	// Se elimina una campaña
	$( "#btnDeleteCompany" ).on('click', function(event) {
		event.preventDefault();
		var company_id = $( this ).attr( 'company_id' );

        $.ajax({
        	url: 'deleteCompany',
        	type: 'POST',
        	dataType: 'json',
        	data: { company_id: company_id },
        })
        .done(function( data ) {
        	
        	if( data == true ){
        		$( "#deleteCompanyModal" ).modal( "hide" );
        		window.location.reload();
        	}
        })
        .fail(function() {
        	console.log("error");
        })
        .always(function() {
        	console.log("complete");
        });
        

	});
    /**
     * Actualizar
     */
    
    /**
     * VALIDACIÓN DEL FORMULARIO DE REGISTRO
     */
     $('#formUpdateCompany').validate({
        ignore: [],
        rules :{
            company_name: {
                required  : true
            },
            fileLogoCompany: {
                extension: "png|jpeg|jpg|gift|bmp",
                imageDimentions : true
            }
        },
        messages : {
            company_name: {
                required  : "Se requiere que escribas el nombre del cliente."
            },
            fileLogoCompany: {
                extension  : "Se requiere una imagen png, jpeg, jpg, gift o bmp."
            }
        }
    });

    // Se edita una compañia/cliente
    $( ".dataTable_wrapper" ).on('click', '.btn-edit-company', function(event) {
        event.preventDefault();
        
        var company_id = $( this ).attr( 'company_id' );

        $.ajax({
            url: 'getCompanyInfo',
            type: 'POST',
            dataType: 'json',
            data: { company_id: company_id },
        })
        .done(function( data ) {
            $( "#company_id" ).val( data.company_id );
            $( "#company_name" ).val( data.name );
            $( "#imgLogoCompany" ).attr( 'src', data.logo_src );

            $( "#divEditCompany" ).show();
            $( "#divCompanies" ).hide();
        })
        .fail(function() {
            console.log("error");
        });

    });

    /**
     * Boton para cancelar la edición
     */
    $( "#btnCancelCompany" ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */
        var validator = $( "#formUpdateCompany" ).validate();
        validator.resetForm();

        $( "#company_name" ).val( '' );
        $( "#imgLogoCompany" ).attr('src', 'img/img_icon.png');

        $( "#divEditCompany" ).hide();
        $( "#divCompanies" ).show();
    });


});