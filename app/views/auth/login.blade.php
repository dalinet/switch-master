<!DOCTYPE html>
<html lang="es">

<head>
    @include( 'layouts.head' )
</head>

<body>
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 50px">
	    <div class="navbar-header">
	        <a class="navbar-brand" href=""><img src="{{url('/img/logo.png')}}" height="30" class="inline-block"> Clear Channel Switch</a>
	    </div>
	    <!-- /.navbar-header -->

	    <!-- /.navbar-static-side -->
	</nav>

    <div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Iniciar Sesión</div>
					<div class="panel-body">
						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<strong>Whoops!</strong> There were some problems with your input.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						@if( Session::get( "login_errors" ) == true )
							<div class="alert alert-danger">
								Tu usuario o contrase&ntilde;a no son correctos.
							</div>
						@endif

						<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group">
								<label class="col-md-4 control-label">Nombre de usuario</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="username" value="">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Contraseña</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password">
								</div>
							</div>

							{{-- <div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember"> Remember Me
										</label>
									</div>
								</div>
							</div> --}}

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">Iniciar Sesión</button>

									{{-- <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a> --}}
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

    @include( 'layouts.footer' )

</body>

</html>