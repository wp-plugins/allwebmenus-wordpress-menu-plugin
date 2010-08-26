<?php
/*
Plugin Name: AllWebMenus WordPress Menu Plugin
Plugin URI: http://www.likno.com/addins/wordpress-menu.html
Description: WordPress plugin for the AllWebMenus PRO Javascript Menu Maker - Create stylish drop-down menus or sliding menus for your blogs!
Version: 1.0.19
Author: Likno Software
Author URI: http://www.likno.com/ 
*/

/*

NOTE:

This plugin is licensed under the GNU General Public License (GPL).

As such, you may use the source code of this plugin as you wish.
This plugin is used as a bridge to a non-GPL licensed software (AllWebMenus PRO) that is a property of Likno Software.

The license of AllWebMenus PRO states that a WordPress Menu (a menu which structure is retrieved using this plugin and compiled with AllWebMenus PRO using the WordPress Add-In) can be used in **one single domain**.

Thus, the part of the code below that confirms that the menu is used only in one domain **cannot** be changed/removed.

*/

/*
 * Load the include files
 */
include_once ABSPATH . 'wp-content/plugins/allwebmenus-wordpress-menu-plugin/menu_helper.php';
include_once ABSPATH . 'wp-content/plugins/allwebmenus-wordpress-menu-plugin/include.php';


$AWM_ver = '1.0.19';
$awm_total_tabs = 5;

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
//if ($_POST["AWM_selected_tab"]=="") $_POST["AWM_selected_tab"]="1";


// set the first time options (if they do not already exist)
awm_set_first_time_options();


// Check if already had the plugin when it was single-tab and convert values
awm_convert_from_single_to_multi_tab();

$awm_is_yarpp_enabled = in_array('yet-another-related-posts-plugin/yarpp.php', get_option('active_plugins'));

// If neede, update YARPP's auto_display option
//for ($awm_i=0; $awm_i<$awm_total_tabs; $awm_i++) if (get_option('AWM_Related_'.$awm_i)) update_option('yarpp_auto_display', 0);


/* 
 * Add options page
 */
function AWM_add_option_pages() {
	if (function_exists('add_options_page')) {
		add_options_page('AllWebMenus WordPress Menu Plugin', 'AllWebMenus-WP-Menu', 8, __FILE__, 'AWM_options_page');
	}
}

function awm_wp_settings_link($awm_links) {
	$awm_settings_link = '<a href="options-general.php?page=allwebmenus-wordpress-menu-plugin/allwebmenus-wordpress-menu.php">Settings</a>';
	array_unshift($awm_links, $awm_settings_link);
	return $awm_links;
}

$awm_plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$awm_plugin", 'awm_wp_settings_link' );

// Check if you need to check for updates
if ((get_option('AWM_Checked_Date') <= (date(d) - 15)) || (get_option('AWM_Checked_Date') === '00')) {
	update_option('AWM_Check_Show', TRUE);
}


/* 
 * Generate options page
 */
function AWM_options_page() {
	global $AWM_ver, $awm_total_tabs, $awm_is_yarpp_enabled;
?>

	<div style="max-width: 980px; margin-left: 15px;">

	<span class="wrap">
	<h2>AllWebMenus WordPress Menu Plugin v<?php echo $AWM_ver; ?></h2>

	<?php
//	echo "<br><br>|".$_POST['theaction']."|<br><br>";

	if ($_POST['theaction']=="set_defaults") {
		// first update all values, then reset this tab to defaults
		awm_update_option_values();
		awm_set_default_option_values(get_option('AWM_selected_tab'));
		echo '<div class="updated fade"><p><strong>Default Settings Loaded!</strong></p></div>';	
	} elseif ($_POST['theaction']=="info_update") {
		// update all values
		awm_update_option_values();
		echo '<div class="updated fade"><p><strong>Settings Updated!</strong></p></div>';
	} elseif ($_POST['theaction']=="generate_structure") {
		// first update all values, then generate the current tab's structure
		awm_update_option_values();
		$awm_str_code = AWM_create_menu_structure(get_option('AWM_selected_tab'));
		$awm_up_path = get_bloginfo('url') . get_option('AWM_menu_path');
		echo <<<STR
		<div class="updated fade"><p><strong>Menu Structure Code generated!</strong></p></div>
		<div style="background-color: #FEFCF5; border: #E6DB55 solid 1px;">
			<table>
				<tr><td style="width: 800px; text-align: center; padding-top: 10px; padding-bottom: 10px;"><strong>Menu Structure Code</strong></td></tr>
				<tr><td style="width: 800px; text-align: center; padding-top: 0px; padding-bottom: 10px;">
					<textarea cols="100" rows="10" id="loginfo" name="loginfo">$awm_str_code</textarea>
				</td></tr>
				
				<tr><td style="padding-left: 100px; width: 800px; text-align: left; padding-top: 10px; padding-bottom: 10px;">
					- Press <strong>Ctrl+C</strong> to copy the above code
					<br>- Switch to the AllWebMenus desktop application
					<br>- Open the <i>"Add-ins -> WordPress Menu -> Import/Update Menu Structure from WordPress"</i> form
					<br>- Paste the above copied "Menu Structure Code"
					<br>- Configure further your menu (styles, etc.) through the AllWebMenus properties
					<br>- Compile your menu from "Add-ins -> WordPress Menu -> Compile WordPress Menu"
					<br>- Make sure that you upload all compiled files in the <strong>$awm_up_path</strong> directory on your server
				</td></tr>
			</table>
		</div>
		<script type='text/javascript'>var t=document.getElementById('loginfo');t.select();t.focus();</script>
STR;
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
*/	} elseif ($_POST['theaction']=='hide_msg') {
		update_option('AWM_Check_show', FALSE);
	}
?>
	<style>
		.awm_itemInfo {
			font-style: italic;
			font-size: 11px;
			vertical-align: top;
			padding-bottom: 0px;
			padding-top: 0px;
		}
		.awm_tab_header {
			display: inline-block;
			border: #000066 solid 1px;
			background-color: #DDDDDD;
			height: 20px;
			cursor: pointer;
			padding: 5px;
			margin: 0px 5px;
		}
		.awm_tab_header_selected {
			display: inline-block;
			border-top: #000066 solid 1px;
			border-left: #000066 solid 1px;
			border-right: #000066 solid 1px;
			border-bottom: #FFFFFF solid 1px;
			background-color: #FFFFFF;
			height: 20px;
			cursor: pointer;
			padding: 5px;
			margin: 0px 5px;
		}
		.awm_tab_body {
			padding: 15px;
			margin: 0px;
			float: none;
			display: block;
			background-color: #FFFFFF;
			top: 0px;
		}
		#AWM_tabHeaders {
			z-index: 2;
			float: none;
			display: block;
			position: relative;
			top: 0px;
			margin: 0px;
			padding: 0px;
		}
		#AWM_tabBodies {
			z-index: 1;
			float: none;
			display: block;
			position: relative;
			border: #000066 solid;
			border-width: 1px;
			top: -1px;
			margin: 0px;
			padding: 0px;
		}
		#AWM_tab_wrapper {
			position: relative;
			margin: 0px;
			padding: 0px;
			border: none;
		}
		.AWM_section {
			padding: 5px;
			background-color: #CCDDFF;
			font-weight: bold;
		}
	</style>
	<script type="text/javascript">
	<!--
	function awm_show_tab(x) {
		for (i=0; i<<?php echo $awm_total_tabs; ?>; i++) {
			document.getElementById('AWM_tab_body_'+i).style.display="none";
			document.getElementById('AWM_tab_header_'+i).className = "awm_tab_header";
		}
		document.getElementById('AWM_tab_header_'+x).className = "awm_tab_header_selected";
		document.getElementById('AWM_tab_body_'+x).style.display="";
		document.getElementById('AWM_selected_tab').value = x;
	}
	function awm_select_menu_type(x,t) {
		document.getElementById('AWM_menu_type_'+t+'_Dynamic_info').style.display="none";
		document.getElementById('AWM_menu_type_'+t+'_Mixed_info').style.display="none";
		document.getElementById('AWM_menu_type_'+t+'_Static_info').style.display="none";
		document.getElementById('AWM_menu_type_'+t+'_'+x+'_info').style.display="";
		document.getElementById('AWM_menu_type_'+t+'_'+x).checked=true;
		if (x != document.getElementById('awm_initial_menu_type_'+t).value) {
			document.getElementById('awm_changed_type_a_'+t).style.display = "";
			document.getElementById('awm_changed_type_b_'+t).style.display = "";
		} else {
			document.getElementById('awm_changed_type_a_'+t).style.display = "none";
			document.getElementById('awm_changed_type_b_'+t).style.display = "none";
		}
	}
	function awm_uncheck(x) {
		if (document.getElementById('AWM_menu_active_'+x).checked) {
			document.getElementById('AWM_unchecked_'+x).style.color="#009900";
			document.getElementById('AWM_unchecked_'+x).innerHTML = "(this menu will appear in your blog)";
		} else {
			document.getElementById('AWM_unchecked_'+x).style.color="#990000";
			document.getElementById('AWM_unchecked_'+x).innerHTML = "Unchecked! (this menu will not appear in your blog)";
		}
	}
	function awm_set_path() {
		document.getElementById('AWM_the_path').innerHTML = document.getElementById('AWM_menu_path').value;
	}
	function awm_folder_info(x,t) {
		document.getElementById('AWM_folder_info_'+t).style.display = x;
	}
	-->
	</script>
	
		<p>For information and updates, please visit: 
		<a href="http://www.likno.com/addins/wordpress-menu.html">http://www.likno.com/addins/wordpress-menu.html</a></p>
		<?php
			/* Display a message in the Options page if the menu version is outdated
			NOTE: we do not check date as we have to recheck to display the message to the admin */
			if (get_option('AWM_Check_show')) {
				$AWM_buildText = AWM_check();
				if ($AWM_buildText != '') echo $AWM_buildText;
				else update_option('AWM_Check_show', FALSE);
			}
		?>
	</span>
	<br><br>
<form method="post" id="theform" name="theform" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input id="AWM_selected_tab" name="AWM_selected_tab" type="hidden" value="<?php echo get_option('AWM_selected_tab');?>"/>
	<table>
		<tr><td width="250"><strong>Online folder for menu files:</strong></td>
		<td><input id="AWM_menu_path" name="AWM_menu_path" onkeyup="awm_set_path();" onchange="awm_set_path();" type="text" size="30" value="<?php echo get_option('AWM_menu_path'); ?>"/>&nbsp;&nbsp;(relative to blog's root folder)</td></tr>
		<tr><td width="250">&nbsp;</td>
		<td class="awm_itemInfo">
			Every time you compile your local AllWebMenus project you should upload the compiled menu files at this folder.
			<br><?php echo get_bloginfo('url');?><span id="AWM_the_path"><?php echo get_option('AWM_menu_path');?></span>
			<br>(note: you need to create this online folder yourself)
		</td></tr>
	</table>
	
	<div class="submit" style="text-align: right;">
		<input type="button" name="info_update" value="Save settings &raquo;" onclick="theform.theaction.value='info_update'; theform.submit();"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="generate_structure" value="Save settings &amp; Generate Menu Structure Code &raquo;" onclick="theform.theaction.value='generate_structure'; theform.submit();"/>
	</div>
	<div id="AWM_tab_wrapper">
		<div id="AWM_tabHeaders">
<?php 
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
		echo "<div class='awm_tab_header' style='color: #".(get_option('AWM_menu_active_'.$awm_t)?"0099":"9900")."00;' id='AWM_tab_header_$awm_t' onclick='awm_show_tab($awm_t)'>".get_option('AWM_menu_name_'.$awm_t)."</div>";
	}
?>
		</div>
		<div id="AWM_tabBodies">
<?php 
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
?>
			<div class='awm_tab_body' id='AWM_tab_body_<?php echo $awm_t;?>'>
				<div><input id="AWM_menu_active_<?php echo $awm_t;?>" name="AWM_menu_active_<?php echo $awm_t;?>" onclick="awm_uncheck(<?php echo $awm_t;?>);" type="checkbox" value="true" <?php if (get_option('AWM_menu_active_'.$awm_t)) echo "checked='checked'"; ?> /> <strong>Show "<?php echo get_option('AWM_menu_name_'.$awm_t);?>" in blog</strong>
				&nbsp;&nbsp;&nbsp;<?php if (!get_option('AWM_menu_active_'.$awm_t)) { ?><span id='AWM_unchecked_<?php echo $awm_t;?>' style='color:#990000;'>Unchecked! (this menu will not appear in your blog)</span><?php } else { ?><span id='AWM_unchecked_<?php echo $awm_t;?>' style='color:#009900;'>(this menu will appear in your blog)</span><?php } ?></div>
				<div style="padding-left: 19px; margin-top: 13px;"><strong>Menu name: </strong> <input name="AWM_menu_name_<?php echo $awm_t;?>" type="text" size="30" value="<?php echo get_option('AWM_menu_name_'.$awm_t) ?>"/></div>
				<div style="padding-left: 19px;" class="awm_itemInfo">Please make sure that the "Menu name" value matches the value in the "Compiled Menu Name" property of the AllWebMenus project file (<i>Tools > Project Properties > Folders</i>). <a href="javascript:void(0)" onclick="awm_folder_info('',<?php echo $awm_t;?>);">show me</a></div>
				<div id="AWM_folder_info_<?php echo $awm_t;?>" style="margin-top: 20px; display: none; background-color: #FEFCF5; border: #E6DB55 solid 1px;">
					<table>
						<tr><td style="width: 800px; text-align: center; padding-top: 10px; padding-bottom: 10px;"><strong>More info</strong> - <a href="javascript:void(0)" onclick="awm_folder_info('none',<?php echo $awm_t;?>);">close</a></td></tr>
						<tr><td style="width: 800px; text-align: center; padding-top: 0px; padding-bottom: 10px;">
							<img src="<?php echo get_bloginfo('url');?>/wp-content/plugins/allwebmenus-wordpress-menu-plugin/more_info.jpg" width="486" height="560" alt="More info" title="More info"/>
						</td></tr>
						<tr><td style="width: 800px; text-align: center; padding-top: 0px; padding-bottom: 10px;">
							<a href="javascript:void(0)" onclick="awm_folder_info('none',<?php echo $awm_t;?>);">close</a>
					</table>
				</div>
				<br>
				<fieldset class="options">
					<div class="AWM_section">Menu Structure</div>
					<p>Please select the items you want to include/exclude in your menu structure:</p>
					<table width="100%" height="auto" style="padding-left: 40px;">
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td width="230"><input name="AWM_include_home_<?php echo $awm_t;?>" type="checkbox" value="true" <?php if (get_option('AWM_include_home_'.$awm_t)) echo "checked='checked'"; ?> /> <strong>"Home"</strong></td>
						<td class="awm_itemInfo">A "Home" item that opens the blog's Home Page.</td></tr>

						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>

						<tr><td width="230"><input name="AWM_pages_<?php echo $awm_t;?>" type="checkbox" <?php if (get_option('AWM_pages_'.$awm_t)) echo "checked='checked'"; ?> /> <strong>Pages:</strong></td>
						<td><span style="color: #009900">Show all Pages</span> <span style="color: #990000">except the following:</span></td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_excluded_pages_<?php echo $awm_t;?>" type="text" size="55" value="<?php echo get_option('AWM_excluded_pages_'.$awm_t) ?>"/></td></tr>
						<tr><td width="230" align="right">&nbsp;</td>
						<td class="awm_itemInfo">Page IDs, separated by commas (their sub-pages will also be excluded). Example: 34, 59, 140</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_pages_ms_<?php echo $awm_t;?>" value="main" type="radio" <?php if (get_option('AWM_pages_ms_'.$awm_t) == 'main') echo "checked='checked'"; ?> />&nbsp;Show Pages as Main Menu items</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_pages_ms_<?php echo $awm_t;?>" value="sub" type="radio"<?php if (get_option('AWM_pages_ms_'.$awm_t) == 'sub') echo "checked='checked'"; ?> />&nbsp;Show a Main Menu item named <input name="AWM_pages_name_<?php echo $awm_t;?>" type="text" size="10" value="<?php echo get_option('AWM_pages_name_'.$awm_t) ?>"/>
						and show Pages as its submenu items</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						
						<tr><td width="230"><input name="AWM_posts_<?php echo $awm_t;?>" type="checkbox" <?php if (get_option('AWM_posts_'.$awm_t)) echo "checked='checked'"; ?> /> <strong>Posts:</strong></td>
						<td><span style="color: #009900">Show the following Posts:</span></i></td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_posts_ids_<?php echo $awm_t;?>" type="text" size="55" value="<?php echo get_option('AWM_posts_ids_'.$awm_t) ?>"/></td></tr>
						<tr><td width="230" align="right">&nbsp;</td>
						<td class="awm_itemInfo">Post IDs, separated by commas. Example: 34, 59, 140</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_posts_ms_<?php echo $awm_t;?>" value="main" type="radio" <?php if (get_option('AWM_posts_ms_'.$awm_t) == 'main') echo "checked='checked'"; ?> />&nbsp;Show Posts as Main Menu items</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_posts_ms_<?php echo $awm_t;?>" value="sub" type="radio"<?php if (get_option('AWM_posts_ms_'.$awm_t) == 'sub') echo "checked='checked'"; ?> />&nbsp;Show a Main Menu item named <input name="AWM_posts_name_<?php echo $awm_t;?>" type="text" size="10" value="<?php echo get_option('AWM_posts_name_'.$awm_t) ?>"/>
						and show Posts as its submenu items</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						
						<tr><td width="230"><input name="AWM_categories_<?php echo $awm_t;?>" type="checkbox" <?php if (get_option('AWM_categories_'.$awm_t)) echo "checked='checked'"; ?> /> <strong>Categories:</strong></td>
						<td><span style="color: #009900">Show all Categories</span> <span style="color: #990000">except the following:</span></td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_excluded_cats_<?php echo $awm_t;?>" type="text" size="55" value="<?php echo get_option('AWM_excluded_cats_'.$awm_t) ?>"/></td></tr>
						<tr><td width="230" align="right">&nbsp;</td>
						<td class="awm_itemInfo">Category IDs, separated by commas (their sub-categories will also be excluded). Example: 34, 59, 140</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_categories_subitems_<?php echo $awm_t;?>" type="checkbox" <?php if (get_option('AWM_categories_subitems_'.$awm_t)) echo "checked='checked'"; ?> /> Also show (up to) the <input name="AWM_categories_subitems_no_<?php echo $awm_t;?>" type="text" size="2" value="<?php echo get_option('AWM_categories_subitems_no_'.$awm_t) ?>"/> newest posts of each Category as its submenu items</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td class="awm_itemInfo">Value must be between 1 and 50.</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_categories_ms_<?php echo $awm_t;?>" value="main" type="radio" <?php if (get_option('AWM_categories_ms_'.$awm_t) == 'main') echo "checked='checked'"; ?> />&nbsp;Show Categories as Main Menu items</td></tr>
						<tr><td width="230">&nbsp;&nbsp;</td>
						<td><input name="AWM_categories_ms_<?php echo $awm_t;?>" value="sub" type="radio" <?php if (get_option('AWM_categories_ms_'.$awm_t) == 'sub') echo "checked='checked'"; ?> />&nbsp;Show a Main Menu item named <input name="AWM_categories_name_<?php echo $awm_t;?>" type="text" size="10" value="<?php echo get_option('AWM_categories_name_'.$awm_t) ?>"/>
						and show Categories as its submenu items</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						
						<tr><td width="230"><span style="margin-left: 20px;"><strong>Other:</strong></span></td><td>
							<input type="checkbox" name="AWM_hide_future_<?php echo $awm_t;?>" value="checkbox" <?php if (get_option('AWM_hide_future_'.$awm_t)) echo "checked='checked'"; ?>/> Hide future-dated posts
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="AWM_hide_protected_<?php echo $awm_t;?>" value="checkbox" <?php if (get_option('AWM_hide_protected_'.$awm_t)) echo "checked='checked'"; ?>/> Hide password-protected items
						</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					</table>
					
					<div class="AWM_section">Menu Type</div>
					<p>Please select how you want your menu to behave:</p>
					<input type="hidden" id="awm_initial_menu_type_<?php echo $awm_t;?>" value="<?php echo get_option('AWM_menu_type_'.$awm_t);?>"/>
					<table width="100%" height="auto" style="padding-left: 40px;">
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2" id="awm_changed_type_a_<?php echo $awm_t;?>" class="updated fade" style="display: none;">
								<span style="color: #990000;">&quot;Menu Type&quot; changed:</span> New behavior will take effect only when you perform the "Save settings & Generate Menu Structure Code" action and re-import to AllWebMenus.
						</td></tr>
						<tr><td colspan="2" id="awm_changed_type_b_<?php echo $awm_t;?>">&nbsp;</td></tr>
						
						<tr><td width="230" valign="top"><table>
							<tr><td><span style="cursor: pointer;" onclick="awm_select_menu_type('Dynamic',<?php echo $awm_t;?>);"><strong><input id="AWM_menu_type_<?php echo $awm_t;?>_Dynamic" name="AWM_menu_type_<?php echo $awm_t;?>" value="Dynamic" type="radio" <?php if (get_option('AWM_menu_type_'.$awm_t) == 'Dynamic') echo "checked='checked'"; ?> />&nbsp;"Dynamic" Menu Type</strong></span></td></tr>
							<tr><td><span style="cursor: pointer;" onclick="awm_select_menu_type('Mixed',<?php echo $awm_t;?>);"><strong><input id="AWM_menu_type_<?php echo $awm_t;?>_Mixed" name="AWM_menu_type_<?php echo $awm_t;?>" value="Mixed" type="radio" <?php if (get_option('AWM_menu_type_'.$awm_t) == 'Mixed') echo "checked='checked'"; ?> />&nbsp;"Mixed" Menu Type</strong></span></td></tr>
							<tr><td><span style="cursor: pointer;" onclick="awm_select_menu_type('Static',<?php echo $awm_t;?>);"><strong><input id="AWM_menu_type_<?php echo $awm_t;?>_Static" name="AWM_menu_type_<?php echo $awm_t;?>" value="Static" type="radio" <?php if (get_option('AWM_menu_type_'.$awm_t) == 'Static') echo "checked='checked'"; ?> />&nbsp;"Static" Menu Type</strong></span></td></tr>
						</table></td><td valign="top">
							<div class="awm_itemInfo" id="AWM_menu_type_<?php echo $awm_t;?>_Dynamic_info" <?php echo get_option('AWM_menu_type_'.$awm_t)=='Dynamic'?'':'style="display:none;"'; ?>>
								<p style="margin-top: 0px; padding-top: 0px;">You have selected to create a menu structure of "Dynamic Type".</p>
								<p>This means that the menu items in AllWebMenus will only be used for preview/styling purposes.</p>
								<p>In your actual blog these items will be ignored and the menu will be populated "dynamically" based on the plugin settings.</p>
								<p>The styles in AllWebMenus Style Editor will be used to form the actual menu items.</p>
								<p><a href="http://www.likno.com/blog/wordpress-javascript-menu/1184/" target="_blank">View short video explaining all your settings</a></p>
							</div>
							<div class="awm_itemInfo" id="AWM_menu_type_<?php echo $awm_t;?>_Mixed_info" <?php echo get_option('AWM_menu_type_'.$awm_t)=='Mixed'?'':'style="display:none;"'; ?>>
								<p style="margin-top: 0px; padding-top: 0px;">You have selected to create a menu structure of "Mixed Type".</p>
								<p>This means that your menu will contain both the items you create within AllWebMenus ("static") and the items you import from WordPress ("dynamic").</p>
								<p>The imported Wordpress items will use the styles of the AllWebMenus Style Editor but their actual content will be populated "dynamically" based on the plugin settings.</p>
								<p>The static items you create within AllWebMenus will be shown as is.</p>
								<p><a href="http://www.likno.com/blog/wordpress-javascript-menu/1184/" target="_blank">View short video explaining all your settings</a></p>
							</div>
							<div class="awm_itemInfo" id="AWM_menu_type_<?php echo $awm_t;?>_Static_info" <?php echo get_option('AWM_menu_type_'.$awm_t)=='Static'?'':'style="display:none;"'; ?>>
								<p style="margin-top: 0px; padding-top: 0px;">You have selected to create a menu structure of "Static Type".</p>
								<p>Your menu will be edited (addition/removal/customization of items) within AllWebMenus only.</p>
								<p>Any changes on your online blog will not affect its items until you perform the "Save settings & Generate Menu Structure Code" action and <strong>re-import</strong> to AllWebMenus.</p>
								<p>This allows for maximum customization, as your online menu will show all items and styles customized within AllWebMenus.</p>
								<p><a href="http://www.likno.com/blog/wordpress-javascript-menu/1184/" target="_blank">View short video explaining all your settings</a></p>
							</div>
						</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					</table>
					
					<div class="AWM_section">Collaboration with external plugins</div>
					<table width="100%" height="auto" style="padding-left: 40px;">
						<tr><td colspan="2">&nbsp;</td></tr>
						
						<tr><td width="230" valign="top"><strong>Related Posts (YARPP):</strong>
						<?php if (!$awm_is_yarpp_enabled) echo "<br><span class='awm_itemInfo' style='color:#990000;'>Currently not installed and activated</span>";?>
						<br><span class="awm_itemInfo"><a href="http://wordpress.org/extend/plugins/yet-another-related-posts-plugin/" target="_blank">get it here</a></span></td>
						<td valign="top"><input type="checkbox" name="AWM_Related_<?php echo $awm_t;?>" value="checkbox" <?php if (get_option('AWM_Related_'.$awm_t)) echo "checked='checked'"; if (!$awm_is_yarpp_enabled) echo "disabled='disabled'"; ?>/>
						Show a "Related Posts" item when viewing a post</td>
						<tr><td width="230" valign="top">&nbsp;</td>
						<td class="awm_itemInfo">
							<?php if ($awm_is_yarpp_enabled) { ?>
								<p style="margin-top: 0px; padding-top: 0px;">This feature uses the 3rd-party "Related Posts (YARPP)" plugin, which needs to be installed separately.</p>
								<p>It dynamically adds a "Related Posts" item at the end of the Main Menu, with a submenu that contains posts related to the post you are currently viewing, regardless of the menu type you have selected.</p>
								<p>The "Related Posts" item appears only when viewing a single post.</p>
							<?php } else { ?>
								<p style="margin-top: 0px; padding-top: 0px;"><span style="color: #990000;">Note!</span> It seems that the "Related Posts (YARPP)" Plugin is not installed and activated so this option is disabled.</p>
								<p>This feature uses the 3rd-party "Related Posts (YARPP)" plugin, which needs to be installed separately.</p>
								<p>It dynamically adds a "Related Posts" item at the end of the Main Menu, with a submenu that contains posts related to the post you are currently viewing, regardless of the menu type you have selected.</p>
								<p>The "Related Posts" item appears only when viewing a single post.</p>
							<?php } ?>
						</td></tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					</table>
				</fieldset>
			</div>
<?php
	}	// end of tab body for loop
?>
		</div>	<!-- tab_bodies -->
	</div>	<!-- tab_wrapper -->
	
	<br>
	<br>
	<div style="text-align: center;" class="awm_itemInfo"><span style="color:#990000;">Note:</span> Always click the "Save settings" button below to apply your changes. If you leave this page without saving you will lose your unsaved changes.</div>
	<div class="submit" style="text-align: center;">
		<input type="button" name="info_update" value="Save settings &raquo;" onclick="theform.theaction.value='info_update'; theform.submit();"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="generate_structure" value="Save settings &amp; Generate Menu Structure Code &raquo;" onclick="theform.theaction.value='generate_structure'; theform.submit();"/>
	</div>
	
	<input type="hidden" name="theaction" value="" />
	
	<script>
		awm_show_tab(<?php echo get_option('AWM_selected_tab'); ?>);
	</script>
	
</form>
</div>	
<?php

}	// END of AWM_options_page()



function AWM_generate_linking_code() {
	global $awm_total_tabs, $awm_is_yarpp_enabled;
	
	for ($awm_t=0; $awm_t<$awm_total_tabs; $awm_t++) {
		if (!get_option('AWM_menu_active_'.$awm_t)) continue;
		
		$awm_name = get_option('AWM_menu_name_'.$awm_t);
		echo "<!-- ******** BEGIN ALLWEBMENUS CODE FOR " . $awm_name . " ******** -->\n";
		echo "<script type='text/javascript'>var MenuLinkedBy='AllWebMenus [4]',awmMenuName='" . $awm_name . "',awmBN='WP';awmAltUrl='';</script>\n";
		echo "<script charset='UTF-8' src='" . get_bloginfo('url') . get_option('AWM_menu_path') . $awm_name . ".js' type='text/javascript'></script>\n";
		echo "<script type='text/javascript'>if (typeof(" . $awm_name . ")!='undefined') awmBuildMenu();\n";
		echo "<!-- -------  Add your Server-Side code right after this comment  ---------- -->\n";
		
		
		if (get_option('AWM_menu_type_'.$awm_t)=='Dynamic') {
			AWM_create_dynamic_menu($awm_t,false);
		} elseif (get_option('AWM_menu_type_'.$awm_t)=='Mixed') {
			AWM_create_dynamic_menu($awm_t,true);
		}
		$awm_parentgroup = "wpgroup";
		
		// only if we are viewing a single post
		if ($awm_is_yarpp_enabled && get_option('AWM_Related_'.$awm_t) && is_single()) {
			// convert quotes to add code to item's <Text> property
			$awm_related = related_posts('', false);
			$awm_related = str_replace('"', "'", $awm_related);
			$awm_related = str_replace("'", "\'", $awm_related);
			$awm_related = ereg_replace(chr(10), "", $awm_related);
			$awm_related = ereg_replace(chr(13), "", $awm_related);
			$awm_related = str_replace('\n', '', $awm_related);
			$awm_related = str_replace('\r', '', $awm_related);
			$awm_related = trim($awm_related);
			
			echo "IRP=$awm_parentgroup.newItem('style=". $awm_name ."_'+(wplevel==0?'main_item_style':'sub_item_style')+';visible=0');\n";
			echo "IRP.visible=1; IRP.text0='Related Posts'; IRP.text1='Related Posts'; IRP.text2='Related Posts';\n";
			echo "IRP1=IRP.newGroup('style=". $awm_name ."_'+(wplevel==0?'sub_group_style':'sub_group_plus_style'));\n";
			echo "IRP2=IRP1.newItem('style=". $awm_name ."_'+(wplevel==0?'sub_item_style':'sub_item_plus_style')+';text0=" . $awm_related . ";htmlMode=1;');\n";
		}
		echo "if (typeof(" . $awm_name . ")!='undefined') ProduceMenu(" . $awm_name . ");\n";
		echo "</script>\n";
		echo "<!-- ******** END ALLWEBMENUS CODE FOR " . $awm_name . " ******** -->\n\n";
	}
}



/* 
 * Initialize query var for sitemap permalinks
 */
function AWM_query_vars ( $awm_vars ) {
	$awm_vars[] = "pg";
	return $awm_vars;
}



//add_filter('query_vars', 'AWM_query_vars');

add_action('admin_menu', 'AWM_add_option_pages');

?>