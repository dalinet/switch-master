<?php 
	class Company extends Eloquent { 
		protected $table      = 'Companies';
		protected $primaryKey = 'company_id';

		/**
		 * Se obtienen las compañias registradas
		 */
		public static function getCompanies(){
			$result = Company::select( 'company_id', 'name', 'logo_src' )
						   ->where('status', '=', 1)
						   ->get();
			return $result;
		}

		/**
		 * Se obtienen las compañias con campañas
		 */
		public static function getCompaniesWithCampaigns(){
			$result = Company::distinct()
		                   ->select( 'Companies.company_id', 'Companies.name' )
						   ->join( 'Campaigns AS t2', 'Companies.company_id', '=', 't2.company_id' )
						   ->where('Companies.status', '=', 1)
						   ->where('t2.status', '=', 1)
						   ->get();
			return $result;
		}

		/**
		 * Se elimina una compañia/cliente
		 */
		public static function deleteCompany( $company_id ){
			$oCompany = Company::find( $company_id );
			$oCompany->status = 0;

			$bResult = $oCompany->save();

			return $bResult;
		}

		/**
		 * Se obtiene la información
		 * para editar una compañia/cliente
		 *
		 * @return object
		 */
		public static function getCompanyInfo( $company_id ){
			$oResult = Company::find( $company_id,
									   array( 'company_id', 'name', 'logo_src' ) );

			return $oResult;
		}
	}
?>