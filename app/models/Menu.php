<?php 
	class Menu extends Eloquent { 
		protected $table      = 'Menus';
		protected $primaryKey = 'menu_id';

		public static function getMenu()
		{
			$aInfo = array();

			$queryMenuPrivilege = Privilege::select( 'privilege_id', 'menu_id' )
										 ->where( 'status', '=', 1)
										 ->get();

			$numMenuPrivilege = count( $queryMenuPrivilege );

			for ( $i=0; $i < $numMenuPrivilege; $i++ ) { 
				$menuId = $queryMenuPrivilege[ $i ]->menu_id;

				$queryMenu = Menu::select( 'menu_id', 'name', 'url', 'icon_classes' )
							   ->where( 'status', '=', 1 )
							   ->where( 'menu_id', '=', $menuId )
							   ->get();

				$querySubmenu = Submenu::select( 'submenu_id', 'name', 'url' )
							   ->where( 'status', '=', 1 )
							   ->where( 'menu_id', '=', $menuId )
							   ->get()
							   ->toArray();

				$aMenu    = array();
				$aSubmenu = array();

				$numSubmenu = count( $querySubmenu );

				for( $j=0; $j < $numSubmenu; $j++ ){
					array_push( $aSubmenu , $querySubmenu[ $j ] );
				}

				$aMenu[ 'id' ]           = $menuId;
				$aMenu[ 'name' ]         = $queryMenu[0]->name;
				$aMenu[ 'url' ]          = $queryMenu[0]->url;
				$aMenu[ 'icon_classes' ] = $queryMenu[0]->icon_classes;
				$aMenu[ 'submenu' ]      = $aSubmenu;

				array_push( $aInfo , $aMenu);
			}

			return $aInfo;

		}
	}
?>