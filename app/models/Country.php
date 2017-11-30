<?php 
	class Country extends Eloquent { 
		protected $table      = 'Countries';
		protected $primaryKey = 'country_id';

		public static function getCountryTimeZone( $costumer_id ){

			$result = Country::join('Costumers', 'Countries.country_id', '=', 'Costumers.country_id')
			                       ->select( 'Countries.name','timezone' )
								   ->where( 'costumer_id', '=', $costumer_id )
								   ->where( 'Countries.status', '=', 1 )
								   ->get();
			return $result;
		}
	}
?>