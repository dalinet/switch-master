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

	 		$( "#btnGetPDF" ).attr('disabled', 'disabled');
	 		$( "#btnGetZIP" ).attr('disabled', 'disabled');
	 		
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
			url: 'getCreatedAdsHistorical',
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

					var $mediaElement =null;
					console.log(data[ index ].formato);
					if( data[ index ].formato=="image" ){
						$mediaElement= $( "<img>" )
							.prop('src', data[ index ].image_src )
							.addClass('img-responsive');
					}else{
						$mediaElement= $( "<video>" )
							.prop('src', data[ index ].image_src )
							.prop('controls','controls' )
							.prop('preload','metadata')
							.prop('poster', data[ index ].file_src)
							.addClass('img-responsive');
					}


					row = $( "<tr></tr>" )
								.append( $( "<td></td>" )
										.html( data[ index ].costumer_name )
								)
								.append( $( "<td></td>" )
										.append( $mediaElement
										)
								)
								.append( $( "<td></td>" )
										.html( data[ index ].created_at )
								);		

					$( "#dTBody" ).append( row );
				});

				$( "#btnGetPDF" ).removeAttr('disabled');
				$( "#btnGetZIP" ).removeAttr('disabled');

			}else{
				$( "#btnGetPDF" ).attr('disabled', 'disabled');
				$( "#btnGetZIP" ).attr('disabled', 'disabled');

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

	/**
	 * GENERACIÓN DEL ARCHIVO PDF
	 */
	$( "#btnGetPDF" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var campaignId = $( "#selectCampaign" ).val();

		var $btn = $(this).button('loading');

		$.ajax({
			url: 'getCreatedAdsPDF',
			type: 'POST',
			dataType: 'json',
			data: { campaign_id: campaignId},
		})
		.done(function( data ) {
			console.log( "data: " + data );
			// $("body").append("<iframe src='" + data + "' style='display: none;' ></iframe>");
			window.open( data );
			
			$btn.button('reset');
		})
		.fail(function() {
			console.log("error");
		});
		
	});

	/**
	 * GENERACIÓN DEL ARCHIVO ZIP
	 */
	$( "#btnGetZIP" ).on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var campaignId = $( "#selectCampaign" ).val();

		var $btn = $(this).button('loading');

		$.ajax({
			url: 'getCreatedAdsZIP',
			type: 'POST',
			dataType: 'json',
			data: { campaign_id: campaignId},
		})
		.done(function( data ) {
			$("body").append("<iframe src='" + data + "' style='display: none;' ></iframe>");

			$btn.button('reset');
		})
		.fail(function() {
			console.log("error");
		});
		
	});

 });