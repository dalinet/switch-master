<?php

class CompanyController extends AuthController {

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
	 * registrar un cliente/compañia
	 *
	 * @return View
	 */
	public function getViewAddCompany()
	{
		return View::make('companies.addCompany');
	}

	/**
	 * Se muestra la vista para 
	 * administrar un cliente/compañia
	 *
	 * @return View
	 */
	public function getViewCompanies()
	{
		$aCompanies = Company::getCompanies();

		return View::make('companies.companies')->with( 'companies', $aCompanies );
	}

	/**
	 * Se registra una compañia/cliente
	 *
	 * @return View
	 */
	public function addCompany()
	{
		// $aData;
		if( Input::hasFile( 'fileLogoCompany' )) {
			$oLogoFile      = Input::file( 'fileLogoCompany' );
			$sLogoExtension = Input::file( 'fileLogoCompany' )->getClientOriginalExtension();

			$aAllowedExtensions  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			if( in_array( $sLogoExtension, $aAllowedExtensions) ){

				if( $oLogoFile->getClientOriginalName()!=''){

					$sRelativePath  = '/uploads/companies/';
					$sAbsolutePath  = public_path() . $sRelativePath;
					$sFilename      = $oLogoFile->getClientOriginalName();
					$sFileNewName   = "company_logo_" . time(). "_" . rand() . "." . $sLogoExtension;
					$bUploadSuccess = $oLogoFile->move( $sAbsolutePath, $sFileNewName);

					if( $bUploadSuccess ){

						$oCompany           = new Company;
						$oCompany->name     = Input::get('company_name');
						$oCompany->logo_src = $sRelativePath . $sFileNewName;
						$bStatus            = $oCompany->save();

						if( $bStatus ){
							$iCompany_id = $oCompany->company_id;

							$oCostumer             = new Costumer;
							$oCostumer->company_id = $iCompany_id;
							$oCostumer->country_id = Auth::user()->country_id;
							$oCostumer->name       = Input::get( "name" );
							$oCostumer->email      = Input::get( "email" );
							$oCostumer->user_name  = Input::get( "username" );
							$oCostumer->password   = Input::get( "password" );
							$bUserStatus		   = $oCostumer->save();

							if( $bUserStatus ){
								// $aData = array( 'status'    => true );
								return Redirect::to( 'companies' )->with( "inserted", true );
							}else{
								// $aData = array( 'status'    => false );
								return Redirect::back()->with('insert_error', true );
							}
						}else{
							// $aData = array( 'status'    => false );
							return Redirect::back()->with('insert_error', true );
						}

					} // ./ Upload Success

				} // /. No empty name

			} // ./ Allowed File

		}

	}

	/**
	 * Se "elimina" una compañia
	 *
	 * @return boolean
	 */
	public function deleteCompany()
	{
		$company_id = Input::get( "company_id" );
		$bStatus    = Company::deleteCompany( $company_id );

		return Response::json( $bStatus );
	}

	/**
	 * Se obtiene la información
	 * para editar una compañia/cliente
	 *
	 * @return boolean
	 */
	public function getCompanyInfo()
	{
		$company_id = Input::get( "company_id" );
		$aCompany   = Company::getCompanyInfo( $company_id );

		return Response::json( $aCompany );
	}

	/**
	 * Se obtiene la actualiza
	 * la informaciónn de una 
	 * compañia/cliente
	 *
	 * @return boolean
	 */
	public function updateCompany()
	{
		$company_id = Input::get( "company_id" );

		$oCompany       = Company::find( $company_id );
		$oCompany->name = Input::get( "company_name" );

		// Se sube la imagen
		if( Input::hasFile( "fileLogoCompany" ) ){
			// Imagen para la campaña
			$imgFile      = Input::file( 'fileLogoCompany' );
			$imgOrigName  = $imgFile->getClientOriginalName();
			$imgExtension = $imgFile->getClientOriginalExtension();

			$allowedImgExt  = array( 'jpeg', 'jpg', 'png', 'gift', 'bmp' );

			$imgRelativePath  = '/uploads/companies/';
			$imgAbsolutePath  = public_path() . $imgRelativePath;

			if( in_array( $imgExtension, $allowedImgExt) ){

				if( $imgOrigName != '' && !empty( $imgOrigName ) ){

					if( $imgFile->isValid() ){
						$imgFileNewName = "logo_company_" . time() . "_" . rand() . "." . $imgExtension;
						// Se sube la Imagen
						$imgUploadSuccess  = $imgFile->move( $imgAbsolutePath, $imgFileNewName );

						if( $imgUploadSuccess ){

							// Se elimina la imagen anterior
							$oldLogo = $oCompany->logo_src;

							unlink( public_path() . $oldLogo );

							// Ruta relativa de los archivos subidos
							$sImgPath  = $imgRelativePath . $imgFileNewName;

							$oCompany->logo_src = $sImgPath;

						} // ./ Upload Success

					} // /. isValid

				} // /. No empty name

			} // ./ Allowed File
		}

		$bUpdtStatus = $oCompany->save();

		return Redirect::back()->with('status', $bUpdtStatus );
	}


}
