<!DOCTYPE html>
<html lang="es">

<head>
    @include( 'layouts.head' )
</head>

<body>

    <div id="wrapper">

        @include( 'layouts.header' )
        
        <!-- page-wrapper -->
        <div id="page-wrapper">
            @yield( 'content' )
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    @include( 'layouts.footer' )

    @yield( 'page_scripts' )
</body>

</html>
