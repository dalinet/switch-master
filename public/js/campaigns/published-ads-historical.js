$(document).ready(function() {
	
	var createdAdsTable;

	function initTable(){
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
			url: 'getPublishedAdsHistorical',
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
					var $element = null;

					console.log(data[ index ].formato);
					if(data[ index ].formato=='video'){
						$element=$( "<video>" )
									.attr({
										'width'  : '384',
										'height' : '216',
										'controls' : 'controls',
										'preload' : 'metadata',
										'poster' : data[ index ].file_src + "?t=" + new Date().getTime(),
										'src'    : data[ index ].image_src + "?t=" + new Date().getTime()
									}).addClass('img-responsive');
					}else{
						$element=$( "<img>" )
									.prop('src', data[ index ].image_src + "?t=" + new Date().getTime() )
									.addClass('img-responsive');
					}
					row = $( "<tr></tr>" )
								.append( $( "<td></td>" )
										.html( data[ index ].costumer_name )
								)
								.append( $( "<td></td>" )
										.append( $element )
								)
								.append( $( "<td></td>" )
										.html( data[ index ].location )
								)
								.append( $( "<td></td>" )
										.html( data[ index ].created_at )
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
			createdAdsTable.fnDraw();
		});
		
	});

 });