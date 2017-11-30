@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/campaigns/add-urls-to-screens.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Agregar URL's a Pantalla</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissable" id="alert-urls-screens-saved">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Han sido guardadas las URLs en las pantallas correspondientes.
            </div>
            <div class="panel panel-default" >
                <div class="panel-heading">
                    Contenido
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                        
                    <div class="form-group">
                        <label>Cliente:</label>
                        <select class="form-control" name="selectCompany" id="selectCompany">
                            <option value=''>Selecciona un cliente</option>
                            @foreach ($companies as $company)
                                <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Campaña:</label>
                        <select class="form-control" name="selectCampaign" id="selectCampaign" disabled="disabled">
                            <option value=''>Selecciona una campaña</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="panel panel-default" >
                                <div class="panel-heading">
                                    URL's de la campaña
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 text-center">
                                            <p>
                                                <strong>Total: </strong> <i id="numURLs">0</i>
                                            </p>
                                        </div>
                                        {{-- <div class="col-lg-6 text-center">
                                            <p>
                                                <strong>Disponibles: </strong> <i id="availableURls">0</i>
                                            </p>
                                        </div> --}}
                                    </div>
                                    
                                    
                                    <div class="list-group" id="urls-in-campaign" style="height: 435px; overflow-y: scroll;">
                                        <p>Selecciona una campaña</p>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" id="btnAddToAllScreens" >Agregar a todas las pantallas</button>
                                        <button type="button" class="btn btn-primary" id="btnCleanAllScreens" >Limpiar todas las pantallas</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="panel panel-default" >
                                <div class="panel-heading">
                                    Pantallas
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>Pantallas:</label>
                                        <select class="form-control" name="selectScreen" id="selectScreen" disabled="disabled">
                                            <option value=''>Selecciona una pantalla</option>
                                            @foreach ($screens as $key => $screen )

                                                @if( @$screens[ $key - 1 ]->phase_id != @$screens[ $key ]->phase_id)
                                                    <optgroup label="{{$screen->phase_name}}">
                                                @endif

                                                <option value='{{ $screen->screen_id }}'>{{ $screen->name }} / {{ $screen->location }}</option>

                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <p>
                                            <strong>URLs en la pantalla: </strong> <i id="urlsInScreen">0</i>
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <div class="well">
                                            <h4>Seleccionadas:</h4>
                                            <div class="list-group" id="urls-selecteds" style="height: 218px; overflow-y: scroll;">
                                               <h5>Selecciona una pantalla</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" id="btn-add-urls-screen"class="btn btn-primary">Agregar URLs a Pantalla</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    URL's en Pantallas
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body" id="urls-in-screens">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Enviar información al programador
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body" id="urls-in-screens">
                                    <form role="form" id="formSendProgrammerMail" method='post'>
                                        <div class="form-group">
                                            <label for="programmer_email">Correo electrónico</label>
                                            <input class="form-control" name="programmer_email" id="programmer_email" placeholder="Correo electrónico">
                                        </div>
                                        <div class="form-group">
                                            <label for="programmer_msg">Mensaje:</label>
                                            <textarea class="form-control" placeholder="Mensaje" id="programmer_msg" name="programmer_msg" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-default">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnConfirmSaveRULs" >Guardar</button>
                        {{-- <button type="button" class="btn btn-primary" id="btnTest" >Test</button> --}}
                    </div>

                    @if ( isset( $error ) )
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Ocurrio un error al registrar la campaña.
                        </div>
                    @endif


                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- Modal Seleccionar una pantalla -->
    <div class="modal fade" id="modalSelectScreenInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Información</h4>
                </div>
                <div class="modal-body">
                    <p id="pInfoMsg"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Confirmar guardar -->
    <div class="modal fade" id="modalConfirmSave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Información</h4>
                </div>
                <div class="modal-body">
                    ¿Estas seguro que quieres guardar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnSaveURLsScreens">Aceptar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@overwrite