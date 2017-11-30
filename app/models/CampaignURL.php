<?php 
	class CampaignURL extends Eloquent { 
		protected $table      = 'Campaigns_url';
		protected $primaryKey = 'campaign_url_id';

		public static function getUrlByCampaign( $campaign_id ){
			$sHost        = "http://" . $_SERVER[ 'HTTP_HOST' ];

			$result = CampaignURL::select( 'campaign_url_id', DB::raw('CONCAT("' . $sHost . '", url) AS url'), 'url AS shorturl', 'formato', DB::raw('CONCAT("'.$sHost.'/videoforscreen/", campaign_url_id ) AS URLVIDEO') )
								   ->whereRaw('campaign_id =' . $campaign_id)
								   ->whereRaw('status = 1')
								   ->get();
			return $result;
		} 

		/**
		 * Se obtienen las URLs de la campaña 
		 * que no han sido cambiadas por un anuncio
		 */
		public static function getUrlByCampaignDefault( $campaign_id ){

			$result = CampaignURL::select( 'campaign_url_id','url', 'url AS shorturl' )
								   ->where( 'campaign_id', '=', $campaign_id )
								   ->where( 'status', '=', 1)
								   ->where( 'type', '=', 'default')
								   ->get();
			return $result;
		}
	}
?>