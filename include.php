<?php
/* This code saves the form values */
function awm_update_option_values() {
	global $awm_total_tabs;
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
		update_option('AWM_menu_active_'.$awm_t, (bool) $_POST["AWM_menu_active_".$awm_t]);
		update_option('AWM_menu_type_'.$awm_t, (string) $_POST["AWM_menu_type_".$awm_t]);
		update_option('AWM_menu_name_'.$awm_t, (string) $_POST["AWM_menu_name_".$awm_t]);
		update_option('AWM_include_home_'.$awm_t, (string) $_POST["AWM_include_home_".$awm_t]);
		update_option('AWM_pages_'.$awm_t, (bool) $_POST["AWM_pages_".$awm_t]);
		update_option('AWM_pages_ms_'.$awm_t, (string) $_POST["AWM_pages_ms_".$awm_t]);
		update_option('AWM_pages_name_'.$awm_t, (string) $_POST["AWM_pages_name_".$awm_t]);
		update_option('AWM_excluded_pages_'.$awm_t, (string) $_POST["AWM_excluded_pages_".$awm_t]);	
		update_option('AWM_posts_'.$awm_t, (bool) $_POST["AWM_posts_".$awm_t]);
		update_option('AWM_posts_ms_'.$awm_t, (string) $_POST["AWM_posts_ms_".$awm_t]);
		update_option('AWM_posts_name_'.$awm_t, (string) $_POST["AWM_posts_name_".$awm_t]);
		update_option('AWM_posts_ids_'.$awm_t, (string) $_POST["AWM_posts_ids_".$awm_t]);
		update_option('AWM_categories_'.$awm_t, (bool) $_POST["AWM_categories_".$awm_t]);
		update_option('AWM_categories_ms_'.$awm_t, (string) $_POST["AWM_categories_ms_".$awm_t]);
		update_option('AWM_categories_name_'.$awm_t, (string) $_POST["AWM_categories_name_".$awm_t]);
		update_option('AWM_categories_subitems_'.$awm_t, (bool) $_POST["AWM_categories_subitems_".$awm_t]);
		update_option('AWM_categories_subitems_no_'.$awm_t, (string) $_POST["AWM_categories_subitems_no_".$awm_t]);
		update_option('AWM_excluded_cats_'.$awm_t, (string) $_POST["AWM_excluded_cats_".$awm_t]);	
		update_option('AWM_hide_future_'.$awm_t, (bool) $_POST["AWM_hide_future_".$awm_t]);	
		update_option('AWM_hide_protected_'.$awm_t, (bool) $_POST["AWM_hide_protected_".$awm_t]);	
		update_option('AWM_Related_'.$awm_t, (bool) $_POST["AWM_Related_".$awm_t]);
	}
	update_option('AWM_menu_path', (string) $_POST["AWM_menu_path"]);
	update_option('AWM_selected_tab', (string) $_POST["AWM_selected_tab"]);
}

/* This code sets the plugin up for first-time-run */
function awm_set_first_time_options() {
	global $awm_total_tabs;
	add_option('AWM_menu_active_0', TRUE);
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
		add_option('AWM_menu_active_'.$awm_t, FALSE);
		add_option('AWM_menu_type_'.$awm_t, "Dynamic");
		add_option('AWM_menu_name_'.$awm_t, 'menu'.($awm_t+1));
		add_option('AWM_include_home_'.$awm_t, TRUE);
		add_option('AWM_pages_'.$awm_t, TRUE);
		add_option('AWM_pages_ms_'.$awm_t, 'main');
		add_option('AWM_pages_name_'.$awm_t, 'Pages');
		add_option('AWM_excluded_pages_'.$awm_t, ''); 
		add_option('AWM_posts_'.$awm_t, FALSE);
		add_option('AWM_posts_ms_'.$awm_t, 'sub');
		add_option('AWM_posts_name_'.$awm_t, 'Posts');
		add_option('AWM_posts_ids_'.$awm_t, '');
		add_option('AWM_categories_'.$awm_t, TRUE);
		add_option('AWM_categories_ms_'.$awm_t, 'sub');
		add_option('AWM_categories_name_'.$awm_t, 'Categories');
		add_option('AWM_categories_subitems_'.$awm_t, TRUE);
		add_option('AWM_categories_subitems_no_'.$awm_t, 5);
		add_option('AWM_excluded_cats_'.$awm_t, ''); 
		add_option('AWM_hide_future_'.$awm_t, TRUE); 
		add_option('AWM_hide_protected_'.$awm_t, TRUE); 
		add_option('AWM_Related_'.$awm_t, FALSE);
	}
	add_option('AWM_menu_path', '/menu/');
	add_option('AWM_Checked', FALSE);
	add_option('AWM_Checked_Date', '00');
	add_option('AWM_selected_tab', '0');
}

/* This code sets the default option values for a given tab */
function awm_set_default_option_values($awm_t) {
	update_option('AWM_menu_active_'.$awm_t, TRUE);
	update_option('AWM_menu_type_'.$awm_t, "Dynamic");
	update_option('AWM_menu_name_'.$awm_t, 'menu'.($awm_t+1));
	update_option('AWM_include_home_'.$awm_t, TRUE);
	update_option('AWM_pages_'.$awm_t, TRUE);
	update_option('AWM_pages_ms_'.$awm_t, 'main');
	update_option('AWM_pages_name_'.$awm_t, 'Pages');
	update_option('AWM_excluded_pages_'.$awm_t, '');
	update_option('AWM_posts_'.$awm_t, FALSE);
	update_option('AWM_posts_ms_'.$awm_t, 'sub');
	update_option('AWM_posts_name_'.$awm_t, 'Posts');
	update_option('AWM_posts_ids_'.$awm_t, '');
	update_option('AWM_categories_'.$awm_t, TRUE);
	update_option('AWM_categories_ms_'.$awm_t, 'sub');
	update_option('AWM_categories_name_'.$awm_t, 'Categories');
	update_option('AWM_categories_subitems_'.$awm_t, FALSE);
	update_option('AWM_categories_subitems_no_'.$awm_t, 5);
	update_option('AWM_excluded_cats_'.$awm_t, ''); 
	update_option('AWM_hide_future_'.$awm_t, TRUE); 
	update_option('AWM_hide_protected_'.$awm_t, TRUE); 
	update_option('AWM_Related_'.$awm_t, FALSE);
//	update_option('AWM_menu_path', '/menu/');
}

/* This code converts the options from old single-tab version to multi-tab */
function awm_convert_from_single_to_multi_tab() {
	global $awm_total_tabs;
	add_option('AWM_menu_name','nowayyouhavethisvalue');	// first create an impossible value
	if (get_option('AWM_menu_name')=='nowayyouhavethisvalue') {	// if the option now has this value, it did not exist (this means you already have the new version
		delete_option('AWM_menu_name');
	} else {										// else you had the old so you need to convert
		for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
			update_option('AWM_include_home_'.$awm_t, get_option('AWM_include_home'));
			update_option('AWM_pages_'.$awm_t, get_option('AWM_pages'));
			update_option('AWM_pages_ms_'.$awm_t, get_option('AWM_pages_ms'));
			update_option('AWM_pages_name_'.$awm_t, get_option('AWM_pages_name'));
			update_option('AWM_posts_'.$awm_t, get_option('AWM_posts'));
			update_option('AWM_posts_ms_'.$awm_t, get_option('AWM_posts_ms'));
			update_option('AWM_posts_name_'.$awm_t, get_option('AWM_posts_name'));
			update_option('AWM_posts_ids_'.$awm_t, get_option('AWM_posts_ids'));
			update_option('AWM_categories_'.$awm_t, get_option('AWM_categories'));
			update_option('AWM_categories_ms_'.$awm_t, get_option('AWM_categories_ms'));
			update_option('AWM_categories_name_'.$awm_t, get_option('AWM_categories_name'));
			update_option('AWM_categories_subitems_'.$awm_t, get_option('AWM_categories_subitems'));
			update_option('AWM_categories_subitems_no_'.$awm_t, get_option('AWM_categories_subitems_no'));
			update_option('AWM_hide_future_'.$awm_t, get_option('AWM_hide_future'));
			update_option('AWM_hide_protected_'.$awm_t, get_option('AWM_hide_protected'));
			update_option('AWM_excluded_cats_'.$awm_t, get_option('AWM_excluded_cats'));
			update_option('AWM_excluded_pages_'.$awm_t, get_option('AWM_excluded_pages'));
			update_option('AWM_Related_'.$awm_t, get_option('AWM_Related'));
		}
		
		$awm_mn = explode(",", get_option('AWM_menu_name'));
		for ($awm_i=0; $awm_i<count($awm_mn) && $awm_i<$awm_total_tabs; $awm_i++) {
			$awm_n = awm_fix_menu_name(trim($awm_mn[$awm_i]));
			update_option('AWM_menu_name_'.$awm_i, $awm_n);
			update_option('AWM_menu_active_'.$awm_i, TRUE);
		}
		
		delete_option('AWM_include_home');
		delete_option('AWM_pages');
		delete_option('AWM_pages_ms');
		delete_option('AWM_pages_name');
		delete_option('AWM_posts');
		delete_option('AWM_posts_ms');
		delete_option('AWM_posts_name');
		delete_option('AWM_posts_ids');
		delete_option('AWM_categories');
		delete_option('AWM_categories_ms');
		delete_option('AWM_categories_name');
		delete_option('AWM_categories_subitems');
		delete_option('AWM_categories_subitems_no');
		delete_option('AWM_archives');
		delete_option('AWM_hide_future');
		delete_option('AWM_new_window');
		delete_option('AWM_show_post_date');
		delete_option('AWM_date_format');
		delete_option('AWM_hide_protected');
		delete_option('AWM_excluded_cats');
		delete_option('AWM_excluded_pages');
		delete_option('AWM_menu_name');
		delete_option('AWM_Related');
		delete_option('AWM_Related_name');
	}
}

/* This code corrects the menu name if it has paths or extension */
function awm_fix_menu_name($awm_m) {
	$awm_name = $awm_m;
	if (strpos($awm_name,"/")>-1 || strpos($awm_name,".js")>-1 ) {
		$awm_nm = explode("/", $awm_name);
		$awm_name = $awm_nm[1];
		$awm_nm = explode(".", $awm_name);
		$awm_name = $awm_nm[0];
	}
	return $awm_name;
}

/* This code checks for updated versions of the AllWebMenus software and informs the user if necessary */
function AWM_check()
{
	global $awm_url, $awm_total_tabs;
	$awm_the_msg = array();
	$awm_realpath = dirname(__FILE__) .'/../../..'. get_option('AWM_menu_path');
	$awm_path = get_bloginfo('url') . get_option('AWM_menu_path');
	
	error_reporting(0);
	
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
		$awm_the_msg[$awm_t] = "";
		if (!get_option('AWM_menu_active_'.$awm_t)) continue;
		$awm_name = trim(get_option('AWM_menu_name_'.$awm_t)).".js";
		
		if (! ($awm_menufile = fopen($awm_realpath . $awm_name, 'r'))) {
			$awm_the_msg[$awm_t] = "Menu ".get_option('AWM_menu_name_'.$awm_t)." was not found at: ". $awm_path . $awm_name;
			continue;
		} elseif (! ($awm_mfile = fread($awm_menufile, filesize($awm_realpath . $awm_name)))) {
			$awm_the_msg[$awm_t] = "Could not read menu at: ". $awm_path . $awm_name;
			continue;
		}
		$awm_bNo = explode('awmLibraryBuild=', $awm_mfile);
		if ($awm_bNo[1]==null) {
			$awm_the_msg[$awm_t] = "Could not read menu at: ". $awm_path . $awm_name;
			continue;
		}
		$awm_bNo = explode(';', $awm_bNo[1]);
		$awm_buildNo = $awm_bNo[0];
		$awm_hNo = explode('awmHash=\'', $awm_mfile);
		if ($awm_hNo[1]==null) {
			$awm_the_msg[$awm_t] = "Could not read menu at: ". $awm_path . $awm_name;
			continue;
		}
		$awm_hNo = explode('\'', $awm_hNo[1]);
		$awm_HashNo = $awm_hNo[0];
		
		$awm_params = "plugin=wordpress&build=$awm_buildNo&hash=$awm_HashNo&rand=". rand(1,10000) ."&domain=". get_bloginfo('url');

		if (function_exists('curl_init')) {
			if (! ($awm_tmp = geturl($awm_params))) {
				$awm_the_msg[$awm_t] = "Could not retrieve version information for ".get_option('AWM_menu_name_'.$awm_t).". Please <a href='mailto:support@likno.com?subject=WordPress: Error while retrieving version info'>contact Likno</a> for more information.";
			} else {
				$awm_the_msg[$awm_t] = $awm_tmp;
			}
			continue;
		} else {
			$awm_the_msg[$awm_t] = '<iframe src='. $awm_url .'?'. $awm_params .' width="600px" height="80px"></iframe>';
		}
	}
	
	$awm_has_msg = false;
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) { if ($awm_the_msg[$awm_t] != "") $awm_has_msg = true; break; }
	
	$awm_the_full_msg = "";
	if ($awm_has_msg) {
		$awm_the_full_msg = "<div class='updated fade'>";
		for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) if ($awm_the_msg[$awm_t] != "") $awm_the_full_msg .= "<br><strong>Note about ".get_option('AWM_menu_name_'.$awm_t).": </strong><br>".$awm_the_msg[$awm_t]."<br>";
		$awm_the_full_msg .= "<br><input type='button' value='Hide Notifications' onclick='theform.theaction.value=\"hide_msg\"; theform.submit();'/><br>&nbsp;</div>";
	}
	
	update_option('AWM_Checked', TRUE);
	update_option('AWM_Checked_Date', date(d));
	
	error_reporting(1);
	
	return $awm_the_full_msg;
}

/* Helper code for above function */
$awm_url="http://www.likno.com/addins/plugin-check.php";
function geturl($awm_params)
{
    global $awm_url;
    $awm_ch = curl_init();
    curl_setopt ($awm_ch, CURLOPT_URL,$awm_url);
	curl_setopt($awm_ch, CURLOPT_POST, 1);
    curl_setopt($awm_ch, CURLOPT_POSTFIELDS, $awm_params);
    
    curl_setopt($awm_ch, CURLOPT_RETURNTRANSFER, 1);
    $awm_postResult = curl_exec($awm_ch);

    return $awm_postResult;
}



?>