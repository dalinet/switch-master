<?php 
	class Ads extends Eloquent { 
		protected $table      = 'Ads';
		protected $primaryKey = 'ad_id';

		/**
		 * Se obtienen los anuncios precargados para la campaña
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getPreloadedAdsCampaign( $campaign_id ){
			$result = Ads::select( 'ad_id', 'campaign_id', 'name as nombre', 'image_src as imagen', 'created_at', 'status','Ads.formato','Ads.file_src as tumb' )
							  ->where('campaign_id', '=',$campaign_id )
							  ->where('type', '=', 'preloaded' )
							  ->where('status', '=', '1' )
							  ->get();
			return $result;
		}

		/**
		 * Se obtienen los anuncios creados para la campaña
		 * API QUERY
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCreatedAdsCampaign( $campaign_id ){
			/*$result = Ads::join('Campaigns_url_ads_published AS t2', 'Ads.ad_id', '=', 't2.ad_id')
							->select( 't2.campaign_url_ad_id', 'Ads.ad_id', 'campaign_id', 'name as nombre', 'image_src as imagen', 'Ads.created_at', 'Ads.status' )
							->where('campaign_id', '=',$campaign_id )
							->where('type', '=', 'created' )
							->get();*/

			$result = Ads::select( 'Ads.ad_id', 'campaign_id', 'name as nombre', 'image_src as imagen', 'Ads.created_at', 'Ads.status', 'Ads.formato','Ads.file_src as tumb' )
							->where('campaign_id', '=',$campaign_id )
							->where('type', '=', 'created' )
							->where('Ads.status', '=', '1' )
							->orderBy('Ads.ad_id', 'DESC')
							->get();

			return $result;
		}

		/**
		 * Se obtienen los anuncios creados en una campaña
		 * CMS QUERY
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCreatedAdsHistorical( $iCampaignId ){

			$result = Ads::join('Costumers AS t2', 't2.costumer_id', '=', 'Ads.costumer_id')
							->select( 'ad_id', 't2.name as costumer_name', 'image_src', 'Ads.created_at', 'Ads.updated_at', 'Ads.formato','file_src' )
							->where('campaign_id', '=', $iCampaignId )
							->where('type', '=', 'created' )
							->where('Ads.status', '=', 1 )
							->orderBy('Ads.created_at', 'DESC')
							->get();
			return $result;
		}

		/**
		 * Se obtienen los anuncios creados en una campaña
		 * CMS QUERY
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCreatedAdsHistoricalImg( $iCampaignId ){

			$result = Ads::join('Costumers AS t2', 't2.costumer_id', '=', 'Ads.costumer_id')
							->select( 'ad_id', 't2.name as costumer_name', 'image_src', 'Ads.created_at', 'Ads.updated_at', 'Ads.formato','file_src' )
							->where('campaign_id', '=', $iCampaignId )
							->where('type', '=', 'created' )
							->where('Ads.status', '=', 1 )
							->where('Ads.formato', '=', 'image' )
							->orderBy('Ads.created_at', 'DESC')
							->get();
			return $result;
		}

		/**
		 * Se obtiene el Path de los anuncios creados
		 * en una campaña
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCreatedAdsHistoricalPath( $iCampaignId ){

			$result = Ads::select( DB::raw('CONCAT("' . public_path() . '", image_src) AS path') )
							->where('campaign_id', '=', $iCampaignId )
							->where('type', '=', 'created' )
							->where('Ads.status', '=', 1 )
							->orderBy('Ads.created_at', 'DESC')
							->get();
			return $result;
		}

			/**
		 * Se obtiene el Path de los anuncios creados
		 * en una campaña
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCreatedAdsHistoricalPathImg( $iCampaignId ){

			$result = Ads::select( DB::raw('CONCAT("' . public_path() . '", image_src) AS path') )
							->where('campaign_id', '=', $iCampaignId )
							->where('type', '=', 'created' )
							->where('Ads.status', '=', 1 )
							->where('Ads.formato', '=', 'image' )
							->orderBy('Ads.created_at', 'DESC')
							->get();
			return $result;
		}

		/**
		 *  Devuelve  el ultimo Ad Video
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getLastAdVideo( $iCampaignId ){

			$result = Ads::select('image_src AS url', 'file_src AS previo')
							->where('campaign_id', '=', $iCampaignId )
							->where('type', '=', 'preloaded' )
							->where('formato', '=', 'video' )
							->where('status', '=', 1 )
							->orderBy('ad_id', 'DESC')
							->get();
			return $result;
		}

		/*public static function getPublishedAdsCampaign( $campaign_id ){
			$result = Ads::join('Companies', 'Costumers.company_id', '=', 'Companies.company_id')
						   ->select( 'ad_id', 'campaign_id', 'name as nombre', 'image_src as imagen', 'created_at', 'status' )
						   ->where('campaign_id', '=',$campaign_id )
						   ->get();
			
			return $result;
		}*/

	}
?>