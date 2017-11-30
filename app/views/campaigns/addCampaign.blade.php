@extends('layouts.master')

@section('page_scripts')
    <script src="{{ URL::asset('js/campaigns.js') }}"></script>
@overwrite

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Registro de Campañas</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default" id="divRegCampaign">
                <div class="panel-heading">
                    Información de la campaña
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                        
                    <form role="form" id="formCampaign" name="formCampaign" action='/addscreen' method='post'>
                        <div class="form-group">
                            <label>Cliente:</label>
                            <select class="form-control" name="selectCompanies" id="selectCompanies">
                                <option value=''>Selecciona un cliente</option>
                                @foreach ($companies as $company)
                                    <option value='{{ $company->company_id }}'>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="divUsersForCampaign">
                            <label>Usuarios:</label>
                            <p>
                                Selecciona los usuarios permitidos para la campaña.
                            </p>
                            <p id="pUsers">
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Nombre de la campaña:</label>
                            <input class="form-control" name="campaign_name" id="campaign_name" placeholder="Nombre de la campaña">
                        </div>
                        <div class="form-group">
                            <label>Imagen:</label>
                            <p>
                                <img src="{{ URL::asset('img/img_icon.png') }}" id='imgCampaign'class="inline-block" height="352" width="726">
                            </p>
                            <p>
                                <input id='fileImgCampaign' name="fileImgCampaign" class="inline-block" type="file">
                            </p>
                        </div>
                        <div class="form-group" id="fontFIlesContainer">
                            <label>Fuentes:</label>
                            <p>
                                Selecciona las tipografías que utiliza la campaña, recomendamos hacerlo para tenerlas disponibles en caso de ajustes y cambios.
                            </p>
                            <p id="pFont0">
                                <input id='fileFontCampaign0' name="fileFontCampaign0" class="file-font campaign-fonts-group" type="file" >
                            </p>
                        </div>

                        <div class="form-group">
                            <p>
                                <button type="button" class="btn btn-info" id='btnAddFontFile'>Agregar otra fuente</button>
                            </p>
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
                                <input type="hidden" id="fileAdCampaignVidTum0" class="tum-ad-vid" value="" >
                                <input id='fileAdCampaignVid0' data-idlink="vidrec0" name="fileAdCampaignVid0" class="file-ad-vid campaign-ads-group" type="file" accept="video/mp4" >
                            </p>

                        </div>

                        <div class="form-group">
                            <p>
                                <button type="button" class="btn btn-info" id='btnAddAdVideo'>Agregar otro Video</button>
                            </p>
                        </div>
                        
                      

                        

                        <div class="form-group">
                            <label>¿Cuantas URLs necesitas para la campaña (IMAGEN)? (Sumando todas las pantallas):</label>
                            <input class="form-control" name="cant_links" id="cant_links">
                        </div>
                        
                         <div class="form-group">
                            <label>¿Cuantas URLs necesitas para la campaña (VIDEO)? (Sumando todas las pantallas):</label>
                            <input class="form-control" name="cant_linksVideo" id="cant_linksVideo">
                        </div>

                        <div class="form-group">
                            <label>Fechas:</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="input-sm form-control" name="campaign_start" id="campaign_start" placeholder="Fecha de inicio"/>
                                <span class="input-group-addon">a</span>
                                <input type="text" class="input-sm form-control" name="campaign_end" id="campaign_end"  placeholder="Fecha de fin" />
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aceptar</button>
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

            <div class="panel panel-default" id="divLinksCampaign">
                <div class="panel-heading">
                    URLs registrados para la campaña
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="alert alert-success">
                        orem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
                    </div>

                    <ol id="links-list">
                        
                    </ol>
                    
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

    <!-- Modal Agregar una Campaña -->
    <div class="modal fade" id="addCampaignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-90w">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Registro de una nueva campaña</h4>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSubmitCampaign" class="btn btn-primary">Aceptar</button>
                    <button type="button" id="btnUpdateScreen" class="btn btn-info hide">Actualizar</button>
                    <button type="reset"  id="btnResetScreen" class="btn btn-default">Cancelar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal Agregar una Campaña -->

@overwrite