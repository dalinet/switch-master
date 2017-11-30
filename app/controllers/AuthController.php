<?php 

class AuthController extends BaseController {

	public function __construct()
    {
        $this->beforeFilter( 'auth');
    }

}
