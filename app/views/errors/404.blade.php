<!DOCTYPE html>
<html>
<head>
    <title>Página no encontrada</title>
    <!-- Bootstrap Core CSS -->
    <link href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}">
</head>
<body class="error-background">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                        Oops!</h1>
                    <h2>
                        404 Not Found</h2>
                    <div class="error-details">
                        Disculpa, ha ocurrido un error, no se encuentra la página solicitada!
                    </div>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>Ir al Inicio </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>