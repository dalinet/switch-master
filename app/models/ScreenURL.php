<?php 
	class ScreenURL extends Eloquent { 
		protected $table      = 'Screens_url';
		protected $primaryKey = 'screen_url_id';

		/**
		 * RELATIONSHIPS
		 */
		public function campaignURL(){
		    return $this->belongsTo('CampaignURL', 'campaign_url_id');
		}

		public function screen(){
		    return $this->belongsTo('Screen', 'screen_id');
		}

		/**
		 * Se obtienen las URLs
		 * segun la pantalla de una campaña
		 */
		public static function getURLsByScreenCampaign( $iCampaign_id, $iScreen_id , $sFormato )
		{	

			$query = ScreenURL::query();

			$query->join( 'Campaigns_url AS t2', 'Screens_url.campaign_url_id', '=', 't2.campaign_url_id' )
											  ->select( 'Screens_url.screen_id', 'Screens_url.screen_url_id', 't2.url', 'Screens_url.campaign_url_id','t2.previo as tumb ','t2.formato' )
											  ->where( 'Screens_url.screen_id', '=', $iScreen_id )
											  ->where( 't2.campaign_id', '=', $iCampaign_id )
											  ->where( 't2.status', '=', 1 )
											  ->where( 'Screens_url.status', '=', 1 );

			if($sFormato!=""){
				$query->where('t2.formato','=',$sFormato);
			}

		     $aURLsByScreenCampaign= $query->get();
		

			return $aURLsByScreenCampaign;

		}

		/**
		 * Se obtienen las URLs
		 * segun la pantalla de una campaña
		 */
		public static function getURLInfo( $iScreenURLId )
		{
			$oURLInfo = ScreenURL::join( 'Campaigns_url AS t2', 'Screens_url.campaign_url_id', '=', 't2.campaign_url_id' )
								->join( 'Screens AS t3', 't3.screen_id', '=', 'Screens_url.screen_id' )
								->join( 'Screens_size AS t4', 't4.screen_size_id', '=', 't3.screen_size_id' )
								->select( 't2.campaign_url_id','t2.campaign_id', 't2.url','t4.width','t4.height' )
								->where( 'Screens_url.screen_url_id', '=', $iScreenURLId )
								->get();

			return $oURLInfo;

		}

		/**
		 * Se obtienen las URLs relacionadas con 
		 * Pantallas
		 */
		public static function getURLScreenByCampaign( $iCampaign_id )
		{
			$oURLInfo = ScreenURL::join( 'Campaigns_url AS t2', 'Screens_url.campaign_url_id', '=', 't2.campaign_url_id' )
								   ->join( 'Screens AS t3', 'Screens_url.screen_id', '=', 't3.screen_id' )
								   ->select( 't2.campaign_url_id', 'Screens_url.screen_id', 't3.name', 't3.location', 't2.url' )
								   ->where( 't2.campaign_id', '=', $iCampaign_id )
								   ->where( 't2.status', '=', 1 )
								   ->where( 'Screens_url.status', '=', 1 )
								   ->where( 't3.status', '=', 1 )
								   ->orderBy('Screens_url.screen_id', 'ASC')
								   ->get();

			return $oURLInfo;

		}

		/**
		 * Se actualizan a status = 0
		 * las relaciones URLs/Pantallas
		 * pertenecientes a una Campaña
		 */
		public static function updateURLScreenByCampaign( $iCampaign_id )
		{
			$aURLsScreen = ScreenURL::join( 'Campaigns_url AS t2', 'Screens_url.campaign_url_id', '=', 't2.campaign_url_id' )
								   ->join( 'Screens AS t3', 'Screens_url.screen_id', '=', 't3.screen_id' )
								   ->select( 'Screens_url.screen_url_id' )
								   ->where( 't2.campaign_id', '=', $iCampaign_id )
								   ->where( 't2.status', '=', 1 )
								   ->where( 'Screens_url.status', '=', 1 )
								   ->where( 't3.status', '=', 1 )
								   ->get()
								   ->toArray();

			$iNoResults= count( $aURLsScreen );

			if( $iNoResults > 0 ){
				$aToUpdate = array();

				foreach ($aURLsScreen as $key => $value) {
					array_push( $aToUpdate, $aURLsScreen[ $key ][ 'screen_url_id' ]);
				}

				$oUpdtScreenURL =	ScreenURL::whereIn( 'screen_url_id', $aToUpdate )
										       ->update( array('status' => 0) );

				return $oUpdtScreenURL;
			}else{
				return false;
			}
			

		}

	}
?>