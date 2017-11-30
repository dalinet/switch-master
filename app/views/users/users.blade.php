@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/users.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Usuarios</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row" id="divUsersList">
        <div class="col-lg-12">

            @if( Session::get('inserted') == true  )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se registro el usuario con éxito.
                </div>
            @endif

            @if( Session::get('updated') == true  )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se realizó la actualización con éxito.
                </div>
            @endif

            <div class="alert alert-success alert-dismissable" id="alertSendEmailSuccess">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                El correo electrónico ha sido enviado con exito.
            </div>

            <div class="alert alert-danger alert-dismissable" id="alertSendEmailError">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Ocurrio un error al enviar el correo electrónico, por favor intenta de nuevo.
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Usuarios registrados
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Nombre de usuario</th>
                                    <th>Email</th>
                                    <th>Compañia</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    <th>Enviar contraseña</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $info[ "users" ] as $user )
                                    <tr>
                                        <td id='tdName{{ $user->costumer_id }}'>
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-center" id='tdUserName{{ $user->costumer_id }}'>
                                            {{ $user->user_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $user->email }}
                                        </td>
                                        <td class="text-center">
                                            {{ $user->company_name }}
                                        </td>
                                        <td class="text-center"> 
                                            <button type="button" class="btn btn-outline btn-info btn-edit-user" costumer_id="{{ $user->costumer_id }}">Editar</button> 
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-danger" data-costumer_id="{{ $user->costumer_id }}" data-toggle="modal" data-target="#deleteUserModal">Eliminar</button> 
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-success" data-costumer_id="{{ $user->costumer_id }}" data-toggle="modal" data-target="#sendPasswordModal">Enviar contraseña</button> 
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
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row" id="divEditUser">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información del usuario
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formUpdateUser" action='updateUser' method='post'>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <select class="form-control" name="selectCompanies" id="selectCompanies">
                                <option value=''>Selecciona un cliente</option>
                                @foreach ( $info[ "companies" ] as $company )
                                    <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre para mostrar:</label>
                            <input class="form-control" name="name" id="name" placeholder="Nombre para mostrar">
                            <input name="costumer_id" id="costumer_id" type="hidden">
                        </div>
                        <div class="form-group">
                            <label>Correo electrónico:</label>
                            <input class="form-control" name="email" id="email" placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <label>Nombre de usuario:</label>
                            <input class="form-control" name="username" id="username" placeholder="Nombre de usuario">
                        </div>
                        {{-- <div class="form-group">
                            <label>Contraseña:</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
                        </div>
                        <div class="form-group">
                            <label>Confirmar contraseña:</label>
                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Contraseña">
                        </div> --}}

                        <button type="button" class="btn btn-default" id='btnCancelUpdtUser'>Cancelar</button>
                        <button type="submit" class="btn btn-primary" id='btnEditUser'>Guardar</button>
                    </form>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <!-- Modal Eliminar Campaña -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    ¿Esta seguro que desea eliminar <strong id='modalUserName'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnDeleteUser' costumer_id=''>Eliminar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal para confirmar enviar el correo con los accesos -->
    <div class="modal fade" id="sendPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    ¿Esta seguro que desea enviar la contraseña de su cuenta al usuario: <strong id='modalSPUserName'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnSendPassword' costumer_id=''>Enviar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@overwrite