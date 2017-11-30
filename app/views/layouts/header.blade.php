<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href=""><img src="{{url('/img/logo.png')}}" height="30" class="inline-block"> Clear Channel Switch</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                {{-- <li><a href="#"><i class="fa fa-user fa-fw"></i> Perfil de usuario</a>
                </li> --}}
                <!-- <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li> -->
                {{-- <li class="divider"></li> --}}
                <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                

                @foreach ( Menu::getMenu() as $menu)
                   
                    <li>
                        
                        @if ( count( $menu[ 'submenu' ] ) > 0 )
                            <a href="{{ $menu[ 'url' ] }}"><i class="{{ $menu[ 'icon_classes' ] }}"></i>  {{ $menu[ 'name' ] }}<span class="fa arrow"></a>

                            <ul class="nav nav-second-level">

                            @foreach ( $menu[ 'submenu' ] as $submenu)
                                <li>
                                    <a href="{{ $submenu[ 'url' ] }}">{{ $submenu[ 'name' ] }}</a>
                                </li>
                            @endforeach

                            </ul>
                            <!-- /.nav-second-level -->
                        @else
                            <a href="{{ $menu[ 'url' ] }}"><i class="{{ $menu[ 'icon_classes' ] }}"></i>  {{ $menu[ 'name' ] }}</a>
                        @endif
                        

                    </li>

                @endforeach

                {{-- <li>
                    <a href="/home"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="/screens"><i class="fa fa-desktop fa-fw"></i> Pantallas</a>
                </li>
                <li>
                    <a href="/campaigns"><i class="glyphicon glyphicon-bullhorn"></i> Campañas</a>
                </li> --}}
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>