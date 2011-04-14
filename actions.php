<?php
ob_start();
define('WP_USE_THEMES', false);

/** Loads the WordPress Environment and Template */
require_once((string) $_POST["abspath"].'wp-blog-header.php');
require_once (ABSPATH . 'wp-admin/includes/file.php');
global $wpdb;
$awm_table_name = $wpdb->prefix . "awm";

include_once WP_PLUGIN_DIR.'/allwebmenus-wordpress-menu-plugin/include.php';
include_once WP_PLUGIN_DIR.'/allwebmenus-wordpress-menu-plugin/widgetClass.php';


$AWM_ver = '1.1.1';
$awm_total_tabs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $awm_table_name;"));

/*
 * Do the Form Error-Checking
 */




for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
	// AWM_categories_subitems_no
	if (isset($_POST['AWM_categories_subitems_no_'.$awm_t])) {
		if ($_POST['AWM_categories_subitems_no_'.$awm_t]<1) $_POST['AWM_categories_subitems_no_'.$awm_t]=1;
		if ($_POST['AWM_categories_subitems_no_'.$awm_t]>50) $_POST['AWM_categories_subitems_no_'.$awm_t]=50;
	}
	// AWM_menu_name
	if (isset($_POST["AWM_menu_name_".$awm_t])) $_POST["AWM_menu_name_".$awm_t] = awm_fix_menu_name($_POST["AWM_menu_name_".$awm_t]);
}
// AWM_menu_path
if (isset($_POST["AWM_menu_path"])) {
	$awm_path = (string) $_POST["AWM_menu_path"];
	if ((strpos($awm_path, "/") != 0) || (strpos($awm_path, "/") === FALSE)) $awm_path = "/" . $awm_path;
	if (substr($awm_path, strlen($awm_path)-1,1) != "/") $awm_path = $awm_path . "/";
	$_POST["AWM_menu_path"] = $awm_path;
}

if (!isset($_POST['theaction'])){
    ob_end_clean();
    wp_redirect($_POST['ref'] );
    exit;

}
if ($_POST['theaction'] == "createnew") {

            $message = awm_create_new_menu();
            
            if (!session_id())
                session_start();
                $_SESSION['message'] = $message;
                ob_end_clean();
            wp_redirect($_POST['ref'] );
            exit;
        }
        else if ($_POST['theaction']=="delete") {
		// update all values

                $message = awm_delete_menu();
           if (!session_id())
                session_start();
                $_SESSION['message'] =  '<div class="updated fade"><p><strong>'.$message.'</strong></p></div>';
                ob_end_clean();
                wp_redirect($_POST['ref'] );
                exit;
	}
        
        elseif ($_POST['theaction']=="generate_structure") {
		// first update all values, then generate the current tab's structure
                global $awm_total_tabs;
                if ($awm_total_tabs){
                    awm_update_option_values();
                    ob_end_clean();
                    wp_redirect($_POST['ref']."&generated=true" );}
                else{
                    if (!session_id())
                session_start();
                $_SESSION['message'] =  '<div class="updated fade"><p><strong>There are no menus. You can create one using the appropriate button.</strong></p></div>';
                ob_end_clean();
                wp_redirect($_POST['ref'] );
                
                }
                exit;
/*	} elseif ($_POST['theaction']=='Activate') {
		echo '<div class="updated fade"><p><strong>';
		if (!in_array('yet-another-related-posts-plugin/yarpp.php', get_option('active_plugins'))) {
			include_once('yet-another-related-posts-plugin/yarpp.php');
			update_option('AWM_YARPP', TRUE);
			echo "YARPP Activated!";
		} else {
			update_option('AWM_YARPP', TRUE);
			echo "YARPP Activated!";
		}
	    echo '</strong></p></div>';
	} elseif ($_POST['theaction']=='Deactivate') {
		update_option('AWM_YARPP', FALSE);
		echo '<div class="updated fade"><p><strong>YARPP Dectivated!</strong></p></div>';
*/	}
	else if ($_POST['theaction']=="set_defaults") {
		// first update all values, then reset this tab to defaults
		awm_update_option_values();
		awm_set_default_option_values(get_option('AWM_selected_tab'));
		if (!session_id())
                session_start();
                $_SESSION['message'] =  '<div class="updated fade"><p><strong>Default Settings Loaded!</strong></p></div>';

                ob_end_clean();
                wp_redirect($_POST['ref'] );
                exit;
	}
        
        else if ($_POST['theaction']=="info_update") {
		// update all values
		$message = awm_update_option_values();
                if (!session_id())
                session_start();
                $_SESSION['message'] =  '<div class="updated fade"><p><strong>'.$message.'</strong></p></div>';
                ob_end_clean();
                wp_redirect($_POST['ref'] );
                exit;
	}
        else if ($_POST['theaction']=='hide_msg') {
		update_option('AWM_Check_show', FALSE);
                ob_end_clean();
                wp_redirect($_POST['ref'] );
                exit;
	}
        else if ($_POST['theaction']=="zip_update") {
                $message = awm_update_zip();
                if (!session_id())
                session_start();
                $_SESSION['message'] =  '<div class="updated fade"><p><strong>'.$message.'</strong></p></div>';
                ob_end_clean();
                wp_redirect($_POST['ref'] );
                exit;
	}
        else{
            ob_end_clean();
            wp_redirect($_POST['ref'] );
            exit;
            
        }
?>
