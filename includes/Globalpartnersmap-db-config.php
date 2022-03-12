<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( !class_exists('Globalpartnersmap_DB_Config') ){

	/**
	 * Plugin Database files Class
	 */
	class Globalpartnersmap_DB_Config
	{
				
		public $Globalpartnersmap_pl_table;
		/**
		 * Static Singleton Holder
		 * @var self
		 */
		protected static $instance;
		
		/**
		 * Get (and instantiate, if necessary) the instance of the class
		 *
		 * @return self
		 */
		public static function instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
		
		public function __construct()
		{			
			$this->Globalpartnersmap_pl_table = 'Globalpartnersmap_pl';
		}

		public function fn_create_Globalpartnersmap_tables()
		{
			global $table_prefix, $wpdb;
			$tblname = $this->Globalpartnersmap_pl_table;
			$Globalpartnersmap_pl_table = $table_prefix . "$tblname";
			if($wpdb->get_var( "show tables like '$Globalpartnersmap_pl_table'" ) != $Globalpartnersmap_pl_table) 
			{
		        $sql = "CREATE TABLE `". $Globalpartnersmap_pl_table . "` ( ";
			    $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
			    $sql .= "  `Name` varchar(500) NOT NULL, ";
			    $sql .= "  `Email` varchar(500) NOT NULL, ";
			    $sql .= "  `Phone_number` varchar(500) NOT NULL, ";
			    $sql .= "  `Adress` varchar(500) NOT NULL, ";
			    $sql .= "  `Country` varchar(500) NOT NULL, ";
			    $sql .= "  `Status` bit not null default 0, ";
			    $sql .= "  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP, ";
			    $sql .= "  PRIMARY KEY (`id`) "; 
			    $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
			    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
			    dbDelta($sql);

			}
		}
    }
}

