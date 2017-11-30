<?php 
	class Campaign extends Eloquent { 
		protected $table      = 'Campaigns';
		protected $primaryKey = 'campaign_id';

		/**
		 * Se retornan todas las campañas 
		 * registradas
		 * @return [obj] Campañas
		 */
		public static function getCampaigns(){
			$result = Campaign::join('Companies', 'Campaigns.company_id', '=', 'Companies.company_id')
							  ->select( 'campaign_id', 'Companies.name as company_name', 'Campaigns.name as campaign_name', 'Campaigns.start', 'Campaigns.end' )
							  ->where('Campaigns.status', '=', 1)
							  ->get();
			return $result;
		}

		/**
		 * Se retornan las campañas de un usuario
		 * @param  [int] $costumer_id
		 * @return [obj] Campañas del usuario
		 */
		public static function getCampaignsCostumer($costumer_id){
			$result = Campaign::join('Campaign_costumers', 'Campaign_costumers.campaign_id', '=', 'Campaigns.campaign_id')
							  ->select( 'Campaigns.campaign_id', 'Campaigns.name as nombre', 'Campaigns.image_src as imagen', 'Campaigns.start as start', 'Campaigns.end as end' )
							  ->where('Campaigns.status', '=', 1)
							  ->where('Campaign_costumers.status', '=', 1)
							  ->where('Campaign_costumers.costumer_id', '=', $costumer_id)
							  ->orderBy('Campaigns.campaign_id', 'ASC')
							  ->get();

			return $result;
		}

		/**
		 * Se obtienen las campañas de un cliente/compañia
		 * @param  [int] $company_id
		 * @return [obj] Campañas del cliente/compañia
		 */
		public static function getCampaignsByCompany($companyId){

			$result = Campaign::select( 'campaign_id', 'name', 'start', 'end' )
							  ->where('company_id', '=', $companyId)
							  ->where('status', '=', 1)
							  ->orderBy('campaign_id', 'DESC')
							  ->get();

			return $result;
		}

		/**
		 * Se obtienen las campañas de un cliente/compañia
		 * @param  [int] $company_id
		 * @return [obj] Campañas del cliente/compañia
		 */
		public static function getCampaignsBySearch( $companyId, $startDate, $endDate , $allDates ){

			if( $companyId == "all" ){
				if( $allDates == "true" ){
					$result = Campaign::select( 'campaign_id', 'name', 'start', 'end' )
									  ->where('status', '=', 1)
									  ->orderBy('campaign_id', 'DESC')
									  ->get();
				}else{
					$result = Campaign::select( 'campaign_id', 'name', 'start', 'end' )
									  ->whereRaw( "start BETWEEN '" . $startDate . "' AND '" . $endDate . "'" )
									  ->whereRaw( "end BETWEEN '" . $startDate . "' AND '" . $endDate . "'" )
									  ->where('status', '=', 1)
									  ->orderBy('campaign_id', 'DESC')
									  ->get();
				}
				
			}else{
				if( $allDates == "true" ){
					$result = Campaign::select( 'campaign_id', 'name', 'start', 'end' )
									  ->where('company_id', '=', $companyId)
									  ->where('status', '=', 1)
									  ->orderBy('campaign_id', 'DESC')
									  ->get();
				}else{
					$result = Campaign::select( 'campaign_id', 'name', 'start', 'end' )
									  ->whereRaw( "start BETWEEN '" . $startDate . "' AND '" . $endDate . "'" )
									  ->whereRaw( "end BETWEEN '" . $startDate . "' AND '" . $endDate . "'" )
									  ->where('company_id', '=', $companyId)
									  ->where('status', '=', 1)
									  ->orderBy('campaign_id', 'DESC')
									  ->get();
				}
				
			}

			return $result;
		}

		/**
		 * Se "elimina" una campaña
		 * @return [bool] estado de la operación
		 */
		public static function deleteCampaign( $campaign_id ){

			$oCampaign = Campaign::find( $campaign_id );
			$oCampaign->status = 0;

			$bResult = $oCampaign->save();

			return $bResult;
		}

		/**
		 * Se retorna la información de una campaña
		 * @return [obj] Información de la campaña
		 */
		public static function getCampaignInfo( $campaign_id ){
			$aCampaignInfo = array();

			// Se obtiene la información de la campaña
			$oCampaign = Campaign::find( $campaign_id );

			$campaign_id = $oCampaign->campaign_id;
			$company_id  = $oCampaign->company_id;

			// Se obtiene la información de la compañia
			$oCompany = Company::find( $company_id );

			$aCompany = array( 'company_id' => $company_id,
							   'name'       => $oCompany->name
							 );

			// Se obtienen los usuario de la campaña
			$aCampaignCostumer = CampaignCostumer::join('Costumers', 'Costumers.costumer_id', '=', 'Campaign_costumers.costumer_id')
												   ->select( 'Costumers.costumer_id', 'Costumers.name', 'Campaign_costumers.campaign_costumer_id' )
												   ->where('Campaign_costumers.status', '=', 1)
												   ->where('Campaign_costumers.campaign_id', '=', $campaign_id)
												   ->get()
												   ->toArray();

			// Se obtienen los usuario de la compañia
			$aCostumer = Costumer::select( 'costumer_id', 'name' )
								   ->where( 'status', '=', 1 )
								   ->where( 'company_id', '=', $company_id)
								   ->get()
								   ->toArray();

			$iNumCostumers = count( $aCostumer );

			for ($i=0; $i < $iNumCostumers; $i++) { 
				$costumer_id = $aCostumer[ $i ][ "costumer_id" ];

				$inArray = Campaign::in_array_r( $costumer_id, $aCampaignCostumer );

				if( $inArray->status === true ){
					$aCostumer[ $i ][ "campaign_costumer_id" ] = $inArray->campaign_costumer_id[ 'campaign_costumer_id' ];
					$aCostumer[ $i ][ "checked" ] = true;
				}else{
					$aCostumer[ $i ][ "checked" ] = false;
				}
			}

			// Se obtienen las fuentes de la campaña
			$aFonts_campaign = FontCampaign::join('Fonts', 'Fonts_campaign.font_id', '=', 'Fonts.font_id')
											 ->select( 'Fonts.font_id', 'Fonts.name', 'Fonts_campaign.font_campaign_id' )
											 ->where( 'Fonts.status', '=', 1 )
											 ->where( 'Fonts_campaign.status', '=', 1 )
											 ->where( 'campaign_id', '=', $campaign_id)
											 ->get()
											 ->toArray();

			// Se obtiene los anuncios precargados
			$aPreloadedAds = Ads::select( 'ad_id', 'image_src','formato' )
								  ->where( 'status', '=', 1 )
								  ->where( 'type', '=', 'preloaded' )
								  ->where( 'campaign_id', '=', $campaign_id)
								  ->get()
								  ->toArray();

			// Se obtienen los usuarios para la campaña
			/*SELECT t1.costumer_id, t1.name, t1.user_name, t2.campaign_costumer_id
			FROM Costumers AS t1
			LEFT JOIN ( SELECT * FROM Campaign_costumers WHERE status = 1 ) AS t2 ON t1.costumer_id = t2.costumer_id
			WHERE t1.company_id = 10
			AND t1.status = 1*/

			$subQuery = DB::table('Campaign_costumers')
						  	->select( 'campaign_costumer_id', 'costumer_id', 'campaign_id' )
						  	->whereRaw( "status = 1")
						  	->whereRaw( "campaign_id = " . $campaign_id);

			$aCampaignUsers = Costumer::select( 'Costumers.costumer_id', 'Costumers.name', 'Costumers.user_name', 't2.campaign_costumer_id' )
										->leftJoin( DB::raw("({$subQuery->toSql()}) as t2"), 'Costumers.costumer_id', '=', 't2.costumer_id' )
										->whereRaw( 'company_id =' . $company_id )
										->whereRaw( 'status = 1' )
										->get()
										->toArray();

			// Se acomoda la información
			$aCampaignInfo[ 'campaign_id' ] = $oCampaign->campaign_id;
			$aCampaignInfo[ 'name' ]        = $oCampaign->name;
			$aCampaignInfo[ 'image_src' ]   = $oCampaign->image_src;
			$aCampaignInfo[ 'links' ]       = $oCampaign->links;
			$aCampaignInfo[ 'links_video' ] = $oCampaign->links_video;
			$aCampaignInfo[ 'start' ]       = $oCampaign->start;
			$aCampaignInfo[ 'end' ]         = $oCampaign->end;
			$aCampaignInfo[ 'company' ]     = $aCompany;
			$aCampaignInfo[ 'costumers' ]   = $aCostumer;
			$aCampaignInfo[ 'fonts' ]       = $aFonts_campaign;
			$aCampaignInfo[ 'ads' ]         = $aPreloadedAds;
			$aCampaignInfo[ 'users' ]       = $aCampaignUsers;

			return $aCampaignInfo;
		}

		/**
		 * Se busca información en un Array
		 */
		public static function in_array_r( $search, $arrayElemnt, $strict = false ) {
		    foreach ($arrayElemnt as $item) {
		    	$inArray;

		    	if( is_array( $item ) ){
		    		$inArray = Campaign::in_array_r($search, $item, $strict);
		    	}
		    	
		        if (($strict ? $item === $search : $item == $search) || (is_array($item) && $inArray->status == true )) {
		            return (object) array('status' => true, 'campaign_costumer_id' => $item );
		        	// return true;
		        }
		    }
		    return (object) array( 'status' => false );
		    // return false;
		}

	}
?>