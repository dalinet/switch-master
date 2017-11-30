<?php 
	class ScheduledAds extends Eloquent { 
		protected $table      = 'Scheduled_ads';
		protected $primaryKey = 'ID';

		public static function getScheduledAds( $iAdId ){
			$aInfo = array();

			// Hora de programación ( Hora del Pais )
			$subQuery = DB::table('Scheduled_ads_slots AS st1')
						  	->select( DB::raw('COUNT( st1.scheduled_ad_id )') )
						  	->whereRaw( "st1.scheduled_ad_id = Scheduled_ads.ID")
						  	->whereRaw( "st1.status = 1");

			$oScheduleds = ScheduledAds::join('Scheduled_ads_slots', 'Scheduled_ads.ID', '=', 'Scheduled_ads_slots.scheduled_ad_id')
								   ->join('Screens_url', 'Scheduled_ads_slots.slot_id', '=', 'Screens_url.screen_url_id')
								   ->join('Screens', 'Screens_url.screen_id', '=', 'Screens.screen_id')
			                       ->select( 'Scheduled_ads.ID AS crono_id', 'Scheduled_ads.country_date AS hora', 'Screens.screen_id', 'Screens.name AS nombre_pantalla', DB::raw("({$subQuery->toSql()}) as no_slots") )
								   ->where( 'Scheduled_ads.ad_id', '=', $iAdId )
								   ->where( 'Scheduled_ads.status', '=', 1 )
								   ->distinct()
								   ->get();

			foreach ( $oScheduleds as $oScheduled ) {
				$iScheduledAdID = $oScheduled->crono_id;

				$oScheduledSlots = ScheduledAdsSlots::select( 'Scheduled_ads_slots.slot_id' )
									->where( 'Scheduled_ads_slots.scheduled_ad_id', '=', $iScheduledAdID )
									->where( 'Scheduled_ads_slots.status', '=', '1' )
									->get()
									->toArray();

				$aTmpArray = array(
									'crono_id'        => $iScheduledAdID, 
									'hora'            => $oScheduled->hora,
									'screen_id'       => $oScheduled->screen_id,
									'nombre_pantalla' => $oScheduled->nombre_pantalla,
									'no_slots'        => $oScheduled->no_slots,
									'slots'           => $oScheduledSlots
								);

				array_push( $aInfo, $aTmpArray );
			}
			return $aInfo;
		}

		/*public static function getScheduledAdsSlots( $iAdId ){
			
			$oResult = ScheduledAds::join('Scheduled_ads_slots', 'Scheduled_ads.ID', '=', 'Scheduled_ads_slots.scheduled_ad_id')
									->select( 'Scheduled_ads_slots.slot_id' )
									->where( 'Scheduled_ads.ad_id', '=', $iAdId )
									// ->where( 'Scheduled_ads_slots.scheduled_ad_id', '=', 'Scheduled_ads.ID' )
									->where( 'Scheduled_ads_slots.status', '=', '1' )
									->get();

			return $oResult;
		}*/
	}
?>