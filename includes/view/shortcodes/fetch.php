  <?php  

            $dbdata = array();
            global $wpdb;
            $result = $wpdb->get_results( "SELECT * FROM wp_globalpartnersmap_pl" );
           foreach ( $result as $print ) {
                    $dbdata[]=$print;
            } 
           	 //  echo "<pre>";
             // print_r($dbdata);
            echo json_encode($dbdata);