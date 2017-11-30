$(document).ready(function() {

	/**
	 * CAMPAÑAS
	 */

	 /**
	 * Registro de campañas
	 */
	// DataTable

	var campaignsTable;


	function initTable(){
		console.log( "initTable" );
	    campaignsTable = $('#dataTables-example').dataTable({
											    responsive: true
										  	});
	}

	initTable();

	// Calendario
    $('.input-daterange').datepicker({
		format: 'yyyy-mm-dd',
		todayBtn: "linked",
		language: "es",
		todayHighlight: true
	});

	// Calendario para la búsqueda
    $('#findDateRange').datepicker({
		format: 'yyyy-mm-dd',
		todayBtn: "linked",
		language: "es",
		todayHighlight: true
	});


    $('.input-daterange  #campaign_start').datepicker('setStartDate', new Date());
    $('.input-daterange  #campaign_end').datepicker('setStartDate', new Date());

	// Se obtienen los usuarios según la compañia
	$( "#selectCompanies" ).on('change', function(event) {
	 	event.preventDefault();
	 	/* Act on the event */

	 	$( "#divUsersForCampaign" ).show();

	 	var company_id = $( this ).val();

    	$.ajax({
			url: '/getCompanyUsers',
			type: 'POST',
			dataType: 'json',
			data: { company_id: company_id },
		})
		.done(function( data ) {
			if( data.status == true ){

				$( "#pUsers" ).html( "" );

				$.each( data.users, function(key, value) {
					costumerId   = data.users[ key ].costumer_id;
					costumerName = data.users[ key ].name;

					$( "#pUsers" ).append($( '<label class="checkbox-inline"> <input type="checkbox" class="campaign-users-group" id="user' + costumerId + '" name="user' + costumerId + '" value="' + costumerId + '"> <b>' + costumerName + ' </b></label>' ));
				});

				/* Se agregar las reglas y mensajes dinámicamente*/
	            $( '.campaign-users-group' ).each(function( index ){

	                var costumerName = $( this ).attr('name');

	                $( '[name="' + costumerName + '"]' ).rules("add", {
	                        require_from_group: [ 1, ".campaign-users-group" ],
	                        messages: {
	                           require_from_group: "Es necesario que selecciones por lo menos un usuario."
	                        }
	                    }
	                );

	            });

			}else{
				$( "#pUsers" ).html( "No hay usuarios registrados" );
			}
		})
		.fail(function() {
			console.log("error");
		});
	});

    // Se muestra una vista previa de la imagen de la campaña
    $( '#fileImgCampaign' ).on('change', function(event) {
        event.preventDefault();

		var fileName      = this.files[0].name
		var fileExtension = fileName.split('.').pop();
		var allowedFiles  = [ "jpg", "jpeg", "png", "gift", "bmp" ];

        var allowFile = $.inArray( fileExtension, allowedFiles );

        var fileContent = $( '#fileImgCampaign' );

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

	                $('#imgCampaign').attr('src', e.target.result);
	            }
	            reader.readAsDataURL( this.files[0] );
	        }
        }

    });



    // Se obtiene la dimensión de la imagen
    $( '#adFilesContainer' ).on('change', '.file-ad', function(event) {
        event.preventDefault();

		var fileName      = this.files[0].name
		var fileExtension = fileName.split('.').pop();
		var allowedFiles  = [ "jpg", "jpeg", "png", "gift", "bmp" ];

        var allowFile = $.inArray( fileExtension, allowedFiles );

        var fileContent = $( this );

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
	            }
	            reader.readAsDataURL( this.files[0] );
	        }
        }

    });

    // Se agrega dinámicamente [input=file]
    // para agregar fuentes
	$( "#btnAddFontFile" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var index = $( '#fontFIlesContainer p:last' ).attr( 'id' ).substring( 5 );
		index     = parseInt( index ) + 1;

        var clone = $( '#pFont0' ).clone();

        clone.attr( 'id', 'pFont' + index )
        	.find( '#fileFontCampaign0' )
        	.attr( 'name', 'fileFontCampaign' + index )
        	.attr( 'id', 'fileFontCampaign' + index );

        clone.find( 'label.error' ).remove();

        $( '#fontFIlesContainer' ).append( clone );

        // Se agrega la regla al validador
        $( '[name="fileFontCampaign' + index + '"]' ).rules("add", {
	            extension: "ttf|otf",
	            messages: {
	               extension  : "Se requiere una fuente de formato ttf o otf."
	            }
	        }
	    );

	});

	// Se crea un método para validar las dimensiones de la imagen
	$.validator.addMethod( 'imageDimentions', function(value, element, params) {
		if( value != "" ){
			var width = $( element ).attr( 'originalWidth' );
			var height = $( element ).attr( 'originalHeight' );

			if( width == 726 && height == 352 ){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}

	}, 'Las dimensiones de la imagen no son las necesarias ( 726 x 352 ).');


	// Se crea un método para validar las dimensiones de la imagen
	$.validator.addMethod( 'videoDimentions', function(value, element, params) {
		if( value != "" ){
			var tamano = $(element).get(0).files[0].size;

				return true;

		}else{
			return true;
		}

	}, 'Debe ingresar un video  ');

    // Video por default
	$.validator.addMethod( 'videoDefault', function(value, element, params) {
		if( value != "" &&  parseInt( $("#cant_linksVideo").val() )==0 ){
			return false;
		}else{
			return true;
		}

	}, 'Debe cargar un video por Default ');



    /**
	 * VALIDACIÓN DEL FORMULARIO DE REGISTRO
	 */
	 $('#formCampaign').validate({
        ignore: [],
        rules :{
            selectCompanies: {
                required  : true
            },
            campaign_name: {
                required  : true
            },
            fileImgCampaign: {
				required  : true,
				extension: "png|jpeg|jpg|gift|bmp",
				imageDimentions: true
            },
            fileFontCampaign0: {
                extension: "ttf|otf"
            },
            fileAdCampaign0: {
            	extension: "png|jpeg|jpg|gift|bmp",
            	imageDimentions: true
            },
            fileAdCampaignVid0: {
            	extension: "mp4",
            	videoDimentions:true
            },
            cant_links: {
				required : true,
				min      : 1,
				digits   : true
            },
            cant_linksVideo: {
				required : true,
				digits   : true
            },
            campaign_start: {
                required  : true
            },
            campaign_end: {
                required  : true
            }
        },
        messages : {
            selectCompanies: {
                required  : "Es necesario que selecciones un cliente."
            },
            campaign_name: {
                required  : "Es necesario que escribas un nombre para la campaña."
            },
            fileImgCampaign: {
                required  : "Es necesario que agreges una imagen.",
                extension  : "Se requiere una imagen png, jpeg, jpg, gift o bmp."
            },
            fileFontCampaign0: {
                extension  : "Se requiere una fuente de formato ttf o otf."
            },
            fileAdCampaignVid0:{
            	extension  : "Se requiere formato de video mov.",
            	required  : "Debe tener un video cargado"
            },
            cant_links: {
				required : "Es necesario que indiques un n&uacute;mero.",
				min : "El n&uacute;mero min&iacute;mo permitido es 1.",
				digits   : "Solo se permiten n&uacute;meros"
            },
            cant_linksVideo: {
				required : "Es necesario que indiques un n&uacute;mero.",
				min : "El n&uacute;mero min&iacute;mo permitido es 1.",
				digits   : "Solo se permiten n&uacute;meros"
            },
            campaign_start: {
                required  : "Es necesario que escribas el inicio de la campaña."
            },
            campaign_end: {
                required  : "Es necesario que escribas el fin de la campaña."
            }
        },
        submitHandler: function() {
			var numFontFiles    = 0;
			var numAdFiles		= 0;
			var numCheckedUsers = 0;
			var company         = $( "#selectCompanies" ).val();
			var campaign_name   = $( "#campaign_name" ).val();
			var imgCampaign     = $( "#fileImgCampaign" ).get(0).files[0];
			var links  			= $( "#cant_links" ).val();
			var links_videos  	= $( "#cant_linksVideo" ).val();
			var campaign_start  = $( "#campaign_start" ).val();
			var campaign_end    = $( "#campaign_end" ).val();

			var fdCampaign = new FormData();
			fdCampaign.append( 'company', company );
			fdCampaign.append( 'campaign_name', campaign_name );
			fdCampaign.append( 'imgCampaign', imgCampaign );
			fdCampaign.append( 'links', links );
			fdCampaign.append( 'links_videos', links_videos );
			fdCampaign.append( 'campaign_start', campaign_start );
			fdCampaign.append( 'campaign_end', campaign_end );

			// Se agregan los usuarios seleccionados
			$( '.campaign-users-group' ).each(function( index ){
				userID = $( this ).val();

				if( $( this ).is(':checked') ){
					checkboxName = "checkUser" + numCheckedUsers;

					fdCampaign.append( checkboxName, userID );

					numCheckedUsers++;
				}
	        });

			fdCampaign.append( 'numCheckedUsers', numCheckedUsers );

			// Se agregan las fuentes
			$( '.file-font' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileFontCampaign = $( this ).get(0).files[0];

					fileFontName = "fileFontCampaign" + numFontFiles;

					fdCampaign.append( fileFontName, fileFontCampaign );

					numFontFiles++;
				}

	        });

			fdCampaign.append( 'numFontFiles', numFontFiles );

			// Se agregan los anuncios
			$( '.file-ad' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).get(0).files[0];

					fileAdName = "fileAdCampaign" + numAdFiles;

					fdCampaign.append( fileAdName, fileAdCampaign );

					numAdFiles++;
				}

	        });

			// Se agregan los videos precargados
			var numAdFilesVid=0;

			$( '.file-ad-vid' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).get(0).files[0];

					fileAdName = "fileAdCampaignVid" + numAdFilesVid;

					fdCampaign.append( fileAdName, fileAdCampaign );

					numAdFilesVid++;
				}

	        });

			var numAdFilesVidTum =0;

			// Agregamso Tumbl
			$( '.tum-ad-vid' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).val();

					fileAdName = "fileAdCampaignVidTum" + numAdFilesVidTum;

					fdCampaign.append( fileAdName, fileAdCampaign );

					numAdFilesVidTum++;
				}

	        });

			fdCampaign.append( 'numAdFilesVid', numAdFilesVid );
			fdCampaign.append( 'numAdFiles', numAdFiles );



			$.ajax({
				url: '/addCampaign',
				type: 'POST',
				dataType: 'json',
				data: fdCampaign,
				async: false,
	            cache: false,
	            contentType: false,
	            processData: false,
	            beforeSend: function(){
	            	$( "#pleaseWaitDialog" ).modal( "show" );
	            }
			})
			.done(function( data ) {
				if( data ){
					$( "#pleaseWaitDialog" ).modal( "hide" );

					$.each( data, function(index, val) {
						$( "#links-list" ).append( $( "<li></li>" ).html( data[ index ] ) );

						$( "#divLinksCampaign" ).show();
						$( "#divRegCampaign" ).hide();
					});

				}


			})
			.fail(function(data) {
				console.log( data );
				console.log( data.responseText );
				console.log("error");
			});


        }
    });

	$("#cant_linksVideo").on("change",function(){

		if($(this).hasClass('editar')==false){

			if( parseInt($(this).val())==0 ){
				$("#fileAdCampaignVid0").rules('remove', {
		        	required: true
		    	});
			}else{
				$("#fileAdCampaignVid0").rules('add', {
		        	required: true
		    	});
			}

		}else{

			if( parseInt($(this).val())==0 ){
				$("#fileAdCampaignVid0").rules('remove', {
		        	required: true
		    	});
			}else{
				console.log("prevideos:"+$(".pre_videos").length);
				if($(".pre_videos").length>0){
					$("#fileAdCampaignVid0").rules('remove', {
		        		required: true
		    		});
				}else{
					$("#fileAdCampaignVid0").rules('add', {
		        		required: true
		    		});
				}

			}

		}
	});

	/**
	 * Administración de las campañas
	 */

	// Se muestrans los links de una campaña
	$( ".dataTable_wrapper" ).on('click', '.btn-view-url', function(event) {
		event.preventDefault();

        var campaignId = $( this ).attr( 'campaign_id' );

        $.ajax({
        	url: 'getUrlByCampaign',
        	type: 'POST',
        	dataType: 'json',
        	data: { campaign_id: campaignId },
        })
        .done(function( data ) {
        	$( "#links-list" ).html('');

        	console.log( data.status );

        	if( data.status == true ){
        		$.each( data.urls , function(index, val) {
					//$( "#links-list" ).append( $( "<li></li>" ).html( data.urls[ index ].url ) );
					if(data.urls[ index ].formato=='video'){
						$( "#links-list" ).append( $( "<li></li>" ).html( data.urls[ index ].URLVIDEO ) );
					}else{
						$( "#links-list" ).append( $( "<li></li>" ).html( data.urls[ index ].url ) );
					}
        		});
        	}else{
        		$( "#links-list" ).html( "No se encontrarón URLS" );
        	}

        	$( "#campaignURLModal" ).modal( 'show' );

        })
        .fail(function() {
        	console.log("error");
        });

	});

	// Se coloca en el modal la información según
	// la campaña pulsada
	$('#deleteCampaignModal').on('show.bs.modal', function (event) {
		var button      = $( event.relatedTarget );
		var campaign_id = button.data('campaign_id');

		var modal     = $(this)
		modal.find( '#btnDeleteCampaign' ).attr( 'campaign_id', campaign_id );

		// Nombre de la pantalla
		var campaignName = $( "#tdCampaignName" + campaign_id ).html();

		$( "#modalCampaignName" ).html( campaignName );

	});

	// Se elimina una campaña
	$( "#btnDeleteCampaign" ).on('click', function(event) {
		event.preventDefault();
		var campaign_id = $( this ).attr( 'campaign_id' );

        $.ajax({
        	url: 'deleteCampaign',
        	type: 'POST',
        	dataType: 'json',
        	data: { campaign_id: campaign_id },
        })
        .done(function( data ) {

        	if( data == true ){
        		$( "deleteCampaignModal" ).modal( "hide" );
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
	 * EDICIÓN DE UNA CAMPAÑA
	 */
	// Se obtiene la información de la campaña
	// para editarla
	$( ".dataTable_wrapper" ).on('click', '.btn-edit-campaign', function(event) {
		event.preventDefault();

       	var campaign_id = $( this ).attr( 'campaign_id' );

        $.ajax({
        	url: 'getCampaignInfo',
        	type: 'POST',
        	dataType: 'json',
			data: { campaign_id: campaign_id },
			timeout: 10000,
			async: false,
        })
        .done(function( data ) {
        	if( data ){
        		fieldsName = 'fileFontCampaign0';

        		$( "#divCampaignTble" ).hide();
        		$( "#divEditCampaign" ).show();

        		$( "#selectCompanies" ).val( data.company.company_id )
        							   .prop( 'disabled', 'disabled' );
        		$( "#campaign_name" ).val( data.name );
        		$( "#imgCampaign" ).attr('src', data.image_src );
        		$( "#cant_links" ).val( data.links );
        		$( "#cant_linksVideo" ).val( data.links_video );



        		$( "#campaign_id" ).val( data.campaign_id );

        		// Se muestran los usuarios
        		$.each( data.users, function(key, val) {
					campaignCostumerId = data.users[ key ].campaign_costumer_id;
					costumerId         = data.users[ key ].costumer_id;
					costumerName       = data.users[ key ].name;
					check              = false;

        			if( campaignCostumerId != null ){
        				check = true;
        			}

        			$( "#pUsers" ).append( $( '<label></label>')
        									.addClass( 'checkbox-inline' )
        									.append( $( '<input>' )
        											 .prop({
														'type'    : 'checkbox',
														'id'      : 'user' + costumerId,
														'name'    : 'user' + costumerId,
														'value'   : costumerId,
														'checked' : check
        											 })
        											 .attr( 'campaign_costumer_id', campaignCostumerId )
        											 .addClass('campaign-users-group')
        										   )
        									.append( $( '<b></b>' )
        											  .append( costumerName )
        									)
        					 			);
        		});

				/* Se agregar las reglas y mensajes dinámicamente*/
	            $( '.campaign-users-group' ).each(function( index ){

	                var costumerName = $( this ).attr('name');

	                $( '[name="' + costumerName + '"]' ).rules("add", {
	                        require_from_group: [ 1, ".campaign-users-group" ],
	                        messages: {
	                           require_from_group: "Es necesario que selecciones por lo menos un usuario."
	                        }
	                    }
	                );

	            });

        		// Fuentes
        		$.each( data.fonts, function(index, val) {
        			checkName = "Font-" + index;

        			$( "#fonts-list" ).append( $( "<a></a>" )
        										.attr( 'font_campaign_id', data.fonts[ index ].font_campaign_id )
        										.attr( 'delete', false )
        										.addClass( 'list-group-item' )
        										.html( data.fonts[ index ].name )
        										.append(
        											$('<span></span>')
        											.append( "<input type='checkbox' class='hidden campaign-fonts-group' name ='" + checkName + "' checked='true'>" )
        											.addClass('glyphicon glyphicon-remove pull-right remove-font')
        											.css('cursor', 'pointer')
											 	)
											 );

        			// Se agrega la validación
        			$( '[name="' + checkName + '"]' ).rules("add", {
	                        require_from_group: [ 1, ".campaign-fonts-group" ],
	                        messages: {
	                           require_from_group: "Es necesario que dejes por lo menos una."
	                        }
	                    }
	                );
        		});

				var divAdsContainer = $( "#divInsertedAds .row" );

				// Anuncios precargados
				$.each( data.ads, function(index, val) {
					checkbox = $( "<input>" )
								.prop('type', 'checkbox')
								.attr('value', data.ads[ index ].ad_id )
								.hide();

					btnDiscard = $( "<button></button>" )
								 .prop('type', 'button')
								 .addClass('btn btn-danger btn-discard-ad btn-block')
								 .html( "Descartar" );

					btnKeep = $( "<button></button>" )
								 .prop('type', 'button')
								 .addClass('btn btn-success btn-keep-ad btn-block')
								 .html( "Mantener" )
								 .hide();
					console.log(data.ads[ index ]);
					if( data.ads[ index ].formato=='video'){
						adImg = $( "<video/>" )
							.attr({
								'class'  : 'pre_videos',
								'width'  : '384',
								'height' : '216',
								'controls' : 'controls',
								'src'    : data.ads[ index ].image_src
							})
							.addClass( "img-responsive" );
					}else{
						adImg = $( "<img>" )
							.attr({
								'width'  : '384',
								'height' : '216',
								'src'    : data.ads[ index ].image_src
							})
							.addClass( "img-responsive" );
					}


					imgContainer = $( "<div></div>" )
								   .addClass( "col-xs-6 col-md-3" )
								   .append( $( "<div></div>" )
								   			.addClass( "thumbnail ad-container" )
								   );

					imgContainer.children()
								.append( adImg )
								.append( checkbox )
								.append( btnDiscard )
								.append( btnKeep );

					divAdsContainer.append( imgContainer );
        		});

				/**
				 * Se colocan las fechas de la campaña
				 */
				var startDate = new Date( data.start ).toUTCString();
				var todayDate = new Date();
				var pickerStartDate;

				if( startDate > todayDate ){
					pickerStartDate = todayDate;
				}else{
					pickerStartDate = data.start;
				}

				$('.input-daterange  #campaign_start').datepicker('setStartDate', pickerStartDate);
				$('.input-daterange  #campaign_end').datepicker('setStartDate', pickerStartDate);

        		$('.input-daterange #campaign_start').datepicker( 'update', data.start );
	    		$('.input-daterange #campaign_end').datepicker( 'update', data.end );
	    		$('.input-daterange').datepicker( "updateDates" );

        	}
        })
        .fail(function() {
        	console.log("error");
        });

	});

	// Marcar fuentes a eliminar
	$( "#fonts-list" ).on('click', '.remove-font', function(event) {
		event.preventDefault();

		if( $( this ).hasClass('glyphicon-remove') ){
			$( this ).removeClass('glyphicon-remove')
				 .addClass('glyphicon-ok')
				 .parent().addClass( 'list-group-item-danger' )
				 .attr( 'delete', true );

			$( this ).children()
					 .prop('checked', false);
		}else{
			$( this ).removeClass('glyphicon-ok')
				 .addClass('glyphicon-remove')
				 .parent().removeClass( 'list-group-item-danger' )
				 .attr( 'delete', false );

			$( this ).children()
					 .prop('checked', true);
		}

	});

	// Botón para cancelar la ediciónd de una campaña
	$( "#btn-cancel-edit-campaign" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */

        $( "#divEditCampaign" ).hide();
        $( "#divCampaignTble" ).show();
        $( "#imgCampaign" ).attr('src', 'img/img_icon.png');
        $( "#fonts-list" ).html( '' );
        $( "#pUsers" ).html( '' );
        $( "#divInsertedAds .row" ).html( '' );
        $( "#formUpdateCampaign" )[0].reset();
	});

	$('.input-daterange #campaign_start').datepicker()
				    .on( "changeDate" , function(e){
				    	var startDate = new Date(e.date.valueOf());
				    	$('.input-daterange  #campaign_end').datepicker('setStartDate', startDate);
				    });

	/**
	 * VALIDACIÓN DEL FORMULARIO DE ACTUALIZACIÓN
	 */
	 $('#formUpdateCampaign').validate({
        ignore: [],
        rules :{
            selectCompanies: {
                required  : true
            },
            campaign_name: {
                required  : true
            },
            fileImgCampaign: {
				extension: "png|jpeg|jpg|gift|bmp",
				imageDimentions: true
            },
            fileFontCampaign0: {
                extension: "ttf|otf"
            },
            fileAdCampaign0: {
            	extension: "png|jpeg|jpg|gift|bmp",
            	imageDimentions: true
            },
            fileAdCampaignVid0: {
            	extension: "mp4",
            	videoDimentions:true
            },
            cant_links: {
				required : true,
				min      : 1,
				digits   : true
            },
            cant_linksVideo: {
				required : true,
				digits   : true
            },
            campaign_start: {
                required  : true
            },
            campaign_end: {
                required  : true
            }
        },
        messages : {
            selectCompanies: {
                required  : "Es necesario que selecciones un cliente."
            },
            campaign_name: {
                required  : "Es necesario que escribas un nombre para la campaña."
            },
            fileImgCampaign: {
                extension  : "Se requiere una imagen png, jpeg, jpg, gift o bmp."
            },
            fileFontCampaign0: {
                extension  : "Se requiere una fuente de formato ttf o otf."
            },
            fileAdCampaignVid0:{
            	extension  : "Se requiere una fuente de formato ttf o otf.",
            	required  : "Debe tener un video cargado"
            },
            cant_links: {
				required : "Es necesario que indiques un n&uacute;mero.",
				min : "El n&uacute;mero min&iacute;mo permitido es 1.",
				digits   : "Solo se permiten n&uacute;meros"
            },
            cant_linksVideo: {
				required : "Es necesario que indiques un n&uacute;mero.",
				min : "El n&uacute;mero min&iacute;mo permitido es 1.",
				digits   : "Solo se permiten n&uacute;meros"
            },
            campaign_start: {
                required  : "Es necesario que escribas el inicio de la campaña."
            },
            campaign_end: {
                required  : "Es necesario que escribas el fin de la campaña."
            }
        },
        errorPlacement: function(error, element){
            if (element.hasClass('campaign-users-group') ){
            	error.insertAfter("#pUsers");
            }
            else{
                error.insertAfter(element);
            }
        },
        submitHandler: function() {
			var numFontFiles    = 0;
			var numAdFiles      = 0;
			var numCheckedUsers = 0;
			var numDeleteUsers  = 0;
			var company_id      = $( "#selectCompanies" ).val();
			var campaign_id     = $( "#campaign_id" ).val();
			var campaign_name   = $( "#campaign_name" ).val();
			var fileImgName     = $( "#fileImgCampaign" ).val();
			var cant_links      = $( "#cant_links" ).val();
			var cant_linksVideo = $( "#cant_linksVideo" ).val();

			var campaign_start  = $( "#campaign_start" ).val();
			var campaign_end    = $( "#campaign_end" ).val();

			var fontsChanges = new Array();
			var fileImgCampaign;

			/* Se agregan las modificaciones a las fuentes */
            $( '#fonts-list a' ).each(function( index ){
                font_campaign_id = $( this ).attr( 'font_campaign_id' );
                font_delete      = $( this ).attr( 'delete' );

				font                  = {};
				font.font_campaign_id = font_campaign_id;
				font.font_delete      = font_delete;

                fontsChanges.push( font );

            });

            // Se agrega los datos al FormData
            var fdUpdateCampaign = new FormData();
			fdUpdateCampaign.append( 'company_id', company_id );
			fdUpdateCampaign.append( 'campaign_id', campaign_id );
			fdUpdateCampaign.append( 'campaign_name', campaign_name );
			fdUpdateCampaign.append( 'cant_links', cant_links );
			fdUpdateCampaign.append( 'links_videos', cant_linksVideo );
			fdUpdateCampaign.append( 'campaign_start', campaign_start );
			fdUpdateCampaign.append( 'campaign_end', campaign_end );
			fdUpdateCampaign.append( 'fontsChanges', JSON.stringify( fontsChanges ) );

			/**
			 * Se agregan las fuentes
			 */
			$( '.file-font' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileFontCampaign = $( this ).get(0).files[0];

					fileFontName = "fileFontCampaign" + numFontFiles;

					fdUpdateCampaign.append( fileFontName, fileFontCampaign );

					numFontFiles++;
				}

	        });

			fdUpdateCampaign.append( 'numFontFiles', numFontFiles );

			/**
			 * Se agrega la imagen
			 */
			if( fileImgName != "" ){

				var imgCampaign = $( "#fileImgCampaign" ).get(0).files[0];
				fdUpdateCampaign.append( 'imgCampaign', imgCampaign );
			}

			/**
			 * Anuncios precargados
			 */

			var aAdsChanges = new Array();

			// Se enlistan los cambios en los anuncios
			$( ".ad-container" ).each(function(index, el) {
				checkboxAd = $( this ).children( "input[type='checkbox']" );

				oAdChange = {};
				iAdId     = checkboxAd.val();

				oAdChange.ad_id  = iAdId;
				oAdChange.delete = checkboxAd.is(':checked');

				aAdsChanges.push( oAdChange );

			});

			fdUpdateCampaign.append( 'adsChanges', JSON.stringify( aAdsChanges ) );

			// Se agregan los anuncios
			$( '.file-ad' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).get(0).files[0];

					fileAdName = "fileAdCampaign" + numAdFiles;

					fdUpdateCampaign.append( fileAdName, fileAdCampaign );

					numAdFiles++;
				}

	        });

			fdUpdateCampaign.append( 'numAdFiles', numAdFiles );


			// Se agregan los videos precargados
			var numAdFilesVid=0;

			$( '.file-ad-vid' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).get(0).files[0];

					fileAdName = "fileAdCampaignVid" + numAdFilesVid;

					fdUpdateCampaign.append( fileAdName, fileAdCampaign );

					numAdFilesVid++;
				}

	        });

			var numAdFilesVidTum =0;

			// Agregamso Tumbl
			$( '.tum-ad-vid' ).each(function( index ){
				fileValue = $( this ).val();

				if( fileValue != "" ){
					fileAdCampaign = $( this ).val();

					fileAdName = "fileAdCampaignVidTum" + numAdFilesVidTum;

					fdUpdateCampaign.append( fileAdName, fileAdCampaign );

					numAdFilesVidTum++;
				}

	        });

			fdUpdateCampaign.append( 'numAdFilesVid', numAdFilesVid );



			// Se agregan los usuarios seleccionados
			$( '.campaign-users-group' ).each(function( index ){
				userID             = $( this ).val();
				campaignCostumerId = $( this ).attr( 'campaign_costumer_id' );

				if( $( this ).is(':checked') ){
					if( campaignCostumerId == undefined ){
						checkboxName = "checkUser" + numCheckedUsers;

						fdUpdateCampaign.append( checkboxName, userID );

						numCheckedUsers++;
					}
				}else{
					if( campaignCostumerId != undefined ){
						checkDelete = "deleteUser" + numDeleteUsers;

						fdUpdateCampaign.append( checkDelete, campaignCostumerId );

						numDeleteUsers++;
					}
				}
	        });

			fdUpdateCampaign.append( 'numCheckedUsers', numCheckedUsers );
			fdUpdateCampaign.append( 'numDeleteUsers', numDeleteUsers );

			// Se envian los datos
			$.ajax({
				url: 'updateCampaign',
				type: 'POST',
				dataType: 'json',
				data: fdUpdateCampaign,
				async: false,
	            cache: false,
	            contentType: false,
				processData: false,
				timeout:100000,
			})
			.done(function( data ) {
				console.log("Return From Update No Error Fail");
				console.log(data);
				if( data == true ){
					if(typeof(Storage) !== "undefined") {
					    // Code for localStorage/sessionStorage.
					    sessionStorage.updateCampaign = true;
					} else {
					    // Sorry! No Web Storage support..
					}

					window.location.reload();
				}
			})
			.fail(function(e) {
				console.log(e);
				console.log(e.responseText);
				console.log("error");
			});


        }
    });

	/**
	 * Anuncios precargados IMAGENES
	 */
     // Se agrega dinámicamente [input=file]
     // para agregar un anuncio
 	$( "#btnAddAd" ).on('click', function(event) {
 		event.preventDefault();
 		/* Act on the event */
 		var index = $( '#adFilesContainer p:last' ).attr( 'id' ).substring( 3 );
 		index     = parseInt( index ) + 1;

        var clone = $( '#pAd0' ).clone();

        clone.attr( 'id', 'pAd' + index )
         	.find( '#fileAdCampaign0' )
         	.attr( 'name', 'fileAdCampaign' + index )
         	.attr( 'id', 'fileAdCampaign' + index );

        clone.find( 'label.error' ).remove();

        $( '#adFilesContainer' ).append( clone );

         // Se agrega la regla al validador
        $( '[name="fileAdCampaign' + index + '"]' ).rules("add", {
 	            extension: "png|jpeg|jpg|gift|bmp",
 	            imageDimentions: true,
 	            messages: {
 	               extension: "Se requiere una imagen png, jpeg, jpg, gift o bmp."
 	            }
 	        }
 	    );

 	});

 	/**
	 * Anuncios precargados VIDEOS
	 */
     // Se agrega dinámicamente [input=file]
     // para agregar un anuncio
 	$( "#btnAddAdVideo" ).on('click', function(event) {
 		event.preventDefault();
 		/* Act on the event */
 		var index = $( '#adFilesContainerVid p:last' ).attr( 'id' ).substring( 6 );
 		index     = parseInt( index ) + 1;

        var clone = $( '#pAdvid0' ).clone();

        // Reproductor Video
        clone.find('#vidrec0')
        	.attr('id', 'vidrec'+index)
        	.attr('data-input', 'fileAdCampaignVidTum'+index)
        	.attr('src', '');

        // Contenedor de todo
        clone.attr( 'id', 'pAdvid' + index )
         	.find( '#fileAdCampaignVid0' )
         	.attr('data-idlink', 'vidrec'+index)
         	.attr( 'name', 'fileAdCampaignVid' + index )
         	.attr( 'id', 'fileAdCampaignVid' + index );

        //
        clone.find( 'label.error' ).remove();

        // Input
        clone.find("#fileAdCampaignVidTum0").attr("id",'fileAdCampaignVidTum'+index);


        $( '#adFilesContainerVid' ).append( clone );

         // Se agrega la regla al validador
        $( '[name="fileAdCampaignVid' + index + '"]' ).rules("add", {
 	            extension: "mp4",
 	            videoDimentions:true,
 	            messages: {
 	               extension: "Se requiere un video mov."
 	            }
 	        }
 	    );

 	});

 	//
 	$("#adFilesContainerVid").on("change",".file-ad-vid",function(e){
 		var fileUrl = window.URL.createObjectURL($(this).get(0).files[0]);
 		var linkVideo =  $(this).attr("data-idlink");

   		$("#"+linkVideo).attr("src", fileUrl);
   		var video = document.getElementById(linkVideo);

   		video.addEventListener('loadeddata', function(e) {

		  	var canvas = document.createElement('canvas');
	        canvas.width  = e.target.width;
	        canvas.height = e.target.height;
    		var ctx = canvas.getContext('2d');
        	ctx.drawImage( e.target, 0, 0, 384, 216);
  			var jpegUrl = canvas.toDataURL("image/jpeg",1.0);

  			var idInput= e.target.getAttribute("data-input");
  			$("#"+idInput).attr("value",jpegUrl);
		}, false);
 	});

 	// Botón para descartar un anuncio
 	$( "#divInsertedAds" ).on('click', '.btn-discard-ad', function(event) {
 		event.preventDefault();
 		/* Act on the event */
 		// Se marca el checkbox
        $( this ).parent().children( "input[type='checkbox']" ).prop('checked', 'checked');
        // Se muestra el botón de mantener
        $( this ).parent().children( ".btn-keep-ad" ).show();
        // Se oculta el botón de descartar
        $( this ).hide();

 	});

 	// Botón para mantener un anuncio
 	$( "#divInsertedAds" ).on('click', '.btn-keep-ad', function(event) {
 		event.preventDefault();
 		/* Act on the event */
 		// Se marca el checkbox
        $( this ).parent().children( "input[type='checkbox']" ).removeProp( 'checked' );
        // Se muestra el botón de mantener
        $( this ).parent().children( ".btn-discard-ad" ).show();
        // Se oculta el botón de descartar
        $( this ).hide();

 	});

 	// Se muestra el msj de actualización éxitosa de la campaña
 	if( sessionStorage.updateCampaign == "true" ){
 		$( "#alert-update-campaign" ).show();
 		sessionStorage.clear();
 	}

	$( "#all-dates" ).on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		if ( $( this ).is(':checked') ) {
			$( "#campaign_find_start" ).attr('disabled', 'disabled');
			$( "#campaign_find_end" ).attr('disabled', 'disabled');
		}else{
			$( "#campaign_find_start" ).removeAttr('disabled');
			$( "#campaign_find_end" ).removeAttr('disabled');
		}

	});

	/*$( "#btn-find-campaigns" ).on('click', function(event) {
		event.preventDefault();



	});*/

	$('#formSearchCampaign').validate({
	    ignore: [],
	    rules :{
	        selectCompaniesMain: {
	            required  : true
	        },
	        campaign_find_start: {
	            required  : true
	        },
	        campaign_find_end: {
	            required  : true
	        }
	    },
	    messages : {
	        selectCompaniesMain: {
	            required  : "Selecciona un cliente para realizar la búsqueda."
	        },
	        campaign_find_start: {
	            required  : "Selecciona una fecha de inicio."
	        },
	        campaign_find_end: {
	            required  : "Selecciona una fecha de fin."
	        }
	    },
	    submitHandler: function() {
			var companyId = $( "#selectCompaniesMain" ).val();
			var startDate = $( "#campaign_find_start" ).val();
			var endDate   = $( "#campaign_find_end" ).val();
			var allDates  = false;

			if ( $( "#all-dates" ).is(':checked') ) {
				allDates = true;
			}

	        $.ajax({
	        	url: 'getCampaignsBySearch',
	        	type: 'POST',
	        	dataType: 'json',
	        	data: { companyId : companyId,
	        			startDate : startDate,
	        			endDate   : endDate,
	        			allDates  : allDates
	        		  },
	        })
	        .done(function( data ) {
	        	console.log("success");

	        	campaignsTable.fnClearTable();
	        	campaignsTable.fnDestroy();

	        	if( data.status == true ){

	        		$.each(data.campaigns, function(index, val) {

	        			row = $( "<tr></tr>" )
	        						.append( $( "<td></td>" )
	        								.attr( "id", "tdCampaignName" + data.campaigns[ index ].campaign_id )
	        								.html( data.campaigns[ index ].name )
	        						)
	        						.append( $( "<td></td>" )
	        								.html( data.campaigns[ index ].start )
	        						)
	        						.append( $( "<td></td>" )
	        								.html( data.campaigns[ index ].end )
	        						)
	        						.append( $( "<td></td>" )
	        								.addClass( "text-center" )
	        								.html( $( "<button></button>" )
	        										.addClass( "btn btn-outline btn-info btn-edit-campaign" )
	        										.attr({
	        											"type"        : 'button',
	        											"campaign_id" : data.campaigns[ index ].campaign_id
	        										})
	        										.html( "Editar" )
	        								)
	        						)
	        						.append( $( "<td></td>" )
	        								.addClass( "text-center" )
	        								.html( $( "<button></button>" )
	        										.addClass( "btn btn-outline btn-danger" )
	        										.attr({
	        											"type"        : 'button',
	        											"data-campaign_id" : data.campaigns[ index ].campaign_id,
	        											"data-toggle" : "modal",
	        											"data-target" : "#deleteCampaignModal"
	        										})
	        										.html( "Eliminar" )
	        								)
	        						)
	        						.append( $( "<td></td>" )
	        								.addClass( "text-center" )
	        								.html( $( "<button></button>" )
	        										.addClass( "btn btn-outline btn-success btn-view-url" )
	        										.attr({
	        											"type"        : 'button',
	        											"campaign_id" : data.campaigns[ index ].campaign_id
	        										})
	        										.html( "Ver URLS" )
	        								)
	        						);

	        			$( "#dTBody" ).append( row );
	        		});

	        	}else{
	        		console.log("no data");
	        	}
	        })
	        .fail(function() {
	        	console.log("error");
	        })
	        .done(function() {
				initTable();
				campaignsTable.fnDraw();
			});
	    }
	});

	/*$( "#fileFontCampaign0" ).on('change', function(event) {
		event.preventDefault();

		var fontFiles = $( "#fileFontCampaign0" )[0];

		console.log( $( "#fileFontCampaign0" ) );

		for (var i = 0; i < fontFiles.files.length; ++i) {
		  	var name = fontFiles.files.item(i).name;
		  	console.log("here is a file name: " + name);

		  	if( i == 0 ){
		  		console.log( "eliminando")
		  		fontFiles.files.item(0).splice(1);
		  	}

		}

		console.log( $( "#fileFontCampaign0" ) );
	});*/


	// PRUEBA DE SUBIR ARCHIVOS DESDE EL IPAD
	/*$( "#btnTest" ).on('click', function(event) {
		event.preventDefault();
		var campaignId  = 21;
		var fDTest      = new FormData();
		var imgCampaign = $( "#fileImgCampaign" ).get(0).files[0];

		fDTest.append( 'campaign_id', campaignId );
		fDTest.append( 'adImageFile', imgCampaign );

		$.ajax({
			url: 'api/v1/saveCreatedAd',
			type: 'POST',
			dataType: 'json',
			data: fDTest,
			async: false,
            cache: false,
            contentType: false,
            processData: false,
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


	});*/

	/*$( "#btnTest" ).on('click', function(event) {
		event.preventDefault();
		var fDTest      = new FormData();
		var imgCampaign = $( "#fileImgCampaign" ).get(0).files[0];

		fDTest.append( 'adImageFile', imgCampaign );

		$.ajax({
			url: 'validateCampaingImg',
			type: 'POST',
			dataType: 'json',
			data: fDTest,
			async: false,
            cache: false,
            contentType: false,
            processData: false,
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


	});*/

});
