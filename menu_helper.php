<?php
/* 
 * Create the actual server-side menu code 
 */

function AWM_create_dynamic_menu($awm_t, $awm_is_sub) {
	$awm_ic = 1000;
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	if ($awm_is_sub) {
		$awm_parentgroup = "wpgroup";
	} else {
		$awm_parentgroup = $awm_m;
	}
	echo "\n";
	if (get_option('AWM_include_home_'.$awm_t)) { // include home
		echo $awm_parentgroup.".newItem('style=".$awm_m."_'+(wplevel==0?'main_item_style':'sub_item_style')+';itemid=".($awm_ic++).";text0=Home;url=".get_bloginfo('url')."');\n";
	}
	
	if (get_option('AWM_pages_'.$awm_t)) {
		$awm_ic = AWM_create_dynamic_menu__pages($awm_t, $awm_parentgroup, $awm_ic, false);
	}
	
	if (get_option('AWM_posts_'.$awm_t)) {
		$awm_ic = AWM_create_dynamic_menu__posts($awm_t, $awm_parentgroup, $awm_ic, false);
	}
	
	if (get_option('AWM_categories_'.$awm_t)) {
		$awm_ic = AWM_create_dynamic_menu__categories($awm_t, $awm_parentgroup, $awm_ic, false);
	}
}



/* 
 * Create the categories menu
 */
function AWM_create_dynamic_menu__categories($awm_t, $awm_parentgroup, $awm_ic, $awm_isXML) {
	global $awm_wpdb;
	$awm_depth = 0;
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	$awm_tp = $awm_wpdb->prefix;
	$awm_xml_out = "";
	$awm_isNew = ($awm_wpdb->get_results("show tables like '{$awm_tp}term_taxonomy'")) > 0;
	
	$awm_post_res = AWM_get_post_restrictions();
	
	$awm_cats_to_avoid = "";
	if (get_option('AWM_excluded_cats_'.$awm_t)!='') {
		$awm_cats_ids = get_option('AWM_excluded_cats_'.$awm_t);
		$awm_cats_ids = str_replace(' ', '', $awm_cats_ids);
		$awm_cats_ids = (array)explode(',', $awm_cats_ids);
		for ($awm_i=0; $awm_i<sizeof($awm_cats_ids); $awm_i++) $awm_cats_to_avoid .= ",".$awm_cats_ids[$awm_i];
		$awm_cats_to_avoid = "AND tt.term_id NOT IN (".substr($awm_cats_to_avoid,1).") AND tt.parent NOT IN (".substr($awm_cats_to_avoid,1).")";
	}
	
	if ($awm_isNew) {
		$awm_cats = (array)$awm_wpdb->get_results("
			SELECT t.term_id as category_ID, t.name as cat_name, tt.parent as category_parent
			FROM {$awm_tp}terms t, {$awm_tp}term_taxonomy tt
			WHERE tt.taxonomy = 'category'
			AND t.term_id = tt.term_id $awm_cats_to_avoid
			GROUP BY category_ID 
			ORDER BY category_parent, cat_name");
		$awm_recent = (array)$awm_wpdb->get_results("
			SELECT p.ID, p.post_title, tt.term_id 
			FROM {$awm_tp}posts p, {$awm_tp}term_taxonomy tt, {$awm_tp}term_relationships tr
			WHERE p.post_type='post' AND tr.object_id=p.ID 
			AND tt.term_taxonomy_id=tr.term_taxonomy_id AND tt.taxonomy='category'
			$awm_post_res $awm_cats_to_avoid
			ORDER BY tt.term_id, p.post_date DESC");
	} else {
		$awm_cats = (array)$awm_wpdb->get_results("
			SELECT cat_ID as category_ID, cat_name, category_parent
			FROM {$awm_tp}categories
			GROUP BY cat_ID 
			ORDER BY category_parent, cat_name");
		$awm_recent = array();
	}
	
	if ($awm_isXML) {
		if (get_option('AWM_categories_ms_'.$awm_t)=='sub') $awm_xml_out .= "<item><id>categories</id><name>".get_option('AWM_categories_name_'.$awm_t)."</name><link></link><submenu>";
		$awm_xml_out .= AWM_create_dynamic_menu__categories_step($awm_t,$awm_ic,$awm_parentgroup,$awm_cats,$awm_depth,0,$awm_recent,true);
		if (get_option('AWM_categories_ms_'.$awm_t)=='sub') $awm_xml_out .= "</submenu></item>";
		return $awm_xml_out;
	} else {
		if (get_option('AWM_categories_ms_'.$awm_t)=='sub') {
			echo "item0=".$awm_parentgroup.".newItem('style=".$awm_m."_'+(wplevel==0?'main_item_style':'sub_item_style')+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",get_option('AWM_categories_name_'.$awm_t))."');\n";
			echo "subMenu0=item0.newGroup('style=".$awm_m."_'+(wplevel==0?'sub_group_style':'sub_group_plus_style'));\n";
			$awm_depth++;
			$awm_parentgroup = "subMenu0";
		}
		return AWM_create_dynamic_menu__categories_step($awm_t,$awm_ic,$awm_parentgroup,$awm_cats,$awm_depth,0,$awm_recent,false);
	}
}

function AWM_cat_has_kids($awm_id, $awm_cats) {
	for ($awm_i=0; $awm_i<count($awm_cats); $awm_i++) { if ($awm_cats[$awm_i]->category_parent==$awm_id) return true; }
	return false;
}

function AWM_create_dynamic_menu__categories_step($awm_t, $awm_ic, $awm_parentgroup, $awm_cats, $awm_depth, $awm_group, $awm_recent, $awm_isXML) {
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	$awm_xml_out = "";
	for ($awm_i=0; $awm_i<count($awm_cats); $awm_i++) {
		if ($awm_cats[$awm_i]->category_parent==$awm_group) {
			if ($awm_isXML) $awm_xml_out .= "<item><id>cat_".$awm_cats[$awm_i]->category_ID."</id><name>".$awm_cats[$awm_i]->cat_name."</name><link>".get_category_link($awm_cats[$awm_i]->category_ID)."</link><submenu>";
			else echo "item".$awm_depth."=".$awm_parentgroup.".newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'main_item_style':((wplevel+$awm_depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",$awm_cats[$awm_i]->cat_name).";url=".get_category_link($awm_cats[$awm_i]->category_ID)."');\n";
			if (AWM_cat_has_kids($awm_cats[$awm_i]->category_ID, $awm_cats)) {
				if ($awm_isXML) {
					$awm_xml_out .= AWM_create_dynamic_menu__categories_step($awm_t, $awm_ic, "subMenu".$awm_depth, $awm_cats, $awm_depth+1, $awm_cats[$awm_i]->category_ID, $awm_recent, true);
				} else {
					echo "subMenu".$awm_depth."=item".$awm_depth.".newGroup('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					$awm_ic = AWM_create_dynamic_menu__categories_step($awm_t, $awm_ic, "subMenu".$awm_depth, $awm_cats, $awm_depth+1, $awm_cats[$awm_i]->category_ID, $awm_recent, false);
				}
			} elseif (get_option('AWM_categories_subitems_'.$awm_t)) {
				$awm_j=$awm_counter=0;
				while ($awm_j<count($awm_recent) && $awm_recent[$awm_j]->term_id!=$awm_cats[$awm_i]->category_ID) $awm_j++;
				if ($awm_recent[$awm_j]->term_id==$awm_cats[$awm_i]->category_ID) {
					if (!$awm_isXML) echo "subMenuRec=item".$awm_depth.".newGroup('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					while ($awm_j<count($awm_recent) && $awm_recent[$awm_j]->term_id==$awm_cats[$awm_i]->category_ID && $awm_counter++<get_option('AWM_categories_subitems_no_'.$awm_t)) {
						if ($awm_isXML) $awm_xml_out .= "<item><id>cat_".$awm_cats[$awm_i]->category_ID."_it".$awm_recent[$awm_j]->ID."</id><name>".$awm_recent[$awm_j]->post_title."</name><link>".get_permalink($awm_recent[$awm_j]->ID)."</link><submenu></submenu></item>";
						else echo "item".($awm_depth+1)."=subMenuRec.newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_item_style':'sub_item_plus_style')+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",$awm_recent[$awm_j]->post_title).";url=".get_permalink($awm_recent[$awm_j]->ID)."');\n";
						$awm_j++;
					}
				}
			}
			if ($awm_isXML) $awm_xml_out .= "</submenu></item>";
		}
	}
	if ($awm_isXML) return $awm_xml_out;
	else return $awm_ic;
}



/* 
 * Create the posts menu
 */
function AWM_create_dynamic_menu__posts($awm_t, $awm_parentgroup, $awm_ic, $awm_isXML) {
	$awm_depth = 0;
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	$awm_xml_out = "";
	global $awm_wpdb;
	$awm_tp = $awm_wpdb->prefix;
	
	if (get_option('AWM_posts_ids_'.$awm_t)=="") return $awm_ic;
	$awm_post_res = AWM_get_post_restrictions();
	
	$awm_posts_to_display = "";
	$awm_posts_ids = get_option('AWM_posts_ids_'.$awm_t);
	$awm_posts_ids = str_replace(' ', '', $awm_posts_ids);
	$awm_posts_ids = (array)explode(',', $awm_posts_ids);
	for ($awm_i=0; $awm_i<sizeof($awm_posts_ids); $awm_i++) $awm_posts_to_display .= " OR ID='".$awm_posts_ids[$awm_i]."'";
	$awm_posts_to_display = "AND (".substr($awm_posts_to_display,4).")";
	
	$awm_posts = (array)$awm_wpdb->get_results("
		SELECT ID, post_title
		FROM {$awm_tp}posts p
		WHERE post_status = 'publish' AND post_type = 'post' 
		$awm_post_res $awm_posts_to_display
		ORDER BY post_date DESC
	");
	
	if (count($awm_posts)>0) {
		if ($awm_isXML) {
			if (get_option('AWM_posts_ms_'.$awm_t)=='sub') $awm_xml_out .= "<item><id>posts</id><name>".get_option('AWM_posts_name_'.$awm_t)."</name><link></link><submenu>";
			for ($awm_i=0; $awm_i<count($awm_posts); $awm_i++) $awm_xml_out .= "<item><id>post_".$awm_posts[$awm_i]->ID."</id><name>".$awm_posts[$awm_i]->post_title."</name><link>".get_permalink($awm_posts[$awm_i]->ID)."</link><submenu></submenu></item>";
			if (get_option('AWM_posts_ms_'.$awm_t)=='sub') $awm_xml_out .= "</submenu></item>";
		} else {
			if (get_option('AWM_posts_ms_'.$awm_t)=='sub') {
				echo "item0=".$awm_parentgroup.".newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'main_item_style':((wplevel+$awm_depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",get_option('AWM_posts_name_'.$awm_t))."');\n";
				echo "subMenu0=item0.newGroup('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
				$awm_depth++;
				$awm_parentgroup = "subMenu0";
			}
			for ($awm_i=0; $awm_i<count($awm_posts); $awm_i++) echo "item0=".$awm_parentgroup.".newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'main_item_style':((wplevel+$awm_depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",$awm_posts[$awm_i]->post_title).";url=".get_permalink($awm_posts[$awm_i]->ID)."');\n";
		}
	}
	if ($awm_isXML) return $awm_xml_out;
	else return $awm_ic;
}

function AWM_create_dynamic_menu__pages($awm_t, $awm_parentgroup, $awm_ic, $awm_isXML) {
	$awm_depth = 0;
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	$awm_xml_out = "";
	global $awm_wpdb;
	$awm_tp = $awm_wpdb->prefix;
	
	$awm_post_res = AWM_get_post_restrictions();
	
	$awm_pages_to_avoid = "";
	if (get_option('AWM_excluded_pages_'.$awm_t)!='') {
		$awm_posts_ids = get_option('AWM_excluded_pages_'.$awm_t);
		$awm_posts_ids = str_replace(' ', '', $awm_posts_ids);
		$awm_posts_ids = (array)explode(',', $awm_posts_ids);
		for ($awm_i=0; $awm_i<sizeof($awm_posts_ids); $awm_i++) $awm_pages_to_avoid .= ",".$awm_posts_ids[$awm_i];
		$awm_pages_to_avoid = "AND p.ID NOT IN (".substr($awm_pages_to_avoid,1).") AND p.post_parent NOT IN (".substr($awm_pages_to_avoid,1).")";
	}
	
	$awm_pages = (array)$awm_wpdb->get_results("
		SELECT post_title, ID, post_parent
		FROM {$awm_tp}posts p
		WHERE post_type = 'page' 
		AND post_status = 'publish' 
		$awm_post_res $awm_pages_to_avoid
		ORDER BY post_parent, post_date ASC
	");
	
	if ($awm_isXML) {
		if (get_option('AWM_pages_ms_'.$awm_t)=='sub') $awm_xml_out .= "<item><id>pages</id><name>".get_option('AWM_pages_name_'.$awm_t)."</name><link></link><submenu>";
		$awm_xml_out .= AWM_create_dynamic_menu__pages_step($awm_t, $awm_ic, $awm_parentgroup, $awm_pages, $awm_depth, 0, $awm_recent, true);
		if (get_option('AWM_pages_ms_'.$awm_t)=='sub') $awm_xml_out .= "</submenu></item>";
		return $awm_xml_out;
	} else {
		if (get_option('AWM_pages_ms_'.$awm_t)=='sub') {
			echo "item0=".$awm_m.".newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'main_item_style':((wplevel+$awm_depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",get_option('AWM_pages_name_'.$awm_t))."');\n";
			echo "subMenu0=item0.newGroup('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
			$awm_depth++;
			$awm_parentgroup = "subMenu0";
		}
		return AWM_create_dynamic_menu__pages_step($awm_t, $awm_ic, $awm_parentgroup, $awm_pages, $awm_depth, 0, $awm_recent, false);
	}
}

function AWM_page_has_kids($awm_id, $awm_pages) {
	for ($awm_i=0; $awm_i<count($awm_pages); $awm_i++) { if ($awm_pages[$awm_i]->post_parent==$awm_id) return true; }
	return false;
}


function AWM_create_dynamic_menu__pages_step($awm_t, $awm_ic, $awm_parentgroup, $awm_pages, $awm_depth, $awm_group, $awm_recent, $awm_isXML) {
	$awm_m = get_option('AWM_menu_name_'.$awm_t);
	$awm_xml_out = "";
	for ($awm_i=0; $awm_i<count($awm_pages); $awm_i++) {
		if ($awm_pages[$awm_i]->post_parent==$awm_group) {
			if ($awm_isXML) $awm_xml_out .= "<item><id>page_".$awm_pages[$awm_i]->ID."</id><name>".$awm_pages[$awm_i]->post_title."</name><link>".get_permalink($awm_pages[$awm_i]->ID)."</link><submenu>";
			else echo "item".$awm_depth."=".$awm_parentgroup.".newItem('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'main_item_style':((wplevel+$awm_depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($awm_ic++).";text0=".str_replace("'","\'",$awm_pages[$awm_i]->post_title).";url=".get_permalink($awm_pages[$awm_i]->ID)."');\n";
			if (AWM_cat_has_kids($awm_pages[$awm_i]->post_parent, $awm_pages)) {
				if ($awm_isXML) {
					$awm_xml_out .= AWM_create_dynamic_menu__categories_step($awm_t, $awm_ic, "subMenu".$awm_depth, $awm_pages, $awm_depth+1, $awm_pages[$awm_i]->post_parent, $awm_recent, true);
				} else {
					echo "subMenu".$awm_depth."=item".$awm_depth.".newGroup('style=".$awm_m."_'+((wplevel+$awm_depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					$awm_ic = AWM_create_dynamic_menu__categories_step($awm_t, $awm_ic, "subMenu".$awm_depth, $awm_pages, $awm_depth+1, $awm_pages[$awm_i]->post_parent, $awm_recent, false);
				}
			}
			if ($awm_isXML) $awm_xml_out .= "</submenu></item>";
		}
	}
	if ($awm_isXML) return $awm_xml_out;
	else return $awm_ic;
}

function AWM_get_post_restrictions() {
	$awm_pass_check = '';
	if (get_option('AWM_hide_protected_'.$awm_t)) {
		$awm_pass_check = " AND p.post_password = '' ";
	}
	
	$awm_future_check = '';
	if (get_option('AWM_hide_future_'.$awm_t)) {
		$awm_future_check = " AND p.post_status != 'future' ";
	}
	return $awm_pass_check.$awm_future_check;
}

function AWM_create_menu_structure($awm_t) {
	$awm_ic = 1000;
	$awm_xml_out = "<?xml version='1.0' encoding='UTF-8'?><mainmenu>";
	$awm_xml_out .="<menutype>".get_option('AWM_menu_type_'.$awm_t)."</menutype>";
	
	if (get_option('AWM_include_home_'.$awm_t)) { // include home
		$awm_xml_out .= "<item><id>home0</id><name>Home</name><link>".get_bloginfo('url')."</link><submenu></submenu></item>";
	}
	
	if (get_option('AWM_pages_'.$awm_t)) {
		$awm_xml_out .= AWM_create_dynamic_menu__pages($awm_t, "", $awm_ic, true);
	}
	
	if (get_option('AWM_posts_'.$awm_t)) {
		$awm_xml_out .= AWM_create_dynamic_menu__posts($awm_t, "", $awm_ic, true);
	}
	
	if (get_option('AWM_categories_'.$awm_t)) {
		$awm_xml_out .= AWM_create_dynamic_menu__categories($awm_t, "", $awm_ic, true);
	}
	
	$awm_xml_out .= "</mainmenu>";
	$awm_xml_out = str_replace("<","&lt;",$awm_xml_out);
	$awm_xml_out = str_replace(">","&gt;",$awm_xml_out);
	
	return $awm_xml_out;
}


?>