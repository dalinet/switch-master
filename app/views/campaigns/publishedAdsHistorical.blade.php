@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/campaigns/published-ads-historical.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Historial de Anuncios Publicados</h2>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row" id="divCampaignTble">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Anuncios
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
                            <option value=''>Selecciona un campaña</option>
                        </select>
                    </div>

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Anuncio</th>
                                    <th>Pantalla</th>
                                    <th>Creación</th>
                                </tr>
                            </thead>
                            <tbody id="dTBody">
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

@overwrite