<?php 
	class Screen extends Eloquent { 
		protected $table      = 'Screens';
		protected $primaryKey = 'screen_id';

		/**
		 * RELATIONSHIPS
		 */
		public function screenURL(){
		    return $this->hasMany( 'screenURL' );
		}

		/**
		 * Se obtienen las pantallas registradas
		 */
		public static function getScreens(){
			$result = Screen::join( 'Phases AS t2', 'Screens.phase_id', '=', 't2.phase_id' )
							  ->select('screen_id', 'Screens.name', 'location', 'latitude', 'longitude', 't2.name AS phase_name', 't2.phase_id')
							  ->where('Screens.status', '=', 1)
							  ->get();
			return $result;
		}

		/**
		 * Se obtienen las pantallas 
		 * con URLs relacionadas de una campaña
		 */
		public static function getScreensByCampaign( $iCampaign_id, $sFormato )
		{	

			$query = Screen::query();
			$query->join( 'Screens_url AS t2', 'Screens.screen_id', '=', 't2.screen_id' )
				    ->join( 'Campaigns_url AS t3', 't2.campaign_url_id', '=', 't3.campaign_url_id' )
				    ->select( 'Screens.screen_id', 'Screens.name', 'Screens.location' )
					->distinct()
					->where( 'Screens.status', '=', 1 )
					->where( 't2.status', '=', 1 )
					->where( 't3.status', '=', 1 )
					->where( 't3.campaign_id', '=', $iCampaign_id );

		
			if($sFormato!=""){
				$query->where( 't3.formato','=',$sFormato);
			}
										 		  
			$aScreensByCampaign=$query->get();

			return $aScreensByCampaign;

		}

	}
?>