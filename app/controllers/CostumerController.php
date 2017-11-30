<?php

class CostumerController extends AuthController {

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
	 * Se muestra la vista para 
	 * registrar un usuario de 
	 * una compañia
	 *
	 * @return View
	 */
	public function getViewAddUser()
	{
		$aCompanies = Company::getCompanies();

		return View::make('users.addUser')->with( 'companies', $aCompanies );
	}

	/**
	 * Se muestra la vista para 
	 * administrar usuarios de las
	 * compañias
	 *
	 * @return View
	 */
	public function getViewUsers()
	{
		$aUsers     = Costumer::getAllUsers();
		$aCompanies = Company::getCompanies();

		$aInfo = array( 'users'     => $aUsers,
						'companies' => $aCompanies );

		return View::make('users.users')->with( 'info', $aInfo );
	}

	/**
	 * Se registra un usuario
	 *
	 * @return boolean
	 */
	public function addUser()
	{
		$oCostumer             = new Costumer;
		$oCostumer->company_id = Input::get( 'selectCompanies' );
		$oCostumer->country_id = Auth::user()->country_id;
		$oCostumer->name       = Input::get( "name" );
		$oCostumer->email      = Input::get( "email" );
		$oCostumer->user_name  = Input::get( "username" );
		$oCostumer->password   = Input::get( "password" );
		$bUserStatus           = $oCostumer->save();

		/*$aUsers     = Costumer::getAllUsers();
		$aCompanies = Company::getCompanies();

		$aInfo = array( 'users'     => $aUsers,
						'companies' => $aCompanies );

		return View::make('users.users')->with( 'info', $aInfo )
										->with( 'inserted', $bUserStatus );*/

		return Redirect::to('/users')->with( 'inserted', $bUserStatus );
	}

	/**
	 * Se elimina un usuario
	 *
	 * @return boolean
	 */
	public function deleteUser()
	{
		if( Request::ajax() ){

			$costumer_id = Input::get( 'costumer_id');

			$bDeleteStatus = Costumer::deleteCostumer( $costumer_id );
			
			return Response::json( $bDeleteStatus );

		}
	}

	/**
	 * Se revisa la disponibilidad de un nombre de usuario
	 *
	 * @return boolean
	 */
	public function checkUserAvailability()
	{
		if( Request::ajax() ){

			$sUserName = Input::get( 'username');

			$oResult = Costumer::where( 'user_name', '=', $sUserName )
								->first();
			
			$iNumResult = count( $oResult );

			if( $iNumResult == 0 ){
				return Response::json( true );
			}else{
				return Response::json( false );
			}

		}
	}
	
	/**
	 * Se revisa la disponibilidad de un nombre de usuario
	 *
	 * @return boolean
	 */
	public function sendUserPassword()
	{
		// if( Request::ajax() ){

			$iCostumerId = Input::get( 'costumer_id');
			
			$oCostumer   = Costumer::find( $iCostumerId );

			// $oLang = $oCostumer->costumerLang;

			$sEmailAddress = $oCostumer->email;

			$aData = array( 'name'     => $oCostumer->name,
							'username' => $oCostumer->user_name, 
							'password' => $oCostumer->password );

			$emailSubject = Lang::get('credentialsEmail.subject');

			$mailStatus = Mail::send('emails.userCredentials', $aData, function ($message) use ( $sEmailAddress, $emailSubject ){
			                        $message->subject( $emailSubject );
			                        $message->to( $sEmailAddress );
			                    });

			return Response::json( $mailStatus );

			// return Response::json( $oLang );

		// }
	}

	/**
	 * Se obtiene la información del
	 * usuario para editarla
	 *
	 * @return json
	 */
	public function getUserInfo()
	{
		if( Request::ajax() ){

			$costumer_id = Input::get( 'costumer_id' );

			$aCostumerInfo = Costumer::getUserInfo( $costumer_id );
			
			return Response::json( $aCostumerInfo );

		}
	}

	/**
	 * Se obtiene la información del
	 * usuario para editarla
	 *
	 * @return json
	 */
	public function updateUser()
	{

		$costumer_id      = Input::get( 'costumer_id' );
		
		$oCostumer        = Costumer::find( $costumer_id );
		
		$oCostumer->name  = Input::get( 'name' );
		$oCostumer->email = Input::get( 'email' );
		$bUpdtStatus      = $oCostumer->save();

		return Redirect::back()->with('updated', $bUpdtStatus );

	}

}
