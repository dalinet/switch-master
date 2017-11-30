$(document).ready(function() {

    /**
     * PANTALLAS
     */

     $('#dataTables-example').DataTable({
             responsive: true
     });

    /**
     * VALIDACIÓN DEL FORMULARIO DE REGISTRO DE PANTALLAS
     */
     $('#formScreen').validate({
         ignore: [],
         rules :{
             screen_name: {
                 required  : true
             },
             location: {
                 required  : true
             },
             latitude: {
                 required  : true
             },
             longitude: {
                 required  : true
             },
             phase: {
                 required  : true
             }
         },
         messages : {
             screen_name: {
                 required  : "Es necesario que escribas un nombre."
             },
             location: {
                 required  : "Es necsario que indiques la ubicación de la pantalla."
             },
             latitude: {
                 required  : "Es necesario que indiques la latitud."
             },
             longitude: {
                 required  : "Es necesario que indiques la longitud."
             },
             phase: {
                 required  : "Es necesario que selecciones una fase."
             }
         }
     });

    // Botón de editar una pantalla
    $('#dataTables-example').on( 'click',".btn-edit-screen", function(event) {
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
            
            $( "#screen_id" ).val( data.screen_id );
            $( "#screen_name" ).val( data.name );
            $( "#location" ).val( data.location );
            $( "#latitude" ).val( data.latitude );
            $( "#longitude" ).val( data.longitude );

            $( "#phase" ).val( data.phase_id );
            $( "#resolution" ).val( data.screen_size_id );
            
            $( "#divScreensTable" ).hide();
            $( "#divEditScreen" ).show();

        })
        .fail(function() {
            console.log("error");
        });
        
    });


    /**
     * VALIDACIÓN DEL FORMULARIO ACTUALIZAR PANTALLAS
     */
     $('#formUpdateScreen').validate({
        ignore: [],
        rules :{
            screen_name: {
                required  : true
            },
            location: {
                required  : true
            },
            latitude: {
                required  : true
            },
            longitude: {
                required  : true
            },
            phase: {
                required  : true
            }
        },
        messages : {
            screen_name: {
                required  : "Es necesario que escribas un nombre."
            },
            location: {
                required  : "Es necsario que indiques la ubicación de la pantalla."
            },
            latitude: {
                required  : "Es necesario que indiques la latitud."
            },
            longitude: {
                required  : "Es necesario que indiques la longitud."
            },
            phase: {
                required  : "Es necesario que selecciones una fase."
            }
        }
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

    // Botón para ocultar el panel de edición de pantalla
    $( "#btn-cancel-edit" ).on('click', function(event) {
        event.preventDefault();
        /* Act on the event */

        $( "#divScreensTable" ).show();
        $( "#divEditScreen" ).hide();
 
    });
    


});