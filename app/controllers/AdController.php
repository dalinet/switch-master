<?php

/**
 * Controlador de los anuncios
 */
class AdController extends AuthController {

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
	 * Se genera el PDF de los anuncios 
	 * creados de una campaña
	 */
	public function getCreatedAdsPDF()
	{
		$iCampaignId = Input::get( "campaign_id" );

		// Nombre del archivo
		$sPdfFile         = time() .  ".pdf";
		// Ruta de la carpeta de almacenamiento de los PDFS
		$sAbsolutePdfPath = public_path() . '/campaigns/' . $iCampaignId . '/pdfs/';
		$sFullPathPdfFile = $sAbsolutePdfPath . $sPdfFile;
		// Ruta URL del archivo
		$sHost            = "http://" . $_SERVER[ 'HTTP_HOST' ];
		$sURL			  = $sHost . '/campaigns/' . $iCampaignId . '/pdfs/';
		$sFullURL         = $sURL . $sPdfFile;

		// Se revisa si existe el directorio
		if( !File::exists( $sAbsolutePdfPath ) ) {
			// Se crea el directorio
		    File::makeDirectory( $sAbsolutePdfPath, 0775, true );
		}

		//$aCreatedAds = Ads::getCreatedAdsHistorical( $iCampaignId );
		$aCreatedAds = Ads::getCreatedAdsHistoricalImg( $iCampaignId );

		/*return View::make( "pdfs.created-ads-pdf")
					->with( "ads", $aCreatedAds );*/

		$aData = array( 'ads' => $aCreatedAds );

		$pdf = PDF::loadView("pdfs.created-ads-pdf", $aData );
		$status = $pdf->save( $sFullPathPdfFile );

		return Response::json( $sFullURL );
	}

    /**
	 * Se crea el archivo ZIP con los
	 * anuncios creados en la campaña
	 */
	public function getCreatedAdsZIP()
	{
		$iCampaignId      = Input::get( "campaign_id" );

		// Nombre del archivo
		$sZipName         = time() .  ".zip";
		// Ruta de la carpeta de almacenamiento de los ZIPS
		$sAbsoluteZipPath = public_path() . '/campaigns/' . $iCampaignId . '/zips/';
		$sFullPathZipFile = $sAbsoluteZipPath . $sZipName;
		// Ruta URL del archivo
		$sHost            = "http://" . $_SERVER[ 'HTTP_HOST' ];
		$sURL			  = $sHost . '/campaigns/' . $iCampaignId . '/zips/';
		$sFullURL         = $sURL . $sZipName;

		// Se revisa si existe el directorio
		if( !File::exists( $sAbsoluteZipPath ) ) {
			// Se crea el directorio
		    File::makeDirectory( $sAbsoluteZipPath, 0775, true );
		}

		// Se obtienen los anuncios creados para una campaña
		//$aCreatedAds = Ads::getCreatedAdsHistoricalPath( $iCampaignId )->toArray();
		$aCreatedAds = Ads::getCreatedAdsHistoricalPathImg( $iCampaignId )->toArray();

		// Se crea el archivo ZIP
		$status = Zipper::make( $sFullPathZipFile )
		                ->add( $aCreatedAds )
		                ->getStatus();

		// return Response::json( $status );
		if( $status == "No error"){
			// return Response::download($sAbsoluteZipPath, "test.zip");
			return Response::json( $sFullURL );
		}

	}

    /**
	 * Se elimina un anuncio actualmente publicado
	 */
	public function delPublishedAd()
	{
		$mailStatus     = 0;
		$iCampaignURLId = Input::get( "campaign_url_id" );

		// Información CampaignURL
		$oCampaignURL = CampaignURL::find( $iCampaignURLId );

		$iCampaignId  = $oCampaignURL->campaign_id;
		$sURL         = $oCampaignURL->url;
		$sAbsoluteURL = public_path() . $sURL;

		// Información de la campaña
		$oCampaign   = Campaign::find( $iCampaignId );
		$sDefaultImg = public_path() . $oCampaign->image_src;

		// Se copia el archivo por default
		$bCopyStatus   = copy( $sDefaultImg, $sAbsoluteURL );

		/**
		 * ENVIO DEL CORREO ELECTRÓNICO
		 */
		if( $bCopyStatus ){

			$oCampaignURL->type = "default";
			$oCampaignURL->save();


			// Se obtienen la información sobre la publicación
			// del anuncio
			$oCampaignPublished = CampaignUrlAdPublished::where( "campaign_url_id", "=", $iCampaignURLId )
															->where( "status", "=", "1" )
															->first();

			// Correo electrónico del usuario que
			// publicó el anuncio
			$oCostumer      = $oCampaignPublished->CostumerInfo;
			$sCostumerEmail = $oCostumer->email;
			$sCostumerName  = $oCostumer->name;

			// Archivo del Anuncio 
			$oAd     = $oCampaignPublished->AdInfo;
			$sImgURL = $oAd->image_src;

			// Se envia el email
			$aData = array( 'name'  => $sCostumerName, 
							'adURL' => $sImgURL 
						    );

			$mailStatus = Mail::send('emails.adDeleted', $aData, function ($message) use( $sCostumerEmail ){
			                        $message->subject( "Su anuncio ha sido eliminado" );
			                        $message->to( $sCostumerEmail );
			                    });

		}
		
		$aStatus = array( 'status'     => $bCopyStatus, 
						  'mailStatus' => $mailStatus,
						);

		return Response::json( $aStatus );

	}
}
