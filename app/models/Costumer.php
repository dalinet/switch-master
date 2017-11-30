<?php 
	class Costumer extends Eloquent { 
		protected $table      = 'Costumers';
		protected $primaryKey = 'costumer_id';

		/*public function costumerLang(){
			return Costumer::hasOne('Langs', 'lang_id');
		}*/

		public static function getCostumersByCompany( $company_id ){
			$result = Costumer::select( 'costumer_id', 'name' )
							  ->where('company_id', '=', $company_id )
							  ->where('status', '=', 1 )
							  ->get();
			return $result;
		}

		public static function getAllUsers(){
			$result = Costumer::join('Companies', 'Costumers.company_id', '=', 'Companies.company_id')
							  ->select( 'costumer_id', 'Costumers.name', 'email', 'user_name', 'Companies.name AS company_name' )
							  ->where('Costumers.status', '=', 1 )
							  ->get();
			return $result;
		}

		/**
		 * Se "elimina" una campaña
		 * @return [bool] estado de la operación
		 */
		public static function deleteCostumer( $costumer_id ){

			$oCostumer = Costumer::find( $costumer_id );
			$oCostumer->status = 0;

			$bResult = $oCostumer->save();
			return $bResult;
		}

		/**
		 * Se retorna la información de una campaña
		 * @return [obj] Información de la campaña
		 */
		public static function getUserInfo( $costumer_id ){
			$oCostumerInfo = Costumer::select( 'costumer_id', 'company_id', 'name', 'email', 'user_name', 'password' )
							  ->where('costumer_id', '=', $costumer_id )
							  ->get();

			$oCostumerInfo = Costumer::find( $costumer_id );

			return $oCostumerInfo;
		}


		/**
		 * API
		 * Autenticación de un usuario
		 */
		public static function userLogin( $username, $password ){

			// Se revisan las credenciales del usuario
			$aUserInfo = Costumer::select( 'costumer_id', 'country_id', 'costumer_slave_id' )
							  ->where('user_name', '=', $username )
							  ->where('password', '=', $password )
							  ->get();

			// Las credenciales fueron encontradas
			if( count( $aUserInfo ) != 0 ){
				$iCountryID       = $aUserInfo[0]->country_id;
				$iCostumerSlaveID = $aUserInfo[0]->costumer_slave_id;

				// El usuario petenece a un pais distinto a México
				if( $iCountryID != 1 ){
					// Se obtiene la url del sitio y el nombre de la conexión a bd
					// correspondiente al pais del usuario
					$aCountryInfo = Country::select( 'country_id', 'url', 'db_connection_name' )
									  ->where('country_id', '=', $iCountryID )
									  ->where('status', '=', 1 )
									  ->get();

					$sURL              = $aCountryInfo[ 0 ]->url;
					$sDBConnectionName = $aCountryInfo[ 0 ]->db_connection_name;

					// Se consulta los datos del usuario en la base de datos de su pais
					$sCostumer = DB::connection( $sDBConnectionName  )
					                ->table( 'Costumers' )
					                ->join('Companies', 'Costumers.company_id', '=', 'Companies.company_id')
									->select( 'costumer_id', 'logo_src', 'Costumers.name', 'email', 'Costumers.status', 'Companies.name AS company_name', 'Companies.logo_src AS company_img','Companies.company_id AS company_id', DB::raw( "'" . $sURL . "' AS url" ) )
									->where('costumer_id', '=', $iCostumerSlaveID )
									->get();
					
					$sCostumer[0]->country_id=$iCountryID;

					return $sCostumer;
				}else{ // El usuario pertenece a México
					$result = Costumer::join('Companies', 'Costumers.company_id', '=', 'Companies.company_id')
									  ->join('Countries', 'Costumers.country_id', '=', 'Countries.country_id')
									  ->select( 'costumer_id', 'logo_src', 'Costumers.name', 'email', 'Costumers.status', 'Companies.name AS company_name', 'Companies.logo_src AS company_img', 'Countries.url','Companies.company_id AS company_id' )
									  ->where('user_name', '=', $username )
									  ->where('password', '=', $password )
									  ->get();
					$result[0]->country_id=$iCountryID;
					
					return $result;
				}
			}else{
				return $aUserInfo;
			}
			
		}

	}
?>