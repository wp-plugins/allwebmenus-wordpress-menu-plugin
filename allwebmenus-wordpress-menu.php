<?php

/*
Plugin Name: AllWebMenus-WordPress-Menu-Plugin
Plugin URI: www.likno.com/addins/wordpress-menu.html
Description: WordPress plugin for the AllWebMenus PRO DHTML Menu Maker - Create stylish menus
Version: 1.0.2
Author: Likno Software
*/

/*

NOTE:

This plugin is licensed under the GNU General Public License (GPL).

As such, you may use the source code of this plugin as you wish.
This plugin is used as a bridge to a non-GPL licensed software (AllWebMenus PRO) that is a property of Likno Software.

The license of AllWebMenus PRO states that a WordPress Menu (a menu which structure is retrieved using this plugin and compiled with AllWebMenus PRO using the WordPress Add-In) can be used in **one single domain**.

Thus, the part of the code below that confirms that the menu is used only in one domain **cannot** be changed/removed.

*/

$AWM_ver = '1.0.2';

/* 
 * Set up options if they do not exist
 */
add_option('AWM_include_home', '');
add_option('AWM_pages', TRUE);
add_option('AWM_pages_ms', 'main');
add_option('AWM_posts', FALSE);
add_option('AWM_posts_name', 'Posts');
add_option('AWM_which_posts','');
add_option('AWM_posts_ids', '');
add_option('AWM_categories', TRUE);
add_option('AWM_categories_ms', 'sub');
add_option('AWM_which_categories','');
add_option('AWM_archives', FALSE);
add_option('AWM_hide_future', FALSE); 
add_option('AWM_new_window', FALSE); 
add_option('AWM_show_post_date', FALSE); 
add_option('AWM_date_format', 'F jS, Y'); 
add_option('AWM_hide_protected', TRUE); 
add_option('AWM_excluded_cats', ''); 
add_option('AWM_excluded_pages', ''); 

add_option('AWM_menu_path', '/menu');
add_option('AWM_menu_name', '/menu.js');

add_option('AWM_YARPP', TRUE);
add_option('AWM_Related', TRUE);
add_option('AWM_Related_color', '');

add_option('AWM_Checked', FALSE);
add_option('AWM_Checked_Date', '00');

/*
 * Load definitions
 */
include ABSPATH . 'wp-content/plugins/allwebmenus-wordpress-menu-plugin/definitions.php';

if (in_array('yet-another-related-posts-plugin/yarpp.php', get_option('active_plugins'))) {
	
	/* to be changed in update of YARPP! */
	update_option('AWM_Related', FALSE);

} else {	
	
	if (get_option('AWM_YARPP'))
		include('yet-another-related-posts-plugin/yarpp.php');
	
}

/* Update YARPP's auto_display option */
if (get_option('AWM_Related'))
	update_option('yarpp_auto_display', 0);
else
	if (in_array('yet-another-related-posts-plugin/yarpp.php', get_option('active_plugins')))
		update_option('yarpp_auto_display', 1);


/* 
 * Add options page
 */
function AWM_add_option_pages() {
	if (function_exists('add_options_page')) {
		add_options_page('AllWebMenus WordPress Menu Plugin', 'AllWebMenus-WP-Menu', 8, __FILE__, 'AWM_options_page');
	}
}

$url="http://www.likno.com/addins/wordpress-check.php";
$AWM_buildText = '';


function geturl($buildNo, $HashNo, $params)
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



/* This code checks for updated versions of the AllWebMenus software and informs the user if necessary */
function AWM_check()
{	
	global $url;
	try {
		/* get build number from menu file */
		$menufile = fopen(dirname(__FILE__) .'/../../..'. get_option('AWM_menu_path') . get_option('AWM_menu_name'), 'r');
		$mfile = fread($menufile, filesize(dirname(__FILE__) .'/../../..'. get_option('AWM_menu_path') . get_option('AWM_menu_name')));
		$bNo = explode('awmLibraryBuild=', $mfile);
		$bNo = explode(';', $bNo[1]);
		$buildNo = $bNo[0];
		$hNo = explode('awmHash=\'', $mfile);
		$hNo = explode('\'', $hNo[1]);
		$HashNo = $hNo[0];
		
		$params = "build=$buildNo&hash=$HashNo&rand=". rand(1,10000) ."&domain=". get_bloginfo('url');

		if (function_exists('curl_init')) {

			try {
				$awm_tmp = geturl($buildNo, $HashNo, $params);
				
			} catch (Exception $e) {
				return "Caught exception: ".  $e->getMessage(). " while retrieving version information. Please <a href='mailto:support@likno.com?subject=WordPress: Error while retrieving version info'>contact Likno</a> for more information.";
			}
			if ($awm_tmp === '')
				$AWM_Text = '';
				
			else {
				$AWM_Text = '<div name="antoyan" id="message1" class="updated fade"><table><tr><td><p><strong>';
				$AWM_Text .= $awm_tmp;
				$AWM_Text .= '</strong></p></td><td width="200px" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="AWM_hide_msg" value="Hide" /></td></tr></table></div>';
			}
				
		} else {
			
			$AWM_Text = '';
			
		}
	
		update_option('AWM_Checked', TRUE);
		update_option('AWM_Checked_Date', date(d));
		
	}
	catch (Exception $e) {
		return "Caught exception: ".  $e->getMessage(). " while reading file ". dirname(__FILE__) .'/../../..'. get_option('AWM_menu_path') . get_option('AWM_menu_name');
	}

	return $AWM_Text;
}	
	
if (((get_option('AWM_Checked_Date') <= (date(d) - 15)) || (get_option('AWM_Checked_Date') === '00')) && file_exists(dirname(__FILE__) .'/../../..'. get_option('AWM_menu_path') . get_option('AWM_menu_name'))) {
		
	$AWM_buildText = AWM_check();

	update_option('AWM_Check_Show', TRUE);

}


/* 
 * Generate options page
 */
function AWM_options_page() {

	global $AWM_buildText;
	global $AWM_ver; ?>

	<script type="text/javascript">
	<!--
	function hide(id){
	    var t=document.getElementById(id);
	    if (t) t.style.display="none";
	}
	function show(id){
	    var t=document.getElementById(id);
	    if (t) t.style.display="block";
	}
	-->
	</script>

	<div style="max-width: 980px; margin-left: 15px;">

	<span class="wrap">
	<h2>AllWebMenus WordPress Menu Plugin v<?php echo $AWM_ver; ?></h2>

	<?php
	if (isset($_POST['set_defaults'])) {
		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('AWM_which_menu', '1');

		update_option('AWM_include_home', TRUE);
		update_option('AWM_pages', TRUE);
		update_option('AWM_pages_ms', 'main');
		update_option('AWM_posts', FALSE);
		update_option('AWM_posts_' . $nnnawm, 'Posts');
		update_option('AWM_posts_ids', '');
		update_option('AWM_categories', TRUE);
		update_option('AWM_categories_ms', 'sub');
		update_option('AWM_archives', FALSE);
		update_option('AWM_hide_future', FALSE); 
		update_option('AWM_new_window', FALSE); 
		update_option('AWM_show_post_date', FALSE); 
		update_option('AWM_date_format', 'F jS, Y'); 
		update_option('AWM_hide_protected', TRUE); 
		update_option('AWM_excluded_cats', ''); 
		update_option('AWM_excluded_pages', '');

		update_option('AWM_menu_path', '/menu');
		update_option('AWM_menu_name', '/menu.js');

		update_option('AWM_Related', TRUE);
		update_option('AWM_Related_color', '');
		

		echo AWM_DEFAULTS_LOADED;
		echo '</strong></p></div>';	

	} else if (isset($_POST['info_update'])) {

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('AWM_which_menu', (string) $_POST["AWM_which_menu"]);

		update_option('AWM_include_home', (string) $_POST["AWM_include_home"]);
		update_option('AWM_pages', (string) $_POST["AWM_pages"]);
		update_option('AWM_pages_ms', (string) $_POST["AWM_pages_ms"]);
		update_option('AWM_posts', (string) $_POST["AWM_posts"]);
		update_option('AWM_posts_' .$nnn, (string) $_POST["AWM_posts_name"]);
		update_option('AWM_posts_ids', (string) $_POST["AWM_posts_ids"]);
		update_option('AWM_categories', (string) $_POST["AWM_categories"]);
		update_option('AWM_categories_ms', (string) $_POST["AWM_categories_ms"]);
		update_option('AWM_archives', (string) $_POST["AWM_archives"]);
		update_option('AWM_hide_future', (bool) $_POST["AWM_hide_future"]);	
		update_option('AWM_new_window', (bool) $_POST["AWM_new_window"]);	
		update_option('AWM_show_post_date', (bool) $_POST["AWM_show_post_date"]);	
		update_option('AWM_date_format', (string) $_POST["AWM_date_format"]);	
		update_option('AWM_hide_protected', (bool) $_POST["AWM_hide_protected"]);	
		update_option('AWM_excluded_cats', (string) $_POST["AWM_excluded_cats"]);	
		update_option('AWM_excluded_pages', (string) $_POST["AWM_excluded_pages"]);	


		update_option('AWM_menu_path', (string) $_POST["AWM_menu_path"]);
		if ((strpos(get_option('AWM_menu_path'), "/") != 0) || (strpos(get_option('AWM_menu_path'), "/") === FALSE))
			update_option('AWM_menu_path', (string) "/" . get_option('AWM_menu_path'));
		
		update_option('AWM_menu_name', (string) $_POST["AWM_menu_name"]);
		if ((strpos(get_option('AWM_menu_name'), "/") != 0) || (strpos(get_option('AWM_menu_name'), "/") === FALSE))
			update_option('AWM_menu_name', (string) "/" . get_option('AWM_menu_name'));
			
		if (!strpos(get_option('AWM_menu_name'), ".js"))
			update_option('AWM_menu_name', (string) get_option('AWM_menu_name') . ".js");

		update_option('AWM_Related', (bool) $_POST["AWM_Related"]);
		update_option('AWM_Related_color', (string) $_POST["AWM_Related_color"]);

		echo AWM_CONFIG_UPDATED;
	    echo '</strong></p></div>';

	} else if (isset($_POST['AWM_create'])) {
	
		echo '<div id="message" class="updated fade"><p><strong>';
		
		update_option('AWM_which_menu', (string) $_POST["AWM_which_menu"]);

		update_option('AWM_include_home', (string) $_POST["AWM_include_home"]);
		update_option('AWM_pages', (string) $_POST["AWM_pages"]);
		update_option('AWM_pages_ms', (string) $_POST["AWM_pages_ms"]);
		update_option('AWM_posts', (string) $_POST["AWM_posts"]);
		update_option('AWM_posts_'.$nnn, (string) $_POST["AWM_posts_name"]);
		update_option('AWM_posts_ids', (string) $_POST["AWM_posts_ids"]);
		update_option('AWM_categories', (string) $_POST["AWM_categories"]);
		update_option('AWM_categories_ms', (string) $_POST["AWM_categories_ms"]);
		update_option('AWM_archives', (string) $_POST["AWM_archives"]);
		update_option('AWM_hide_future', (bool) $_POST["AWM_hide_future"]);	
		update_option('AWM_new_window', (bool) $_POST["AWM_new_window"]);	
		update_option('AWM_show_post_date', (bool) $_POST["AWM_show_post_date"]);	
		update_option('AWM_date_format', (string) $_POST["AWM_date_format"]);	
		update_option('AWM_hide_protected', (bool) $_POST["AWM_hide_protected"]);	
		update_option('AWM_excluded_cats', (string) $_POST["AWM_excluded_cats"]);	
		update_option('AWM_excluded_pages', (string) $_POST["AWM_excluded_pages"]);	

		
		update_option('AWM_menu_path', (string) $_POST["AWM_menu_path"]);
		if ((strpos(get_option('AWM_menu_path'), "/") != 0) || (strpos(get_option('AWM_menu_path'), "/") === FALSE))
			update_option('AWM_menu_path', (string) "/" . get_option('AWM_menu_path'));
		
		update_option('AWM_menu_name', (string) $_POST["AWM_menu_name"]);
		if ((strpos(get_option('AWM_menu_name'), "/") != 0) || (strpos(get_option('AWM_menu_name'), "/") === FALSE))
			update_option('AWM_menu_name', (string) "/" . get_option('AWM_menu_name'));
			
		if (!strpos(get_option('AWM_menu_name'), ".js"))
			update_option('AWM_menu_name', (string) get_option('AWM_menu_name') . ".js");

		update_option('AWM_Related', (bool) $_POST["AWM_Related"]);
		update_option('AWM_Related_color', (string) $_POST["AWM_Related_color"]);

		echo AWM_AWM_CREATED;
	    echo '</strong></p></div>';

		$output = AWM_create_menu();
		
		echo '<table border="2px" cellpadding="10px"><tr><td>'. $output[0] . $output[1] . $output[2] .'</td></tr></table>';
		
	} else if (!strcmp($_POST['AWM_YARPP'], 'Activate')) {
	
		echo '<div id="message" class="updated fade"><p><strong>';
		
		if (!in_array('yet-another-related-posts-plugin/yarpp.php', get_option('active_plugins'))) {
		
			echo "YARPP Activated!";
			include('yet-another-related-posts-plugin/yarpp.php');
			update_option('AWM_YARPP', TRUE);
			update_option('AWM_Related', TRUE);

		} else {
			echo "YARPP NOT Activated!</strong> In order to be able to use the YARPP functionality as an item of your menu, you have to <strong>DEACTIVATE</strong> the YARP plugin from the <i>Plugins</i> panel and then <strong>ACTIVATE</strong> it from this page (the relevant button below).<strong>";
		}

	    echo '</strong></p></div>';

	} else if (!strcmp($_POST['AWM_YARPP'], 'Deactivate')) {
	
		echo '<div id="message" class="updated fade"><p><strong>';
		
			echo "YARPP Dectivated!";
			update_option('AWM_YARPP', FALSE);
			update_option('AWM_Related', FALSE);

	    echo '</strong></p></div>';

	} else if (isset($_POST['AWM_hide_msg'])) {
	
		update_option('AWM_Check_show', FALSE);

	}	?>


	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

	<p><?php echo AWM_FOR_INFO; ?>
	<a href="http://www.likno.com/addins/wordpress-menu.html">http://www.likno.com/addins/wordpress-menu.html</a></p>

	<?php
	
		/* Display a message in the Options page if the menu version is outdated */
		if (get_option('AWM_Check_show')) {
			
			$AWM_buildText = AWM_check();

			if ($AWM_buildText != '')
				echo $AWM_buildText;
		}

	?>

	</span>
	
	<br>

	<br>

	<fieldset class="options"> 
	<legend><?php echo AWM_MENU_ITEMS; ?></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_SHOW; ?></th><td valign="top">
	<table width="100%" height="auto">
		<tr><td width="100%"><input name="AWM_include_home" type="checkbox" value="both" <?php if (get_option('AWM_include_home')) echo "checked='checked'"; ?> />&nbsp;&nbsp; <?php echo AWM_INCLUDEHOME; ?></td></tr>
	</table><br />
	
	<table width="100%" height="auto">
		<tr><td width="20%"><input name="AWM_pages" type="checkbox" <?php if (get_option('AWM_pages')) echo "checked='checked'"; ?> />&nbsp;&nbsp; <?php echo 'Pages'; ?> </td>
		<td width="80%"><input name="AWM_pages_ms" value="main" type="radio" <?php if (get_option('AWM_pages_ms') == 'main') echo "checked='checked'"; ?> />&nbsp;<?php echo 'Pages '. AWM_MAIN; ?> </td></tr>
		<tr><td width="20%">&nbsp;&nbsp;</td>
		<td width="80%"><input name="AWM_pages_ms" value="sub" type="radio"<?php if (get_option('AWM_pages_ms') == 'sub') echo "checked='checked'"; ?> />&nbsp;<?php echo '"Pages" ' . AWM_MAINORSUB . ' pages'; ?> </td></tr>
		
		<tr><td height="10px">&nbsp;&nbsp;</td>

		<tr><td width="20%"><input name="AWM_posts" type="checkbox" <?php if (get_option('AWM_posts')) echo "checked='checked'"; ?> />&nbsp;&nbsp; <?php echo 'Posts'; ?> </td>
		<td width="80%"><input name="AWM_posts_name" type="text" size="" value="<?php echo get_option('AWM_posts_name') ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Name of Main Item</td></tr>
		<tr><td width="20%">&nbsp;&nbsp;</td>
		<td width="80%">&nbsp;</td></tr>
		<tr><td width="20%">&nbsp;&nbsp;</td>
		<td width="80%"><input name="AWM_posts_ids" type="text" size="55" value="<?php echo get_option('AWM_posts_ids') ?>"/></td></tr>
		<tr><td width="20%">&nbsp;&nbsp;</td>
		<td width="80%"><?php echo AWM_POSTIDS; ?></td></tr>
		
		<tr><td height="10px">&nbsp;&nbsp;</td>

		<tr><td width="20%"><input name="AWM_categories" type="checkbox" <?php if (get_option('AWM_categories')) echo "checked='checked'"; ?> />&nbsp;&nbsp; <?php echo 'Categories'; ?> </td>
		<td width="80%"><input name="AWM_categories_ms" value="main" type="radio" <?php if (get_option('AWM_categories_ms') == 'main') echo "checked='checked'"; ?> />&nbsp;<?php echo 'Categories '. AWM_MAIN; ?></td></tr>
		<tr><td width="20%">&nbsp;&nbsp;</td>
		<td width="80%"><input name="AWM_categories_ms" value="sub" type="radio" <?php if (get_option('AWM_categories_ms') == 'sub') echo "checked='checked'"; ?> />&nbsp;<?php echo '"Categories" ' . AWM_MAINORSUB . ' categories'; ?></td></tr>
		
		<tr><td height="10px">&nbsp;&nbsp;</td>

	</table>
	</td></tr>

	</table>
	</fieldset>

	<br>

	<fieldset class="options"> 
	<legend><?php echo AWM_EXCLUSIONS; ?></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_EXCLUDED_PAGES; ?></th><td valign="top">
	<input name="AWM_excluded_pages" type="text" size="55" value="<?php echo get_option('AWM_excluded_pages') ?>"/><br />
	<?php echo AWM_EXCLUDED_PAGES_DESC; ?>
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_EXCLUDED_CATS; ?></th><td valign="top">
	<input name="AWM_excluded_cats" type="text" size="55" value="<?php echo get_option('AWM_excluded_cats') ?>"/><br />
	<?php echo AWM_EXCLUDED_CATS_DESC; ?>
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_HIDE_FUTURE; ?></th><td valign="top">
	<input type="checkbox" name="AWM_hide_future" value="checkbox" <?php if (get_option('AWM_hide_future')) echo "checked='checked'"; ?>/>
	</td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_HIDE_PASS; ?></th><td valign="top">
	<input type="checkbox" name="AWM_hide_protected" value="checkbox" <?php if (get_option('AWM_hide_protected')) echo "checked='checked'"; ?>/>
	</td></tr>

		<tr><td height="10px">&nbsp;&nbsp;</td>

	</table>
	</fieldset>

	<br>

	<fieldset class="options"> 
	<legend><?php echo AWM_LOCATION; ?></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_MENU_PATH; ?></th><td valign="top">
	<input name="AWM_menu_path" type="text" size="20" value="<?php echo get_option('AWM_menu_path') ?>"/> <br />
	<?php echo AWM_LOCATION_NF; ?></td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_MENU_NAME; ?></th><td valign="top">
	<input name="AWM_menu_name" type="text" size="20" value="<?php echo get_option('AWM_menu_name') ?>"/> <br />
	<br /><?php echo AWM_LOCATION_DEFAULT; ?></td></tr>

	</table>
	</fieldset>

	<br>

	<fieldset class="options"> 
	<legend><?php echo AWM_YARP; ?></legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="6">

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_YARPDESC; ?></th><td valign="top" width="15%">
	<input type="checkbox" name="AWM_Related" value="checkbox" <?php if (get_option('AWM_Related')) echo "checked='checked'"; if (!get_option('AWM_YARPP')) echo 'disabled="disabled"'; ?>/></td><td valign="top" width="35%"><?php if (!get_option('AWM_YARPP')) echo AWM_YARP_NA; else echo '&nbsp;'; ?></td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo AWM_YARPBGC; ?></th><td valign="top" width="15%">
	<input name="AWM_Related_color" type="text" value="<?php echo get_option('AWM_Related_color') ?>" <?php if (!get_option('AWM_YARPP')) echo 'disabled="disabled"'; ?> />
	</td><td valign="top" width="35%"><?php if (!get_option('AWM_YARPP')) echo AWM_YARP_NA; else echo '&nbsp;'; ?></td></tr>

	<tr><th width="45%" valign="top" align="right" scope="row"><?php echo 'Activate/Deactivate YARPP:'; ?></th><td valign="top" width="15%">
	<input type="submit" name="AWM_YARPP" value="<?php if (!get_option('AWM_YARPP')) echo 'Activate'; else echo 'Deactivate'; ?>" /></td><td valign="top" width="35%"><?php if (get_option('AWM_YARPP')) echo AWM_YARP_D; else echo AWM_YARP_A; ?></td></tr>

	</table>
	</fieldset>

	<div class="submit">
		<input type="submit" name="AWM_create" value="<?php echo AWM_CREATE_BUTTON; ?> &raquo;" />

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<input type="submit" name="info_update" value="<?php echo AWM_UPDATE_BUTTON; ?> &raquo;" />
		<input type="submit" name="set_defaults" value="<?php echo AWM_DEFAULT_BUTTON; ?> &raquo;" />
	</div>

	</form>
	
	</div>
	
	
<?php

}






/* 
 * Build list of categories
 */
function AWM_get_cats($cat_data, $cats, $num_cats, $cats_with_children, $excluded_cats, $parent = 0, $level = 0) {

	$k = 0;
	while (isset($cats[$k]) && ($cats[$k]->category_parent != $parent) && ($k < $num_cats)) {
		$k++;
	}

	while (isset($cats[$k]) && ($cats[$k]->category_parent == $parent) && ($k < $num_cats)) {

		if (in_array($cats[$k]->category_ID, $excluded_cats, FALSE) === FALSE) {
			$cat_data[] = array( 
				'id' => $cats[$k]->category_ID, 
				'title' => $cats[$k]->cat_name,
				'level' => $level
			);
		}

		if (count($cats_with_children) > 0) {
			if (in_array($cats[$k]->category_ID, $cats_with_children, FALSE)) {
				if (in_array($cats[$k]->category_ID, $excluded_cats, FALSE) === FALSE) {
					$cat_data = AWM_get_cats($cat_data, $cats, $num_cats, $cats_with_children, $excluded_cats, $cats[$k]->category_ID, $level + 1);
				}
			}
		}

		$k++;

	}

	return $cat_data;

}



/* 
 * Build list of pages
 */
function AWM_get_pages($page_data, $pages, $num_pages, $pages_with_children, $excluded_pages, $show_page_date, $parent = 0, $level = 0) {

	$k = 0;
	while (isset($pages[$k]) && ($pages[$k]->post_parent != $parent) && ($k < $num_pages)) {
		$k++;
	}
	while (isset($pages[$k]) && ($pages[$k]->post_parent == $parent) && ($k < $num_pages)) {

		if (in_array($pages[$k]->ID, $excluded_pages, FALSE) === FALSE) {

			$tmp_array = array();
			$tmp_array['id'] = $pages[$k]->ID;
			$tmp_array['title'] = $pages[$k]->post_title;
			$tmp_array['level'] = $level;
			if ($show_page_date) $tmp_array['date'] = $pages[$k]->post_date;
			$page_data[] = $tmp_array;
		}
	
		if (in_array($pages[$k]->ID, $pages_with_children, FALSE)) {
			if (in_array($pages[$k]->ID, $excluded_pages, FALSE) === FALSE) {
				$page_data = AWM_get_pages($page_data, $pages, $num_pages, $pages_with_children, $excluded_pages, $show_page_date, $pages[$k]->ID, $level + 1);
			}
		}

		$k++;

	}

	return $page_data;

}

/* 
 * Build list of posts
*/ 
function AWM_get_posts($post_data, $posts, $num_posts, $posts_ids, $show_post_date) {

	for ($c = 0; $c < $num_posts; $c++) {

		$any_posts = 0;


			if (in_array($posts[$c]->ID, $posts_ids, FALSE))
			{
				$tmp_array = array();
				$tmp_array['type'] = 'p';
				$tmp_array['id'] = $posts[$c]->ID;
				$tmp_array['title'] = $posts[$c]->post_title;
				$tmp_array['level'] = $level + 1;
				if ($show_post_date) $tmp_array['date'] = $posts[$c]->post_date;
				$post_data[] = $tmp_array;
			}

	
	}

	return $post_data;

}




/* 
 * Generate xml code
 */
function AWM_display_pages($page_data, $num_pages, $new_window, $show_page_date, $date_format, $pages_ms) {

	if ($num_pages == 0) "";

	$xml_out = '';
	
	if (!strcmp($pages_ms, "sub"))
	{
		$xml_out .= MAIN . ID . 'pages_0' . UNID . TITLE . 'Pages' . UNTITLE . LINK . UNLINK;
		$xml_out .= SUB;
		
	}


	for ($p = 0; $p < $num_pages; $p++) {

			$xml_out .= MAIN . ID . $page_data[$p]['id'] . UNID . TITLE . $page_data[$p]['title'] . UNTITLE . LINK . get_permalink($page_data[$p]['id']) . UNLINK . SUB . UNSUB . UNMAIN;
		
	}

	if (!strcmp($pages_ms, "sub"))
	{
		$xml_out .= UNSUB . UNMAIN;
		
	}

	return $xml_out;

}

function AWM_display_categories($cat_data, $num_cats, $new_window, $categories_ms) {

	if ($num_cats == 0) return "";

	$xml_out = '';
	
	if (!strcmp($categories_ms, "sub"))
	{
		$xml_out .= MAIN . ID . 'cat_0' . UNID . TITLE . 'Categories' . UNTITLE . LINK . UNLINK;
		$xml_out .= SUB;
		
	}

	for ($p = 0; $p < $num_cats; $p++) {

			$xml_out .= MAIN . ID . 'cat_' . $cat_data[$p]['id'] . UNID . TITLE . $cat_data[$p]['title'] . UNTITLE . LINK . get_category_link($cat_data[$p]['id']) . UNLINK . SUB . UNSUB . UNMAIN;
		
	}

	if (!strcmp($categories_ms, "sub"))
	{
		$xml_out .= UNSUB . UNMAIN;
		
	}

	return $xml_out;

}

function AWM_display_posts($post_data, $num_posts, $new_window, $show_post_date, $date_format, $post_date, $post_item_name) {

	if ($num_posts == 0) return "";

	$xml_out = '';
	
	$xml_out .= MAIN . ID . 'posts_0' . UNID . TITLE . $post_item_name . UNTITLE . LINK . UNLINK;
	$xml_out .= SUB;

	for ($p = 0; $p < $num_posts; $p++) {

			$xml_out .= MAIN . ID . $post_data[$p]['id'] . UNID . TITLE . $post_data[$p]['title'] . UNTITLE . LINK . get_permalink($post_data[$p]['id']) . UNLINK . SUB . UNSUB . UNMAIN;

	}

	$xml_out .= UNSUB . UNMAIN;

	return $xml_out;

}



/*
 * Remove categories with no posts
 */
function AWM_remove_empty_cats($post_data) {

	$llp = -1;
	$last_type = 'x';
	$last_level = 'x';
	$last_del = FALSE;

	$pdc = count($post_data);

	for ($i = $pdc - 1; $i >= 0; $i--) {

		$type = $post_data[$i]['type'];
		$title = $post_data[$i]['title'];
		$level = $post_data[$i]['level'];

		if (($type == 'c') && ($last_type == 'c') && (($last_level <= $level) || ($last_del == TRUE))) {
			$post_data[$i]['type'] = 'r';
			$last_del = TRUE;
		} else {
			$last_del = FALSE;
		}

		$last_type = $type;
		$last_level = $level;
		if ($post_data[$i]['type'] == 'p') {
			$llp = $post_data[$i]['level'];

		}

	}


	$new_post_data = array();
	foreach ($post_data as $pd) {
		if ($pd['type'] != 'r') {
			$new_post_data[] = $pd;
		}
	}


	return $new_post_data;

}




/* 
 * Create the menu
 */
function AWM_create_menu() {

	global $wpdb;

	$tp = $wpdb->prefix;

	// Currently using a work-around for the version system
	// determines if pre or post 2.3 from wp_term_taxonomy 

	$ver = 2.2;
	$wpv = $wpdb->get_results("show tables like '{$tp}term_taxonomy'");
	if (count($wpv) > 0) {
		$ver = 2.3;
	}



	$include_home = get_option('AWM_include_home');
	$show_pages = get_option('AWM_pages');
	$pages_ms = get_option('AWM_pages_ms');
	$show_posts = get_option('AWM_posts');
	$post_item_name = get_option('AWM_posts_name');
	$posts_ids = get_option('AWM_posts_ids');
	$show_categories = get_option('AWM_categories');
	$categories_ms = get_option('AWM_categories_ms');
	$show_archives = get_option('AWM_archives');
	$show_authors = get_option('AWM_authors');
	$hide_future = get_option('AWM_hide_future');
	$new_window = get_option('AWM_new_window');
	$show_post_date = get_option('AWM_show_post_date');
	$date_format = get_option('AWM_date_format');
	$hide_protected = get_option('AWM_hide_protected');
	$excluded_cats = get_option('AWM_excluded_cats');
	$excluded_pages = get_option('AWM_excluded_pages');
	
	$post_date = get_option('AWM_show_post_date');
	
	$related_posts = get_option('AWM_Related');
	
	// prepare exclusion lists
	$excluded_cats = str_replace(' ', '', $excluded_cats);
	$excluded_cats = (array)explode(',', $excluded_cats);
	$excluded_pages = str_replace(' ', '', $excluded_pages);
	$excluded_pages = (array)explode(',', $excluded_pages);

	// prepare post ids list
	$posts_ids = str_replace(' ', '', $posts_ids);
	$posts_ids = (array)explode(',', $posts_ids);


	if ($include_home) {
	
		$home['name'] = get_bloginfo('');
		$home['url'] = get_bloginfo('url');
		
	}
	
	/* get categories */
	if ($show_categories) {

		if ($ver < 2.3) {

			$cats = (array)$wpdb->get_results("
				SELECT cat_ID as category_ID, cat_name, category_parent
				FROM {$tp}categories
				GROUP BY cat_ID 
				ORDER BY category_parent, cat_name
			"); 
	
			$cats_with_children = (array)$wpdb->get_col("
				SELECT category_parent
				FROM {$tp}categories
				WHERE category_parent != '0' 
				GROUP BY category_parent
				ORDER BY category_parent
			", 0);

		} else { // >= 2.3

			$cats = (array)$wpdb->get_results("
				SELECT {$tp}terms.term_id as category_ID, 
					{$tp}terms.name as cat_name, 
					{$tp}term_taxonomy.parent as category_parent
				FROM {$tp}terms, {$tp}term_taxonomy 
				WHERE {$tp}term_taxonomy.taxonomy = 'category'
				AND {$tp}terms.term_id = {$tp}term_taxonomy.term_id
				GROUP BY category_ID 
				ORDER BY category_parent, cat_name
			"); 

			$cats_with_children = (array)$wpdb->get_col("
				SELECT parent as category_parent
				FROM {$tp}term_taxonomy
				WHERE parent != '0' 
				AND {$tp}term_taxonomy.taxonomy = 'category'
				GROUP BY category_parent
				ORDER BY category_parent
			", 0);

		}

		$num_cats = count($cats);

		$cat_data = array();

		$cat_data = AWM_get_cats($cat_data, $cats, $num_cats, $cats_with_children, $excluded_cats);

		$num_cats = count($cat_data);

	}
		
	/* get posts */
	if ($show_posts) {
		
		$sort_string = 'post_date DESC';

		$extra_data = '';
		if ($show_post_date) {
			$extra_data .= ', post_date ';
		}

		$dup_check = '';

		$pass_check = '';
		if ($hide_protected) {
			$pass_check = " AND post_password = '' ";
		}

		$future_check = '';
		if ($hide_future) {
			$future_check = " AND post_status != 'future' ";
		}


		$posts_to_display = '';
		for ($i=0; $i<sizeof($posts_ids); $i++)
			$posts_to_display = " AND posts.ID='".$posts_ids[$i]."'";
			

		if ($ver < 2.3) {

			$posts = (array)$wpdb->get_results("
				SELECT ID, category_id, post_title $extra_data
				FROM {$tp}posts, {$tp}post2cat
				WHERE {$tp}posts.ID = {$tp}post2cat.post_id 
				AND post_status = 'publish' 
				AND post_type = 'post' 
				$dup_check 
				$pass_check 
				$future_check
				$posts_to_display
				ORDER BY category_id, $sort_string
			");
		
		} else { // >= 2.3

			$posts = (array)$wpdb->get_results("
				SELECT ID, {$tp}term_taxonomy.term_id as category_id, post_title $extra_data
				FROM {$tp}posts, {$tp}term_relationships, {$tp}term_taxonomy
				WHERE {$tp}posts.ID = {$tp}term_relationships.object_id  
				AND {$tp}term_relationships.term_taxonomy_id = {$tp}term_taxonomy.term_taxonomy_id 
				AND {$tp}term_taxonomy.taxonomy = 'category' 
				AND post_status = 'publish' 
				AND post_type = 'post' 
				$dup_check 
				$pass_check 
				$future_check
				ORDER BY category_id, $sort_string
			");

		}


		$num_posts = count($posts);

		$post_data = array();	

		$post_data = AWM_get_posts($post_data, $posts, $num_posts, $posts_ids, $show_post_date);

		$num_posts = count($post_data);

	}



	if ($show_pages) { // show pages

		$sort_string = 'post_date DESC';
/*		switch ($page_sort_order) { 
			case 'datea':
				$sort_string = 'post_date ASC';
				break;
			case 'dated':
				$sort_string = 'post_date DESC';
				break;
			case 'menua':
				$sort_string = 'menu_order ASC';
				break;
			case 'menud':
				$sort_string = 'menu_order DESC';
				break;
			default: // title
				$sort_string = 'post_title';
				break;
		}
*/
		$pass_check = '';
		if ($hide_protected) {
			$pass_check = " AND post_password = '' ";
		}
			
		$pages = (array)$wpdb->get_results("
			SELECT post_title, ID, post_parent $extra_data
			FROM {$tp}posts
			WHERE post_type = 'page' 
			AND post_status = 'publish' 
			$pass_check 
			ORDER BY post_parent, $sort_string 
		");

		
		$pages_with_children = (array)$wpdb->get_col("
			SELECT post_parent
			FROM {$tp}posts
			WHERE post_type = 'page'
			AND post_status = 'publish' 
			AND post_parent != '0' 
			GROUP BY post_parent
			ORDER BY post_parent
		", 0);

		$num_pages = count($pages);

		$page_data = array();

		$page_data = AWM_get_pages($page_data, $pages, $num_pages, $pages_with_children, $excluded_pages, $show_page_date);

		$num_pages = count($page_data);

	}


	$wpdb->flush();


	$total_items = 0;
	if ($show_posts)
		$total_items = $num_posts;
	if ($show_pages)
		$total_items += $num_pages;
	if ($show_categories)
		$total_items += $num_cats;
		

	
	$plugin_out .= "\n\n<!-- BEGIN ALLWEBMENUS CODE FOR XML -->\n\n";

	$plugin_out .= '';
	
	$plugin_out .= '<div align="center" class="wrap" style="width:910px"><p style="width:420px">Make sure that you upload all the menu files in the <strong>' . get_bloginfo('url') . get_option('AWM_menu_path') . '</strong> directory of your server.</p>';
	$plugin_out .= '<p><strong><u>Menu Structure Code:</u></strong><br><textarea cols="100" rows="10" id="loginfo" name="loginfo">';

	$xml_out = '';
	$xml_out .= '&lt;?xml version="1.0" encoding="UTF-8"?&gt;';
	$xml_out .= '&lt;mainmenu&gt;';
	

	if ($include_home) { // include home
	
		$xml_out .= MAIN . ID . 'home_0' . UNID . TITLE . $home['name'] . UNTITLE . LINK . $home['url'] . UNLINK . SUB . UNSUB . UNMAIN;
		
	}

	if ($show_pages) { // show pages

		$xml_out .= AWM_display_pages($page_data, $num_pages, $new_window, $show_page_date, $date_format, $pages_ms);
		
	}
	
	if ($show_posts) { // show posts

		$xml_out .= AWM_display_posts($post_data, $num_posts, $new_window, $show_post_date, $date_format, $post_date, $post_item_name);

	}
	
	if ($show_categories) { // show categories

		$xml_out .= AWM_display_categories($cat_data, $num_cats, $new_window, $categories_ms);
		
	}

	
	$xml_out .= '&lt;/mainmenu&gt;';

	$plugin2_out = '';
	
	$plugin2_out .= '</textarea></p><div align="center"><p style="width:700px">- Press <strong>Ctrl+C</strong> to copy the above code<br>- Switch to the AllWebMenus desktop application<br>- Open the <i>"Add-ins -> WordPress Menu -> Import/Update Menu Structure from WordPress"</i> form<br>- Paste the above copied "Menu Structure Code"<br>- Configure further your menu (styles, etc.) through the AllWebMenus properties
</p></div>';
	
	$plugin2_out .= "<script type='text/javascript'>var t=document.getElementById('loginfo');t.select();t.focus();</script>";
	$plugin2_out .= "\n\n<!-- END of ALLWEBMENUS CODE FOR XML -->\n\n";


	return array($plugin_out, $xml_out, $plugin2_out);

}



function AWM_generate_linking_code() {

	$lc = "";
	
	// get the name
	$name = get_option('AWM_menu_name');
	$nm = explode("/", $name);
	$name = $nm[1];
	$nm = explode(".", $name);
	$name = $nm[0];
	
	$lc .= "<!-- ******** BEGIN ALLWEBMENUS CODE FOR " . $name . " ******** -->\n";
	$lc .= "<script type='text/javascript'>var MenuLinkedBy='AllWebMenus [4]',awmMenuName='" . $name . "',awmBN='WP';awmAltUrl='';</script>\n";
	$lc .= "<script charset='UTF-8' src='" . get_bloginfo('url') . get_option('AWM_menu_path') . get_option('AWM_menu_name') . "' type='text/javascript'></script>\n";
	$lc .= "<script type='text/javascript'>if (typeof(Menu)!='undefined') awmBuildMenu();\n";
	$lc .= "<!-- -------  Add your Server-Side code right after this comment  ---------- -->\n";
	
									// only if we are viewing a single post
	if (get_option('AWM_Related') && is_single()) {
	
		// convert quotes to add code to item's <Text> property
		$related = str_replace('"', '\'', related_posts('', false));
		$related = trim($related);

		$lc .= 'IRP.visible=1; IRP.text0="Related Posts"; IRP.text1="Related Posts"; IRP.text2="Related Posts";';
		
		if (strcmp(get_option('AWM_Related_color'), '') != 0) {
			// use a custom style, the bgcolor is defined by the user
			$lc .= 'rps1=new ItemStyle("name=itemstyleIRP;textfont0=Tahoma;textfont1=Tahoma;textfont2=Tahoma;textsize0=11px;textsize1=11px;textsize2=11px;textdecor0=B;textdecor1=B;textdecor2=B;color0=#000000;color1=#000000;color2=#000000;padding0=3px 8px 3px 8;padding1=3px 8px 3px 8;padding2=3px 8px 3px 8;bgcolor0='. get_option("AWM_Related_color") .';bgcolor1='. get_option("AWM_Related_color") .';bgcolor2='. get_option("AWM_Related_color") .';align0=center;align1=center;align2=center");';			
			
			$lc .= 'IRP1=IRP.newGroup("style=GRP_groupstyle");';
			$lc .= 'IRP2=IRP1.newItem("style=itemstyleIRP;text0=' . $related . ';htmlMode=1;");';
			
		} else {
			// use the styles from the rest of the menu
			$lc .= 'IRP1=IRP.newGroup("style=GRP_groupstyle");';
			$lc .= 'IRP2=IRP1.newItem("style=IRPS_itemstyle;text0=' . $related . ';htmlMode=1;");';
			
		}
		
	}	
	$lc .= "if (typeof(" . $name . ")!='undefined') ProduceMenu(" . $name . ");";
	$lc .= "</script>\n";
	$lc .= "<!-- ******** END ALLWEBMENUS CODE FOR " . $name . " ******** -->\n\n";
	
	echo $lc;

}




/* 
 * Initialize query var for sitemap permalinks
 */
function AWM_query_vars ( $vars ) {
	$vars[] = "pg";
	return $vars;
}



add_filter('query_vars', 'AWM_query_vars');

add_action('admin_menu', 'AWM_add_option_pages');

?>