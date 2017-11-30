<?php

class ScreenController extends AuthController {

	/*
	|--------------------------------------------------------------------------
	| Screen Controller
	|--------------------------------------------------------------------------
	|
	| Este controlador maneja la administración de las pantallas como lo son
	| registros, actualizaciones y eliminación de registros. De igual forma 
	| hace las consultas necesarias sobre la tabla de pantallas
	|
	*/

	/**
	 * Se hace la llamada al constructor de AuthController
	 * el cual protege el acceso a los metodos del controlador
	 * al requerir que haya un usuario autenticado
	 *
	 * @return void
	 */
	public function __construct()
    {
        parent::__construct();
    }

    /**
	 * Se muestra la vista de las pantallas
	 *
	 * @return View
	 */
	public function getViewScreen()
	{
		$aScreens = Screen::getScreens();
		$aPhases  = Phase::getPhases();
		$aScreenResolutions = ScreenResolution::getResolutions();

		return View::make('screens.screens')
					->with('phases', $aPhases)
					->with('screens', $aScreens)
					->with('screen_resolutions', $aScreenResolutions);
	}

	/**
	 * Se muestra la vista de las pantallas
	 *
	 * @return View
	 */
	public function getViewAddScreen()
	{
		$aPhases            = Phase::getPhases();
		$aScreenResolutions = ScreenResolution::getResolutions();
		
		return View::make('screens.addScreen')
				            ->with('phases', $aPhases)
				            ->with('screen_resolutions', $aScreenResolutions);
	}

	/**
	 * Se registra una pantalla
	 *
	 * @return View
	 */
	public function postAddScreen()
	{

		$rules = array(
			'screen_name' => 'required',
			'location'    => 'required',
			'latitude'    => 'required',
			'longitude'   => 'required',
			'phase'       => 'required'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
		    return Redirect::back()
		    				->with('validation-error', true );
		} else {
		   $screen            = new Screen;
		   $screen->name      = Input::get('screen_name');
		   $screen->location  = Input::get('location');
		   $screen->latitude  = Input::get('latitude');
		   $screen->screen_size_id = Input::get('resolution');
		   $screen->longitude = Input::get('longitude');
		   $screen->phase_id  = Input::get('phase');
		   $status            = $screen->save();

			if( $status ){
		   		return Redirect::to('/screens')
		   						->with( "inserted", true );;
		    }else{
		   		return Redirect::back()
		   						->with('register-error', true );
		    }
		}
		
	}

	/**
	 * Se obtiene la información de una pantalla
	 * según el id 
	 *
	 * @return View
	 */
	public function getScreen()
	{
		$screen = Screen::find( Input::get( 'screen_id' ), array( 'screen_id', 'name', 'location', 'latitude', 'longitude', 'phase_id','screen_size_id') );

		if( Request::ajax() ){
			return Response::json( $screen );
		}
	}

	/**
	 * Se actualiza la información de
	 * una pantalla
	 *
	 * @return View
	 */
	public function updateScreen()
	{

		$rules = array(
			'screen_id'   => 'required',
			'screen_name' => 'required',
			'location'    => 'required',
			'latitude'    => 'required',
			'longitude'   => 'required',
			'phase'       => 'required'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
		    return Redirect::back()
		    				->with('validation-error', true );
		} else {
		   	$screen = Screen::find( Input::get( 'screen_id' ) );

		   	$screen->name      = Input::get( 'screen_name' );
		   	$screen->location  = Input::get( 'location' );
		   	$screen->latitude  = Input::get( 'latitude' );
		   	$screen->screen_size_id = Input::get('resolution');
		   	$screen->longitude = Input::get( 'longitude' );
		   	$screen->phase_id  = Input::get( 'phase' );
		   	$status           = $screen->save();

			if( $status ){
		   		return Redirect::back()
		   						->with( "updated", true );;
		    }else{
		   		return Redirect::back()
		   						->with('update-error', true );
		    }
		}

	}

	/**
	 * Se elimina una pantallla
	 *
	 * @return Boolean
	 */
	public function deleteScreen()
	{
		
		if( Request::ajax() ){

			$screen = Screen::find( Input::get( 'screen_id' ) );
			$screen->status = '0';
			$status = $screen->save();
			
			if( $status ){
				return Response::json( true );
			}else{
				return Response::json( false );
			}

		}
		
	}

}
