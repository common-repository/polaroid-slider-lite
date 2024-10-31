<?php
/*
Plugin Name: Polaroid Slider Lite
Plugin URI: http://codecanyon.net/item/polaroid-slider-for-wordpress/
Description: Polaroid slider Lite mimics the feeling of the old-style polaroid photographs. Its a powerful javascript-css slider using CSS3 and jQuery.
Version: 1.0
Author: Ernest Marcinko - anago @ codecanyon
Author URI: http://codecanyon.net/user/anago/
*/
?>
<?php
  //return;
  define( 'POLAROIDSLIDER_PATH', plugin_dir_path(__FILE__) );                                               
  define( 'POLAROIDSLIDER_DIR', 'polaroid-slider-lite'); 
  
  /*A headerbe érkezõ scripteket és css fájlokat csak itt lehet hozzáadni, alpageken nem! Ott már az az action lefutott! */
  if (isset($_GET) && isset($_GET['page']) && (
    $_GET['page']=="polaroid-slider-lite/settings.php"
  ))
    require_once(POLAROIDSLIDER_PATH."/settings/types.class.php");
  require_once(POLAROIDSLIDER_PATH."/functions.php");
  require_once(POLAROIDSLIDER_PATH."/includes/shortcodes.php");

  $funcs = new polaroidFuncCollector();
  /*
    Create pages
  */
  add_action( 'admin_menu', array($funcs, 'navigation_menu') );  
  
  /*
    Add Hacks
  */
  
  register_activation_hook( __FILE__, array($funcs, 'polaroidslider_activate') );
  add_action('wp_print_styles', array($funcs, 'styles'));
  add_action('wp_enqueue_scripts', array($funcs, 'scripts'));
  add_action('wp_ajax_reorder_slides', array($funcs, 'reorder_slides')); 

  class polaroidFuncCollector {
  
    function polaroidslider_activate() {
      global $wpdb;
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      $table_name = $wpdb->prefix . "polaroid_slides_lite";
      $query = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `parent_id` int(11) NOT NULL DEFAULT '0',
          `slider_id` int(11) NOT NULL,
          `ordering` int(11) NOT NULL,
          `name` text NOT NULL,
          `data` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
      "; 
      dbDelta($query);
      ob_start();
      ?>      
INSERT INTO `wp_polaroid_slides_lite` (`id`, `parent_id`, `slider_id`, `ordering`, `name`, `data`) VALUES
(4, 0, 0, 4, 'Default slide 1', '{"image":"http:\\/\\/images4.alphacoders.com\\/183\\/thumbbig-183774.jpg","html":"","slidecolor":"#ffffff","image100x":"0","image100y":"0","labelfont":"font-weight:bold;font-family:Anonymous Pro;color:#ededed;font-size:19px;","script-labelfont":"    <style>\\r\\n      @import url(http:\\/\\/fonts.googleapis.com\\/css?family=Anonymous+Pro:300|Anonymous+Pro:400|Anonymous+Pro:700);\\r\\n    <\\/style>\\r\\n    ","label":"<a href=\\\\''#\\\\''>Phoenix<\\/a>","labelposition":"duration:500;direction:bottom-left;position:60||60;","labelwidth":"0","labelbgcolor":"#000000","labelborderwidth":"1","labelbordercolor":"#222222","labelbgopacity":"0.5"}'),
(5, 0, 0, 3, 'Default slide 2', '{"image":"http:\\/\\/2.bp.blogspot.com\\/-pNs8Uj-HTP8\\/UFM6tv28iSI\\/AAAAAAAAKAo\\/YMiJwxYJB5Q\\/s400\\/Male+Lion+Wallpapers.jpg","html":"","slidecolor":"#ffffff","image100x":"0","image100y":"0","labelfont":"font-weight:bold;font-family:Anonymous Pro;color:#ededed;font-size:19px;","script-labelfont":"    <style>\\r\\n      @import url(http:\\/\\/fonts.googleapis.com\\/css?family=Anonymous+Pro:300|Anonymous+Pro:400|Anonymous+Pro:700);\\r\\n    <\\/style>\\r\\n    ","label":"<a href=\\\\''#\\\\''>Lion<\\/a>","labelposition":"duration:500;direction:left;position:190||312;","labelwidth":"0","labelbgcolor":"#000000","labelborderwidth":"1","labelbordercolor":"#222222","labelbgopacity":"0.5"}'),
(6, 0, 0, 2, 'Default slide 3', '{"image":"","html":"<iframe width=\\\\\\"400\\\\\\" height=\\\\\\"300\\\\\\" src=\\\\\\"http:\\/\\/www.youtube.com\\/embed\\/RzI9v_B4sxw\\\\\\" frameborder=\\\\\\"0\\\\\\" allowfullscreen><\\/iframe>","slidecolor":"#ffffff","image100x":"0","image100y":"0","labelfont":"font-weight:bold;font-family:Anonymous Pro;color:#c9c9c9;font-size:19px;","script-labelfont":"    <style>\\r\\n      @import url(http:\\/\\/fonts.googleapis.com\\/css?family=Anonymous+Pro:300|Anonymous+Pro:400|Anonymous+Pro:700);\\r\\n    <\\/style>\\r\\n    ","label":"","labelposition":"duration:500;direction:bottom-left;position:60||60;","labelwidth":"0","labelbgcolor":"#000000","labelborderwidth":"1","labelbordercolor":"#222222","labelbgopacity":"0.5"}'),
(7, 0, 0, 1, 'Default slide 4', '{"image":"http:\\/\\/4.bp.blogspot.com\\/_Y67UOWS92Ys\\/TQ18pQw4yOI\\/AAAAAAAAAFA\\/d-4hnPHY7og\\/s400\\/paris.gif","html":"","slidecolor":"#ffffff","image100x":"0","image100y":"0","labelfont":"font-weight:bold;font-family:Anonymous Pro;color:#0a0a0a;font-size:19px;","script-labelfont":"    <style>\\r\\n      @import url(http:\\/\\/fonts.googleapis.com\\/css?family=Anonymous+Pro:300|Anonymous+Pro:400|Anonymous+Pro:700);\\r\\n    <\\/style>\\r\\n    ","label":"<a href=\\\\''#\\\\''>Paris<\\/a>","labelposition":"duration:500;direction:bottom-right;position:50||62;","labelwidth":"0","labelbgcolor":"#ffffff","labelborderwidth":"1","labelbordercolor":"#222222","labelbgopacity":"0.5"}');
    <?php
      $query = ob_get_clean();
      $wpdb->query($query);
    }
       
    function navigation_menu() {
      if(current_user_can('add_users')) {
      	add_menu_page( 
      	 __('Polaroid Slider Lite', EMU2_I18N_DOMAIN),
      	 __('Polaroid Slider Lite', EMU2_I18N_DOMAIN),
      	 0,
      	 POLAROIDSLIDER_DIR.'/settings.php',
      	 '',
      	 plugins_url('/slider.png', __FILE__)
        );
      }
      
    	/*add_submenu_page( 
    	POLAROIDSLIDER_DIR.'/settings.php',
    	__("Polaroid Slider", EMU2_I18N_DOMAIN),
    	__("Slides", EMU2_I18N_DOMAIN),
    	0,
    	POLAROIDSLIDER_DIR.'/settingas.php'
    	);  */     
         
    }
    
    function reorder_slides() {
    	global $wpdb; // this is how you get access to the database
    	foreach($_POST['nodes'] as $k=>$v) {
        $wpdb->query("UPDATE  ".$wpdb->prefix."polaroid_slides_lite SET ordering=".$k." WHERE id=".$v);
      }
    	die(); 
    }    
     
    function styles() {
      wp_register_style('wpdreams-pslider', plugin_dir_url(__FILE__).'/css/pslider.css');
      wp_enqueue_style('wpdreams-pslider');   
    }
    
    function scripts() {
      wp_enqueue_script('jquery');
      wp_enqueue_script('jquery-ui-draggable');
      if (wpdreams_ismobile()) {
        wp_register_script('wpdreams-dragfix', plugin_dir_url(__FILE__).'/js/non-minified/jquery.drag.fix.js', array('jquery-ui-draggable'));
        wp_enqueue_script('wpdreams-dragfix');
        wp_register_script('wpdreams-rotel', plugin_dir_url(__FILE__).'js/non-minified/jquery.rotel.js', array('jquery'));
        wp_enqueue_script('wpdreams-rotel');
        wp_register_script('wpdreams-easing', plugin_dir_url(__FILE__).'js/non-minified/jquery.easing.js', array('jquery'));
        wp_enqueue_script('wpdreams-easing');
        wp_register_script('wpdreams-easingcomp', plugin_dir_url(__FILE__).'js/non-minified/jquery.easing.compatibility.js', array('jquery', 'wpdreams-easing'));
        wp_enqueue_script('wpdreams-easingcomp');
        wp_register_script('wpdreams-pslider', plugin_dir_url(__FILE__).'js/non-minified/pslider.js', array('jquery'));
        wp_enqueue_script('wpdreams-pslider');
      } else {
        wp_register_script('wpdreams-pslidermin', plugin_dir_url(__FILE__).'js/pslider.min.js', array('jquery'));
        wp_enqueue_script('wpdreams-pslidermin');      
      }

    }   
  }  

  /*add_action('wp_ajax_wpdreams-ajaxinput', "callback1");
  function callback1() {
    echo "asd";
    exit;
  } */


?>
