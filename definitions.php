<?php 

/*
AllWebMenus-WP-Menu
http://www.likno.com/addins/wordpress-menu.html

*/


// Text shown on sitemap page 

define('AWM_PAGE_HEADER', '<h2>Pages</h2>');
define('AWM_POST_HEADER', '<h2>Posts</h2>');
define('AWM_CAT_HEADER', '<strong>Category:</strong>');
define('AWM_NO_TITLE', '(No Title)');
define('AWM_CREDITS', 'Plugin by');

// XML creation
define('MAIN', '&lt;item&gt;');
define('UNMAIN', '&lt;/item&gt;');
define('SUB', '&lt;submenu&gt;');
define('UNSUB', '&lt;/submenu&gt;');
define('ID', '&lt;id&gt;');
define('UNID', '&lt;/id&gt;');
define('LINK', '&lt;link&gt;');
define('UNLINK', '&lt;/link&gt;');
define('TITLE', '&lt;name&gt;');
define('UNTITLE', '&lt;/name&gt;');

// Text shown in options page 
define('AWM_DEFAULTS_LOADED', 'Default Options Loaded!');
define('AWM_CONFIG_UPDATED', 'Configuration Updated!');
define('AWM_AWM_CREATED', 'Menu Created!');

define('AWM_FOR_INFO', 'For information and updates, please visit:');
define('AWM_DEFAULT_NOTICE', '<strong>Upgrading?</strong> If you are upgrading from a previous version, click the <strong>Load Default Options</strong> button below. Some settings may have changed.');

define('AWM_GENERAL_OPTIONS', 'General Options');
define('AWM_MENU_ITEMS', 'Select Items for the Menu');
define('AWM_LOCATION', 'Select the Path and name of the menu');
define('AWM_LOCATION_NF', '- Define a folder relative to the blog\'s Root. If a folder with the specific name does not exist you have to create a <strong>new</strong> one.');
define('AWM_LOCATION_DEFAULT', '<font color="blue">Note:</font> It is <strong>strongly</strong> recommended that you <strong>do not change the above two values</strong>. If you do, then make sure the new values match the values in the relevant properties of the AllWebMenus project file (<i>Tools > Project Properties > Folders</i>).');
define('AWM_SHOW', 'My Main Items:');
define('AWM_INCLUDEHOME', 'Include Home');
define('AWM_POSTIDS', '- Post IDs, separated by commas');
define('AWM_MAIN', 'as Main Items');
define('AWM_MAINORSUB', 'is the Main Item and the Sub Menu Group contains the');
define('AWM_EXCLUSIONS', 'Exclusions');
define('AWM_EXCLUDED_CATS', 'Excluded categories:');
define('AWM_EXCLUDED_CATS_DESC', '- Category IDs, separated by commas<br />- Sub-categories will also be excluded');
define('AWM_EXCLUDED_PAGES', 'Excluded pages:');
define('AWM_EXCLUDED_PAGES_DESC', '- Page IDs, separated by commas<br />- Sub-pages will also be excluded');
define('AWM_HIDE_FUTURE', 'Hide future-dated posts');
define('AWM_HIDE_PASS', 'Hide password-protected items:');
define('AWM_MENU_PATH', 'Location of the menu file:');
define('AWM_MENU_NAME', 'Name of menu:');

define('AWM_YARP', '"Yet Another Related Posts Plugin" Options:');
define('AWM_YARPDESC', 'Show "Related Posts" item when viewing a post:');
define('AWM_YARPBGC', 'Background Color of the item that will contain the posts:');
define('AWM_YARP_D', 'If you want to activate the YARP plugin from within the Plugins panel (as a separate plugin) make sure that you DEACTIVATE the YARP plugin first from this option or you will get a FATAL ERROR message! Then you can come back and reactivate it to use the YARP plugin either in your menu or in your posts.');
define('AWM_YARP_A', 'If YARP plugin is already activated you cannot use the YARPP functionality in your menu! You need to DEACTIVATE YARPP from the Plugins panel and then ACTIVATE it by clicking on this button.');
define('AWM_YARP_NA', '<i>You have deactivated the YARPP so you cannot select this option</i>');

define('AWM_DEFAULT_BUTTON', 'Load Default Options');
define('AWM_UPDATE_BUTTON', 'Update options');
define('AWM_CREATE_BUTTON', 'Generate Menu Structure Code');

?>
