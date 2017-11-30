<?php

	class UserLoginController extends BaseController {

		public function getLogin()
		{
			if (Auth::guest()){
				return View::make('auth.login');
			}else{
				return Redirect::to('/home');
			}

		}

		public function postLogin()
		{
			$userdata = array(
				'username' =>  Input::get('username'), 
				'password' =>  Input::get('password')
			);

			if(Auth::attempt($userdata))
			{
				// Se captura la hora de inicio de sesión
				$oUserLog             = new UserLog;
				$oUserLog->user_id    = Auth::user()->administrator_id;
				$oUserLog->ip_address = Request::getClientIp();
				$oUserLog->save();
				
				#return View::make('layouts.master');
				return Redirect::to('/home');
				// return "ok";

				
			}
			else
			{
				return Redirect::to('/')->with('login_errors', true);
				// return 'error de autenticación';
			}
		}

	}

?>