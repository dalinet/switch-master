<?php 
	class Phase extends Eloquent { 
		protected $table      = 'Phases';
		protected $primaryKey = 'phase_id';

		public static function getPhases(){
			$oPhases = Phase::select( 'phase_id', 'name' )
							  ->where( 'status', '=', '1' )
							  ->get();

			return $oPhases;
		}
	}
?>