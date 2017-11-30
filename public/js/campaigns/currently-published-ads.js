$(document).ready(function() {
	
	var createdAdsTable;

	function initTable(){
		console.log( "initTable" );
	    createdAdsTable = $('#dataTables-example').dataTable({
											    responsive: true
										  	});
	}

	initTable();

	/**
	 * Selección del cliente
	 */
	$( "#selectCompany" ).on('change', function(event) {
	 	event.preventDefault();
	 	/* Act on the event */

	 	var companyId = $( this ).val();

	 	createdAdsTable.fnClearTable();
	 	createdAdsTable.fnDestroy();

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

	 	})
	 	.fail(function() {
	 		console.log("error");
	 	})
	 	.done(function() {
	 		initTable();
	 		createdAdsTable.fnDraw();
	 	});
	 	
	 });

	/**
	 * HISTORIAL DE ANUNCIOS CREADOS
	 */
	$( "#selectCampaign" ).on('change', function(event) {
		event.preventDefault();
		/* Act on the event */

		var campaignId = $( this ).val();

		$.ajax({
			url: 'getCurrentlyPublishedAds',
			type: 'post',
			dataType: 'json',
			data: {campaignId: campaignId},
		})
		.done(function( data ) {
			console.log("success");

			createdAdsTable.fnClearTable();
			createdAdsTable.fnDestroy();
			// createdAdsTable = null;

			if( data.length > 0 ){

				$.each(data, function(index, val) {
					costumer_name = data[ index ].costumer_name;
					ad_type       = data[ index ].type;

					if( costumer_name == null || ad_type == "default" ){
						costumer_name = "CC Administrador";
					}

					if(data[ index ].formato=='video'){
						$element=$( "<video>" )
									.attr({
										'width'  : '384',
										'height' : '216',
										'controls' : 'controls',
										'src'    : data[ index ].url + "?t=" + new Date().getTime()
									}).addClass('img-responsive');
					}else{
						$element=$( "<img>" )
									.prop('src', data[ index ].url + "?t=" + new Date().getTime() )
									.addClass('img-responsive');
					}

					row = $( "<tr></tr>" )
								.append( $( "<td></td>" )
										.html( costumer_name )
								)
								.append( $( "<td></td>" )
										.append( $element
										)
								)
								.append( $( "<td></td>" )
										.attr('id', 'tdScreen-' + data[ index ].campaign_url_id )
										.html( data[ index ].location )
								);		

					if( ad_type == "ad" ){

						row.append( $( "<td></td>" )
									.addClass('text-center')
									.append( $( "<button></button>" )
											.attr({
												"type"                 : "button",
												"data-toggle"          : "modal",
												"data-target" 		   : "#deletePublishAd",
												"data-loading-text"    : "Procesando...",
												"data-campaign-url-id" : data[ index ].campaign_url_id
											})
											.addClass('btn btn-outline btn-danger btn-del-published-ad')
											.html( "Eliminar" )
									)
							);
					}else{
						row.append( $( "<td></td>" )
									.addClass('text-center')
									.append( $( "<button></button>" )
											.attr({
												"type"     : "button",
												"disabled" : "disabled",
											})
											.addClass('btn btn-outline btn-danger btn-del-published-ad')
											.html( "Eliminar" )
									)
							);
					}

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
			createdAdsTable.fnDraw();
		});
		
	});
	
	// Se coloca en el modal la información según 
	// la campaña pulsada
	$('#deletePublishAd').on('show.bs.modal', function (event) {
		var button         = $( event.relatedTarget ); 
		var iCampaignUrlId = button.data('campaign-url-id');
		var sURLImg 	   = $( "#img-" + iCampaignUrlId ).attr( 'src' );

		$( "#modalDelImg" ).attr( 'src', sURLImg );
		var modal     = $(this)
		modal.find( '#btnDelURL' ).attr( 'campaign_url_id', iCampaignUrlId );

		// Nombre de la pantalla
		var screenName = $( "#tdScreen-" + iCampaignUrlId ).html();

		$( "#screenName" ).html( screenName );

	});

	$( "#btnDelURL" ).on('click', function(event) {
		event.preventDefault();
		
		var iCampaignUrlId = $( this ).attr( 'campaign_url_id' );
		var $btn           = $(this).button('loading');

		$.ajax({
			url: 'delPublishedAd',
			type: 'POST',
			dataType: 'json',
			data: { "campaign_url_id" : iCampaignUrlId },
		})
		.done(function( data ) {
			if( data.status == true ){
				$btn.button('reset');
				
				$( "#deletePublishAd" ).modal( "hide" );

				$( "#selectCampaign" ).trigger('change');
			}else{

			}
		})
		.fail(function() {
			console.log("error");
		});
		

	});
	/*$( ".dataTable_wrapper" ).on('click', '.btn-del-published-ad', function(event) {
		event.preventDefault();

		console.log( "hey!" );
	});*/


 });