$(document).ready(function() {
	
	/**
	 * Administración de URL's
	 */
	
	/**
	 * Selección del cliente
	 */
	$( "#selectCompany" ).on('change', function(event) {
	 	event.preventDefault();
	 	/* Act on the event */

	 	var companyId = $( this ).val();

	 	$.ajax({
	 		url: 'getCampaignsByCompany',
	 		type: 'POST',
	 		dataType: 'json',
	 		data: { companyId: companyId },
	 	})
	 	.done(function( data ) {
	 		$( '#selectCampaign' ).children('option:not(:first)').remove();

	 		if( data.status == true ){
	 			$( '#selectCampaign' ).removeAttr('disabled');

	 			// Se agregan las campañas al select
	 			$.each( data.campaigns, function(key, value) {
	 			    $('#selectCampaign')
	 			        .append( $("<option></option>" )
		 			        .attr( "value", data.campaigns[ key ].campaign_id )
		 			        .text( data.campaigns[ key ].name ) 
	 			        );
	 			});

	 		}else{
	 			$( '#selectCampaign' ).attr('disabled', 'disabled');
	 		}

	 		$( "#urls-in-screens" ).html( "" );
	 		$( "#urls-in-campaign" ).html( "" );
	 		$( "#urls-selecteds" ).html( "" );
	 		$( "#urlsInScreen" ).html( "0" );
	 		$( "#selectScreen" ).val( $("#selectScreen option:first").val() ).attr('disabled', 'disabled');

	 	})
	 	.fail(function() {
	 		console.log("error");
	 	});
	 	
	 });

	/**
	 * Selección de la campaña
	 */
	$( "#selectCampaign" ).on('change', function(event) {
	 	event.preventDefault();
	 	/* Act on the event */
        
        var campaignId = $( this ).val();

        $( "#selectScreen" ).val( "" );

        $.ajax({
        	url: 'getUrlScreenByCampaign',
        	type: 'POST',
        	dataType: 'json',
        	data: { campaign_id: campaignId },
        })
        .done(function( data ) {
        	$( "#urls-in-campaign" ).html( "" );
        	$( "#urls-selecteds" ).html( "" );

        	// Se resetea el número de URLs por pantalla
        	$( "#selectScreen option" ).each(function(index, el) {
        		$( this ).attr( 'numURLs', 0 );
        	});

        	if( data.status == true ){
				iNumURLs       = data.urls.length;
				// iAvailableURLs = 0;

				// Se habilita la selección de una pantalla
				$( "#selectScreen" ).removeAttr( 'disabled' );

        		$( "#urls-in-screens" ).html( "" );
        		$( "#numURLs" ).html( iNumURLs );

        		$.each( data.urls , function(index, val) {
        			screenId = data.urls[ index ].screen_id;

        			if( screenId == 0){
	        			$( "#urls-in-campaign" ).append( 
														$( "<a></a>" )
														.addClass('list-group-item lg-small')
														.attr( "urlid", data.urls[ index ].campaign_url_id )
														.attr( "shorturl", data.urls[ index ].shorturl )
														.html( data.urls[ index ].url )
														.append( $( "<span></span>" )
																 .addClass('glyphicon glyphicon-plus pull-right cursor-pointer add-url-to-screen')
														)
						 							);

	        			// iAvailableURLs++;

        			}else{
	        			$( "#urls-in-campaign" ).append( 
														$( "<a></a>" )
														.addClass('list-group-item lg-small')
														.attr( "urlid", data.urls[ index ].campaign_url_id )
														.attr( "shorturl", data.urls[ index ].shorturl )
														.html( data.urls[ index ].url )
														.append( $( "<span></span>" )
																 .addClass('glyphicon glyphicon-plus pull-right cursor-pointer add-url-to-screen')
														)
						 							);

	        			/*// Se coloca el número de urls a cada pantalla
	        			iNumULSinScreen = $( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs' );

	        			if( iNumULSinScreen === undefined ){
	        				iNumULSinScreen = 0;
	        			}

	        			iNumULSinScreen++

	        			$( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs', iNumULSinScreen );

				 		// Si no existe el contenedor de la pantalla se crea
				 		divConteiner = $( "div[screenid='" + screenId + "']" ).length;

				 		if( divConteiner == 0){
				 			$( "#urls-in-screens" ).append( $( "<div></div>" )
					 										.addClass('col-lg-6')
					 										.append( $( "<div></div>" )
					 												 .addClass('well url-screen')
					 												 .append("<h4>" + data.urls[ index ].screen_name + " / " + data.urls[ index ].screen_location + "</h4>")
					 												 .append( $( "<div></div>" )
					 												 		  .attr('screenid', screenId )
					 												 		  .addClass('list-group')
					 												 		  .css({
					 												 		  	'height'     : '156px',
					 												 		  	'overflow-y' : 'scroll'
					 												 		  })
					 												  )		
					 										)
					 							   );
				 		}

				 		// Se agrega la URL como un item
	 		        	$( "div[screenid='" + screenId + "']" ).append(
	 		        												$( "<a></a>" )
	 																.addClass('list-group-item lg-small')
	 																.attr( "urlid", data.urls[ index ].campaign_url_id )
	 																.attr( "shorturl", data.urls[ index ].shorturl )
	 																.html( "http://.../" + data.urls[ index ].shorturl )
	 																.append( $( "<span></span>" )
	 																		 .addClass('glyphicon glyphicon-minus pull-right cursor-pointer remove-screen-url')
	 																)
	 		        											);*/
        			}
        			
        		});
				
				// URLs en Pantallas
				$.each( data.urlsInScreen , function(index, val) {
					screenId = data.urlsInScreen[ index ].screen_id;
					// Se coloca el número de urls a cada pantalla
        			iNumULSinScreen = $( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs' );

        			if( iNumULSinScreen === undefined ){
        				iNumULSinScreen = 0;
        			}

        			iNumULSinScreen++

        			$( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs', iNumULSinScreen );
        															// .html( $( "#selectScreen option:eq(" + screenId + ")" ).html() + "| SELECCIONADA" );

			 		// Si no existe el contenedor de la pantalla se crea
			 		divConteiner = $( "div[screenid='" + screenId + "']" ).length;

			 		if( divConteiner == 0){
			 			$( "#urls-in-screens" ).append( $( "<div></div>" )
				 										.addClass('col-lg-6')
				 										.append( $( "<div></div>" )
				 												 .addClass('well url-screen')
				 												 .append( $( "<button></button>" )
				 												 		.addClass('btn btn-primary btn-del-screen')
				 												 		.attr('type', 'button')
				 												 		.html( "Eliminar pantalla" )
				 												 )
				 												 .append("<h4>" + data.urlsInScreen[ index ].name + " / " + data.urlsInScreen[ index ].location + "</h4>")
				 												 .append( $( "<div></div>" )
				 												 		  .attr('screenid', screenId )
				 												 		  .addClass('list-group')
				 												 		  .css({
				 												 		  	'height'     : '156px',
				 												 		  	'overflow-y' : 'scroll'
				 												 		  })
				 												  )		
				 										)
				 							   );
			 		}

			 		// Se agrega la URL como un item
 		        	$( "div[screenid='" + screenId + "']" ).append(
 		        												$( "<a></a>" )
 																.addClass('list-group-item lg-small')
 																.attr( "urlid", data.urlsInScreen[ index ].campaign_url_id )
 																.attr( "shorturl", data.urlsInScreen[ index ].url )
 																.html( "http://.../" + data.urlsInScreen[ index ].url )
 																.append( $( "<span></span>" )
 																		 .addClass('glyphicon glyphicon-minus pull-right cursor-pointer remove-screen-url')
 																)
 		        											);
				});

				// $( "#availableURls" ).html( iAvailableURLs );
        	}else{
        		// Se deshabilita la selección de una pantalla
        		$( "#selectScreen" ).attr('disabled', 'disabled');

        		// Se agrega un msj
        		$( "#urls-in-campaign" ).html( "Esta campaña no cuenta con URLs Registradas" );

        		// Se limpia el contenedor de las ULR's en la pantalla
        		$( "#urls-in-screens" ).empty();
        	}

        })
        .fail(function() {
        	console.log("error");
        });
        
	 });

	/**
	 * Botón para agregar todas las urls a todas las pantallas
	 */
	$( "#btnAddToAllScreens" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( "#selectScreen option" ).each(function(index, el) {
			$( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs', 0 );

			var screenId = $( this ).val();

			if( screenId != "" ){
				var screenName = $( "#selectScreen option:eq(" + screenId + ")" ).text();
				
				divConteiner   = $( "div[screenid='" + screenId + "']" ).length;

		 		// Si no existe el contenedor de la pantalla se crea
		 		if( divConteiner == 0){
		 			$( "#urls-in-screens" ).append( $( "<div></div>" )
			 										.addClass('col-lg-6')
			 										.append( $( "<div></div>" )
			 												 .addClass('well url-screen')
			 												 .append("<h4>" + screenName + "</h4>")
			 												 .append( $( "<div></div>" )
			 												 		  .attr('screenid', screenId )
			 												 		  .addClass('list-group')
			 												 		  .css({
			 												 		  	'height'     : '156px',
			 												 		  	'overflow-y' : 'scroll'
			 												 		  })
			 												  )		
			 										)
			 							   );
		 		}else{
		 			$( "div[screenid='" + screenId + "']" ).html( "" );
		 		}

		 		// Se agregan las URLs
		        $( "#urls-in-campaign a" ).each(function(index, el) {
		        	// Se coloca el número de urls a cada pantalla
		        	iNumULSinScreen = $( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs' );

		        	if( iNumULSinScreen === undefined ){
		        		iNumULSinScreen = 0;
		        	}

		        	iNumULSinScreen++

		        	$( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs', iNumULSinScreen );
		        	
					urlId    = $( this ).attr('urlid');
					shortUrl = $( this ).attr('shorturl');

		        	$( "div[screenid='" + screenId + "']" ).append(
		        												$( "<a></a>" )
																.addClass('list-group-item lg-small')
																.attr( "urlid", urlId )
																.attr( "shorturl", shortUrl )
																.html( "http://.../" + shortUrl )
																.append( $( "<span></span>" )
																		 .addClass('glyphicon glyphicon-minus pull-right cursor-pointer remove-screen-url')
																)
		        											);
		        	
		        });
			}
		});

	});
	
	/**
	 * Se agrega una URL al contenedor
     * de URLs en Pantallas
	 */
	$( "#btn-add-urls-screen" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var screenId   = $( "#selectScreen" ).val();
		var screenName = $( "#selectScreen option:selected" ).text();
		
		divConteiner   = $( "div[screenid='" + screenId + "']" ).length;

 		// Si no existe el contenedor de la pantalla se crea
 		if( divConteiner == 0){
 			$( "#urls-in-screens" ).append( $( "<div></div>" )
	 										.addClass('col-lg-6')
	 										.append( $( "<div></div>" )
	 												 .addClass('well url-screen')
	 												 .append("<h4>" + screenName + "</h4>")
	 												 .append( $( "<div></div>" )
	 												 		  .attr('screenid', screenId )
	 												 		  .addClass('list-group')
	 												 		  .css({
	 												 		  	'height'     : '156px',
	 												 		  	'overflow-y' : 'scroll'
	 												 		  })
	 												  )		
	 										)
	 							   );
 		}else{
 			$( "div[screenid='" + screenId + "']" ).html( "" );
 		}

 		// Se agregan las URLs
        $( "#urls-selecteds a" ).each(function(index, el) {
        	urlId = $( this ).attr('urlid');
        	shortUrl = $( this ).attr('shorturl');

        	$( "div[screenid='" + screenId + "']" ).append(
        												$( "<a></a>" )
														.addClass('list-group-item lg-small')
														.attr( "urlid", urlId )
														.attr( "shorturl", shortUrl )
														.html( "http://.../" + shortUrl )
														.append( $( "<span></span>" )
																 .addClass('glyphicon glyphicon-minus pull-right cursor-pointer remove-screen-url')
														)
        											);
        	
        });

        // Se limpian las urls agregas del menú de pre-carga
        $( "#urls-selecteds" ).html( "<p>Han sido agregadas las URLs a la pantalla seleccionada.</p>" );
	});
	
	/**
	 * Se remueve una URL del
     * contenedor de URLs en las 
     * pantallas
	 */
	$( "#urls-in-screens" ).on('click', '.remove-screen-url', function(event) {
		event.preventDefault();
		/* Act on the event */
		screenId        = $( this ).parent().parent().attr( 'screenid' );
		screenContainer = $( this ).parent().parent().parent().parent();
		noChilds        = $( this ).parent().parent().children().length;
		urlId           = $( this ).parent().attr( 'urlid' );

		// Se remueve la URL
		$( this ).parent().remove();

		// Se muestra la URL disponible de nuevo
		var elemtInCampaign = $( "#urls-in-campaign a[urlid='" + urlId + "']" );
		elemtInCampaign.show();

		// Se incrementa el número de URLs disponibles
		/*var availableURls = parseInt( $( "#availableURls" ).html() );
		availableURls++;
		$( "#availableURls" ).html( availableURls )*/

		// Se decrementa el número de URLs en pantalla
		var numURLsInScreen = $( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs' );
		numURLsInScreen--;
		$( "#selectScreen option:eq(" + screenId + ")" ).attr( 'numURLs', numURLsInScreen );
		$( "#selectScreen" ).trigger('change');

		// Si no existe ninguna URL
		// en el contenedor se remueve
		if( noChilds == 1){
			screenContainer.remove();
		}
	});
	
	/**
	 * Se agrega una URL a una campaña
	 */
	$( "#urls-in-campaign" ).on('click', '.add-url-to-screen', function(event) {
		event.preventDefault();

		var screenId = $( "#selectScreen" ).val();

		if( screenId == "" ){
			$( "#pInfoMsg" ).html( "Por favor selecciona una pantalla." );
			$( "#modalSelectScreenInfo" ).modal( "show" );
		}else{
			var urlId    = $( this ).parent().attr( 'urlid' );
			var shortUrl = $( this ).parent().attr( 'shorturl' );

			// Se revisa si existe el link en el contenedor de pantallas
			var urlsInScreenExist = $( "#urls-selecteds a[urlid='" + urlId + "']" ).length;

			if( urlsInScreenExist == 0 ){
				// $( this ).parent().hide();

				$( "#urls-selecteds" ).append( 
												$( "<a></a>" )
												.addClass('list-group-item lg-small')
												.attr( "urlid", urlId )
												.attr( "shorturl", shortUrl )
												.html( "http://.../" + shortUrl )
												.append( $( "<span></span>" )
														 .addClass('glyphicon glyphicon-minus pull-right cursor-pointer remove-prescreen-url')
												)
				 							);

				// Se decrementan las ULRs disponibles
				/*var availableURls = parseInt( $( "#availableURls" ).html() );
				availableURls--;
				$( "#availableURls" ).html( availableURls );*/

				// Se incrementan las ULRs en Pantalla
				var urlsInScreen = parseInt( $( "#urlsInScreen" ).html() );
				urlsInScreen++;
				$( "#urlsInScreen" ).html( urlsInScreen );
			}

		}

	});

	/**
	 * Se remueve una URL del contenedor
	 * la selección de Pantallas
	 */
	$( "#urls-selecteds" ).on('click', '.remove-prescreen-url', function(event) {
		event.preventDefault();
		/* Act on the event */
		var screenId = $( "#selectScreen" ).val();
		var urlId    = $( this ).parent().attr( 'urlid' );urlsInScreen

		// Se decrementa el número de URLs en pantalla
		var urlsInScreen = parseInt( $( "#urlsInScreen" ).html() );
		urlsInScreen--;
		$( "#urlsInScreen" ).html( urlsInScreen );

		// Se incrementan las URLs disponibles
		/*var availableURls = parseInt( $( "#availableURls" ).html() );
		availableURls++;
		$( "#availableURls" ).html( availableURls );*/

		// Se remueve la URL del contenedor de URLs en Pantalla
		var aURL = $( "div[screenid='" + screenId + "'] a[urlid='" + urlId + "']" );

		if( aURL.length == '1' ){
			aURL.remove();
		}

		// Se remueve el elemento
		$( this ).parent().remove();

		// Se muestra la URL en el contenedor principal
		var elemtInCampaign = $( "#urls-in-campaign a[urlid='" + urlId + "']" );
		elemtInCampaign.show();

	});
	

	/**
	 * Selección de una pantalla
	 */
	$( "#selectScreen" ).on('change', function(event) {
		event.preventDefault();
		/* Act on the event */
		var screenId = $( this ).val();

		// Se limpian las URLs del menú 
		// de pre-carga
		$( "#urls-selecteds" ).html( "" );

		// Se muestran de nuevo las URL's
		// pre-cargadas
		$( "#urls-selecteds a" ).each(function(index, el) {
        	urlId = $( this ).attr('urlid');

        	elemtInCampaign = $( "#urls-in-campaign a[urlid='" + urlId + "']" );
			elemtInCampaign.show();
        });
		
		// Se muestran la cantidad de URLs
		// que tiene la pantalla
		iNumURls = $( this ).children( 'option:selected' ).attr( "numurls" );
		
		if( iNumURls === undefined ){
			$( "#urlsInScreen" ).html( "0" );
		}else{
			$( "#urlsInScreen" ).html( iNumURls );
		}

		// Se obtienen las ULRs en la pantalla
		var divScreenContainer = $( "div[screenid='" + screenId + "']" ).length;

		if( divScreenContainer != 0 ){
			var urlsInScreen = $( "div[screenid='" + screenId + "']" );

			urlsInScreen.each(function(index, el) {
				// var element = $( this ).children('a').children('span').removeClass( 'remove-screen-url' ).addClass( 'remove-prescreen-url' );
				var element = $( this ).children('a').clone();
				element.children('span').removeClass( 'remove-screen-url' ).addClass( 'remove-prescreen-url' );

				$( "#urls-selecteds" ).append( element );
			});

			// urlsInScreen.replace( "remove-screen-url" , "remove-prescreen-url");

			// $( "#urls-selecteds" ).html( urlsInScreen );
		}
		
	});

	/**
	 * Botón para confirmar si se
	 * desea guardar la relación URLs/Pantallas
	 */
	$( "#btnConfirmSaveRULs" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var campaignId = $( "#selectCampaign" ).val();
		var NumScreens = $( "#urls-in-screens" ).children().length;

		if( campaignId != "" ){
			if( NumScreens != 0 ){
				$( "#modalConfirmSave" ).modal( "show" );
			}else{
				$( "#pInfoMsg" ).html( "Por favor añada URLs por lo menos a una pantalla." );
				$( "#modalSelectScreenInfo" ).modal( "show" );
			}
		}else{
			$( "#pInfoMsg" ).html( "Por favor selecciona una campaña y añade URLs por lo menos a una pantalla." );
			$( "#modalSelectScreenInfo" ).modal( "show" );
		}

	});

	/**
	 * Se guardan las URLs
	 * en las pantallas seleccionadas
	 */
	$( "#btnSaveURLsScreens" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var campaignId  = $( "#selectCampaign" ).val();
		var aScreenURLs = new Array();

        $( "#urls-in-screens .url-screen .list-group" ).each(function(index, el) {
			var screenId = $( this ).attr( 'screenid' );
			var aScreen  = {};
			var aURLIds  = new Array(); 
			var $this    = $( this );

        	$this.children('a').each(function(index, el) {
        		urlId = $( this ).attr( 'urlid' );
        		aURLIds.push( urlId );
        	});

        	aScreen.screen_id = screenId;
        	aScreen.urls      = aURLIds;

        	aScreenURLs.push( aScreen );
        });

        var jsonScreenURLs = JSON.stringify( aScreenURLs );

        $.ajax({
        	url: 'setURLsToScreens',
        	type: 'POST',
        	dataType: 'html',
        	data: { screensURLs: jsonScreenURLs,
        	 		campaign_id: campaignId },
        })
        .done(function( data ) {
        	if( data == "true" ){
        		$( "#modalConfirmSave" ).modal( "hide" );
        		$('html, body').animate({scrollTop: 0}, 600, 'swing');
        		$( "#selectCampaign" ).val('').trigger('change');
        		$( "#urls-in-screens" ).html("");
        		$( "#alert-urls-screens-saved" ).show();
        	}
        })
        .fail(function() {
        	console.log("error");
        });
        
	});
	
	/**
	 * Se envia el email al programador
	 */
	$('#formSendProgrammerMail').validate({
	    ignore: [],
	    rules :{
	        programmer_email: {
	            required  : true,
	            email     : true
	        }
	    },
	    messages : {
	        programmer_email: {
	            required  : "Es necesario que escribas un correo electrónico.",
	            email     : "Por favor escribe un correo electrónico válido."
	        }
	    },
	    submitHandler: function() {
	    	var iCampaignId   = $( "#selectCampaign" ).val();
	    	var sEmailAddress = $( "#programmer_email" ).val();
	    	var sMsg          = $( "#programmer_msg" ).val();

	    	$.ajax({
	    		url: '/sendProgrammerMail',
	    		type: 'POST',
	    		dataType: 'json',
	    		data: { campaign_id   : iCampaignId,
	    				email_address : sEmailAddress,
	    				msg           : sMsg
	    			},
	    	})
	    	.done(function( data ) {
	    		if( data == '1' ){
	    			console.log("correo enviado");
	    		}
	    	})
	    	.fail(function() {
	    		console.log("error");
	    	});
	    }
	});

	/**
	 * Se eliminan todas las pantallas
	 */
	$( "#btnCleanAllScreens" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

		$( "#urls-in-screens" ).empty();
	});	

	$( "#urls-in-screens" ).on('click', '.btn-del-screen', function(event) {
		event.preventDefault();
		/* Act on the event */
		$( this ).parent().parent().remove();
	});
 });