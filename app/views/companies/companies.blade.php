@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/companies.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Clientes</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row" id="divCompanies">
        <div class="col-lg-12">
            @if( Session::get('status') === true )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se realizó la actualización con exito.
                </div>
            @elseif( Session::get('status') === false )
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Ocurrio un error al actualizar.
                </div>
            @endif

            @if( Session::get('inserted') === true )
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Se registró con exito el cliente.
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    Clientes registrados
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Compañia</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $companies as $company )
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ URL::asset( $company->logo_src ) }}" height="30">
                                        </td>
                                        <td id='tdCompanyName{{ $company->company_id }}'>
                                            {{ $company->name }}
                                        </td>
                                        <td class="text-center"> 
                                            <button type="button" class="btn btn-outline btn-info btn-edit-company" company_id="{{ $company->company_id }}">Editar</button> 
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-danger" data-company_id="{{ $company->company_id }}" data-toggle="modal" data-target="#deleteCompanyModal">Eliminar</button> 
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
    <div class="row" id="divEditCompany">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información del cliente
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <form role="form" id="formUpdateCompany" action='updateCompany' method='post' enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input class="form-control" name="company_name" id="company_name" placeholder="Nombre de la compañia">
                            <input type="hidden" name="company_id" id="company_id" value="" >
                        </div>
                        <div class="form-group">
                            <label>Logo:</label>
                            <p>
                                <img src="{{ URL::asset('img/img_icon.png') }}" id='imgLogoCompany'class="inline-block" height="80">
                                <input id='fileLogoCompany' name="fileLogoCompany" class="inline-block" type="file">
                            </p>
                        </div>

                        <button type="button" class="btn btn-default" id='btnCancelCompany'>Cancelar</button>
                        <button type="submit" class="btn btn-primary" id='btnAddCompany'>Actualizar</button>
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
    <div class="modal fade" id="deleteCompanyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    ¿Esta seguro que desea eliminar <strong id='modalCompanyName'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnDeleteCompany' company_id=''>Eliminar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@overwrite