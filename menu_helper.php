<?php
/* 
 * Create the actual server-side menu code 
 */

function AWM_create_dynamic_menu($t, $is_sub) {
	$ic = 1000;
	$m = get_option('AWM_menu_name_'.$t);
	if ($is_sub) {
		$parentgroup = "wpgroup";
	} else {
		$parentgroup = $m;
	}
	echo "\n";
	if (get_option('AWM_include_home_'.$t)) { // include home
		echo $parentgroup.".newItem('style=".$m."_'+(wplevel==0?'main_item_style':'sub_item_style')+';itemid=".($ic++).";text0=Home;url=".get_bloginfo('url')."');\n";
	}
	
	if (get_option('AWM_pages_'.$t)) {
		$ic = AWM_create_dynamic_menu__pages($t, $parentgroup, $ic, false);
	}
	
	if (get_option('AWM_posts_'.$t)) {
		$ic = AWM_create_dynamic_menu__posts($t, $parentgroup, $ic, false);
	}
	
	if (get_option('AWM_categories_'.$t)) {
		$ic = AWM_create_dynamic_menu__categories($t, $parentgroup, $ic, false);
	}
}



/* 
 * Create the categories menu
 */
function AWM_create_dynamic_menu__categories($t, $parentgroup, $ic, $isXML) {
	global $wpdb;
	$depth = 0;
	$m = get_option('AWM_menu_name_'.$t);
	$tp = $wpdb->prefix;
	$xml_out = "";
	$isNew = ($wpdb->get_results("show tables like '{$tp}term_taxonomy'")) > 0;
	
	$post_res = AWM_get_post_restrictions();
	
	$cats_to_avoid = "";
	if (get_option('AWM_excluded_cats_'.$t)!='') {
		$cats_ids = get_option('AWM_excluded_cats_'.$t);
		$cats_ids = str_replace(' ', '', $cats_ids);
		$cats_ids = (array)explode(',', $cats_ids);
		for ($i=0; $i<sizeof($cats_ids); $i++) $cats_to_avoid .= ",".$cats_ids[$i];
		$cats_to_avoid = "AND tt.term_id NOT IN (".substr($cats_to_avoid,1).") AND tt.parent NOT IN (".substr($cats_to_avoid,1).")";
	}
	
	if ($isNew) {
		$cats = (array)$wpdb->get_results("
			SELECT t.term_id as category_ID, t.name as cat_name, tt.parent as category_parent
			FROM {$tp}terms t, {$tp}term_taxonomy tt
			WHERE tt.taxonomy = 'category'
			AND t.term_id = tt.term_id $cats_to_avoid
			GROUP BY category_ID 
			ORDER BY category_parent, cat_name");
		$recent = (array)$wpdb->get_results("
			SELECT p.ID, p.post_title, tt.term_id 
			FROM {$tp}posts p, {$tp}term_taxonomy tt, {$tp}term_relationships tr
			WHERE p.post_type='post' AND tr.object_id=p.ID 
			AND tt.term_taxonomy_id=tr.term_taxonomy_id AND tt.taxonomy='category'
			$post_res $cats_to_avoid
			ORDER BY tt.term_id, p.post_date DESC");
	} else {
		$cats = (array)$wpdb->get_results("
			SELECT cat_ID as category_ID, cat_name, category_parent
			FROM {$tp}categories
			GROUP BY cat_ID 
			ORDER BY category_parent, cat_name");
		$recent = array();
	}
	
	if ($isXML) {
		if (get_option('AWM_categories_ms_'.$t)=='sub') $xml_out .= "<item><id>categories</id><name>".get_option('AWM_categories_name_'.$t)."</name><link></link><submenu>";
		$xml_out .= AWM_create_dynamic_menu__categories_step($t,$ic,$parentgroup,$cats,$depth,0,$recent,true);
		if (get_option('AWM_categories_ms_'.$t)=='sub') $xml_out .= "</submenu></item>";
		return $xml_out;
	} else {
		if (get_option('AWM_categories_ms_'.$t)=='sub') {
			echo "item0=".$parentgroup.".newItem('style=".$m."_'+(wplevel==0?'main_item_style':'sub_item_style')+';itemid=".($ic++).";text0=".str_replace("'","\'",get_option('AWM_categories_name_'.$t))."');\n";
			echo "subMenu0=item0.newGroup('style=".$m."_'+(wplevel==0?'sub_group_style':'sub_group_plus_style'));\n";
			$depth++;
			$parentgroup = "subMenu0";
		}
		return AWM_create_dynamic_menu__categories_step($t,$ic,$parentgroup,$cats,$depth,0,$recent,false);
	}
}

function AWM_cat_has_kids($id, $cats) {
	for ($i=0; $i<count($cats); $i++) { if ($cats[$i]->category_parent==$id) return true; }
	return false;
}

function AWM_create_dynamic_menu__categories_step($t, $ic, $parentgroup, $cats, $depth, $group, $recent, $isXML) {
	$m = get_option('AWM_menu_name_'.$t);
	$xml_out = "";
	for ($i=0; $i<count($cats); $i++) {
		if ($cats[$i]->category_parent==$group) {
			if ($isXML) $xml_out .= "<item><id>cat_".$cats[$i]->category_ID."</id><name>".$cats[$i]->cat_name."</name><link>".get_category_link($cats[$i]->category_ID)."</link><submenu>";
			else echo "item".$depth."=".$parentgroup.".newItem('style=".$m."_'+((wplevel+$depth)==0?'main_item_style':((wplevel+$depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($ic++).";text0=".str_replace("'","\'",$cats[$i]->cat_name).";url=".get_category_link($cats[$i]->category_ID)."');\n";
			if (AWM_cat_has_kids($cats[$i]->category_ID, $cats)) {
				if ($isXML) {
					$xml_out .= AWM_create_dynamic_menu__categories_step($t, $ic, "subMenu".$depth, $cats, $depth+1, $cats[$i]->category_ID, $recent, true);
				} else {
					echo "subMenu".$depth."=item".$depth.".newGroup('style=".$m."_'+((wplevel+$depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					$ic = AWM_create_dynamic_menu__categories_step($t, $ic, "subMenu".$depth, $cats, $depth+1, $cats[$i]->category_ID, $recent, false);
				}
			} elseif (get_option('AWM_categories_subitems_'.$t)) {
				$j=$counter=0;
				while ($j<count($recent) && $recent[$j]->term_id!=$cats[$i]->category_ID) $j++;
				if ($recent[$j]->term_id==$cats[$i]->category_ID) {
					if (!$isXML) echo "subMenuRec=item".$depth.".newGroup('style=".$m."_'+((wplevel+$depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					while ($j<count($recent) && $recent[$j]->term_id==$cats[$i]->category_ID && $counter++<get_option('AWM_categories_subitems_no_'.$t)) {
						if ($isXML) $xml_out .= "<item><id>cat_".$cats[$i]->category_ID."_it".$recent[$j]->ID."</id><name>".$recent[$j]->post_title."</name><link>".get_permalink($recent[$j]->ID)."</link><submenu></submenu></item>";
						else echo "item".($depth+1)."=subMenuRec.newItem('style=".$m."_'+((wplevel+$depth)==0?'sub_item_style':'sub_item_plus_style')+';itemid=".($ic++).";text0=".str_replace("'","\'",$recent[$j]->post_title).";url=".get_permalink($recent[$j]->ID)."');\n";
						$j++;
					}
				}
			}
			if ($isXML) $xml_out .= "</submenu></item>";
		}
	}
	if ($isXML) return $xml_out;
	else return $ic;
}



/* 
 * Create the posts menu
 */
function AWM_create_dynamic_menu__posts($t, $parentgroup, $ic, $isXML) {
	$depth = 0;
	$m = get_option('AWM_menu_name_'.$t);
	$xml_out = "";
	global $wpdb;
	$tp = $wpdb->prefix;
	
	if (get_option('AWM_posts_ids_'.$t)=="") return $ic;
	$post_res = AWM_get_post_restrictions();
	
	$posts_to_display = "";
	$posts_ids = get_option('AWM_posts_ids_'.$t);
	$posts_ids = str_replace(' ', '', $posts_ids);
	$posts_ids = (array)explode(',', $posts_ids);
	for ($i=0; $i<sizeof($posts_ids); $i++) $posts_to_display .= " OR ID='".$posts_ids[$i]."'";
	$posts_to_display = "AND (".substr($posts_to_display,4).")";
	
	$posts = (array)$wpdb->get_results("
		SELECT ID, post_title
		FROM {$tp}posts p
		WHERE post_status = 'publish' AND post_type = 'post' 
		$post_res $posts_to_display
		ORDER BY post_date DESC
	");
	
	if (count($posts)>0) {
		if ($isXML) {
			if (get_option('AWM_posts_ms_'.$t)=='sub') $xml_out .= "<item><id>posts</id><name>".get_option('AWM_posts_name_'.$t)."</name><link></link><submenu>";
			for ($i=0; $i<count($posts); $i++) $xml_out .= "<item><id>post_".$posts[$i]->ID."</id><name>".$posts[$i]->post_title."</name><link>".get_permalink($posts[$i]->ID)."</link><submenu></submenu></item>";
			if (get_option('AWM_posts_ms_'.$t)=='sub') $xml_out .= "</submenu></item>";
		} else {
			if (get_option('AWM_posts_ms_'.$t)=='sub') {
				echo "item0=".$parentgroup.".newItem('style=".$m."_'+((wplevel+$depth)==0?'main_item_style':((wplevel+$depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($ic++).";text0=".str_replace("'","\'",get_option('AWM_posts_name_'.$t))."');\n";
				echo "subMenu0=item0.newGroup('style=".$m."_'+((wplevel+$depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
				$depth++;
				$parentgroup = "subMenu0";
			}
			for ($i=0; $i<count($posts); $i++) echo "item0=".$parentgroup.".newItem('style=".$m."_'+((wplevel+$depth)==0?'main_item_style':((wplevel+$depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($ic++).";text0=".str_replace("'","\'",$posts[$i]->post_title).";url=".get_permalink($posts[$i]->ID)."');\n";
		}
	}
	if ($isXML) return $xml_out;
	else return $ic;
}

function AWM_create_dynamic_menu__pages($t, $parentgroup, $ic, $isXML) {
	$depth = 0;
	$m = get_option('AWM_menu_name_'.$t);
	$xml_out = "";
	global $wpdb;
	$tp = $wpdb->prefix;
	
	$post_res = AWM_get_post_restrictions();
	
	$pages_to_avoid = "";
	if (get_option('AWM_excluded_pages_'.$t)!='') {
		$posts_ids = get_option('AWM_excluded_pages_'.$t);
		$posts_ids = str_replace(' ', '', $posts_ids);
		$posts_ids = (array)explode(',', $posts_ids);
		for ($i=0; $i<sizeof($posts_ids); $i++) $pages_to_avoid .= ",".$posts_ids[$i];
		$pages_to_avoid = "AND p.ID NOT IN (".substr($pages_to_avoid,1).") AND p.post_parent NOT IN (".substr($pages_to_avoid,1).")";
	}
	
	$pages = (array)$wpdb->get_results("
		SELECT post_title, ID, post_parent
		FROM {$tp}posts p
		WHERE post_type = 'page' 
		AND post_status = 'publish' 
		$post_res $pages_to_avoid
		ORDER BY post_parent, post_date ASC
	");
	
	if ($isXML) {
		if (get_option('AWM_pages_ms_'.$t)=='sub') $xml_out .= "<item><id>pages</id><name>".get_option('AWM_pages_name_'.$t)."</name><link></link><submenu>";
		$xml_out .= AWM_create_dynamic_menu__pages_step($t, $ic, $parentgroup, $pages, $depth, 0, $recent, true);
		if (get_option('AWM_pages_ms_'.$t)=='sub') $xml_out .= "</submenu></item>";
		return $xml_out;
	} else {
		if (get_option('AWM_pages_ms_'.$t)=='sub') {
			echo "item0=".$m.".newItem('style=".$m."_'+((wplevel+$depth)==0?'main_item_style':((wplevel+$depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($ic++).";text0=".str_replace("'","\'",get_option('AWM_pages_name_'.$t))."');\n";
			echo "subMenu0=item0.newGroup('style=".$m."_'+((wplevel+$depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
			$depth++;
			$parentgroup = "subMenu0";
		}
		return AWM_create_dynamic_menu__pages_step($t, $ic, $parentgroup, $pages, $depth, 0, $recent, false);
	}
}

function AWM_page_has_kids($id, $pages) {
	for ($i=0; $i<count($pages); $i++) { if ($pages[$i]->post_parent==$id) return true; }
	return false;
}


function AWM_create_dynamic_menu__pages_step($t, $ic, $parentgroup, $pages, $depth, $group, $recent, $isXML) {
	$m = get_option('AWM_menu_name_'.$t);
	$xml_out = "";
	for ($i=0; $i<count($pages); $i++) {
		if ($pages[$i]->post_parent==$group) {
			if ($isXML) $xml_out .= "<item><id>page_".$pages[$i]->ID."</id><name>".$pages[$i]->post_title."</name><link>".get_permalink($pages[$i]->ID)."</link><submenu>";
			else echo "item".$depth."=".$parentgroup.".newItem('style=".$m."_'+((wplevel+$depth)==0?'main_item_style':((wplevel+$depth)==1?'sub_item_style':'sub_item_plus_style'))+';itemid=".($ic++).";text0=".str_replace("'","\'",$pages[$i]->post_title).";url=".get_permalink($pages[$i]->ID)."');\n";
			if (AWM_cat_has_kids($pages[$i]->post_parent, $pages)) {
				if ($isXML) {
					$xml_out .= AWM_create_dynamic_menu__categories_step($t, $ic, "subMenu".$depth, $pages, $depth+1, $pages[$i]->post_parent, $recent, true);
				} else {
					echo "subMenu".$depth."=item".$depth.".newGroup('style=".$m."_'+((wplevel+$depth)==0?'sub_group_style':'sub_group_plus_style'));\n";
					$ic = AWM_create_dynamic_menu__categories_step($t, $ic, "subMenu".$depth, $pages, $depth+1, $pages[$i]->post_parent, $recent, false);
				}
			}
			if ($isXML) $xml_out .= "</submenu></item>";
		}
	}
	if ($isXML) return $xml_out;
	else return $ic;
}

function AWM_get_post_restrictions() {
	$pass_check = '';
	if (get_option('AWM_hide_protected_'.$t)) {
		$pass_check = " AND p.post_password = '' ";
	}
	
	$future_check = '';
	if (get_option('AWM_hide_future_'.$t)) {
		$future_check = " AND p.post_status != 'future' ";
	}
	return $pass_check.$future_check;
}

function AWM_create_menu_structure($t) {
	$ic = 1000;
	$xml_out = "<?xml version='1.0' encoding='UTF-8'?><mainmenu>";
	$xml_out .="<menutype>".get_option('AWM_menu_type_'.$t)."</menutype>";
	
	if (get_option('AWM_include_home_'.$t)) { // include home
		$xml_out .= "<item><id>home0</id><name>Home</name><link>".get_bloginfo('url')."</link><submenu></submenu></item>";
	}
	
	if (get_option('AWM_pages_'.$t)) {
		$xml_out .= AWM_create_dynamic_menu__pages($t, "", $ic, true);
	}
	
	if (get_option('AWM_posts_'.$t)) {
		$xml_out .= AWM_create_dynamic_menu__posts($t, "", $ic, true);
	}
	
	if (get_option('AWM_categories_'.$t)) {
		$xml_out .= AWM_create_dynamic_menu__categories($t, "", $ic, true);
	}
	
	$xml_out .= "</mainmenu>";
	$xml_out = str_replace("<","&lt;",$xml_out);
	$xml_out = str_replace(">","&gt;",$xml_out);
	
	return $xml_out;
}


?>