@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/screens/screens.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pantallas</h1>
        </div>
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12" id="divScreensTable">
            @if ( Session::get( "inserted" ) )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se registró la pantalla con éxito.
                </div>
            @endif

            @if ( Session::get( "updated" ) )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se actualizó la información de la pantalla con éxito.
                </div>
            @endif

            @if ( Session::get( "update-error" ) )
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Ocurrió un error al actualizar la información de la pantalla, por favor intenta de nuevo.
                </div>
            @endif

            @if ( Session::get( "validation-error" ) )
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Los datos enviados no son válidos, por favor verificalos.
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    Pantallas registradas
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Ubicación</th>
                                    <th>Fase</th>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>

                            	@foreach ($screens as $screen)
		                            <tr>
	                                    <td id='tdScreenName{{ $screen->screen_id }}'> {{ $screen->name }} </td>
	                                    <td> {{ $screen->location }}</td>
                                        <td> {{ $screen->phase_name }} </td>
	                                    <td> {{ $screen->latitude }} </td>
                                        <td> {{ $screen->longitude }} </td>
	                                    <td class="text-center btn-edit-screen"> 
	                                    	<button type="button" class="btn btn-outline btn-info" screen_id="{{ $screen->screen_id }}">Editar</button> 
	                                    </td>
	                                    <td class="text-center">
	                                    	<button type="button" class="btn btn-outline btn-danger" data-screen_id="{{ $screen->screen_id }}" data-toggle="modal" data-target="#deleteModal">Eliminar</button> 
	                                    </td>
	                                </tr>
		                        @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->

        <div class="col-lg-12" id="divEditScreen">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Registro de pantallas
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formUpdateScreen" action="updatescreen" method='post'>
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input class="form-control" name="screen_name" id="screen_name">
                            <input type="hidden" name="screen_id" id="screen_id">
                        </div>
                        <div class="form-group">
                            <label>Ubicación:</label>
                            <input class="form-control" name="location" id="location">
                        </div>
                        <div class="form-group">
                            <label>Latitud:</label>
                            <input class="form-control" name="latitude" id="latitude">
                        </div>
                        <div class="form-group">
                            <label>Longitud:</label>
                            <input class="form-control" name="longitude" id="longitude">
                        </div>
                        <div class="form-group">
                            <label>Fase:</label>
                            <select class="form-control" name="phase" id="phase">
                                <option value=''>Selecciona una fase</option>

                                @foreach ($phases as $phase)
                                    <option value='{{ $phase->phase_id }}'>{{ $phase->name }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="form-group">
                            <label>Resolución:</label>
                            <select class="form-control" name="resolution" id="resolution">
                                <option value=''>Selecciona una resolución</option>

                                @foreach ( $screen_resolutions as $screen_resolution )
                                    <option value = "{{ $screen_resolution->screen_size_id }}">{{ $screen_resolution->resolution }}</option>
                                @endforeach
                            </select>
                        </div> 
                        
                        <div class="form-group">
                            <button type="button" id="btn-cancel-edit" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>

                    </form>

                    @if ( isset( $error ) )
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Ocurrio un error al registrar la pantalla.
                        </div>
                    @endif

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.row -->

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    ¿Esta seguro que desea eliminar <strong id='modalScreenName'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnDeleteScreen' screen_id=''>Eliminar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@overwrite