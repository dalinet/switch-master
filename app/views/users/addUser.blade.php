@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/users.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Registro de Usuarios</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información del usuario
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formUser" action='addUser' method='post'>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <select class="form-control" name="selectCompanies" id="selectCompanies">
                                <option value=''>Selecciona un cliente</option>
                                @foreach ($companies as $company)
                                    <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
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

                        <button type="button" class="btn btn-default" id='btnCancelUser'>Cancelar</button>
                        <button type="submit" class="btn btn-primary" id='btnAddUser'>Registrar</button>
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