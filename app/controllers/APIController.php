<?php

class APIController extends BaseController {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*public function __construct()
    {
        parent::__construct();
    }*/

    /**
     * Se obtiene la URL de un anuncio publicado
     */
    public function networkPostImage( $iCostumerId, $sImgPath ){
    // public function networkPostImage(){
    	
    	/**
    	 * Publicación de la imagena en twitter
    	 */
    	
    	/*$iCostumerId = 1;
    	$sImgPath    = asset("uploads/campaigns/img/img_campaign_14310325031083545431.jpg");*/

    	// Se obtiene el ID del pais según el host
    	$sHostName = $_SERVER['SERVER_NAME'];

    	$oCountryInfo = Country::select( 'country_id' )
    					  ->where('url', 'like', '%' . $sHostName . '%' )
    					  ->get();

    	$iCountryID = $oCountryInfo[0]->country_id;

    	// Se obtiene el ID de la compañia por el id del cliente
    	$oCostumerInfo = Costumer::select( 'company_id' )
    					  ->where('costumer_id', '=', $iCostumerId )
    					  ->get();

    	$iCompanyID = $oCostumerInfo[0]->company_id;

    	// Post en Twitter
		$sRequestURL = 'http://velika.torodigital.com.mx/switch-social/backends/postImage.php';
		
		$aData = array( 
						'country_id' => $iCountryID,
						'company_id' => $iCompanyID, 
						'image_path' => $sImgPath
					);

		$oCurl      = curl_init();

		// Se realiza la petición por POST
	    curl_setopt_array(	$oCurl, array(
	    							CONNECTION_TIMEOUT     => 1,
									CURLOPT_RETURNTRANSFER => 1,
									CURLOPT_POST           => 1,
									CURLOPT_POSTFIELDS     => $aData,
									CURLOPT_URL            => $sRequestURL
	    						)
	    				);

	    $sResponse = curl_exec( $oCurl );

	    curl_close( $oCurl );

	    // Post en Facebook
    	$sRequestURL = 'http://velika.torodigital.com.mx/switch-social/Facebook/FBpostImage.php';
    	
    	$aData = array( 
    					'country_id' => $iCountryID,
    					'company_id' => $iCompanyID, 
    					'image_path' => $sImgPath
    				);

    	$oCurl      = curl_init();

    	// Se realiza la petición por POST
        curl_setopt_array(	$oCurl, array(
        							CONNECTION_TIMEOUT     => 1,
    								CURLOPT_RETURNTRANSFER => 1,
    								CURLOPT_POST           => 1,
    								CURLOPT_POSTFIELDS     => $aData,
    								CURLOPT_URL            => $sRequestURL
        						)
        				);

        $sResponse = curl_exec( $oCurl );

        curl_close( $oCurl );

    }

    /**
     * Se envia la imagen a facebook  con el sistema switch social
     * @return [json]
     */
    public function networkPostImageFacebook()
  	{
  		// Post en Facebook
    	$sRequestURL = 'http://velika.torodigital.com.mx/switch-social/Facebook/AppFb_PostImage.php';
    	
    	$aData = array( 
    					'country_id' => Input::get( 'country_id' ),
    					'company_id' => Input::get( 'company_id' ), 
    					'image_path' => Input::get( 'image_path' )
    				);

    	$oCurl      = curl_init();

    	// Se realiza la petición por POST
        curl_setopt_array(	$oCurl, array(
        							CONNECTION_TIMEOUT     => 20,
    								CURLOPT_RETURNTRANSFER => 1,
    								CURLOPT_POST           => 1,
    								CURLOPT_POSTFIELDS     => $aData,
    								CURLOPT_URL            => $sRequestURL
        						)
        				);

        $sResponse = curl_exec( $oCurl );

        curl_close( $oCurl );

        return $sResponse;
  	}

  	/**
     * Se envia la imagen a twitter  con el sistema switch social
     * @return [json]
     */
    public function networkPostImageTwitter()
  	{
  		// Post en Twitter
    	$sRequestURL = 'https://velika.torodigital.com.mx/switch-social/backends/AppTwitter_PostImage.php';
    	
    	$aData = array( 
    					'country_id' => Input::get( 'country_id' ),
    					'company_id' => Input::get( 'company_id' ), 
    					'image_path' => Input::get( 'image_path' )
    				);

    	$oCurl      = curl_init();

    	// Se realiza la petición por POST
        curl_setopt_array(	$oCurl, array(
        							CONNECTION_TIMEOUT     => 20,
    								CURLOPT_RETURNTRANSFER => 1,
    								CURLOPT_POST           => 1,
    								CURLOPT_POSTFIELDS     => $aData,
    								CURLOPT_URL            => $sRequestURL
        						)
        				);

        $sResponse = curl_exec( $oCurl );

        curl_close( $oCurl );

        return $sResponse;
  	}

    /**
     * Se verifica si un usuario 
     * esta registrado y se autentica
     * @return [json]
     */
	public function userLogin()
	{
		$username = Input::get( 'username' );
		$password = Input::get( 'password' );

		$userData = Costumer::userLogin( $username, $password );

		return Response::json( $userData );
		
	}

	/**
	 * Se obtienen las campañas
	 * de un usuario
	 * @return [json]
	 */
	public function getCampaign()
	{
		$costumer_id  = Input::get( 'costumer_id' );

		$CampaignData = Campaign::getCampaignsCostumer( $costumer_id );

		return Response::json( $CampaignData );
		
	}

	/**
	 * Se obtienen los anuncios precargados
	 * de una campañas
	 * @return [json]
	 */
	public function getPreloadedAds()
	{
		$campaign_id = Input::get( 'campaignid' );

		$AdsData = Ads::getPreloadedAdsCampaign( $campaign_id );

		return Response::json( $AdsData );
		
	}

	/**
	 * Se obtienen los anuncios creados
	 * de una campañas
	 * @return [json]
	 */
	public function getCreatedAds()
	{
		$campaign_id = Input::get( 'campaignid' );

		$AdsData = Ads::getCreatedAdsCampaign( $campaign_id );

		return Response::json( $AdsData );
		
	}

	/**
	 * Se obtienen los anuncios 
	 * que han sido publicados
	 * @return [json] 
	 */
	public function getPublishedAds()
	{
		$campaign_id = Input::get( 'campaignid' );

		$AdsPublishedData = CampaignUrlAdPublished::getPublishedAdsCampaign( $campaign_id );

		return Response::json( $AdsPublishedData );
		
	}

	/**
	 * Se obtienen las pantallas 
	 * con URLs relacionadas de una campaña
	 */
	public function getScreensByCampaign()
	{
		$iCampaign_id = Input::get( "campaign_id" );

		$sFormato="";
		if (Input::has('formato')){
			$sFormato = Input::get( "formato" );
		}
		
		$aScreensByCampaign = Screen::getScreensByCampaign( $iCampaign_id , $sFormato );
		
		return Response::json( $aScreensByCampaign );
	}
	/**
	 * Se obtienen las urls
	 * de una pantalla de una campaña
	 */
	public function getURLsByScreenCampaign()
	{
		$iCampaign_id = Input::get( "campaign_id" );
		$iScreen_id   = Input::get( "screen_id" );
		$sFormato="";

		if( Input::has( "formato" ) ){
			$sFormato     = Input::get( "formato" );
		}
		
		
		$aURLsByScreenCampaign = ScreenURL::getURLsByScreenCampaign( $iCampaign_id, $iScreen_id , $sFormato );
		
		return Response::json( $aURLsByScreenCampaign );
	}

	/**
	 * Se pone un anuncio
	 */
	public function setAdinURLSlot(){
		// $iScreenURLId = json_decode( Input::get( "screen_url_id" ) );
		$bStatus     = false;
		$iAdId       = Input::get( "ad_id" );
		$iCostumerId = Input::get( "costumerid" );
		$oSlots      = json_decode( Input::get( "aDataSlots" ) );

		// Información del Ad
		$oAdURL      = Ads::find( $iAdId );
		$sAdURL      = $oAdURL->image_src;
		$sAdFormato  = $oAdURL->formato;

		$sAdPrev	 = "";

		if($sAdFormato == 'video' ){
			$sAdPrev     = $oAdURL->file_src;
		}else{
			$sAdPrev     = $oAdURL->image_src;
		}
		

		$sAdFullPath = public_path() . $sAdURL;
		$oDataPathAd =pathinfo( $sAdFullPath );

		

		// Se coloca el anuncio
		foreach ( $oSlots as $oSlot ) {
			$iSlotId = $oSlot->slotID;

			$oScreenURL   = ScreenURL::getURLInfo( $iSlotId );

			$iCampaignURLId = $oScreenURL[0]->campaign_url_id;
			$sURL           = $oScreenURL[0]->url;
			$sURLFullPath   = public_path() . $sURL;

			// tomamso las dimenciones y escalamos la imagen a dichas dimensiones
			$bCopyStatus=false;

			if($sAdFormato == 'video'){
				// Remplazamos la extension de la ruta que se publica a partir del formato del video
				$partes_ruta    = pathinfo($sAdFullPath);
				$sURL 			= substr_replace( $sURL , $partes_ruta['extension'] , strrpos($sURL , '.') +1);
				$sURLFullPath   = public_path() . $sURL;
				$bCopyStatus = copy( $sAdFullPath, $sURLFullPath );

			}else{
				
				$iWidth 	  = $oScreenURL[0]->width;//Ancho de la pantalla asociada al slot
				$iHeigth 	  = $oScreenURL[0]->height;//Ancho de la pantalla asociada al slot
				$oImage = new Imagick( $sAdFullPath );
				$oImage->scaleImage($iWidth, $iHeigth);
				$oImage->setImageFormat( 'jpg' );
				$oImage->setImageCompressionQuality(95);
				$oImage->writeImage( $sURLFullPath );
				$bCopyStatus=true;
			}

			if( $bCopyStatus ){
				// Se marca la ULR(Slot) con tipo Ad
				$oUpdtCampaignUrl = CampaignURL::where( 'campaign_url_id', $iCampaignURLId )
										       			   ->update( array('type' => 'ad' ,'previo'=>$sAdPrev, 'url'=>$sURL ) );

				// Se marcan como status 0 anuncios anteriores
				$oUpdtPublisheds = CampaignUrlAdPublished::where( 'campaign_url_id', $iCampaignURLId )
										       			   ->update( array('status' => 0) );

				// Se registran en la lista de anuncios publicados
				$oAdPublished                  = new CampaignUrlAdPublished;
				$oAdPublished->campaign_url_id = $iCampaignURLId;
				$oAdPublished->screen_url_id   = $iSlotId;
				$oAdPublished->costumer_id     = $iCostumerId;
				$oAdPublished->ad_id           = $iAdId;
				$bInserted                     = $oAdPublished->save();

				if( $bInserted == true ){
					$bStatus = true;
				}

				//$this->networkPostImage( $iCostumerId, asset( $sURL ) );

				/*if( $oUpdtPublisheds == true ){
				}*/
				
			}
		}

		return Response::json( $bStatus );
	}

	/**
	 * Se obtienen las fuentes de una campaña
	 */
	public function getCampaignFonts(){
		$iCampaignId = Input::get( "campaign_id" );

		$aFonts = Font::getCampaignFonts( $iCampaignId );

		return Response::json( $aFonts );
	}

	/**
	 * Se obtienen las fuentes en un zip
	 */
	public function getCampaignZipFonts(){
		$iCampaignId = Input::get( "campaign_id" );

		$aFonts = Font::getCampaignFonts( $iCampaignId )
		                ->toArray();

		$aZipFontURL = Campaign::find( $iCampaignId );

		$sZipFontURL       = $aZipFontURL->fonts_zip_src;
		$sZipFontTimestamp = $aZipFontURL->fonts_zip_timestamp;

		$aFontsData = array( 'zip_url'       => $sZipFontURL,
							 'zip_timestamp' => $sZipFontTimestamp,
							 'fonts'         => $aFonts,
						    );

		return Response::json( $aFontsData );
	}

	/**
	 * Se obtiene la URL de un anuncio publicado
	 */
	public function getAdImageURL(){

		$iAdId  = Input::get( "ad_id" );
		
		$oAd    = Ads::find( $iAdId );
		
		$iNumAd = count($oAd);
		
		if( $iNumAd > 0){
			$sImageURL = $oAd->image_src;

			return Response::json( $sImageURL );
		}else{
			return Response::json( false );
		}
		
	}

	/**
	 * Se elimina un anuncio
	 */
	public function deleteAd(){
		$iAdId = Input::get( "ad_id" );

		// Actualización del status
		$oAd         = Ads::find( $iAdId );
		$oAd->status = 0;
		$bStatus     = $oAd->save();

		return Response::json( $bStatus );
	}

	/**
	 * Se guarda un anuncio creado
	 */
	public function saveCreatedAd(){
		
		if( Input::hasFile( 'adImageFile' ) ){
			$iCampaignId  = Input::get( "campaign_id" );
			$iCostumerId  = Input::get( "user_id" );
			$oAdImageFile = Input::file( 'adImageFile' );

			$imgOrigName  = $oAdImageFile->getClientOriginalName();
			$imgExtension = $oAdImageFile->getClientOriginalExtension();
			
			$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			$imgRelativePath  = '/campaigns/' . $iCampaignId . '/ads/';
			$imgAbsolutePath  = public_path() . $imgRelativePath;

			if( in_array( $imgExtension, $allowedImgExt) ){

				if( $imgOrigName != '' && !empty( $imgOrigName ) ){

					if( $oAdImageFile->isValid() ){
						$imgFileName        = "img_ad_" . time();
						$imgFileFullNewName = $imgFileName . "." . $imgExtension;
						// Se sube la Imagen
						$imgUploadSuccess  = $oAdImageFile->move( $imgAbsolutePath, $imgFileFullNewName );

						if( $imgUploadSuccess ){
							// Ruta relativa de los archivos subidos
							$sImgPath  = $imgRelativePath . $imgFileFullNewName;

							// Conversión de la imagen
							$oImage = new Imagick( public_path() . $sImgPath );
							// $oImage->resizeImage( 726, 352, imagick::FILTER_LANCZOS, 0.9, true);

							/**
							 * Cambio del tamaño según la campaña
							 */
							if( $iCampaignId == 21 ){
								$oImage->scaleImage( 1152, 559 );
							}else if( $iCampaignId == 93 ){
								$oImage->scaleImage( 1680, 815 );
							}else{
								$oImage->scaleImage( 726, 352 );
							}
							
							$oImage->setImageFormat( 'jpg' );
							$oImage->setImageCompressionQuality(95);
							$oImage->writeImage( $imgAbsolutePath . $imgFileName . '.jpg' );

							$sImgNewPath = $imgRelativePath . $imgFileName . '.jpg';

							// Se hace la inserción
							$oAd              = new Ads;
							$oAd->campaign_id = $iCampaignId;
							$oAd->costumer_id = $iCostumerId;
							$oAd->name        = "ad_created_" . time();
							$oAd->image_src   = $sImgNewPath;
							$oAd->type        = "created";

							$bStatus = $oAd->save();

							return Response::json( $bStatus );

						} // ./ Upload Success
						else{
							return Response::json( "Error al guardar el archivo" );
						}
					} // /. isValid
					else{
						return Response::json( "Archivo inválido" );
					}
				} // /. No empty name
				else{
					return Response::json( "Nombre del archivo vacio" );
				}
			} // ./ Allowed File
			else{
				return Response::json( "La extension no es válida" );
			}

		}else{
			return Response::json( "No se reconoce el archivo" );
		}
		
	}


	/**
	 * En Desarrollo
	 */
	public function saveCreatedAdDevelopment(){
		
		if( Input::hasFile( 'adImageFile' ) ){
			$iCampaignId  = Input::get( "campaign_id" );
			$iCostumerId  = Input::get( "user_id" );
			$oAdImageFile = Input::file( 'adImageFile' );
			$oAdBlobFile  = Input::file( 'blobEditable' );
			$iAdID        = Input::get( 'Ad_id' );
			$iIsAdCreated = Input::get( 'isAdCreated' );

			// Imagen
			$imgOrigName  = $oAdImageFile->getClientOriginalName();
			$imgExtension = $oAdImageFile->getClientOriginalExtension();

			$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			// Archivo editable
			$fileOrigName  = $oAdBlobFile->getClientOriginalName();
			$fileExtension = $oAdBlobFile->getClientOriginalExtension();

			$allowedFileExt  = array( 'switch' );

			// Ruta
			$imgRelativePath  = '/campaigns/' . $iCampaignId . '/ads/';
			$imgAbsolutePath  = public_path() . $imgRelativePath;

			if( in_array( $imgExtension, $allowedImgExt) && in_array( $fileExtension, $allowedFileExt ) ){

				if( $imgOrigName != '' && !empty( $imgOrigName ) && $fileOrigName != '' && !empty( $fileOrigName ) ){

					if( $oAdImageFile->isValid() && $oAdBlobFile->isValid() ){

						if( $iAdID != 0 && $iIsAdCreated==1 ) {
							// Imagen
							$imgFileName        = "img_ad_" . $iAdID;
							$imgFileFullNewName = $imgFileName . "." . $imgExtension;
							// Archivo Editable
							$fileFileName        = "file_ad_" . $iAdID;
							$fileFileFullNewName = $fileFileName . "." . $fileExtension;
						}else{
							// Imagen
							$imgFileName        = "img_ad_" . time();
							$imgFileFullNewName = $imgFileName . "." . $imgExtension;
							// Archivo Editable
							$fileFileName        = "file_ad_" . time();
							$fileFileFullNewName = $fileFileName . "." . $fileExtension;
						}

						// Se sube la Imagen
						$imgUploadSuccess  = $oAdImageFile->move( $imgAbsolutePath, $imgFileFullNewName );
						// Se sube el archivo editable
						$fileUploadSuccess  = $oAdBlobFile->move( $imgAbsolutePath, $fileFileFullNewName );

						/**
						 * Resize Img
						 */
						$aStatus  = array();
						$sImgPath = $imgRelativePath . $imgFileFullNewName;

						// Conversión de la imagen
						$oImage = new Imagick( public_path() . $sImgPath );
						// $oImage->resizeImage( 726, 352, imagick::FILTER_LANCZOS, 0.9, true);

						/**
						 * Cambio del tamaño según la campaña
						 */
						if( $iCampaignId == 21 ){
							$oImage->scaleImage( 1152, 559 );
						}else{
							$oImage->scaleImage( 726, 352 );
						}
						
						$oImage->setImageFormat( 'jpg' );
						$oImage->setImageCompressionQuality(95);
						$oImage->writeImage( $imgAbsolutePath . $imgFileName . '.jpg' );

						$sImgNewPath = $imgRelativePath . $imgFileName . '.jpg';
						$iTimestamp  = time();

						if( $imgUploadSuccess && $fileUploadSuccess ){

							if( $iAdID != 0 && $iIsAdCreated==1 ) {
								// Update
								$oAdUpdate                 = Ads::find( $iAdID );
								$oAdUpdate->image_src      = $imgRelativePath . 'img_ad_' . $iAdID . '.jpg';
								$oAdUpdate->file_src       = $imgRelativePath . 'file_ad_' . $iAdID . '.switch';
								$oAdUpdate->file_timestamp = $iTimestamp;
								$bUpdated                  = $oAdUpdate->save();

								if( $bUpdated ) {
									$aStatus = array(
														'status'    => 1, 
														'ad_id'     => $iAdID,
														'timestamp' => $iTimestamp
													);
								}else{
									$aStatus = array(
														'status'    => 0
													);
								}
								
								return Response::json( $aStatus );
								
							}else{
								// Insert
								// Se hace la inserción
								$oAd                 = new Ads;
								$oAd->campaign_id    = $iCampaignId;
								$oAd->costumer_id    = $iCostumerId;
								$oAd->name           = "ad_created_" . time();
								$oAd->image_src      = $sImgNewPath;
								$oAd->file_src       = $imgRelativePath . $fileFileFullNewName;
								$oAd->file_timestamp = $iTimestamp;
								$oAd->type           = "created";

								$bStatus = $oAd->save();

								$iInsertedID = $oAd->ad_id;

								if ( $bStatus ) {
									
									// Imagen
									rename(  public_path() . $sImgNewPath, $imgAbsolutePath . 'img_ad_' . $iInsertedID . '.jpg' );
									rename(  public_path() . $imgRelativePath . $fileFileFullNewName, $imgAbsolutePath . 'file_ad_' . $iInsertedID . '.switch' );

									$oAdUpdate            = Ads::find( $iInsertedID );
									$oAdUpdate->image_src = $imgRelativePath . 'img_ad_' . $iInsertedID . '.jpg';
									$oAdUpdate->file_src  = $imgRelativePath . 'file_ad_' . $iInsertedID . '.switch';
									$bUpdated             = $oAdUpdate->save();

									if( $bUpdated ) {
										$aStatus = array(
															'status'    => 1, 
															'ad_id'     => $iInsertedID,
															'timestamp' => $iTimestamp
														);
									}else{
										$aStatus = array(
															'status'    => 0
														);
									}
									
									return Response::json( $aStatus );

								}else{
									$aStatus = array(
														'status'    => 0
													);
								}

								return Response::json( $aStatus );
							}

						} // ./ Upload Success
						else{
							return Response::json( "Error al guardar el archivo" );
						}
					} // /. isValid
					else{
						return Response::json( "Archivo inválido" );
					}
				} // /. No empty name
				else{
					return Response::json( "Nombre del archivo vacio" );
				}
			} // ./ Allowed File
			else{
				return Response::json( "La extension no es válida" );
			}

		}else{
			return Response::json( "No se reconoce el archivo" );
		}
		
	}

	/**
	 * Sube un video
	 */
	public function saveAdvideo(){
		
		if( Input::hasFile( 'adVideoFile' ) &&  Input::hasFile( 'adVideoTum' ) ){
			$iCampaignId  = Input::get( "campaign_id" );
			$iCostumerId  = Input::get( "user_id" );
			$oAdVidFile   = Input::file( 'adVideoFile' );
			$oAdVidTum    = Input::file( 'adVideoTum' );


			// Video
			$vidOrigName  = $oAdVidFile->getClientOriginalName();
			$vidExtension = $oAdVidFile->getClientOriginalExtension();

			// Video
			$vidOrigNameTum  = $oAdVidTum->getClientOriginalName();
			$vidExtensionTum = $oAdVidTum->getClientOriginalExtension();


			$allowedvidExt  = array( 'mp4' );
			$allowedvidExtTum  = array( 'jpg' );


			// Ruta
			$vidRelativePath  = '/campaigns/' . $iCampaignId . '/ads/';
			$vidAbsolutePath  = public_path() . $vidRelativePath;

			if( in_array( $vidExtension, $allowedvidExt) &&  in_array( $vidExtensionTum, $allowedvidExtTum) ){

				if( $vidOrigName != '' && !empty( $vidOrigName )  && 
					$vidOrigNameTum != '' && !empty( $vidOrigNameTum ) ){

					if( $oAdVidFile->isValid() && $oAdVidTum->isValid() ){

						// Video
						$vidFileName        = "vid_ad_" . time();
						$vidFileFullNewName = $vidFileName . "." . $vidExtension;
						
						// Imagen
						$vidFileNameTum        = "vid_ad_tum" . time();
						$vidFileFullNewNameTum = $vidFileNameTum . "." . $vidExtensionTum;

						// Se sube el video
						$vidUploadSuccess  = $oAdVidFile->move( $vidAbsolutePath, $vidFileFullNewName );
						// sube la imagen
						$imgUploadSuccess  = $oAdVidTum->move( $vidAbsolutePath, $vidFileFullNewNameTum );


						$sVidNewPath    = $vidRelativePath . $vidFileName . '.mp4';
						$sVidNewPathOgg = $vidRelativePath . $vidFileName . '.ogg';
						$sVidNewPathTum = $vidRelativePath . $vidFileNameTum . '.jpg';

						$iTimestamp  = time();

						if( $vidUploadSuccess  &&  $imgUploadSuccess ){
							/*Aqui convertimos el video subido en OGG*/
							try{
								/*Se convierte el video en formato OGG*/
								//$commandToExecute = 'ffmpeg -i '.$sAdAbsolutePath.$sAdFileNewName.'.mp4 -c:a libvorbis -b:a 200k '.$sAdAbsolutePath.$sAdFileNewName.'.ogg';
								$commandToExecute = 'ffmpeg -i '.$vidAbsolutePath.$vidFileName.'.mp4 -codec:v libtheora -qscale:v 5 -codec:a libvorbis -qscale:a 5 '.$vidAbsolutePath.$vidFileName.'.ogg';
								exec($commandToExecute);
							}catch(Exception $e){
								echo $e->getMessage();
							}

							// Insert
							// Se hace la inserción
							$oAd                 = new Ads;
							$oAd->campaign_id    = $iCampaignId;
							$oAd->costumer_id    = $iCostumerId;
							$oAd->name           = "ad_vid_created_" . time();
							$oAd->image_src      = $sVidNewPath;
							$oAd->url_video_ogg = $sVidNewPathOgg;
							$oAd->file_src       = $sVidNewPathTum;
							$oAd->file_timestamp = $iTimestamp;
							$oAd->type           = "created";
							$oAd->formato        = "video";

							$bStatus = $oAd->save();

							$iInsertedID = $oAd->ad_id;

							if ( $bStatus ) {
								
								// Video
								rename(  public_path() . $sVidNewPath, $vidAbsolutePath . 'vid_ad_' . $iInsertedID . '.mp4' );
								rename(  public_path() . $sVidNewPathOgg, $vidAbsolutePath . 'vid_ad_' . $iInsertedID . '.ogg' );
								
								// Imagen
								rename(  public_path() . $sVidNewPathTum, $vidAbsolutePath . 'vid_ad_tum' . $iInsertedID . '.jpg' );

								$oAdUpdate            = Ads::find( $iInsertedID );
								$oAdUpdate->image_src = $vidRelativePath . 'vid_ad_' . $iInsertedID . '.mp4';
								$oAdUpdate->url_video_ogg = $vidRelativePath . 'vid_ad_' . $iInsertedID . '.ogg';
								
								$oAdUpdate->file_src = $vidRelativePath . 'vid_ad_tum' . $iInsertedID . '.jpg';

								$bUpdated             = $oAdUpdate->save();

								if( $bUpdated ) {
									$aStatus = array(
														'status'    => 1, 
														'ad_id'     => $iInsertedID,
														'timestamp' => $iTimestamp
													);
								}else{
									$aStatus = array(
														'status'    => 0
													);
								}
								
								return Response::json( $aStatus );

							}else{
								$aStatus = array(
													'status'    => 0
												);
							}

							return Response::json( $aStatus );
							

						} // ./ Upload Success
						else{
							return Response::json( "Error al guardar el archivo" );
						}
					} // /. isValid
					else{
						return Response::json( "Archivo inválido" );
					}
				} // /. No empty name
				else{
					return Response::json( "Nombre del archivo vacio" );
				}
			} // ./ Allowed File
			else{
				return Response::json( "La extension no es válida" );
			}

		}else{
			return Response::json( "No se reconoce el archivo" );
		}
		
	}

	/**
	 * Se obtiene la URL de un anuncio publicado
	 */
	public function getAdEditableFile(){

		$iAdId  = Input::get( "ad_id" );
		
		$oAd    = Ads::find( $iAdId );
		
		$iNumAd = count($oAd);
		
		if( $iNumAd > 0){
			$sAdID          = $oAd->ad_id;
			$sFileTimestamp = $oAd->file_timestamp;
			$sFileSrc       = $oAd->file_src;

			if ( !isset( $sFileSrc ) || $sFileSrc == '') {
				$aResponse = array(
									'status' => 0,
									'msg'    => 'No se encuentra archivo editable en el Ad.'
								);
			}else{
				$aResponse = array(
									'ad_id'     => $sAdID, 
									'timestamp' => $sFileTimestamp, 
									'file_src'  => $sFileSrc,
									'status'    => 1
								);
			}

			return Response::json( $aResponse );
		}else{
			$aResponse = array(
								'status' => 0,
								'msg'    => 'No se encuentra el Ad'
							);

			return Response::json( $aResponse );
		}
		
	}

	/**
	 * Se obtiene el historial de Ads de una campaña
	 */
	public function getCampaingAdsHistory(){
		// $iCampaignId = 83;
		// $iCampaignId = 67;
		
		header("Access-Control-Allow-Origin: *");

		$iCampaignId       = Input::get( "campaignid" );

		// Se obtienen los anuncios creados para una campaña
		$aCreatedAds = CampaignUrlAdPublished::getPublishedAdsHistoricalByCampaign( $iCampaignId )
							->toArray();
	    
	    if(count($aCreatedAds)<=0)
	    {

	    	$aCreatedAds= array( 
	    		'0' => array('url'=>"assets/imgPrevio.jpg")
	    		);
	    }

		return Response::json( $aCreatedAds );
	}

	/**
	 * Se coloca un anuncio programado
	 */
	public function setScheduledAd(){
		$aData       = array();
		$iAdId       = Input::get( "ad_id" );
		$sDate       = Input::get( "sDate" );
		$iCostumerId = Input::get( "costumerid" );
		$iCampaignId = Input::get( "campaignid" );
		$oSlots      = json_decode( stripslashes( Input::get( "aDataSlots" ) ) );
		$iCronoId 	 = Input::get( "crono_id" );

	
		// Se obtiene la zona horaria del pais
		$aCountryTimeZone = Country::getCountryTimeZone( $iCostumerId );
		$sTimeZoneOffset  = $aCountryTimeZone[0]->timezone;

		// Calculo en segundos del Offset
		list( $iHours, $iMinutes) = explode( ':', $sTimeZoneOffset );

		$iHours   = $iHours * -1;
		$sSeconds = $iHours * 60 * 60 + $iMinutes * 60;
		// Se obtiene el nombre de la zona horaria
		$oOffsetTimeZone = timezone_name_from_abbr('', $sSeconds, 1);

		// Workaround for bug #44780
		if( $oOffsetTimeZone === false ){
			$oOffsetTimeZone = timezone_name_from_abbr( '', $sSeconds, 0 );
		}
		// Set timezone
		date_default_timezone_set( $oOffsetTimeZone );

		$sUnixDate  = strtotime( $sDate );
		$sHumanDate = gmdate("Y-m-d H:i:s", $sUnixDate);

		/*$aInfo = array(
						'current' => date('Y-m-d H:i:s'),
						'human'   => $sHumanDate,
						'seconds' => $sSeconds
					);*/
		
		// Si el registro es una insercion 
		if($iCronoId==0){

			// Se guarda la programación
			$oScheduleAd               = new ScheduledAds;
			$oScheduleAd->campaign_id  = $iCampaignId;
			$oScheduleAd->ad_id        = $iAdId;
			$oScheduleAd->unix_date    = $sUnixDate;
			$oScheduleAd->human_date   = $sHumanDate;
			$oScheduleAd->country_date = $sDate;
			$oScheduleAd->costumer_id  = $iCostumerId;
			$bInserted                 = $oScheduleAd->save();

			//Si se inserto correctamente 
			if( $bInserted ) {
				$iInsertedID = $oScheduleAd->ID;
				
				//Iteracion de cada Slots 
				foreach ( $oSlots as $oSlot ){
					$oScheduleAdSlot                  = new ScheduledAdsSlots;
					$oScheduleAdSlot->slot_id         = $oSlot->slotID;
					$oScheduleAdSlot->scheduled_ad_id = $iInsertedID;
					$oScheduleAdSlot->save();
				}

				$aData = array(
							'status' => 1, 
							'msg'    => 'Registro exitoso.'
						);
			}else{
				$aData = array(
							'status' => 0, 
							'msg'    => 'Ocurrio un error.'
						);
			}
		
		}else{ //Si no , se actualiza el registro con los nuevos datos

			// Se obtiene el registro de la programación
			$oScheduleAd               = ScheduledAds::find($iCronoId);

			//Se actualiza la fecha 
			$oScheduleAd->unix_date    = $sUnixDate;
			$oScheduleAd->human_date   = $sHumanDate;
			$oScheduleAd->country_date = $sDate;
			$bInserted                 = $oScheduleAd->save();

			//Si se actualizo la fecha correctamente
			if( $bInserted ) {
				
				
				//Borramos todos los Slots asociados a esta programacion con Status 0
				ScheduledAdsSlots::cleanScheduledSlots($iCronoId);

				$iInsertedID = $oScheduleAd->ID;

				//Iteracion de cada Slots 
				foreach ( $oSlots as $oSlot ){
					$oScheduleAdSlot                  = new ScheduledAdsSlots;
					$oScheduleAdSlot->slot_id         = $oSlot->slotID;
					$oScheduleAdSlot->scheduled_ad_id = $iInsertedID;
					$oScheduleAdSlot->save();
				}

				$aData = array(
							'status' => 1, 
							'msg'    => 'Actualizacion exitoso.'
						);
			}else{
				$aData = array(
							'status' => 0, 
							'msg'    => 'Ocurrio un error.'
						);
			}
		
		}// IF Crono_id

		return Response::json( $aData );
	}

	/**
	 * Se obtiene la lista de Cronos
	 */
	public function getScheduledAd(){
		$aData       = array();
		$iAdId       = Input::get( "ad_id" );

		// Se obtiene la programación del Ad
		$aAdSchedule = ScheduledAds::getScheduledAds( $iAdId );

		return Response::json( $aAdSchedule );
	}

	/**
	 * Borra una programacion
	 */
	public function deleteScheduledAd(){
		$aData          = array();
		$iCronoId       = Input::get( "crono_id" );

		// Se borra la programación colocando el status 0
		$oScheduleAd               = ScheduledAds::find($iCronoId);
		
		//Se actualiza la fecha 
		$oScheduleAd->status       = 0;
		$bInserted                 = $oScheduleAd->save();
		
		//Si se actualizo la fecha correctamente
		if( $bInserted ) {
			$aData = array(
						'status' => 1, 
						'msg'    => 'Se borro exitosamente.'
					);
		}else{
			$aData = array(
						'status' => 0, 
						'msg'    => 'Ocurrio un error.'
					);
		}

		return Response::json( $aData );
	}

	/**
	 * Borra una programacion
	 */
	public function videoForScreen($id){
		
	}

	/**
	 * Se obtiene la lista de Cronos
	 */
	/*public function updateScheduledAd(){
		$aData       = array();
		$iCronoID    = Input::get( "crono_id" );
		$iAdId       = Input::get( "ad_id" );
		$sDate       = Input::get( "sDate" );
		$iCostumerId = Input::get( "costumerid" );
		$iCampaignId = Input::get( "campaignid" );
		$oSlots      = json_decode( stripslashes( Input::get( "aDataSlots" ) ) );
	}*/
}
