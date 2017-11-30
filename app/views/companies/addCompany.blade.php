@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/companies.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Registro de Clientes</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">

            @if( Session::get('insert_error') === true )
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Ocurrio un error al realizar el registro.
                </div>
            @endif
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información del cliente
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formCompany" action='addCompany' method='post' enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input class="form-control" name="company_name" id="company_name" placeholder="Nombre de la compañia">
                        </div>
                        <div class="form-group">
                            <label>Logo:</label>
                            <p>
                                <img src="{{ URL::asset('img/img_icon.png') }}" id='imgLogoCompany'class="inline-block" height="80">
                                <input id='fileLogoCompany' name="fileLogoCompany" class="inline-block" type="file">
                            </p>
                        </div>
                        <!-- panel-default -->
                        <div class="panel panel-default">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                Información del usuario
                            </div>
                            <!-- /.panel-heading -->
                            <!-- panel-body -->
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Nombre para mostrar:</label>
                                    <input class="form-control" name="name" id="name" placeholder="Nombre para mostrar">
                                </div>
                                <div class="form-group">
                                    <label>Correo electrónico:</label>
                                    <input class="form-control" name="email" id="email" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group">
                                    <label>Nombre de usuario:</label>
                                    <input class="form-control" name="username" id="username" placeholder="Nombre de usuario">
                                </div>
                                <div class="form-group">
                                    <label>Contraseña:</label>
                                    <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
                                </div>
                                <div class="form-group">
                                    <label>Confirmar contraseña:</label>
                                    <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Contraseña">
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel-default -->

                        <button type="button" class="btn btn-default" id='btnCacelCompany'>Cancelar</button>
                        <button type="submit" class="btn btn-primary" id='btnAddCompany'>Registrar</button>
                    </form>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

@overwrite