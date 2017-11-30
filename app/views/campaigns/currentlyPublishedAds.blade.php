@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/campaigns/currently-published-ads.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Anuncios Actualmente Publicados</h2>
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
                                    <th>Eliminar</th>
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

    <!-- Modal Eliminar un anuncio publicado -->
    <div class="modal fade" id="deletePublishAd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    <p>
                        ¿Esta seguro que desea eliminar este anuncio de la pantalla <strong id="screenName"></strong>?
                    </p>
                    <img id="modalDelImg" src="" class="img-responsive">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnDelURL' campaign_url_id=''>Eliminar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@overwrite