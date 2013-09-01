<?php
	/*
	Plugin Name: WP Easy Gallery
	Plugin URI: http://labs.hahncreativegroup.com/wordpress-plugins/easy-gallery/
	Description: Wordpress Plugin for creating dynamic photo galleries	
	Author: HahnCreativeGroup
	Version: 3.0
	Author URI: http://labs.hahncreativegroup.com/
	*/	
	
	/* ==================================================================================
	 * Create custom database table
	 * ================================================================================== 
	 */
	
	global $wpdb;
	global $easy_gallery_table;
	global $easy_gallery_image_table;
	global $easy_gallery_db_version;	
	$easy_gallery_table = $wpdb->prefix . 'easy_gallery';
	$easy_gallery_image_table = $wpdb->prefix . 'easy_gallery_images';
	$easy_gallery_db_version = '1.1';
		
	register_activation_hook( __FILE__,  'easy_gallery_install' );
	
	function easy_gallery_install() {
	  global $wpdb;
	  global $easy_gallery_table;
	  global $easy_gallery_image_table;
	  global $easy_gallery_db_version;
	
	  if ( $wpdb->get_var( "show tables like '$easy_gallery_table'" ) != $easy_gallery_table ) {
				
		$sql = "CREATE TABLE $easy_gallery_table (".
			"Id INT NOT NULL AUTO_INCREMENT, ".
			"name VARCHAR( 30 ) NOT NULL, ".
			"slug VARCHAR( 30 ) NOT NULL, ".
			"description TEXT NOT NULL, ".
			"thumbnail LONGTEXT NOT NULL, ".
			"thumbwidth INT, ".
			"thumbheight INT, ".
			"PRIMARY KEY Id (Id) ".
			")";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		$sql = "CREATE TABLE $easy_gallery_image_table (".
				"Id INT NOT NULL AUTO_INCREMENT, ".
				"gid INT NOT NULL, ".
				"imagePath LONGTEXT NOT NULL, ".
				"title VARCHAR( 50 ) NOT NULL, ".
				"description LONGTEXT NOT NULL, ".
				"sortOrder INT NOT NULL, ".
				"PRIMARY KEY Id (Id) ".
				")";

	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	
		add_option( "easy_gallery_db_version", $easy_gallery_db_version );
	  }
	}
	
	/* ==================================================================================
	 * Include JS File in Header
	 * ================================================================================== 
	 */
	 
	 function define_options() {
		 if(!get_option('wp_easy_gallery_defaults')) {
				$gallery_options = array(
					'version'		   => 'free',
					'thumbnail_width'  => 'auto',
					'thunbnail_height' => 'auto',
					'hide_overlay'	   => 'false',
					'hide_social'	   => 'false',
					'custom_style'	   => ''
				);
				
				add_option('wp_easy_gallery_defaults', $gallery_options);
			}
			else {
				$wpEasyGalleryOptions	= get_option('wp_easy_gallery_defaults');
				$keys = array_keys($wpEasyGalleryOptions);
				
				if (!in_array('version', $keys)) {
					$wpEasyGalleryOptions['version'] = $this->plugin_version;	
				}				
				if (!in_array('hide_overlay', $keys)) {
					$wpEasyGalleryOptions['hide_overlay'] = "false";	
				}
				if (!in_array('hide_social', $keys)) {
					$wpEasyGalleryOptions['hide_social'] = "false";	
				}
				if (!in_array('custom_style', $keys)) {
					$wpEasyGalleryOptions['custom_style'] = "";	
				}
				
				update_option('wp_easy_gallery_defaults', $wpEasyGalleryOptions);	
			}
	 }
	 add_action('init', 'define_options');
	 
	 function wp_custom_style() {
		$styles = get_option('wp_easy_gallery_defaults');
		echo "<style>.wp-easy-gallery img {".$styles['custom_style']."}</style>";
	}
	add_action('wp_head', 'wp_custom_style');
		
	function attach_EasyGallery_scripts() {
		$wpEasyGalleryOptions = get_option('wp_easy_gallery_defaults');
		wp_enqueue_script('jquery');
		wp_register_script('jquery_migrate', WP_PLUGIN_URL.'/wp-easy-gallery/js/jquery-migrate.js', array('jquery'));
		wp_enqueue_script('jquery_migrate');
		wp_register_script('prettyPhoto', WP_PLUGIN_URL.'/wp-easy-gallery/js/jquery.prettyPhoto.js', array('jquery'));
		if ($wpEasyGalleryOptions['hide_social'] == 'true' && $wpEasyGalleryOptions['hide_overlay'] == 'false') {
			wp_register_script('easyGalleryLoader', WP_PLUGIN_URL.'/wp-easy-gallery/js/EasyGalleryLoader_hideSocial.js', array('prettyPhoto', 'jquery'));
		}
		else if ($wpEasyGalleryOptions['hide_social'] == 'false' && $wpEasyGalleryOptions['hide_overlay'] == 'true') {
			wp_register_script('easyGalleryLoader', WP_PLUGIN_URL.'/wp-easy-gallery/js/EasyGalleryLoader_hideOverlay.js', array('prettyPhoto', 'jquery'));
		}
		else if ($wpEasyGalleryOptions['hide_social'] == 'true' && $wpEasyGalleryOptions['hide_overlay'] == 'true') {
			wp_register_script('easyGalleryLoader', WP_PLUGIN_URL.'/wp-easy-gallery/js/EasyGalleryLoader_hideOverlaySocial.js', array('prettyPhoto', 'jquery'));
		}
		else {
			wp_register_script('easyGalleryLoader', WP_PLUGIN_URL.'/wp-easy-gallery/js/EasyGalleryLoader.js', array('prettyPhoto', 'jquery'));
		}
		wp_enqueue_script('prettyPhoto');
		wp_enqueue_script('easyGalleryLoader');
		wp_register_style( 'prettyPhoto_stylesheet', WP_PLUGIN_URL.'/wp-easy-gallery/css/prettyPhoto.css');
		wp_enqueue_style('prettyPhoto_stylesheet');
	}
	add_action('wp_enqueue_scripts', 'attach_EasyGallery_scripts');
	
	function attach_Easy_Gallery_JS()
	{
		if ( ! defined( 'HCGGALLERY_PLUGIN_BASENAME' ) )
		define( 'HCGGALLERY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
	
		if ( ! defined( 'HCGGALLERY_PLUGIN_NAME' ) )
			define( 'HCGGALLERY_PLUGIN_NAME', trim( dirname( HCGGALLERY_PLUGIN_BASENAME ), '/' ) );
		
		if ( ! defined( 'HCGGALLERY_PLUGIN_DIR' ) )
			define( 'HCGGALLERY_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . HCGGALLERY_PLUGIN_NAME );
	}
	
	add_action ('wp_head', 'attach_Easy_Gallery_JS');
	
	function easy_gallery_admin_scripts() {
	  wp_enqueue_script('media-upload');
	  wp_enqueue_script('thickbox');
	  wp_register_script('easy-gallery-uploader', WP_PLUGIN_URL.'/wp-easy-gallery/js/image-uploader.js', array('jquery','media-upload','thickbox'));
	  wp_enqueue_script('easy-gallery-uploader');
	}
	
	function easy_gallery_admin_styles() {	  
	  wp_enqueue_style('thickbox');
	}
	
	if (isset($_GET['page']) && ($_GET['page'] == 'add-gallery' || $_GET['page'] == 'add-images' || $_GET['page'] == 'edit-gallery')) {
	  add_action('admin_print_scripts', 'easy_gallery_admin_scripts');
	  add_action('admin_print_styles', 'easy_gallery_admin_styles');
	}
	
	// Create Admin Panel
	function add_hcg_menu()
	{
		add_menu_page(__('Easy Gallery','menu-hcg'), __('Easy Gallery','menu-hcg'), 'manage_options', 'hcg-admin', 'showHcgMenu' );

		// Add a submenu to the custom top-level menu:
		add_submenu_page('hcg-admin', __('Easy Gallery >> Add Gallery','menu-hcg'), __('Add Gallery','menu-hcg'), 'manage_options', 'add-gallery', 'add_gallery');
		
		// Add a submenu to the custom top-level menu:
		add_submenu_page('hcg-admin', __('Easy Gallery >> Edit Gallery','menu-hcg'), __('Edit Gallery','menu-hcg'), 'manage_options', 'edit-gallery', 'edit_gallery');

		// Add a second submenu to the custom top-level menu:
		add_submenu_page('hcg-admin', __('Easy Gallery >> Add Images','menu-hcg'), __('Add Images','menu-hcg'), 'manage_options', 'add-images', 'add_images');
		
		// Add a second submenu to the custom top-level menu:
		add_submenu_page('hcg-admin', __('Easy Gallery >> Settings','menu-hcg'), __('Settings','menu-hcg'), 'manage_options', 'settings', 'settings');
		
		// Add a second submenu to the custom top-level menu:
		add_submenu_page('hcg-admin', __('Easy Gallery >> Help (FAQ)','menu-hcg'), __('Help (FAQ)','menu-hcg'), 'manage_options', 'help', 'help');
		
		wp_register_style('easy-gallery-style', WP_PLUGIN_URL.'/wp-easy-gallery/css/wp-easy-gallery.css');
	  	wp_enqueue_style('easy-gallery-style');

	}
	
	add_action( 'admin_menu', 'add_hcg_menu' );
	
	function showHcgMenu()
	{
		include("admin/overview.php");
	}
	
	function add_gallery()
	{
		include("admin/add-gallery.php");
	}
	
	function edit_gallery()
	{
		include("admin/edit-gallery.php");
	}
	
	function add_images()
	{
		include("admin/add-images.php");
	}
	
	function settings()
	{
		include("admin/settings.php");
	}
	
	function help()
	{
		include("admin/help.php");
	}
	
	/* ==================================================================================
	 * Gallery Creation Filter
	 * ================================================================================== 
	 */
	 
	// function creates the gallery
	function createEasyGallery($galleryName)	
	{			
		global $wpdb;
		global $easy_gallery_table;
		global $easy_gallery_image_table;
		
		$gallery = $wpdb->get_row( "SELECT Id, name, thumbnail, thumbwidth, thumbheight FROM $easy_gallery_table WHERE slug = '$galleryName'" );
		$imageResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_image_table WHERE gid = $gallery->Id ORDER BY sortOrder ASC" );
		
		$images = array();
		$descriptions = array();
		$titles = array();
		$i = 0;		
		
		foreach($imageResults as $image)
		{
			$images[$i] = "'".$image->imagePath."'";
			$descriptions[$i] = "'".$image->description."'";
			$titles[$i] = "'".$image->title."'";
			$i++;
		}
		
		$img = implode(", ", $images);
		$desc = implode(", ", $descriptions);
		$ttl = implode(", ", $titles);
		
		$thumbwidth = ($gallery->thumbwidth < 1 || $gallery->thumbwidth == "auto") ? "" : "width='".$gallery->thumbwidth."'";
		$thumbheight = ($gallery->thumbheight < 1 || $gallery->thumbheight == "auto") ? "" : "height='".$gallery->thumbheight."'";
		
		$galleryLink = "<a class=\"wp-easy-gallery\" onclick=\"var images=[".$img."]; var titles=[".$ttl."]; var descriptions=[".$desc."]; jQuery.prettyPhoto.open(images,titles,descriptions);\" title=\"".$gallery->name."\" style=\"cursor: pointer;\"><img src=\"".$gallery->thumbnail."\" ".$thumbwidth." ".$thumbheight." border=\"0\" alt=\"".$gallery->name."\" /></a>";
		return $galleryLink;
	}	
	
	function EasyGallery_Handler($atts) {
	  return createEasyGallery($atts['id']);
  }
  add_shortcode('EasyGallery', 'EasyGallery_Handler');
	
?>