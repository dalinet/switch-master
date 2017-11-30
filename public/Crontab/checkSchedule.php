<?php 
    /** Error reporting */
    
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);

    // Ruta 
    $publicPath= '/var/www/vhosts/clearchanneltoolbox.com/subdomains/cc-swapp-14072015/public';

    // incluye 
    include $publicPath.'/Crontab/ConexionBD.php';

    // Datos de la conexion de la base de datos
    $_SESSION["databaseConnection"] = array(
        "host" => "localhost", 
        "user" => "cc_swapp", 
        "password" => "Ftpl$&//dnh(934595S..jsdf", 
        "database" => "cc_swapp"
    );

   
    // Actualiza el status del  Crono 
    function updateCronoStatus($iCronoId){
        
        // Instancia de Conexión a Base de Datos
        $oCon = new ConexionBD($_SESSION["databaseConnection"]);

         // Actualizamos el status del CRONO a ejecutaod (status = 2 )
        $sQuery = "UPDATE Scheduled_ads SET status = 2 WHERE ID = ".$iCronoId."";

        $oCon -> doQuery( $sQuery );

        //echo "OK update";

    }



    // Instancia de Conexión a Base de Datos
    $oConnection = new ConexionBD($_SESSION["databaseConnection"]);
    
    $sDateNow = date('Y-m-j H:i').":00";
    $sDateNow = strtotime ( '-4 hours' , strtotime ( $sDateNow ) ) ;
    $sDateNow = date ( 'Y-m-j H:i' , $sDateNow ).":00";

    //$sDateNow= date("Y-m-d H:i").":00";
    //echo $sDateNow;

    // Obtenemos todos los Cronos que se deben ejecutar
   $sQuery = "SELECT s.campaign_id , s.ad_id , s.ID as Crono_id , a.image_src ,a.file_src, a.formato
                FROM Scheduled_ads as s , Ads as a 
                WHERE s.human_date<='".$sDateNow."' AND s.status=1 AND s.ad_id=a.ad_id";

    $oConnection -> doQuery( $sQuery );
   
    // Arreglo de Cronos
    $aCronos=array();

    // Iteramos en cada uno de los registros y los guardamos en un arreglo temporal
    while ($oCrono = $oConnection -> setWhile()) {
       
        // Agregamos cada Crono 
        array_push( $aCronos , $oCrono );
    }


    // Numero de registros  
    $ilong=count($aCronos);

    //Iteracion de cada arreglo
    for($i=0;$i<$ilong;$i++){

        // Obtenemos los Slots asociados al CRONO
          $sQuery = "SELECT sas.slot_id , c.url ,c.campaign_url_id, ss.width, ss.height 
                    FROM Scheduled_ads_slots as sas  , Screens_url as s , Campaigns_url as c, Screens as se , Screens_size as ss 
                    WHERE sas.scheduled_ad_id=".$aCronos[$i]["Crono_id"]." AND sas.status=1  
                    AND s.screen_url_id=sas.slot_id AND s.campaign_url_id=c.campaign_url_id 
                    AND s.screen_id=se.screen_id AND  se.screen_size_id = ss.screen_size_id";



        $oConnection -> doQuery( $sQuery );

        $pathOrig = $publicPath.$aCronos[$i]["image_src"]; //Ruta de la imagen origen

        //echo $pathOrig."<br/>";

        // Iteramos en cada uno de los registros y los guardamos en un arreglo temporal
        while ($oSlot = $oConnection -> setWhile()) {
            
            $pathDest = $publicPath.$oSlot["url"]; //Ruta de la imagen destino
            //echo $pathDest."<br/>";

           if( $aCronos[$i]["formato"] == 'video'  ){


                $bCopyStatus = copy( $pathOrig, $pathDest); //Copiamos la imagen
                // Instancia de Conexión a Base de Datos
                $oConu = new ConexionBD($_SESSION["databaseConnection"]);
                $sQuery = "UPDATE Campaigns_url SET previo = '".$aCronos[$i]["file_src"]."' WHERE campaign_url_id=".$oSlot["campaign_url_id"]."";
                $oConu -> doQuery( $sQuery );

            }else{

             
                //echo $pathDest."<br/>";

                $oImage = new Imagick( $pathOrig );
                $oImage->scaleImage($oSlot["width"], $oSlot["height"]);
                $oImage->setImageFormat( 'jpg' );
                $oImage->setImageCompressionQuality(95);
                $oImage->writeImage( $pathDest );
                $oImage->destroy(); 
                //$bCopyStatus = copy( $pathOrig, $pathDest); //Copiamos la imagen
            }

        }

        //Actualizamos el Status del Crono
        updateCronoStatus($aCronos[$i]["Crono_id"]);


    }


   

?>