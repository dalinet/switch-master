<?php 
	class ScreenResolution extends Eloquent { 
		protected $table      = 'Screens_size';
		protected $primaryKey = 'screen_size_id';

		/**
		 * Se obtienen las resoluciones registradas
		 */
		public static function getResolutions(){
			$result = ScreenResolution::select('screen_size_id', DB::raw('CONCAT( width, "x", height ) AS resolution') )
									  ->where('status', '=', 1)
									  ->get();
			return $result;
		}

	}
?>