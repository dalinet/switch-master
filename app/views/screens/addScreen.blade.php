@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/screens/screens.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pantallas</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">

            @if ( Session::get( "validation-error" ) )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Los datos introducidos contienen información inválida.
                </div>
            @endif

            @if ( Session::get( "register-error" ) )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Ocurrió un error al registrar la pantalla, por favor intenta de nuevo.
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    Registro de pantallas
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formScreen" action="addscreen" method='post'>
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
                            <select class="form-control" name="phase" id="phase">
                                <option value=''>Selecciona una resolución</option>

                                @foreach ( $screen_resolutions as $screen_resolution )
                                    <option value='{{ $screen_resolution->screen_resolution_id }}'>{{ $screen_resolution->resolution }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input class="form-control" name="screen_name" id="screen_name">
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
                            <button type="button" class="btn btn-default">Cancelar</button>
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
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

@overwrite