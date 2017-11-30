@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/campaigns.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Campañas</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <!-- /.row -->
    <div class="row" id="divCampaignTble">
        <div class="col-lg-12">
            <div class="alert alert-success alert-dismissable" id="alert-update-campaign">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                La campaña fue actualizada con éxito.
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Campañas registradas
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <form role="form" id="formSearchCampaign" name="formSearchCampaign" method='post'>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <select class="form-control" name="selectCompaniesMain" id="selectCompaniesMain">
                                <option value=''>Selecciona un cliente</option>
                                @foreach ($info[ "companies" ] as $company)
                                    <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                                @endforeach

                                @if( count( $info[ "companies" ] ) > 0 )
                                    <option value='all'>Todos los clientes</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label><input type="checkbox" id="all-dates"/> Todas las fechas</label>
                        </div>

                        <div class="form-group">
                            <label>Fechas:</label>
                            <div class="input-daterange input-group" id="findDateRange">
                                <input type="text" class="input-sm form-control" name="campaign_find_start" id="campaign_find_start" placeholder="Fecha de inicio" value=""/>
                                <span class="input-group-addon">a</span>
                                <input type="text" class="input-sm form-control" name="campaign_find_end" id="campaign_find_end" value="" placeholder="Fecha de fin" />
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="btn-find-campaigns">Buscar</button>
                        </div>
                    </form>

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Campaña</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    <th>Ver URLS</th>
                                </tr>
                            </thead>
                            <tbody id="dTBody">
                                {{-- @foreach( $info[ 'campaigns' ] as $campaign ) --}}
                                    {{-- <tr>
                                        <td>
                                            {{ $campaign->company_name }}
                                        </td>
                                        <td id='tdCampaignName{{ $campaign->campaign_id }}'>
                                            {{ $campaign->campaign_name }}
                                        </td>
                                        <td>
                                            {{ $campaign->start }}
                                        </td>
                                        <td>
                                            {{ $campaign->end }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-info btn-edit-campaign" campaign_id="{{ $campaign->campaign_id }}">Editar</button>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-danger" data-campaign_id="{{ $campaign->campaign_id }}" data-toggle="modal" data-target="#deleteCampaignModal">Eliminar</button>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline btn-view-url btn-success" campaign_id="{{ $campaign->campaign_id }}">Ver URLS</button>
                                        </td>
                                    </tr> --}}
                                {{-- @endforeach --}}

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
    <div class="row" id="divEditCampaign">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Información de la campaña
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <form role="form" id="formUpdateCampaign" name="formUpdateCampaign" method='post'>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <select class="form-control" name="selectCompanies" id="selectCompanies">
                                <option value=''>Selecciona un cliente</option>
                                @foreach ($info[ "companies" ] as $company)
                                    <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="campaign_id" id="campaign_id" value="">
                        </div>
                        <div class="form-group" >
                            <label>Usuarios:</label>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            </p>
                            <p id="pUsers">
                            </p>
                            <p id="userError">
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Nombre de la campaña:</label>
                            <input class="form-control" name="campaign_name" id="campaign_name" placeholder="Nombre de la campaña">
                        </div>
                        <div class="form-group">
                            <label>Imagen:</label>
                            <p>
                                <img src="{{ URL::asset('img/img_icon.png') }}" id='imgCampaign'class="inline-block img-responsive" height="352" width="726">
                            </p>
                            <p>
                                <input id='fileImgCampaign' name="fileImgCampaign" class="inline-block" type="file">
                            </p>
                        </div>
                        <div class="form-group" id="fontFIlesContainer">
                            <label>Fuentes:</label>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                            </p>
                            <div class="list-group w-300p" id="fonts-list">

                            </div>

                            <p id="pFont0">
                                <input id='fileFontCampaign0' name="fileFontCampaign0" class="file-font campaign-fonts-group"type="file">
                            </p>
                        </div>
                        <div class="form-group">
                            <p>
                                <button type="button" class="btn btn-info" id='btnAddFontFile'>Agregar otra fuente</button>
                            </p>
                        </div>

                        <div class="form-group" >
                            <label>Anuncios Precargados:</label>

                            <div id="divInsertedAds">
                                <div class="row">

                                </div>
                            </div>


                        </div>


                         <div class="form-group" id="adFilesContainer">
                            <label>Anuncios Precargados (Imagenes):</label>
                            <p id="pAd0">
                                <input id='fileAdCampaign0' name="fileAdCampaign0" class="file-ad campaign-ads-group" type="file" accept="image/*" >
                            </p>
                        </div>
                        <div class="form-group">
                            <p>
                                <button type="button" class="btn btn-info" id='btnAddAd'>Agregar otro anuncio</button>
                            </p>
                        </div>

                        <div class="form-group" id="adFilesContainerVid">  
                            <label>Anuncios Precargados (Videos):</label>
                            <p id="pAdvid0">
                                <video src="" id="vidrec0"   data-input="fileAdCampaignVidTum0" controls width="384" height="216" ></video>
                                <input type="hidden" id="fileAdCampaignVidTum0" class="tum-ad-vid"  value="" >
                                <input id='fileAdCampaignVid0' data-idlink="vidrec0" name="fileAdCampaignVid0" class="file-ad-vid campaign-ads-group" type="file" accept="video/mp4" >
                            </p>

                        </div>

                        <div class="form-group">
                            <p>
                                <button type="button" class="btn btn-info" id='btnAddAdVideo'>Agregar otro Video</button>
                            </p>
                        </div>


                        <div class="form-group">
                            <label>¿Cuantas URLs necesitas para la campaña (IMAGEN) ?  (Sumando todas las pantallas):</label>
                            <input class="form-control" name="cant_links" id="cant_links">
                        </div>

                        <div class="form-group">
                            <label>¿Cuantas URLs necesitas para la campaña (VIDEO)? (Sumando todas las pantallas):</label>
                            <input class="form-control editar" name="cant_linksVideo" id="cant_linksVideo">
                        </div>

                        <div class="form-group">
                            <label>Fechas:</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="campaign_start" id="campaign_start" placeholder="Fecha de inicio" value=""/>
                                <span class="input-group-addon">a</span>
                                <input type="text" class="input-sm form-control" name="campaign_end" id="campaign_end" value="" placeholder="Fecha de fin" />
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="btn-cancel-edit-campaign" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
                            {{-- <button type="button" class="btn btn-primary" id="btnTest">TEST</button> --}}
                        </div>

                    </form>

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

    <!-- Modal ver URLS de la Campaña -->
    <div class="modal fade" id="campaignURLModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">URLs de la campaña</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                       Links de la campañas
                    </div>

                    <ol id="links-list">

                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Eliminar Campaña -->
    <div class="modal fade" id="deleteCampaignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmación</h4>
                </div>
                <div class="modal-body">
                    ¿Esta seguro que desea eliminar <strong id='modalCampaignName'></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id='btnDeleteCampaign' campaign_id=''>Eliminar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@overwrite
