$(document).ready(function() {

	/**
	 * PANTALLAS
	 */

	// Botón de editar una pantalla
    $( ".btn-edit-screen" ).on('click', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	var screen_id = $( this ).children().attr( "screen_id" );

    	$.ajax({
    		url: '/getscreen',
    		type: 'POST',
    		dataType: 'json',
    		data: { screen_id: screen_id },
    	})
    	.done(function( data ) {
    		$( "#screen_name" ).val( data.name );
    		$( "#location" ).val( data.location );
    		$( "#latitude" ).val( data.latitude );
    		$( "#longitude" ).val( data.longitude );

    		$( "#phase" ).val( data.id_phase );

    		$( "#btnSubmitScreen" ).hide();
    		$( "#btnUpdateScreen" ).attr( 'screen_id', data.screen_id ).removeClass('hide');

    		$( "#addScreenModal" ).modal( "show" ); 
    	})
    	.fail(function() {
    		console.log("error");
    	});
    	
    });

    // Botón confirmar eliminar una pantalla
	$('#deleteModal').on('show.bs.modal', function (event) {
		var button   = $( event.relatedTarget ); 
		var screen_id = button.data('screen_id');

		var modal     = $(this)
		modal.find( '#btnDeleteScreen' ).attr( 'screen_id', screen_id );

		// Nombre de la pantalla
		var screenName = $( "#tdScreenName" + screen_id ).html();

		$( "#modalScreenName" ).html( screenName );

	}) 

	// Botón para registrar una pantalla
	$( "#btnSubmitScreen" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var fdScreen = new FormData( $('#formScreen')[0] );

		$.ajax({
			url: 'addscreen',
			type: 'POST',
			dataType: 'json',
			data: fdScreen,
			async: false,
            cache: false,
            contentType: false,
            processData: false,
		})
		.done(function( data ) {

			if( data == true ){
				$( "#addScreenModal" ).modal( "hide" ); 
				window.location.reload();
			}else{
				console.log( "something wrong!" );
			}	
		})
		.fail(function() {
			console.log("error");
		});

	});

	// Botón actualizar una pantalla
	$( "#btnUpdateScreen" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var screen_id   = $( this ).attr( "screen_id" );
		var screen_name = $( "#screen_name" ).val();
		var location    = $( "#location" ).val();
		var latitude    = $( "#latitude" ).val();
		var longitude   = $( "#longitude" ).val();
		var phase       = $( "#phase" ).val();

		$.ajax({
			url: 'updatescreen',
			type: 'POST',
			dataType: 'json',
			data: { screen_id   : screen_id,
					screen_name : screen_name,
					location    : location,
					latitude    : latitude,
					longitude   : longitude,
					phase       : phase
				  },
		})
		.done(function( data ) {

			if( data == true ){
				$( "#addScreenModal" ).modal( "hide" ); 
				window.location.reload();
			}else{
				console.log( "something wrong!" );
			}	
		})
		.fail(function() {
			console.log("error");
		});
		
	});

	// Botón eliminar un pantalla
	$( "#btnDeleteScreen" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var screen_id = $( this ).attr( "screen_id" );

		$.ajax({
			url: 'delscreen',
			type: 'POST',
			dataType: 'json',
			data: { screen_id: screen_id },
		})
		.done(function( data ) {
			if( data == true){
				$( "#deleteModal" ).modal( "hide" )
				window.location.reload();
			}else{
				console.log('something wrong!');
			}
		})
		.fail(function() {
			console.log("error");
		});
		
	});

	// Botón reset formulario de  registro pantalla
	$( "#btnResetScreen" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$( "#formScreen" )[0].reset();

		$( "#btnSubmitScreen" ).show();
    	$( "#btnUpdateScreen" ).addClass('hide');

    	$( "#addScreenModal" ).modal( "hide" ); 
	});

	/**
	 * CAMPAÑAS
	 */

	 // Botón para agregar una compañia
	$( "#btnAddCampaign" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$( "#addCampaignModal" ).modal( "show" );  
	});

	/**
	 * VALIDACIÓN
	 */
	 $('#formCompany').validate({
        ignore: [],
        rules :{
            company_name: {
                required  : true
            },
            fileLogoCompany: {
                required  : true,
                extension: "png|jpeg|jpg|gift|bmp"
            }
        },
        messages : {
            company_name: {
                required  : "Es necesario que escribas un nombre."
            },
            fileLogoCompany: {
                required  : "Es necesario que agreges una imagen.",
                extension  : "Se requiere una imagen png, jpeg, jpg, gift o bmp."
            }
        },
        submitHandler: function() {
        	var name = $( "#company_name" ).val();
			var logo = $( "#fileLogoCompany" ).get(0).files[0];;

			var fdCompany = new FormData();
			fdCompany.append( 'name', name );
			fdCompany.append( 'logo', logo );

			$.ajax({
				url: '/addcompany',
				type: 'POST',
				dataType: 'json',
				data: fdCompany,
				async: false,
	            cache: false,
	            contentType: false,
	            processData: false,
			})
			.done(function( data ) {
				if( data.status == true ){
					$( "#addCompanyModal" ).modal( "hide" );

					$('#selectCompanies').children('option:not(:first)').remove();

					$.each( data.companies, function( key, value ) {
						$('#selectCompanies')
				         .append($("<option></option>")
				         .attr( "value", data.companies[ key ].company_id )
				         .text( data.companies[ key ].name ));
					});
				}
			})
			.fail(function() {
				console.log("error");
			});
        }
    });
	
	// Boton para cancelar el registro de una compañia
	$( "#btnCacelCompany" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$( "#addCompanyModal" ).modal( "hide" );
		$( "#formCompany" )[0].reset();
		$( "#imgLogoCompany" ).attr('src', 'img/img_icon.png');
	});

	// Boton para registrar una compañia
	$( "#btnAddCompany" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		$( "#formCompany" ).submit();
	});

	// Preview de la imagen de la compañia
    $( '#fileLogoCompany' ).on('change', function(event) {
        event.preventDefault();

		var fileName      = this.files[0].name
		var fileExtension = fileName.split('.').pop();
		var allowedFiles  = [ "jpg", "jpeg", "png", "gift", "bmp" ];
        
        var allowFile = $.inArray( fileExtension, allowedFiles );

        if( allowFile != -1 ){
        	if (this.files && this.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (e) {
	                $('#imgLogoCompany').attr('src', e.target.result);
	            }
	            reader.readAsDataURL( this.files[0] );
	        }
        }

    });


});