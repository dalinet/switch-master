<?php

class CampaignController extends AuthController {

	/*
	|--------------------------------------------------------------------------
	| Screen Controller
	|--------------------------------------------------------------------------
	|
	| Este controlador maneja la administración de las pantallas como lo son
	| registros, actualizaciones y eliminación de registros. De igual forma
	| hace las consultas necesarias sobre la tabla de pantallas
	|
	*/

	/**
	 * Se hace la llamada al constructor de AuthController
	 * el cual protege el acceso a los metodos del controlador
	 * al requerir que haya un usuario autenticado
	 *
	 * @return void
	 */
	public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Se muestra la vista para administrar
	 * campañas
	 *
	 * @return View
	 */
	public function getViewCampaign()
	{

		$campaigns = Campaign::getCampaigns();
		$companies = Company::getCompanies();

		$info = array( 'campaigns' => $campaigns,
					   'companies' => $companies );

		return View::make('campaigns.campaign')->with( 'info', $info );
	}

	/**
	 * Se muestra la vista para registrar
	 * una campañas
	 *
	 * @return View
	 */
	public function getViewAddCampaign()
	{
		$companies = Company::getCompanies();

		return View::make('campaigns.addCampaign')->with( 'companies', $companies );
	}

	/**
	 * Se muestra la vista para registrar
	 * una campañas
	 *
	 * @return View
	 */
	public function getViewURLtoScreen()
	{
		// $oCampaigns = Campaign::getCampaigns();
		$oCompanies = Company::getCompaniesWithCampaigns();
		$oScreens   = Screen::getScreens();

		return View::make('campaigns.addUrlToScreen')
					->with( 'companies', $oCompanies )
					->with( 'screens', $oScreens );
	}

	/**
	 * Se muestra la vista para visualizar
	 * el Historial de anuncios creados
	 *
	 * @return View
	 */
	public function getViewCreatedAdsHistorical()
	{
		$oCompanies = Company::getCompanies();

		return View::make('campaigns.createdAdsHistorical')
					->with( "companies", $oCompanies );
	}

	/**
	 * Se muestra la vista para visualizar
	 * el Historial de anuncios publicados
	 *
	 * @return View
	 */
	public function getViewPublishedAdsHistorical()
	{
		$oCompanies = Company::getCompanies();

		return View::make('campaigns.publishedAdsHistorical')
					->with( "companies", $oCompanies );
	}

	/**
	 * Se muestra la vista para visualizar
	 * los anuncios actualmente publicados
	 *
	 * @return View
	 */
	public function getViewCurrentlyPublishedAds()
	{
		$oCompanies = Company::getCompanies();

		return View::make('campaigns.currentlyPublishedAds')
					->with( "companies", $oCompanies );
	}

	/**
	 * Se valida la dimensión de la imagen de
	 * la campaña
	 */
	public function validateCampaingImg(){

		if( Input::hasFile( 'campagnImage' ) ){
			$fCampaginFile  = Input::file( "campagnImage" );
			$sImgOrigName   = $fCampaginFile->getClientOriginalName();
			$sImgExtension  = $fCampaginFile->getClientOriginalExtension();

			$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			if( in_array( $sImgExtension, $allowedImgExt) ){

				if( $sImgOrigName != '' && !empty( $sImgOrigName ) ){

					if( $fCampaginFile->isValid() ){
						// Se obtienen las dimensiones de la imagen
						$aImageSize = getimagesize( $fCampaginFile );

						$iWidth     = $aImageSize['width'];
						$iHeight    = $aImageSize['height'];

						if( ( $iWidth == 726 && $iHeight == 352 ) || ( $iWidth == 1152 && $iHeight == 559 ) || ( $iWidth == 1680 && $iHeight == 815 ) ){
							return Response::json( true );
						}else{
							return Response::json( false );
						}
					}
				}
			}
		}else{
			return Response::json( "No es un archivo" );
		}

	}

	/**
	 * Se obtienen los usuario de
	 * una compañia
	 * @return [json] [usuarios]
	 */
	public function getCompanyUsers()
	{
		if( Request::ajax() ){

			$company_id = Input::get( 'company_id');

			$users = Costumer::getCostumersByCompany( $company_id );

			if( count( $users ) > 0 ){
				$info  = array( 'status' => true,
							'users'  => $users->toArray()
						  );
			}else{
				$info  = array( 'status' => false );
			}


			return Response::json( $info );

		}

	}

	/**
	 * Se obtienen las campañas de un cliente/campaña
	 * @return [json] [campañas]
	 */
	public function getCampaignsByCompany()
	{
		if( Request::ajax() ){

			$companyId = Input::get( 'companyId');

			$campaigns = Campaign::getCampaignsByCompany( $companyId );

			if( count( $campaigns ) > 0 ){
				$info  = array( 'status' => true,
							'campaigns'  => $campaigns->toArray()
						  );
			}else{
				$info  = array( 'status' => false );
			}


			return Response::json( $info );

		}

	}

	/**
	 * Se obtienen las campañas de un cliente/campaña
	 * @return [json] [campañas]
	 */
	public function getCampaignsBySearch()
	{
		if( Request::ajax() ){

			$companyId = Input::get( 'companyId');
			$startDate = Input::get( 'startDate');
			$endDate   = Input::get( 'endDate');
			$allDates  = Input::get( 'allDates');

			$campaigns = Campaign::getCampaignsBySearch( $companyId, $startDate, $endDate, $allDates );

			if( count( $campaigns ) > 0 ){
				$info  = array( 'status' => true,
							'campaigns'  => $campaigns->toArray()
						  );
			}else{
				$info  = array( 'status' => false );
			}


			return Response::json( $info );

		}

	}

	/**
	 * Se agrega una campaña
	 *
	 * @return json
	 */
	public function addCampaign()
	{


		require ("ttfInfo.class.php");
		$oTtfInfo = new ttfInfo;

		if( Request::ajax() ){
			$sCampaignName  = Input::get( 'campaign_name' );
			$iCompanyId     = Input::get( 'company' );
			$iLinks         = Input::get( 'links' );
			$iLinks_videos  = Input::get( 'links_videos' );

			$sCampaignStart = Input::get( 'campaign_start' );
			$sCampaignEnd   = Input::get( 'campaign_end' );

			$sImgPath;
			$imgExtension;
			$aFontId = array();

			$iNumFontFiles = Input::get( 'numFontFiles' );
			// Extensiones permitidas para las fuentes
			$aAllowedFontExt  = array( 'ttf', 'otf' );

			// Rutas para almacenar las fuentes
			$sFontRelativePath = '/uploads/fonts/';
			$sFontAbsolutePath = public_path() . $sFontRelativePath;

			if ( $iNumFontFiles > 0) {
				// Subiendo fuentes
				for ($i=0; $i < $iNumFontFiles; $i++) {
					$sFontFileName = 'fileFontCampaign' . $i;

					if( Input::hasFile( $sFontFileName ) ) {
						// Fuentes para las campañas
						$oFontFile      = Input::file( $sFontFileName );
						$sFontOrigName  = $oFontFile->getClientOriginalName();
						$sFontExtension = $oFontFile->getClientOriginalExtension();

						if( in_array( $sFontExtension, $aAllowedFontExt) ){

							if( $oFontFile->isValid() ){

								if( ( $sFontOrigName != '' && !empty( $sFontOrigName ) ) ){
									// Se obtiene el PostScriptName de la fuente
									$oTtfInfo->setFontFile( $oFontFile );

									$sfontFullName       = $oTtfInfo->getFullFontName();
									$sfontPostScriptName = $oTtfInfo->getFontPostScriptName();
									$sFontFileNewName    = $sfontPostScriptName . "." . $sFontExtension;

									$oFindFont = Font::select( 'font_id' )
													   ->where('postscript_name', '=', $sfontPostScriptName)
													   ->get();

									if( count( $oFindFont ) > 0 ){
										array_push( $aFontId, $oFindFont[0]->font_id );
									}else{
										// Se mueve la Fuente
										$bFontUploadSuccess = $oFontFile->move( $sFontAbsolutePath, $sFontFileNewName );

										if( $bFontUploadSuccess ){
											// Ruta relativa de los archivos subidos
											$sFontPath = $sFontRelativePath . $sFontFileNewName;

											// Registro de la fuente
											$oFont                  = new Font;
											$oFont->name            = $sfontFullName;
											$oFont->postscript_name = $sfontPostScriptName;
											$oFont->font_src        = $sFontPath;
											$bStatus_font           = $oFont->save();

											$iFont_id = $oFont->font_id;

											if( $bStatus_font ){
												array_push( $aFontId, $iFont_id );
											}else{
												array_push( $aFontId, false );
											}

										} // ./ Upload Success

									} // ./ if count( $oFindFont )

								} // /. No empty name

							} // /. Is Valid

						} // ./ Allowed File

					} // ./ Has File
				}
			}

			// Se sube la imagen de la campaña
			if( Input::hasFile( 'imgCampaign' ) ) {
				// Imagen para la campaña
				$imgFile      = Input::file( 'imgCampaign' );
				$imgOrigName  = $imgFile->getClientOriginalName();
				$imgExtension = $imgFile->getClientOriginalExtension();

				$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

				$imgRelativePath  = '/uploads/campaigns/img/';
				$imgAbsolutePath  = public_path() . $imgRelativePath;

				if( in_array( $imgExtension, $allowedImgExt) ){

					if( $imgOrigName != '' && !empty( $imgOrigName ) ){

						if( $imgFile->isValid() ){
							$imgFileNewName = "img_campaign_" . time() . rand();

							$imgFileNewFullName = $imgFileNewName . "." . $imgExtension;
							// Se sube la Imagen
							$imgUploadSuccess  = $imgFile->move( $imgAbsolutePath, $imgFileNewName );

							if( $imgUploadSuccess ){
								// Ruta relativa de los archivos subidos
								$sImgPath  = $imgRelativePath . $imgFileNewName;

								// Conversión de la imagen
								$oImage = new Imagick( public_path() . $sImgPath );
								// $oImage->resizeImage( 726, 352, imagick::FILTER_LANCZOS, 0.9, true);
								$oImage->scaleImage( 800, 384 );
								$oImage->setImageFormat( 'jpg' );
								$oImage->setImageCompressionQuality( 95 );
								$oImage->writeImage( $imgAbsolutePath . $imgFileNewName . '.jpg' );

								$sImgNewPath = $imgRelativePath . $imgFileNewName . '.jpg';

								$sImgPath = $sImgNewPath;

							} // ./ Upload Success

						} // /. isValid

					} // /. No empty name

				} // ./ Allowed File

			} // ./ Has File

			/**
			 * Se registra la campaña
			 */
			if( /*(*/ $sImgPath != "" /*)*/ /*&& ( count( $aFontId ) > 0)*/ ){

				/**
				 *  Registro de la campaña
				 */
				$oCampaign             = new Campaign;
				$oCampaign->company_id = $iCompanyId;
				$oCampaign->name       = $sCampaignName;
				$oCampaign->image_src  = $sImgPath;
				$oCampaign->links      = $iLinks;
				$oCampaign->links_video= $iLinks_videos;
				$oCampaign->start      = $sCampaignStart;
				$oCampaign->end        = $sCampaignEnd;
				$bStatus_campaign      = $oCampaign->save();

				$iCampaign_id = $oCampaign->campaign_id;

				/**
				 *  Se registran las fuentes subidas a la campaña
				 */
				$iNumFonts = count( $aFontId );

				if ( $iNumFonts > 0 ) {
					for ($i=0; $i < $iNumFonts; $i++) {
						$oFontCampaign              = new FontCampaign;
						$oFontCampaign->font_id     = $aFontId[ $i ];
						$oFontCampaign->campaign_id = $iCampaign_id;
						$bStatus_fontCampaign       = $oFontCampaign->save();
					}
				}

				// Se crea el archivo ZIP de las fuentes
				CampaignController::createFontsZip( $iCampaign_id );

				/**
				 * Se registran las URL'S de la campaña
				 */
				$aURLS       = array();
				$iNumLinks   = Input::get( 'links' );
				$sURLPath    = "/campaigns/" . $iCampaign_id . "/urls/";
				$sOriginFile = public_path() . $sImgPath;
				$sHost       = "http://" . $_SERVER[ 'HTTP_HOST' ];

				File::makeDirectory( public_path() . $sURLPath, 0775, true );

				for ($j=0; $j < $iNumLinks; $j++) {
					$sURLFile = $sURLPath . "url_" . $j . "." . "jpg";
					$sFullURLFile = public_path() . $sURLFile;

					copy( $sOriginFile, $sFullURLFile );

					$oCampaignURL              = new CampaignURL;
					$oCampaignURL->campaign_id = $iCampaign_id;
					$oCampaignURL->url         = $sURLFile;
					$oCampaignURL->previo      = $sURLFile;
					$oCampaignURL->formato     = 'image';
					$bStatus_fontCampaign      = $oCampaignURL->save();

					array_push( $aURLS, $sHost . $sURLFile);
				}



				/**
				 *  Se registran los usuarios de la campaña
				 */
				$iNumUsers = Input::get( "numCheckedUsers" );

				for ($i=0; $i < $iNumUsers; $i++) {
					$iCostumerId = Input::get( "checkUser" . $i );

					$oCampCostumer              = new CampaignCostumer;
					$oCampCostumer->costumer_id = $iCostumerId;
					$oCampCostumer->campaign_id = $iCampaign_id;
					$bStatusCampCostumer        = $oCampCostumer->save();
				}

				/**
				 * Se suben los anuncios precargados
				 */
				$aAdId = array();

				$iNumAdFiles = Input::get( 'numAdFiles' );
				// Extensiones permitidas para registrar el anuncio
				$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

				// Rutas para almacenar los Ads
				$sAdRelativePath = "/campaigns/" . $iCampaign_id . "/ads/";
				$sAdAbsolutePath = public_path() . $sAdRelativePath;

				// Se crea el directorio para guardar Ads
				File::makeDirectory( $sAdAbsolutePath, 0775, true );

				// Subiendo Ads
				for ($i=0; $i < $iNumAdFiles; $i++) {
					$sAdFileName = 'fileAdCampaign' . $i;

					if( Input::hasFile( $sAdFileName ) ) {
						// Fuentes para las campañas
						$oAdFile      = Input::file( $sAdFileName );
						$sAdOrigName  = $oAdFile->getClientOriginalName();
						$sAdExtension = $oAdFile->getClientOriginalExtension();

						if( in_array( $sAdExtension, $allowedImgExt) ){

							if( $oAdFile->isValid() ){

								if( ( $sAdOrigName != '' && !empty( $sAdOrigName ) ) ){

									$sAdFileNewName     = "preloaded_ad_" . time() . rand();
									$sAdFileNewFullName = $sAdFileNewName . "." . $sAdExtension;

									// Se mueve la Fuente
									$bAdUploadSuccess = $oAdFile->move( $sAdAbsolutePath, $sAdFileNewFullName );

									if( $bAdUploadSuccess ){
										// Ruta relativa de los archivos subidos
										$sAdPath = $sAdRelativePath . $sAdFileNewFullName;

										// Conversión de la imagen
										$oImage = new Imagick( public_path() . $sAdPath );
										$oImage->scaleImage( 726, 352 );
										$oImage->setImageFormat( 'jpg' );
										$oImage->setImageCompressionQuality( 95 );
										$oImage->writeImage( $sAdAbsolutePath . $sAdFileNewName . '.jpg' );

										$sAdNewPath = $sAdRelativePath . $sAdFileNewName . '.jpg';

										// Registro de la fuente
										$oAd              = new Ads;
										$oAd->campaign_id = $iCampaign_id;
										$oAd->name        = $sAdFileNewFullName;
										$oAd->image_src   = $sAdNewPath;
										$oAd->type        = "preloaded";
										$bStatus_ad       = $oAd->save();

										$iAdId = $oAd->ad_id;

										if( $bStatus_ad ){
											array_push( $aAdId, $iAdId );
										}else{
											array_push( $aAdId, false );
										}

									} // ./ Upload Success

								} // /. No empty name

							} // /. Is Valid

						} // ./ Allowed File

					} // ./ Has File
				}


				/**
				 * Se suben los anuncios precargados VIDEOS
				 */
				$aAdId = array();

				$iNumAdFiles = Input::get( 'numAdFilesVid' );
				// Extensiones permitidas para registrar el anuncio
				$allowedImgExt  = array( 'mp4','mov' );

				// Rutas para almacenar los Ads
				$sAdRelativePath = "/campaigns/" . $iCampaign_id . "/ads/";
				$sAdAbsolutePath = public_path() . $sAdRelativePath;

				// Se crea el directorio para guardar Ads
				//File::makeDirectory( $sAdAbsolutePath, 0775, true );

				// Subiendo Ads

				// Videos fuente
				$sVideoSrc 		="";
				$sVideoTumSrc	="";

				for ($i=0; $i < $iNumAdFiles; $i++) {
					$sAdFileName = 'fileAdCampaignVid' . $i;
					$sAdFileTum  = 'fileAdCampaignVidTum' . $i;

					if( Input::hasFile( $sAdFileName ) ) {
						// Fuentes para las campañas
						$oAdFile      = Input::file( $sAdFileName );
						$sAd64Tum     = Input::get( $sAdFileTum );


						$sAdOrigName  = $oAdFile->getClientOriginalName();
						$sAdExtension = $oAdFile->getClientOriginalExtension();

						if( in_array( $sAdExtension, $allowedImgExt) ){

							if( $oAdFile->isValid() ){

								if( ( $sAdOrigName != '' && !empty( $sAdOrigName ) ) ){

									$sAdFileNewName     = "preloaded_ad_vid_" . time() . rand();
									$sAdFileNewFullName = $sAdFileNewName . "." . $sAdExtension;

									// Se mueve el video
									$bAdUploadSuccess = $oAdFile->move( $sAdAbsolutePath, $sAdFileNewFullName );


									// Creamos la imagen con la cadena
									$exploded = explode(',', $sAd64Tum, 2); // limit to 2 parts, i.e: find the first comma
									$encoded = $exploded[1]; // pick up the 2nd part
									$decoded = base64_decode($encoded);
									$oImagen = imagecreatefromstring($decoded);
									$sNombreImagen = "preloaded_ad_vid_tum_" . time() . rand().".jpg";

									// Guardamos la Imagen
									imagejpeg($oImagen, $sAdAbsolutePath.$sNombreImagen  , 100 );


									if( $bAdUploadSuccess ){

										$sAdNewPath = $sAdRelativePath . $sAdFileNewName . '.'.$sAdExtension;
										$sAdNewPathOgg = $sAdRelativePath . $sAdFileNewName . '.ogg';

										// Registro de Video
										$oAd              = new Ads;
										$oAd->campaign_id = $iCampaign_id;
										$oAd->name        = $sAdFileNewFullName;
										$oAd->image_src   = $sAdNewPath;
										$oAd->url_video_ogg = $sAdNewPathOgg;
										$oAd->file_src    = $sAdRelativePath.$sNombreImagen;
										$oAd->type        = "preloaded";
										$oAd->formato     = "video";
										$bStatus_ad       = $oAd->save();

										$iAdId = $oAd->ad_id;

										// tomamos el primer video
										if($i==0){

											$sVideoSrcOgg 		=$sAdNewPathOgg;
											$sVideoSrc 		=$sAdNewPath;
											$sVideoTumSrc	=$sAdRelativePath.$sNombreImagen;

										}

										if( $bStatus_ad ){
											array_push( $aAdId, $iAdId );
										}else{
											array_push( $aAdId, false );
										}

									} // ./ Upload Success
									try{
										/*Se convierte el video en formato OGG*/
										//$commandToExecute = 'ffmpeg -i '.$sAdAbsolutePath.$sAdFileNewName.'.mp4 -c:a libvorbis -b:a 200k '.$sAdAbsolutePath.$sAdFileNewName.'.ogg';
										$commandToExecute = 'ffmpeg -i '.$sAdAbsolutePath.$sAdFileNewName.'.'.$sAdExtension.' -codec:v libtheora -qscale:v 5 -codec:a libvorbis -qscale:a 5 '.$sAdAbsolutePath.$sAdFileNewName.'.ogg';
										exec($commandToExecute);
									}catch(Exception $e){
										echo $e->getMessage();
									}
	

								} // /. No empty name

							} // /. Is Valid

						} // ./ Allowed File

					} // ./ Has File
				}

				/**
				 * Se registran las URL'S de la campaña VIDEO
				 */
				$iNumLinks   = $iLinks_videos;
				//$sURLPath    = "/campaigns/" . $iCampaign_id . "/urls/";
				$sOriginFile = public_path() . $sVideoSrc;
				$sOriginFileOgg = public_path() . $sVideoSrcOgg;
				$sHost       = "http://" . $_SERVER[ 'HTTP_HOST' ];

				//File::makeDirectory( public_path() . $sURLPath, 0775, true );

				for ($j=0; $j < $iNumLinks; $j++) {
					$sURLFile = $sURLPath . "url_vid_" . $j . "." . $sAdExtension;
					$sURLFileOGG = $sURLPath . "url_vid_" . $j . ".ogg";
					$sFullURLFile = public_path() . $sURLFile;
					$sFullURLFileOgg = public_path() . $sURLFileOGG;

					copy( $sOriginFile, $sFullURLFile );
					copy( $sOriginFileOgg, $sFullURLFileOgg );

					$oCampaignURL              = new CampaignURL;
					$oCampaignURL->campaign_id = $iCampaign_id;
					$oCampaignURL->url         = $sURLFile;
					$oCampaignURL->url_ogg	   = $sURLFileOGG;
					$oCampaignURL->previo      = $sVideoTumSrc;
					$oCampaignURL->formato     = 'video';
					$bStatus_fontCampaign      = $oCampaignURL->save();
					$idURL = $oCampaignURL->campaign_url_id;
					$urlVideoOGG =  $sHost ."/videoforscreen/".$idURL;

					//array_push( $aURLS, $sHost . $sURLFile);
					array_push( $aURLS, $urlVideoOGG);
				}

				$oCampaign     = Company::find( $iCompanyId );
				$sCostumerName = $oCampaign->name;

				$this->sendCreatedCampaignEmail( $sCostumerName, $sCampaignName );

				/**
				 * Retorno de las URLs registradas
				 */
				return Response::json( $aURLS );

			}

		} // ./ Ajax

	}

	/**
	 * Se obtienen las URLS
	 * de una campaña
	 * @return [json] [usuarios]
	 */
	public function getUrlByCampaign()
	{
		if( Request::ajax() ){

			$campaign_id = Input::get( 'campaign_id');

			$urls     = CampaignURL::getUrlByCampaign( $campaign_id )->toArray();
			$iNumUrls = count( $urls );

			// print_r( $urls );

			if( $iNumUrls > 0 ){

				/*$sHost = "http://" . $_SERVER[ 'HTTP_HOST' ];

				for ($i=0; $i < $iNumUrls; $i++) {
					$urls[ $i ][ 'url' ] = $sHost . $urls[ $i ][ 'url' ];
					// echo $urls[ $i ][ 'url' ];
				}*/

				$info  = array( 'status' => true,
							    'urls'  => $urls
						  );
			}else{
				$info  = array( 'status' => false );
			}

			return Response::json( $info );

		}

	}

	/**
	 * Se elimina una campaña
	 */
	public function deleteCampaign()
	{
		if( Request::ajax() ){

			$campaign_id = Input::get( 'campaign_id');

			$bDeleteStatus = Campaign::deleteCampaign( $campaign_id );

			return Response::json( $bDeleteStatus );

		}

	}

	/**
	 * Se edita una campaña
	 */
	public function getCampaignInfo()
	{
		if( Request::ajax() ){

			$campaign_id = Input::get( 'campaign_id');

			$jsonInfo = Campaign::getCampaignInfo( $campaign_id );

			return Response::json( $jsonInfo );

		}

	}

	/**
	 *  Se actualiza
	 *  una campaña
	 */
	public function updateCampaign()
	{

		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		if( Request::ajax() ){
			require ("ttfInfo.class.php");
			$oTtfInfo = new ttfInfo;

			/**
			 * Se capturan los datos que llegaron
			 */
			$iCampaign_id   = Input::get( 'campaign_id' );
			$sName          = Input::get( 'campaign_name' );
			$iLinks         = Input::get( 'cant_links' );
			$iLinks_videos  = Input::get( 'links_videos' );

			$sStart         = Input::get( 'campaign_start' );
			$sEnd           = Input::get( 'campaign_end' );

			$jFontsChanges = json_decode( Input::get( 'fontsChanges' ) );
			$jAdsChanges   = json_decode( Input::get( 'adsChanges' ) );

			$bUpdtImg = false;
			$sNewImgPath;

			// Se sube la imagen de la campaña
			if( Input::hasFile( 'imgCampaign' ) ) {
				// Imagen para la campaña
				$imgFile      = Input::file( 'imgCampaign' );
				$imgOrigName  = $imgFile->getClientOriginalName();
				$imgExtension = $imgFile->getClientOriginalExtension();

				$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

				$imgRelativePath  = '/uploads/campaigns/img/';
				$imgAbsolutePath  = public_path() . $imgRelativePath;

				if( in_array( $imgExtension, $allowedImgExt) ){

					if( $imgOrigName != '' && !empty( $imgOrigName ) ){

						if( $imgFile->isValid() ){
							$imgFileNewName     = "img_campaign_" . time() . rand();
							$imgFileNewFullName = $imgFileNewName . "." . $imgExtension;

							// Se sube la Imagen
							$imgUploadSuccess  = $imgFile->move( $imgAbsolutePath, $imgFileNewFullName );

							if( $imgUploadSuccess ){
								// Ruta relativa de los archivos subidos
								$sNewImgPath  = $imgRelativePath . $imgFileNewFullName;

								$bUpdtImg = true;


								// Conversión de la imagen
								$oImage = new Imagick( public_path() . $sNewImgPath );
								// $oImage->resizeImage( 726, 352, imagick::FILTER_LANCZOS, 0.9, true);


								$oImage->scaleImage( 726, 352 );


								// $oImage->scaleImage( 726, 352 );
								$oImage->setImageFormat( 'jpg' );
								$oImage->setImageCompressionQuality( 95 );
								$oImage->writeImage( $imgAbsolutePath . $imgFileNewName . '.jpg' );

								$sImgNewPath = $imgRelativePath . $imgFileNewName . '.jpg';

								$sNewImgPath = $sImgNewPath;

							} // ./ Upload Success

						} // /. isValid

					} // /. No empty name

				} // ./ Allowed File

			} // ./ Has File


			/**
			 * Se actualiza la información de la campaña
			 */
			$oCampaign        = Campaign::find( $iCampaign_id );
			$oCampaign->name  = $sName;
			$oCampaign->start = $sStart;
			$oCampaign->end   = $sEnd;

			$iRecordedLinks = $oCampaign->links;
			$iRecordedLinksVideo = $oCampaign->links_video;
			$sImgPath       = $oCampaign->image_src;
			$imgExtension   = substr( strrchr( $sImgPath, '.' ), 1 );

			if( $bUpdtImg == true ){

				$unlinkFile = public_path() . $sImgPath;

				// unlink( $unlinkFile );

				// Se actualiza la imagen en las URLs
				$aURLsDefault = CampaignURL::getUrlByCampaignDefault( $iCampaign_id );

				$sOriginFile = public_path() . $sNewImgPath;

				foreach ($aURLsDefault as $URL) {
					$sFullURLFile = public_path() . $URL->shorturl;

					copy( $sOriginFile, $sFullURLFile );
				}

				$sImgPath = $sNewImgPath;

			}

			// Creando links de IMAGENES 

			if( $iLinks > $iRecordedLinks ){
				$iLastLink = (int) $iRecordedLinks;

				// Se registran las URL'S de la campaña
				$sURLPath    = "/campaigns/" . $iCampaign_id . "/urls/";
				$sOriginFile = public_path() . $sImgPath;

				for( $i = $iLastLink; $i < $iLinks; $i++) {
					$sURLFile = $sURLPath . "url_" . $i . "." . "jpg";
					$sFullURLFile = public_path() . $sURLFile;

					copy( $sOriginFile, $sFullURLFile );

					$oCampaignURL              = new CampaignURL;
					$oCampaignURL->campaign_id = $iCampaign_id;
					$oCampaignURL->url         = $sURLFile;
					$oCampaignURL->previo      = $sURLFile;
					$oCampaignURL->save();

				}


			}elseif ( $iLinks < $iRecordedLinks ) {
				$iLinksToDel = (int) $iRecordedLinks - (int) $iLinks;

				$deleteLinks = CampaignURL::where( "campaign_id", "=", $iCampaign_id )
											->where( "status", "=", 1 )
											->orderBy('campaign_url_id', 'DESC')
											->take( $iLinksToDel )
											->update( array('status' => 0) );
			}



			/**
			 * Se hacen los cambios en las fuentes
			 */
			foreach ( $jFontsChanges as $fontChange ) {
				if( $fontChange->font_delete === 'true' ){
					$oFontCampaign         = FontCampaign::find( $fontChange->font_campaign_id );
					$oFontCampaign->status = 0;
					$oFontCampaign->save();
				}
			}

			/**
			 * Se suben las fuentes agregadas
			 */
			$aFontId = array();

			$iNumFontFiles   = Input::get( 'numFontFiles' );
			// Extensiones permitidas para las fuentes
			$aAllowedFontExt = array( 'ttf', 'otf' );

			// Rutas para almacenar las fuentes
			$sFontRelativePath = '/uploads/fonts/';
			$sFontAbsolutePath = public_path() . $sFontRelativePath;

			// Subiendo fuentes
			for ($i=0; $i < $iNumFontFiles; $i++) {
				$sFontFileName = 'fileFontCampaign' . $i;

				if( Input::hasFile( $sFontFileName ) ) {
					// Fuentes para las campañas
					$oFontFile      = Input::file( $sFontFileName );
					$sFontOrigName  = $oFontFile->getClientOriginalName();
					$sFontExtension = $oFontFile->getClientOriginalExtension();

					if( in_array( $sFontExtension, $aAllowedFontExt) ){

						if( $oFontFile->isValid() ){

							if( ( $sFontOrigName != '' && !empty( $sFontOrigName ) ) ){
								// Se obtiene el PostScriptName de la fuente
								$oTtfInfo->setFontFile( $oFontFile );

								$sfontFullName       = $oTtfInfo->getFullFontName();
								$sfontPostScriptName = $oTtfInfo->getFontPostScriptName();
								$sFontFileNewName    = $sfontPostScriptName . "." . $sFontExtension;

								$oFindFont = Font::select( 'font_id' )
												   ->where('postscript_name', '=', $sfontPostScriptName)
												   ->get();

								if( count( $oFindFont ) > 0 ){
									array_push( $aFontId, $oFindFont[0]->font_id );
								}else{
									// Se mueve la Fuente
									$bFontUploadSuccess = $oFontFile->move( $sFontAbsolutePath, $sFontFileNewName );

									if( $bFontUploadSuccess ){
										// Ruta relativa de los archivos subidos
										$sFontPath = $sFontRelativePath . $sFontFileNewName;

										// Registro de la fuente
										$oFont                  = new Font;
										$oFont->name            = $sfontFullName;
										$oFont->postscript_name = $sfontPostScriptName;
										$oFont->font_src        = $sFontPath;
										$bStatus_font           = $oFont->save();

										$iFont_id = $oFont->font_id;

										if( $bStatus_font ){
											array_push( $aFontId, $iFont_id );
										}else{
											array_push( $aFontId, false );
										}

									} // ./ Upload Success

								} // ./ if count( $oFindFont )

							} // /. No empty name

						} // /. Is Valid

					} // ./ Allowed File

				} // ./ Has File
			}

			// Se registran las fuentes subidas a la campaña
			$iNumFonts = count( $aFontId );

			for ($i=0; $i < $iNumFonts; $i++) {
				$oFontCampaign              = new FontCampaign;
				$oFontCampaign->font_id     = $aFontId[ $i ];
				$oFontCampaign->campaign_id = $iCampaign_id;
				$bStatus_fontCampaign       = $oFontCampaign->save();
			}

			// Se crea el archivo ZIP de las fuentes
			CampaignController::createFontsZip( $iCampaign_id );

			/**
			 * USUARIOS
			 */

			 //  Se registran los usuarios nuevos de la campaña
			$iNumUsers = Input::get( "numCheckedUsers" );

			if( (int) $iNumUsers > 0 ){
				for ($i=0; $i < $iNumUsers; $i++) {
					$iCostumerId = Input::get( "checkUser" . $i );

					$oCampCostumer              = new CampaignCostumer;
					$oCampCostumer->costumer_id = $iCostumerId;
					$oCampCostumer->campaign_id = $iCampaign_id;
					$bStatusCampCostumer        = $oCampCostumer->save();
				}
			}

			// Se eliminan los usuarios de la campaña
			$iNumDeleteUsers = Input::get( "numDeleteUsers" );

			if( (int) $iNumDeleteUsers > 0){
				for ($i=0; $i < $iNumDeleteUsers; $i++) {
					$iCampaignCostumerId      = Input::get( "deleteUser" . $i );
					$oCampCostumerDel         = CampaignCostumer::find( $iCampaignCostumerId );
					$oCampCostumerDel->status = 0;
					$bStatusDel               = $oCampCostumerDel->save();
					// echo $oCampCostumerDel->costumer_id;
				}
			}

			/**
			 *  ANUNCIOS PRECARGADOS
			 */
			// Se realizan los cambios en el anuncio
			foreach ( $jAdsChanges as $adChange ) {

				if( $adChange->delete === true ){
					$oAd         = Ads::find( $adChange->ad_id );
					$oAd->status = 0;
					$oAd->save();
				}
			}

			// Se insertan los anuncios nuevos
			$aAdId = array();

			$iNumAdFiles = Input::get( 'numAdFiles' );
			// Extensiones permitidas para registrar el anuncio
			$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			// Rutas para almacenar los Ads
			$sAdRelativePath = "/campaigns/" . $iCampaign_id . "/ads/";
			$sAdAbsolutePath = public_path() . $sAdRelativePath;

			// Subiendo Ads
			for ($i=0; $i < $iNumAdFiles; $i++) {
				$sAdFileName = 'fileAdCampaign' . $i;

				if( Input::hasFile( $sAdFileName ) ) {
					// Fuentes para las campañas
					$oAdFile      = Input::file( $sAdFileName );
					$sAdOrigName  = $oAdFile->getClientOriginalName();
					$sAdExtension = $oAdFile->getClientOriginalExtension();

					if( in_array( $sAdExtension, $allowedImgExt) ){

						if( $oAdFile->isValid() ){

							if( ( $sAdOrigName != '' && !empty( $sAdOrigName ) ) ){

								$sAdFileNewName     = "preloaded_ad_" . time() . rand();
								$sAdFileNewFullName = $sAdFileNewName . "." . $sAdExtension;

								// Se mueve la Fuente
								$bAdUploadSuccess = $oAdFile->move( $sAdAbsolutePath, $sAdFileNewFullName );

								if( $bAdUploadSuccess ){
									// Ruta relativa de los archivos subidos
									$sAdPath = $sAdRelativePath . $sAdFileNewFullName;

									// Conversión de la imagen
									$oImage = new Imagick( public_path() . $sAdPath );


									$oImage->scaleImage( 726, 352 );


									// $oImage->scaleImage( 726, 352 );
									$oImage->setImageFormat( 'jpg' );
									$oImage->setImageCompressionQuality( 95 );
									$oImage->writeImage( $sAdAbsolutePath . $sAdFileNewName . '.jpg' );

									$sAdNewPath = $sAdRelativePath . $sAdFileNewName . '.jpg';

									// Registro de la fuente
									$oAd              = new Ads;
									$oAd->campaign_id = $iCampaign_id;
									$oAd->name        = $sAdFileNewFullName;
									$oAd->image_src   = $sAdNewPath;
									$oAd->type        = "preloaded";
									$bStatus_ad       = $oAd->save();

									$iAdId = $oAd->ad_id;

									if( $bStatus_ad ){
										array_push( $aAdId, $iAdId );
									}else{
										array_push( $aAdId, false );
									}

								} // ./ Upload Success

							} // /. No empty name

						} // /. Is Valid

					} // ./ Allowed File

				} // ./ Has File
			}



			/**
			 * Se suben los anuncios precargados VIDEOS
			 */
			$aAdId = array();

			$iNumAdFiles = Input::get( 'numAdFilesVid' );
			// Extensiones permitidas para registrar el anuncio
			$allowedImgExt  = array( 'mp4','mov' );

			// Rutas para almacenar los Ads
			$sAdRelativePath = "/campaigns/" . $iCampaign_id . "/ads/";
			$sAdAbsolutePath = public_path() . $sAdRelativePath;

			// Subiendo Ads

			// Videos fuente
			$sVideoSrc 		="";
			$sVideoTumSrc	="";
			$sAdExtension   ="";

			for ($i=0; $i < $iNumAdFiles; $i++) {
				$sAdFileName = 'fileAdCampaignVid' . $i;
				$sAdFileTum  = 'fileAdCampaignVidTum' . $i;

				if( Input::hasFile( $sAdFileName ) ) {
					// Fuentes para las campañas
					$oAdFile      = Input::file( $sAdFileName );
					$sAd64Tum     = Input::get( $sAdFileTum );


					$sAdOrigName  = $oAdFile->getClientOriginalName();
					$sAdExtension = $oAdFile->getClientOriginalExtension();

					if( in_array( $sAdExtension, $allowedImgExt) ){

						if( $oAdFile->isValid() ){

							if( ( $sAdOrigName != '' && !empty( $sAdOrigName ) ) ){

								$sAdFileNewName     = "preloaded_ad_vid_" . time() . rand();
								$sAdFileNewFullName = $sAdFileNewName . "." . $sAdExtension;

								// Se mueve el Video
								$bAdUploadSuccess = $oAdFile->move( $sAdAbsolutePath, $sAdFileNewFullName );


								// Creamos la imagen con la cadena
								$exploded = explode(',', $sAd64Tum, 2); // limit to 2 parts, i.e: find the first comma
								$encoded = $exploded[1]; // pick up the 2nd part
								$decoded = base64_decode($encoded);
								$oImagen = imagecreatefromstring($decoded);
								$sNombreImagen = "preloaded_ad_vid_tum_" . time() . rand().".jpg";

								// Guardamos la Imagen
								imagejpeg($oImagen, $sAdAbsolutePath.$sNombreImagen  , 100 );


								if( $bAdUploadSuccess ){

									$sAdNewPath = $sAdRelativePath . $sAdFileNewName . '.'.$sAdExtension;
									$sAdNewPathOgg = $sAdRelativePath . $sAdFileNewName . '.ogg';

									// Registro de la fuente
									$oAd              = new Ads;
									$oAd->campaign_id = $iCampaign_id;
									$oAd->name        = $sAdFileNewFullName;
									$oAd->image_src   = $sAdNewPath;
									$oAd->url_video_ogg = $sAdNewPathOgg;
									$oAd->file_src    = $sAdRelativePath.$sNombreImagen;
									$oAd->type        = "preloaded";
									$oAd->formato     = "video";
									$bStatus_ad       = $oAd->save();

									$iAdId = $oAd->ad_id;

									// tomamos el primer video
									if($i==0){

										$sVideoSrc 		=$sAdNewPath;
										$sVideoTumSrc	=$sAdRelativePath.$sNombreImagen;

									}

									if( $bStatus_ad ){
										array_push( $aAdId, $iAdId );
									}else{
										array_push( $aAdId, false );
									}
									try{
									/*Se convierte el video en formato OGG*/
									//$commandToExecute = 'ffmpeg -i '.$sAdAbsolutePath.$sAdFileNewName.'.mp4 -c:a libvorbis -b:a 200k '.$sAdAbsolutePath.$sAdFileNewName.'.ogg';
									$commandToExecute = 'ffmpeg -i '.$sAdAbsolutePath.$sAdFileNewName.'.'.$sAdExtension.' -codec:v libtheora -qscale:v 5 -codec:a libvorbis -qscale:a 5 '.$sAdAbsolutePath.$sAdFileNewName.'.ogg';
									exec($commandToExecute);
									}catch(Exception $e){
										echo $e->getMessage();
									}

								} // ./ Upload Success

							} // /. No empty name

						} // /. Is Valid

					} // ./ Allowed File

				} // ./ Has File
					
			} // /. END FOR

		
			

			if($sVideoSrc=="" && $sVideoTumSrc	== ""){

				$aPeticion =Ads::getLastAdVideo($iCampaign_id);

				if(count($aPeticion)>0){
					$sVideoSrc = $aPeticion[0]->url;
					$sVideoSrcOgg = $aPeticion[0]->url_video_ogg;

					$sVideoTumSrc = $aPeticion[0]->previo;
				}
			}

            // Creando links de VIDEOS
            if( $iLinks_videos > $iRecordedLinksVideo ){
				$iLastLink = (int) $iRecordedLinksVideo;

				// Se registran las URL'S de la campaña
				$sURLPath    = "/campaigns/" . $iCampaign_id . "/urls/";
				$sOriginFile = public_path() . $sVideoSrc;
				$sOriginFileOgg = public_path() . $sVideoSrc;

				for( $i = $iLastLink; $i < $iLinks_videos; $i++) {
					$sURLFile = $sURLPath . "url_vid_" . $i . "." . $sAdExtension;
					$sURLFileOgg = $sURLPath . "url_vid_" . $i . ".ogg" ;
					$sFullURLFile = public_path() . $sURLFile;
					$sFullURLFileOgg = public_path() . $sURLFileOgg;

					copy( $sOriginFile, $sFullURLFile );
					copy( $sOriginFileOgg, $sFullURLFileOgg );

					$oCampaignURL              = new CampaignURL;
					$oCampaignURL->campaign_id = $iCampaign_id;
					$oCampaignURL->url         = $sURLFile;
					$oCampaignURL->url_ogg     = $sURLFileOgg;
					$oCampaignURL->previo      = $sVideoTumSrc;
					$oCampaignURL->formato     = 'video';
					$oCampaignURL->save();

				}


			}elseif ( $iLinks_videos < $iRecordedLinksVideo ) {
				$iLinksToDel = (int) $iRecordedLinksVideo - (int) $iLinks_videos;

				$deleteLinks = CampaignURL::where( "campaign_id", "=", $iCampaign_id )
											->where( "status", "=", 1 )
											->orderBy('campaign_url_id', 'DESC')
											->take( $iLinksToDel )
											->update( array('status' => 0) );
			}

			$oCampaign->image_src = $sImgPath;
			$oCampaign->links     = $iLinks;
			$oCampaign->links_video  = $iLinks_videos;
			$updtStatus           = $oCampaign->save();


			/**
			 * Se retorna el resultado de la operación
			 */
			return Response::json( $updtStatus );
		
		}
	
	}


	/**
	 * Se colocan URLs a pantallas
	 */
	public function setURLsToScreens()
	{
		$iCampaignId  = Input::get( "campaign_id" );
		$oScreensURLs = json_decode( Input::get( "screensURLs" ) );

		$aInfoToSave = array();

		// Se colocan en status = 0 la relación
		// URLs/Pantallas anterior
		$oUpdtURLsScreen = ScreenURL::updateURLScreenByCampaign( $iCampaignId );

		// Se leen los datos recibidos para hacer la
		// inserción de la relación URLs/Pantallas
		foreach ( $oScreensURLs as $ScreenURL ) {
			$iScreenId =  $ScreenURL->screen_id;

			foreach ($ScreenURL->urls as $key=>$iURLId ) {

				$aTmpData[ "screen_id" ]       = $iScreenId;
				$aTmpData[ "campaign_url_id" ] = $iURLId;
				$aTmpData[ "created_at" ]      = date('Y-m-d H:i:s');

				array_push($aInfoToSave, $aTmpData);
				unset( $aTmpData );
			}
		}

		$bStatus = ScreenURL::insert( $aInfoToSave );;

		return Response::json( $bStatus );
	}

	/**
	 * Se obtienen las URLS y sus pantallas
	 * segun su campaña
	 */
	public function getUrlScreenByCampaign()
	{
		if( Request::ajax() ){
			// $sHost        = "http://" . $_SERVER[ 'HTTP_HOST' ];
			// $iCampaign_id = ( Input::get( 'campaign_id') ? Input::get( 'campaign_id') : 2);


			if( Input::has( 'campaign_id') ){
				$iCampaign_id = Input::get( 'campaign_id');
				// Se obtienen las URLs de la campaña
				$aURLS = CampaignURL::getUrlByCampaign( $iCampaign_id )->toArray();

				// Se obtiene la relación URL/Pantallas
				$aURLsSCreens = ScreenURL::getURLScreenByCampaign( $iCampaign_id )->toArray();
				$iNumUrls = count( $aURLS );

				// Iteración de las URLs
				if( $iNumUrls > 0 ){

					/*for ($i=0; $i < $iNumUrls; $i++) {
						$aURLS[ $i ][ 'url' ] = $sHost . $aURLS[ $i ][ 'url' ];
					}*/

					$aInfo  = array( 'status'        => true,
								     'urls'          => $aURLS,
								     'urlsInScreen'  => $aURLsSCreens
							  );
				}else{
					$aInfo  = array( 'status' => false );
				}

				return Response::json( $aInfo );
			}else{
				$aInfo  = array( 'status' => false );

				return Response::json( $aInfo );
			}

		}

	}

	/**
	 * Se envia un correo electrónico al programador
	 * con las pantallas de la campaña y las urls
	 * correspondientes
	 */
	public function sendEmailURLsInScreen(){
		$iCampaign_id  = ( Input::get( 'campaign_id') ? Input::get( 'campaign_id') : 2 );
		$sEmailAddress = Input::get( 'email_address' );
		$sMsg		   = ( Input::get( 'msg' ) ? Input::get( 'msg') : false );
		$sHost         = "http://" . $_SERVER[ 'HTTP_HOST' ];

		// Se obtienen las URLs de la campaña
		$aURLS = CampaignURL::getUrlByCampaign( $iCampaign_id )->toArray();

		$oScreenURL = new ScreenURL;

		$oURLsScreens = Screen::select( "Screens.name", "Screens.location", "Screens.screen_id" )
		                ->with( array('screenURL' => function($query) use ($sHost, $iCampaign_id)
								{
								  $query->select( DB::raw('CONCAT("' . $sHost . '", url) AS url'), "screen_id" )
								        ->join('Campaigns_url', 'Campaigns_url.campaign_url_id', '=', 'Screens_url.campaign_url_id')
								        ->whereRaw('campaign_id =' . $iCampaign_id)
								        ->whereRaw('Campaigns_url.status = 1')
								        ->whereRaw('Screens_url.status = 1');
								}) )
		                ->join('Screens_url', 'Screens.screen_id', '=', 'Screens_url.screen_id')
		                ->join('Campaigns_url', 'Screens_url.campaign_url_id', '=', 'Campaigns_url.campaign_url_id')
		                ->where( 'Campaigns_url.campaign_id', '=', $iCampaign_id )
		                ->where( 'Campaigns_url.status', '=', 1 )
		                ->where( 'Screens_url.status', '=', 1 )
		                ->where( 'Screens.status', '=', 1 )
		                ->groupBy('Screens.screen_id')
		                ->get()
		                ->toArray();

		$aCampaignInfo = Campaign::find( $iCampaign_id );

		$aInfo  = array( 'urls'          => $aURLS,
					     'urlsInScreen'  => $oURLsScreens,
					     'campaign'      => $aCampaignInfo,
					     'msg'           => $sMsg
				  );

		// return View::make( "emails.programmer" )->with( 'info', $aInfo );

		$aData = array( 'info' => $aInfo );

		$emailSubject  = "Test Pantallas/URLS";

		$mailStatus = Mail::send('emails.programmer', $aData, function ($message) use ( $sEmailAddress, $emailSubject ){
		                        $message->subject( $emailSubject );
		                        $message->to( $sEmailAddress );
		                    });

		return Response::json( $mailStatus );

	}

	/**
	 * Se obtienen el historial de anuncios creados
	 */
	public function getCreatedAdsHistorical(){

		$iCampaignId = Input::get( "campaignId" );

		$aCreatedAds = Ads::getCreatedAdsHistorical( $iCampaignId );

		return Response::json( $aCreatedAds );

	}

	/**
	 * Se obtienen el historial de anuncios creados
	 */
	public function getPublishedAdsHistorical(){

		$iCampaignId = Input::get( "campaignId" );

		$aCreatedAds = CampaignUrlAdPublished::getPublishedAdsHistorical( $iCampaignId );

		return Response::json( $aCreatedAds );

	}

	/**
	 * Se obtienen los anuncios actualmente publicados
	 */
	public function getCurrentlyPublishedAds(){

		$iCampaignId = Input::get( "campaignId" );

		$aCreatedAds = CampaignUrlAdPublished::getCurrentlyPublishedAds( $iCampaignId );

		return Response::json( $aCreatedAds );

	}


	// Busqueda en arrays
	public static function in_array_r( $search, $arrayElemnt, $strict = false ) {
	    foreach ($arrayElemnt as $item) {
	    	$inArray;

	    	if( is_array( $item ) ){
	    		$inArray = CampaignController::in_array_r( $search, $item, $strict );
	    	}

	        if (($strict ? $item === $search : $item == $search) || (is_array($item) && $inArray->status == true )) {
	            return (object) array('status' => true, 'screen_id' => $item );
	        	// return true;
	        }
	    }
	    return (object) array( 'status' => false );
	    // return false;
	}

	/**
	 * Se crea el archivo Zip con las fuentes de la campaña
	 * @param  [integer] $iCampaiignId [Id de la campaña]
	 * @return [string]                [Ruta del archivo creado]
	 */
	public static function createFontsZip( $iCampaignId ) {

		// Ruta del ZIP a crear
		$sRelativeZipPath = '/uploads/fonts/' . $iCampaignId . '.zip';
		$sAbsoluteZipPath = public_path() . '/uploads/fonts/' . $iCampaignId . '.zip';

		// Se obtienen las fuentes de la campaña
		$aFontsCampaigns = Font::getCampaignPathFonts( $iCampaignId )
								->toArray();

		// Se revisa si existen fuentes en el registro
		if( count( $aFontsCampaigns ) > 0 ){
			$aFontsSequential = array_values( $aFontsCampaigns );

			// Se revisa si existe el archivo y se elimina
			if( file_exists( $sAbsoluteZipPath ) ){
				unlink( $sAbsoluteZipPath );
			}

			// Se crea el archivo ZIP
			$status = Zipper::make( $sAbsoluteZipPath )
			                ->add( $aFontsSequential )
			                ->getStatus();

			if( $status == "No error" ){
				$oCampaign                      = Campaign::find( $iCampaignId );
				$oCampaign->fonts_zip_src       = $sRelativeZipPath;
				$oCampaign->fonts_zip_timestamp = time();
				$oCampaign->save();
			}

		}

	}

	/**
	 * Se envía un correo electrónico
	 * informando de la creación de la campaña
	 */
	public function sendCreatedCampaignEmail( $sCostumerName, $sCampaignName )
	{
		/*$sCostumerName = Input::get( "costumer_name" );
		$sCampaignName = Input::get( "campaign_name" );*/

		$aData = array( 'costumer_name'     => $sCostumerName,
						'campaign_name'     => $sCampaignName,
						'current_date'      => date("Y-m-d H:i:s") );

		$mailStatus = Mail::send('emails.createdCampaignEmail', $aData, function ($message){
		                        $message->subject( "Switch Nueva campaña registrada" );
		                        $message->to( "luis@torodigital.com.mx" );
		                    });

		return $mailStatus;
	}







	/**
	 * Se obtiene la información de una pantalla
	 * según el id
	 *
	 * @return View
	 */
	public function getCampaigns()
	{
		$campaigns = Campaign::join('Companies', 'Campaigns.company_id', '=', 'Companies.company_id')
							  ->select( 'campaign_id', 'Companies.name', 'Campaigns.name', 'Campaigns.start', 'Campaigns.end' )
							  ->where('Campaigns.status', '=', 1)
							  ->get();

		return View::make('campaigns.campaign')
		            ->with('campaigns', $campaigns);
	}


}
