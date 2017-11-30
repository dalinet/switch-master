<?php 
	class ScheduledAdsSlots extends Eloquent { 
		protected $table      = 'Scheduled_ads_slots';
		protected $primaryKey = 'ID';

		public static function getScheduledAdsSlots( $Scheduled_ad_id ){
			/*$sHost        = "http://" . $_SERVER[ 'HTTP_HOST' ];

			$result = ScheduledAdsSlots::select( 'campaign_url_id', DB::raw('CONCAT("' . $sHost . '", url) AS url'), 'url AS shorturl' )
								   ->whereRaw('campaign_id =' . $campaign_id)
								   ->whereRaw('status = 1')
								   ->get();
			return $result;*/
		}

		/**
		 * Limpia todos los Slots asociados a una programación colocandoles status 0
		 * @param  [type] $Scheduled_id identificador de la programación asociada
		 * @return [type]               [description]
		 */
		public static function cleanScheduledSlots( $Scheduled_id ){
			
			$result = DB::table('Scheduled_ads_slots')
				            ->where('scheduled_ad_id', $Scheduled_id )
				            ->update(array('status' => 0));
			return 1;
		}
	}
?>