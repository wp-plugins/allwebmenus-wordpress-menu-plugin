<?php
/* This code saves the form values */
function awm_update_option_values() {
	global $total_tabs;
	for ($t=0; $t<$total_tabs; $t++) {
		update_option('AWM_menu_active_'.$t, (bool) $_POST["AWM_menu_active_".$t]);
		update_option('AWM_menu_type_'.$t, (string) $_POST["AWM_menu_type_".$t]);
		update_option('AWM_menu_name_'.$t, (string) $_POST["AWM_menu_name_".$t]);
		update_option('AWM_include_home_'.$t, (string) $_POST["AWM_include_home_".$t]);
		update_option('AWM_pages_'.$t, (bool) $_POST["AWM_pages_".$t]);
		update_option('AWM_pages_ms_'.$t, (string) $_POST["AWM_pages_ms_".$t]);
		update_option('AWM_pages_name_'.$t, (string) $_POST["AWM_pages_name_".$t]);
		update_option('AWM_excluded_pages_'.$t, (string) $_POST["AWM_excluded_pages_".$t]);	
		update_option('AWM_posts_'.$t, (bool) $_POST["AWM_posts_".$t]);
		update_option('AWM_posts_ms_'.$t, (string) $_POST["AWM_posts_ms_".$t]);
		update_option('AWM_posts_name_'.$t, (string) $_POST["AWM_posts_name_".$t]);
		update_option('AWM_posts_ids_'.$t, (string) $_POST["AWM_posts_ids_".$t]);
		update_option('AWM_categories_'.$t, (bool) $_POST["AWM_categories_".$t]);
		update_option('AWM_categories_ms_'.$t, (string) $_POST["AWM_categories_ms_".$t]);
		update_option('AWM_categories_name_'.$t, (string) $_POST["AWM_categories_name_".$t]);
		update_option('AWM_categories_subitems_'.$t, (bool) $_POST["AWM_categories_subitems_".$t]);
		update_option('AWM_categories_subitems_no_'.$t, (string) $_POST["AWM_categories_subitems_no_".$t]);
		update_option('AWM_excluded_cats_'.$t, (string) $_POST["AWM_excluded_cats_".$t]);	
		update_option('AWM_hide_future_'.$t, (bool) $_POST["AWM_hide_future_".$t]);	
		update_option('AWM_hide_protected_'.$t, (bool) $_POST["AWM_hide_protected_".$t]);	
		update_option('AWM_Related_'.$t, (bool) $_POST["AWM_Related_".$t]);
	}
	update_option('AWM_menu_path', (string) $_POST["AWM_menu_path"]);
	update_option('AWM_selected_tab', (string) $_POST["AWM_selected_tab"]);
}

/* This code sets the plugin up for first-time-run */
function awm_set_first_time_options() {
	global $total_tabs;
	add_option('AWM_menu_active_0', TRUE);
	for ($t=0; $t<$total_tabs; $t++) {
		add_option('AWM_menu_active_'.$t, FALSE);
		add_option('AWM_menu_type_'.$t, "Dynamic");
		add_option('AWM_menu_name_'.$t, 'menu'.$t);
		add_option('AWM_include_home_'.$t, TRUE);
		add_option('AWM_pages_'.$t, TRUE);
		add_option('AWM_pages_ms_'.$t, 'main');
		add_option('AWM_pages_name_'.$t, 'Pages');
		add_option('AWM_excluded_pages_'.$t, ''); 
		add_option('AWM_posts_'.$t, FALSE);
		add_option('AWM_posts_ms_'.$t, 'sub');
		add_option('AWM_posts_name_'.$t, 'Posts');
		add_option('AWM_posts_ids_'.$t, '');
		add_option('AWM_categories_'.$t, TRUE);
		add_option('AWM_categories_ms_'.$t, 'sub');
		add_option('AWM_categories_name_'.$t, 'Categories');
		add_option('AWM_categories_subitems_'.$t, TRUE);
		add_option('AWM_categories_subitems_no_'.$t, 5);
		add_option('AWM_excluded_cats_'.$t, ''); 
		add_option('AWM_hide_future_'.$t, TRUE); 
		add_option('AWM_hide_protected_'.$t, TRUE); 
		add_option('AWM_Related_'.$t, FALSE);
	}
	add_option('AWM_menu_path', '/menu/');
	add_option('AWM_YARPP_'.$t, FALSE);
	add_option('AWM_Checked', FALSE);
	add_option('AWM_Checked_Date', '00');
	add_option('AWM_selected_tab', '0');
}

/* This code sets the default option values for a given tab */
function awm_set_default_option_values($t) {
	update_option('AWM_menu_active_'.$t, TRUE);
	update_option('AWM_menu_type_'.$t, "Dynamic");
	update_option('AWM_menu_name_'.$t, 'menu'.$t);
	update_option('AWM_include_home_'.$t, TRUE);
	update_option('AWM_pages_'.$t, TRUE);
	update_option('AWM_pages_ms_'.$t, 'main');
	update_option('AWM_pages_name_'.$t, 'Pages');
	update_option('AWM_excluded_pages_'.$t, '');
	update_option('AWM_posts_'.$t, FALSE);
	update_option('AWM_posts_ms_'.$t, 'sub');
	update_option('AWM_posts_name_'.$t, 'Posts');
	update_option('AWM_posts_ids_'.$t, '');
	update_option('AWM_categories_'.$t, TRUE);
	update_option('AWM_categories_ms_'.$t, 'sub');
	update_option('AWM_categories_name_'.$t, 'Categories');
	update_option('AWM_categories_subitems_'.$t, FALSE);
	update_option('AWM_categories_subitems_no_'.$t, 5);
	update_option('AWM_excluded_cats_'.$t, ''); 
	update_option('AWM_hide_future_'.$t, TRUE); 
	update_option('AWM_hide_protected_'.$t, TRUE); 
	update_option('AWM_Related_'.$t, FALSE);
	update_option('AWM_YARPP_'.$t, TRUE);
//	update_option('AWM_menu_path', '/menu/');
}

/* This code converts the options from old single-tab version to multi-tab */
function awm_convert_from_single_to_multi_tab() {
	global $total_tabs;
	add_option('AWM_menu_name','nowayyouhavethisvalue');	// first create an impossible value
	if (get_option('AWM_menu_name')=='nowayyouhavethisvalue') {	// if the option now has this value, it did not exist (this means you already have the new version
		delete_option('AWM_menu_name');
	} else {										// else you had the old so you need to convert
		for ($t=0; $t<$total_tabs; $t++) {
			update_option('AWM_include_home_'.$t, get_option('AWM_include_home'));
			update_option('AWM_pages_'.$t, get_option('AWM_pages'));
			update_option('AWM_pages_ms_'.$t, get_option('AWM_pages_ms'));
			update_option('AWM_pages_name_'.$t, get_option('AWM_pages_name'));
			update_option('AWM_posts_'.$t, get_option('AWM_posts'));
			update_option('AWM_posts_ms_'.$t, get_option('AWM_posts_ms'));
			update_option('AWM_posts_name_'.$t, get_option('AWM_posts_name'));
			update_option('AWM_posts_ids_'.$t, get_option('AWM_posts_ids'));
			update_option('AWM_categories_'.$t, get_option('AWM_categories'));
			update_option('AWM_categories_ms_'.$t, get_option('AWM_categories_ms'));
			update_option('AWM_categories_name_'.$t, get_option('AWM_categories_name'));
			update_option('AWM_categories_subitems_'.$t, get_option('AWM_categories_subitems'));
			update_option('AWM_categories_subitems_no_'.$t, get_option('AWM_categories_subitems_no'));
			update_option('AWM_hide_future_'.$t, get_option('AWM_hide_future'));
			update_option('AWM_hide_protected_'.$t, get_option('AWM_hide_protected'));
			update_option('AWM_excluded_cats_'.$t, get_option('AWM_excluded_cats'));
			update_option('AWM_excluded_pages_'.$t, get_option('AWM_excluded_pages'));
			update_option('AWM_Related_'.$t, get_option('AWM_Related'));
		}
		
		$mn = explode(",", get_option('AWM_menu_name'));
		for ($i=0; $i<count($mn) && $i<$total_tabs; $i++) {
			$n = awm_fix_menu_name(trim($mn[$i]));
			update_option('AWM_menu_name_'.$i, $n);
			update_option('AWM_menu_active_'.$i, TRUE);
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
function awm_fix_menu_name($m) {
	$awm_name = $m;
	if (strpos($awm_name,"/")>-1 || strpos($awm_name,".js")>-1 ) {
		$nm = explode("/", $awm_name);
		$awm_name = $nm[1];
		$nm = explode(".", $awm_name);
		$awm_name = $nm[0];
	}
	return $awm_name;
}

/* This code checks for updated versions of the AllWebMenus software and informs the user if necessary */
function AWM_check()
{
	global $url, $total_tabs;
	$the_msg = array();
	$path = get_option('AWM_menu_path');
	
	for ($t=0; $t<$total_tabs; $t++) {
		$the_msg[$t] = "";
		if (!get_option('AWM_menu_active_'.$t)) continue;
		$awm_name = trim(get_option('AWM_menu_name_'.$t)).".js";
		if (! ($menufile = fopen(dirname(__FILE__) .'/../../..'. $path . $awm_name, 'r'))) {
			$the_msg[$t] = "<br>Caught exception when opening file ". dirname(__FILE__) ."/../../..". $path . $awm_name;
			continue;
		} elseif (! ($mfile = fread($menufile, filesize(dirname(__FILE__) .'/../../..'. $path . $awm_name)))) {
			$the_msg[$t] = "<br>Caught exception when reading file /../../..". $path . $awm_name;
			continue;
		}
		$bNo = explode('awmLibraryBuild=', $mfile);
		if ($bNo[1]==null) {
			$the_msg[$t] =  "<br>Caught exception when reading file ". dirname(__FILE__) ."/../../..". $path . $awm_name;
			continue;
		}
		$bNo = explode(';', $bNo[1]);
		$buildNo = $bNo[0];
		$hNo = explode('awmHash=\'', $mfile);
		if ($hNo[1]==null) {
			$the_msg[$t] =  "<br>Caught exception when reading file ". dirname(__FILE__) ."/../../..". $path . $awm_name;
			continue;
		}
		$hNo = explode('\'', $hNo[1]);
		$HashNo = $hNo[0];
		
		$params = "plugin=wordpress&build=$buildNo&hash=$HashNo&rand=". rand(1,10000) ."&domain=". get_bloginfo('url');

		if (function_exists('curl_init')) {
			if (! ($awm_tmp = geturl($params))) {
				$the_msg[$t] = "<br>Caught exception while retrieving version information. Please <a href='mailto:support@likno.com?subject=WordPress: Error while retrieving version info'>contact Likno</a> for more information.";
			} else {
				$the_msg[$t] = $awm_tmp;
			}
			continue;
		} else {
			$the_msg[$t] = '<iframe src='. $url .'?'. $params .' width="600px" height="80px"></iframe>';
		}
	}
	
	$has_msg = false;
	for ($t=0; $t<$total_tabs; $t++) { if ($the_msg[$t] != "") $has_msg = true; break; }
	
	$the_full_msg = "";
	if ($has_msg) {
		$the_full_msg = "<div class='updated fade'><table><tr><td><table>";
		for ($t=0; $t<$total_tabs; $t++) if ($the_msg[$t] != "") $the_full_msg .= "<tr><td>Menu ".($t+1).": </td><td>".$the_msg[$t]."</td></tr>";
		$the_full_msg .= "</table></td><td width='200px' align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Hide' onclick='theform.theaction.value=\"hide_msg\"; theform.submit();'/></td></tr></table></div>";
	}
	
	update_option('AWM_Checked', TRUE);
	update_option('AWM_Checked_Date', date(d));
	
	return $the_full_msg;
}

/* Helper code for above function */
$url="http://www.likno.com/addins/plugin-check.php";
function geturl($params)
{
    global $url;
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $postResult = curl_exec($ch);

    return $postResult;
}



?>