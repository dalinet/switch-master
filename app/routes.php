<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

App::missing(function($exception)
{

    // shows an error page (app/views/error.blade.php)
    // returns a page not found error
    // return Response::view('error', array(), 404);
    return Response::view('errors.404');
    // return "Pagina no encontrada";
});


/**
 * LOGIN
 */
// Vista del login
Route::get('login', 'UserLoginController@getLogin');
// Autenticación
Route::post('login', 'UserLoginController@postLogin');
// Logout
Route::get('logout',function()
{
	Auth::logout();
	return Redirect::to('/');
});

/**
 * HOME E INDEX
 */
Route::get('/', 'DashboardController@index');
Route::get('home', 'HomeController@index');

/**
 * CAMPAÑAS
 */
// Vistas
Route::get('addcampaign', 'CampaignController@getViewAddCampaign');
Route::get('campaigns-manager', 'CampaignController@getViewCampaign');
Route::get('add-url-to-screen', 'CampaignController@getViewURLtoScreen');

// Se valida el tamaño de la imagen
Route::post('validateCampaingImg', 'CampaignController@validateCampaingImg');
// Se obtiene los usuarios de una compañia
Route::post('getCompanyUsers', 'CampaignController@getCompanyUsers');
// Se registra una campaña
Route::post('addCampaign', 'CampaignController@addCampaign');
// Se obtienen las URLS de una campaña
Route::post('getUrlByCampaign', 'CampaignController@getUrlByCampaign');
// Se elimina una campaña
Route::post('deleteCampaign', 'CampaignController@deleteCampaign');
// Se edita una campaña
Route::post('getCampaignInfo', 'CampaignController@getCampaignInfo');
// Se actualiza una campaña
Route::post('updateCampaign', 'CampaignController@updateCampaign');
// Se obtienen las URLS de las pantallas
Route::post('getUrlScreenByCampaign', 'CampaignController@getUrlScreenByCampaign');
// Se registran URLS a pantallas
Route::post('setURLsToScreens', 'CampaignController@setURLsToScreens');

// Historial de anuncios creados
Route::get('created-ads-historical', 'CampaignController@getViewCreatedAdsHistorical');
// Se obtiene el historial de anuncios creados
Route::post('getCreatedAdsHistorical', 'CampaignController@getCreatedAdsHistorical');
// Historial de anuncios publicados
Route::get('published-ads-historical', 'CampaignController@getViewPublishedAdsHistorical');
// Se obtiene el historial de anuncios publicados
Route::post('getPublishedAdsHistorical', 'CampaignController@getPublishedAdsHistorical');
// Historial de anuncios publicados
Route::get('currently-published-ads', 'CampaignController@getViewCurrentlyPublishedAds');
// Se obtiene el historial de anuncios publicados
Route::post('getCurrentlyPublishedAds', 'CampaignController@getCurrentlyPublishedAds');
// Se obtienen las campañas de un cliente/compañia
Route::post('getCampaignsByCompany', 'CampaignController@getCampaignsByCompany');
// Se obtienen las campañas según una búsqueda
Route::post('getCampaignsBySearch', 'CampaignController@getCampaignsBySearch');

// Se genera el PDF de historial de Anuncios Creados
Route::post('getCreatedAdsPDF', 'AdController@getCreatedAdsPDF');
// Se genera el PDF de historial de Anuncios Creados
Route::post('getCreatedAdsZIP', 'AdController@getCreatedAdsZIP');

// Se elimina un anuncio publicado
Route::post('delPublishedAd', 'AdController@delPublishedAd');

/**
 * Clientes
 */
// Vistas
Route::get('addcostumer', 'CompanyController@getViewAddCompany');
Route::get('companies', 'CompanyController@getViewCompanies');

// Se registra una compañia
Route::post('addCompany', 'CompanyController@addCompany');
// Se elimina una compañia
Route::post('deleteCompany', 'CompanyController@deleteCompany');
// Se obtiene la información para editar una compañia
Route::post('getCompanyInfo', 'CompanyController@getCompanyInfo');
// Se actualiza una compañia/cliente
Route::post('updateCompany', 'CompanyController@updateCompany');

/**
 * Usuarios
 */
// Vista para agregar un usuario
Route::get('adduser', 'CostumerController@getViewAddUser');
// Vista para administrar usuarios
Route::get('users', 'CostumerController@getViewUsers');
// Se revisa si el nombre de usuario se encuentra disponible
Route::post('checkUserAvailability', 'CostumerController@checkUserAvailability');

// Se registra un usuario
Route::post('addUser', 'CostumerController@addUser');
// Se elimina un usuario
Route::post('deleteUser', 'CostumerController@deleteUser');
// Se obtiene la información del usuario para editarla
Route::post('getUserInfo', 'CostumerController@getUserInfo');
// Se obtiene la información del usuario para editarla
Route::post('sendUserPassword', 'CostumerController@sendUserPassword');
// Route::get('sendUserPassword', 'CostumerController@sendUserPassword');
// Se actualiza la inforación de un usuario
Route::post('updateUser', 'CostumerController@updateUser');

/**
 * PANTALLAS
 */
// Vista para agregar pantalla
Route::get('addscreen', 'ScreenController@getViewAddScreen');
// Vista para administrar pantallas
Route::get('screens', 'ScreenController@getViewScreen');

// Se registra una pantalla
Route::post('addscreen', 'ScreenController@postAddScreen');
// Se obtiene la informacion de una pantalla ( Editar )
Route::post('getscreen', 'ScreenController@getScreen');
// Se elimina una pantalla
Route::post('delscreen', 'ScreenController@deleteScreen');
// Se actualiza una pantalla
Route::post('updatescreen', 'ScreenController@updateScreen');

// TEST
// Route::get('getCurrentlyPublishedAds', 'CampaignController@getCurrentlyPublishedAds');

/**
 * API
 */

Route::group(array('prefix' => 'api/v1'), function()
{
    // Se autentica un usuario
    Route::post('login', 'APIController@userLogin');
    // Se obtienen las campañas de un usuario
    Route::post('getCampaign', 'APIController@getCampaign');
    // Se obtienen los anuncios de un usuario
    // Anuncios Precargados
    Route::post('getAds', 'APIController@getPreloadedAds');
    // Se obtienen los anuncios de un usuario
    // Anuncios Creados
    Route::post('getCreatedAds', 'APIController@getCreatedAds');
    // Se obtienen los anuncios publicados de un usuario
    Route::post('getPublishedAds', 'APIController@getCreatedAds');
    // Se obtienen las pantallas de una campaña
    Route::post('getScreensByCampaign', 'APIController@getScreensByCampaign');
    // Se obtienen los slots de una pantalla
    Route::post('getURLsByScreenCampaign', 'APIController@getURLsByScreenCampaign');
    // Se coloca un anuncio en un URL
    Route::post('setAdinURLSlot', 'APIController@setAdinURLSlot');
    // Se obtienen las fuentes de la campaña
    Route::post('getCampaignFonts', 'APIController@getCampaignZipFonts');
    // Se obtienen la url de un anuncio precargado
    Route::post('getAdImageURL', 'APIController@getAdImageURL');
    // Se elimina un anuncio
    Route::post('deleteAd', 'APIController@deleteAd');
    // Se recibe el archivo de la imagen del anuncio
    Route::post('saveCreatedAd', 'APIController@saveCreatedAd');

    Route::post('saveCreatedAdDev', 'APIController@saveCreatedAdDevelopment');

    Route::post('saveAdvideo', 'APIController@saveAdvideo');
    //http://peru.switch.clearchanneltoolbox.com/api/v1/saveAdvideo

    // Se envia a facebook una imagen a publicar
    Route::post('networkPostImageFacebook', 'APIController@networkPostImageFacebook');

     // Se envia a facebook una imagen a publicar
    Route::post('networkPostImageTwitter', 'APIController@networkPostImageTwitter');

    // Se envía la ruta del archivo editable
    Route::post('getAdEditableFile', 'APIController@getAdEditableFile');


    // URLS PARA PROBAR
    // Route::get('getURLsByScreenCampaign', 'APIController@getURLsByScreenCampaign');
    // http://swapp.clearchanneltoolbox.com/api/v1/getURLsByScreenCampaign?campaign_id=1&screen_id=1

    // Route::get('getCampaign', 'APIController@getCampaign');
    // http://swapp.clearchanneltoolbox.com/api/v1/getCampaign?costumer_id=1

    // Route::get('getScreensByCampaign', 'APIController@getScreensByCampaign');
    // http://swapp.clearchanneltoolbox.com/api/v1/getScreensByCampaign?campaign_id=1

    // Route::get('login', 'APIController@userLogin');
    // http://swapp.clearchanneltoolbox.com/api/v1/login?username=user_test0&password=test

    // Route::get('getAds', 'APIController@getPreloadedAds');
    // http://swapp.clearchanneltoolbox.com/api/v1/getAds?campaignid=1

    // Route::get('getPublishedAds', 'APIController@getCreatedAds');
    // http://swapp.clearchanneltoolbox.com/api/v1/getPublishedAds?campaignid=1

    // Se coloca un anuncio en un URL
    // Route::get('setAdinURLSlot', 'APIController@setAdinURLSlot');
    // http://swapp.clearchanneltoolbox.com/api/v1/setAdinURLSlot?ad_id=2&aDataSlots=[{'slotID':85},{'slotID':86},{'slotID':87},{'slotID':84}]&costumerId=10

    // Se obtienen las fuentes de la campaña
    // Route::get('getCampaignFonts', 'APIController@getCampaignZipFonts');
    // http://swapp.clearchanneltoolbox.com/api/v1/getCampaignFonts?campaign_id=1
    // 
    // Se obtienen los anuncios creados
    // Route::get('getCreatedAds', 'APIController@getCreatedAds');
    // http://swapp.clearchanneltoolbox.com/api/v1/getCreatedAds?campaignid=1
    // 
    // Se obtiene la URL de un anuncio precargado
    // Route::get('getAdImageURL', 'APIController@getAdImageURL');
    // http://swapp.clearchanneltoolbox.com/api/v1/getAdImageURL?ad_id=1
    // 
    // Se elimina un anuncio
    // Route::get('deleteAd', 'APIController@deleteAd');
    // http://swapp.clearchanneltoolbox.com/api/v1/deleteAd?ad_id=13

    // Se elimina un anuncio
    Route::get('test', 'APIController@twitterPostImage');
    // http://swapp.clearchanneltoolbox.com/api/v1/test
    // 
    
    Route::get('getCampaingAdsHistory', 'APIController@getCampaingAdsHistory');

    Route::get('setScheduledAd', 'APIController@setScheduledAd');
    Route::post('setScheduledAd', 'APIController@setScheduledAd');
    // http://swapp.clearchanneltoolbox.com/api/v1/setScheduledAd?ad_id=3385&aDataSlots=[{'slotID':1221}]&campaignid=50&sDate=2016-05-18 11:44:00&costumerid=70

    Route::get('getScheduledAd', 'APIController@getScheduledAd');
    // http://swapp.clearchanneltoolbox.com/api/v1/getScheduledAd?ad_id=3385
    
    Route::post('deleteScheduledAd', 'APIController@deleteScheduledAd');
    // http://swapp.clearchanneltoolbox.com/api/v1/deleteScheduledAd?crono_id=1
    
});


/**
 * Rutas de prueba
 */

Route::post('sendProgrammerMail', 'CampaignController@sendEmailURLsInScreen' );

// Route::get('email-test', 'CampaignController@sendCreatedCampaignEmail' );


/**
 * Rutas de html para video
 */

Route::get('videoforscreen/{id}', 'APIController@videoForScreen');
// http://swapp.clearchanneltoolbox.com/videoforscreen/128
    


