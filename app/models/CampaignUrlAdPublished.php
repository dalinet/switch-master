<?php 
	class CampaignUrlAdPublished extends Eloquent { 
		protected $table      = 'Campaigns_url_ads_published';
		protected $primaryKey = 'campaign_url_ad_id';

		/**
		 * RELATIONSHIPS
		 */
		public function CostumerInfo(){
		    return $this->belongsTo('Costumer', 'costumer_id');
		}

		public function AdInfo(){
		    return $this->belongsTo('Ads', 'ad_id');
		}

		/**
		 * Se obitienen los anuncios publicados
		 */
		public static function getPublishedAdsCampaign( $campaign_id ){
			$result = CampaignUrlAdPublished::join('Ads', 'Campaigns_url_ads_published.ad_id', '=', 'Ads.ad_id')
										      ->select( 'campaign_url_ad_id', 'Ads.ad_id', 'Ads.campaign_id', 'Ads.name as nombre', 'Ads.image_src as imagen', 'Ads.created_at', 'Ads.status', 'Ads.formato' )
										      ->where('Ads.campaign_id', '=',$campaign_id )
										      ->where('Campaigns_url_ads_published.status', '=', 1 )
										      ->orderBy('campaign_url_ad_id', 'DESC')
										      ->get();
			
			
			return $result;
		}

		/**
		 * Se obtienen los anuncios precargados para la campaña
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getPublishedAdsHistorical( $iCampaignId ){

			$result = CampaignUrlAdPublished::join('Ads AS t2', 't2.ad_id', '=', 'Campaigns_url_ads_published.ad_id')
											->join('Costumers AS t3', 't3.costumer_id', '=', 'Campaigns_url_ads_published.costumer_id')
											->join('Screens_url AS t4', 't4.campaign_url_id', '=', 'Campaigns_url_ads_published.campaign_url_id')
											->join('Screens AS t5', 't5.screen_id', '=', 't4.screen_id')
											->select( 't3.costumer_id', 't3.name AS costumer_name', 't2.image_src','t2.file_src','t2.formato',  DB::raw('CONCAT(t5.name, " / ", t5.location) AS location'), 'Campaigns_url_ads_published.created_at', 'Campaigns_url_ads_published.updated_at' )
											->where('t2.campaign_id', '=', $iCampaignId )
											->where('t2.status', '=', '1' )
											->where('t3.status', '=', '1' )
											->where('t4.status', '=', '1' )
											->where('t5.status', '=', '1' )
											->orderBy('Campaigns_url_ads_published.created_at', 'DESC')
											->get();
			return $result;
		}

		/**
		 * Se obtienen los anuncios precargados para la campaña
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getPublishedAdsHistoricalByCampaign( $iCampaignId ){

			$result = CampaignUrlAdPublished::join('Ads AS t2', 't2.ad_id', '=', 'Campaigns_url_ads_published.ad_id')
											->join('Costumers AS t3', 't3.costumer_id', '=', 'Campaigns_url_ads_published.costumer_id')
											->join('Screens_url AS t4', 't4.campaign_url_id', '=', 'Campaigns_url_ads_published.campaign_url_id')
											->join('Screens AS t5', 't5.screen_id', '=', 't4.screen_id')
											->select( DB::raw('CONCAT("' . url('/') . '", t2.image_src) AS url') )
											->distinct()
											->where('t2.campaign_id', '=', $iCampaignId )
											->where('t2.status', '=', '1' )
											->where('t3.status', '=', '1' )
											->where('t4.status', '=', '1' )
											->where('t5.status', '=', '1' )
											->orderBy('Campaigns_url_ads_published.campaign_url_ad_id', 'ASC')
											->get();
			return $result;
		}

		/**
		 * Se obtienen los anuncios actualmente publicados
		 * @param  [int] $campaign_id 
		 * @return [obj] $result
		 */
		public static function getCurrentlyPublishedAds( $iCampaignId ){

			$subQuery = DB::table('Campaigns_url_ads_published AS st1')
						  	->join( 'Costumers AS st2', 'st1.costumer_id', '=', 'st2.costumer_id' )
						  	->select( 'st2.name AS username' )
						  	->whereRaw( "st1.campaign_url_id = Campaigns_url.campaign_url_id")
						  	->whereRaw( "st1.status = 1");

			$result =	CampaignURL::join( 'Screens_url AS t2', 't2.campaign_url_id', '=', 'Campaigns_url.campaign_url_id')
									->join( 'Screens AS t3', 't3.screen_id', '=', 't2.screen_id' )
									->select( 'Campaigns_url.campaign_url_id', 'Campaigns_url.url','Campaigns_url.formato' ,'Campaigns_url.type', DB::raw('CONCAT(t3.name, " / ", t3.location) AS location'), 'Campaigns_url.updated_at', DB::raw("({$subQuery->toSql()}) as costumer_name"))
									->whereRaw( "Campaigns_url.campaign_id = " . $iCampaignId )
									->whereRaw( "Campaigns_url.status = 1")
									->whereRaw( "t2.status = 1" )
									->orderBy( 't3.screen_id', 'DESC')
									->get();

			return $result;
		}

	}

?>