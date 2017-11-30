<?php 
	class Font extends Eloquent { 
		protected $table      = 'Fonts';
		protected $primaryKey = 'font_id';

		public static function getCampaignFonts( $iCampaignId ){

			$aFonts = Font::join( 'Fonts_campaign AS t2', 'Fonts.font_id', '=', 't2.font_id' )
						  ->select( 't2.font_campaign_id', 'Fonts.font_id', 'Fonts.name', 'Fonts.postscript_name', 'Fonts.font_src' )
						  ->where( 't2.campaign_id', '=', $iCampaignId )
						  ->where( 't2.status', '=', 1 )
						  ->get();

			return $aFonts;
		}

		/**
		 * Se obtienen las fuentes de una campaña con su ruta completa
		 * @param  [Integer] $iCampaignId [Id de la campaña]
		 * @return [Object]               [Fuentes de la campaña]
		 */
		public static function getCampaignPathFonts( $iCampaignId ){

			$aFonts = Font::join( 'Fonts_campaign AS t2', 'Fonts.font_id', '=', 't2.font_id' )
						  ->select( DB::raw('CONCAT("' . public_path() . '", Fonts.font_src) AS full_src') )
						  ->whereRaw( "t2.campaign_id = " . $iCampaignId )
						  ->whereRaw( "t2.status = 1" )
						  ->get();

			return $aFonts;
		}

	}
?>