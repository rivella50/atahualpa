<?php
function bfa_header_config() {

	global $bfa_ata, $post;
	$templateURI = get_template_directory_uri(); 
	$homeURL = get_home_url();  

	// Since 3.6: bfa_header_config() instead of bfa_header_config($header_items)
	$header_items = $bfa_ata['configure_header'];
	
	$page_menu_bar	= ''; 		$cat_menu_bar	= '';  		$logo_area	= '';  
	$header_image	= '';  		$horizontal_bar1	= ''; 	$horizontal_bar2	= ''; 

	// Page Menu Bar
	if ( strpos($header_items,'%pages') !== FALSE OR strpos($header_items,'%page-center') !== FALSE 
	OR strpos($header_items,'%page-right') !== FALSE ) {

		// Since 3.5.2: New WP 3 menu system:
		if ( has_nav_menu('menu1') ) 
		{
			if ( strpos($header_items,'%pages') !== FALSE ) 
				$alignment = "left";
			elseif ( strpos($header_items,'%page-center') !== FALSE ) 
				$alignment = "center";
			else 
				$alignment = "right";
			$page_menu_bar = bfa_new_wp3_menus("menu1", $alignment );
		} 

		// Old custom Atahualpa menu system:		
		else 
		{
			ob_start();
			echo '<div id="menu1">';
			
			// Left, Right or Centered
			if ( strpos($header_items,"%page-right") !== FALSE ) 
				echo '<ul id="rmenu2" class="dropdown clearfix rMenu-hor rMenu-hRight rMenu">' . "\n";
			elseif ( strpos($header_items,"%page-center") !== FALSE ) 
				echo '<table cellpadding="0" cellspacing="0" style="margin: 0 auto"><tr><td align="center">
				<ul id="rmenu2" class="clearfix rMenu-hor rMenu">' . "\n";
			else 
				echo '<ul id="rmenu2" class="clearfix rMenu-hor rMenu">' . "\n";	
			
			// "Home" Link?
			if ( $bfa_ata['home_page_menu_bar'] != '' ) 
			{
				echo '<li class="page_item';
				if ( is_front_page() OR is_home() ) { 
					echo ' current_page_item';	
				}
				echo '"><a href="' . $homeURL . '/" title="'; bloginfo('name'); echo '"><span>' . 
				$bfa_ata['home_page_menu_bar'] . '</span></a></li>' . "\n";	
			}
			
			// Empty setting "levels" same as 0
			if ( $bfa_ata['levels_page_menu_bar'] == '' ) 
				$bfa_ata['levels_page_menu_bar'] = 0; 	
			
			echo bfa_hor_pages($bfa_ata['sorting_page_menu_bar'], $bfa_ata['levels_page_menu_bar'], 
			$bfa_ata['titles_page_menu_bar'], $bfa_ata['exclude_page_menu_bar']);
			
			// Close table if centered
			if ( strpos($header_items,"%page-center") !== FALSE ) 
				echo '</ul></td></tr></table></div>' . "\n";
			else 
				echo '</ul></div>' . "\n";

			$page_menu_bar = ob_get_contents(); 
			ob_end_clean();
		}

	}

	// Category Menu Bar 
	if ( strpos($header_items,'%cats') !== FALSE OR strpos($header_items,'%cat-center') !== FALSE 
	OR strpos($header_items,'%cat-right') !== FALSE ) {

		// Since 3.5.2: New WP 3 menu system:
		if ( has_nav_menu('menu2') ) 
		{
			if ( strpos($header_items,'%cats') !== FALSE ) 
				$alignment = "left";
			elseif ( strpos($header_items,'%cat-center') !== FALSE ) 
				$alignment = "center";
			else 
				$alignment = "right";
				
			$cat_menu_bar = bfa_new_wp3_menus("menu2", $alignment );
		} 
		
		// Old custom Atahualpa menu system:
		else 
		{
			ob_start();
			echo '<div id="menu2">';

			if ( strpos($header_items,"%cat-right") !== FALSE ) 
				echo '<ul id="rmenu" class="dropdown clearfix rMenu-hor rMenu-hRight rMenu">' . "\n";
			elseif ( strpos($header_items,"%cat-center") !== FALSE ) 
				echo '<table cellpadding="0" cellspacing="0" style="margin: 0 auto"><tr><td align="center">
				<ul id="rmenu" class="clearfix rMenu-hor rMenu">' . "\n";
			else 
				echo '<ul id="rmenu" class="clearfix rMenu-hor rMenu">' . "\n";
			
			// Home Link?	
			if ( $bfa_ata['home_cat_menu_bar'] != '' ) 
			{
				echo '<li class="cat-item';
				if ( is_front_page() OR is_home() )  
						echo ' current-cat';
				echo '"><a href="' . $homeURL . '/" title="'; bloginfo('name'); echo '">' . 
				$bfa_ata['home_cat_menu_bar'] . '</a></li>' . "\n";	
			}	

			// Empty setting "levels" same as 0
			if ( $bfa_ata['levels_cat_menu_bar'] == '' ) 
				$bfa_ata['levels_cat_menu_bar'] = 0; 
			
			// Create menu list
			echo bfa_hor_cats($bfa_ata['sorting_cat_menu_bar'], $bfa_ata['order_cat_menu_bar'], 
			$bfa_ata['levels_cat_menu_bar'], $bfa_ata['titles_cat_menu_bar'], $bfa_ata['exclude_cat_menu_bar']);
			
			// Close table if centered
			if ( strpos($header_items,"%cat-center") !== FALSE ) 
				echo '</ul></td></tr></table></div>' . "\n";
			else 
				echo '</ul></div>' . "\n";
			
			$cat_menu_bar = ob_get_contents(); 
			ob_end_clean();
		}
	}

	// Logo Area 
	if ( strpos($header_items,'%logo') !== FALSE ) {

		ob_start();
		echo '<table id="logoarea" cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';

		if ( $bfa_ata['show_search_box'] == "Yes" AND ($bfa_ata['show_posts_icon'] == "Yes" OR 
		$bfa_ata['show_email_icon'] == "Yes" OR $bfa_ata['show_comments_icon'] == "Yes") )  
			$header_rowspan = 'rowspan="2" '; 
		else  
			$header_rowspan = ''; 

		// Logo Icon
		if ( $bfa_ata['logo'] != "" ) {
			if($bfa_ata['images_root'] == "atahualpa") {
				$imgdir  = get_template_directory_uri() . '/images/';
			} else {
				if(!isset($bfa_ata['ata_images_dir']) 
				OR ($bfa_ata['ata_images_dir'] == '') ) {	
					$img_folder = 'ata-images';  
					$imgdir  = content_url() . '/ata-images/';
				} else {
					$imgdir  = content_url() . '/' . $bfa_ata['ata_images_dir'] . '/';
				}
			}
			$logo = $imgdir.$bfa_ata['logo'];

			echo '<td ' . $header_rowspan . 'valign="middle" class="logoarea-logo"><a href="' 
				. $homeURL . '/">';
			echo '<img class="logo" src="'.$logo . '" alt="';
			bloginfo('name'); 
			echo '" /></a></td>';
			} 


			// Blog title and description
			if ( $bfa_ata['blog_title_show'] == "Yes" OR $bfa_ata['blog_tagline_show'] == "Yes" ) {
				
				echo '<td ' . $header_rowspan . 'valign="middle" class="logoarea-title">';
				
				if ( $bfa_ata['blog_title_show'] == "Yes" ) {
					echo '<h' . $bfa_ata['h_blogtitle'] . ' class="blogtitle"><a href="' 
					. $homeURL . '/">'; bloginfo('name'); echo '</a></h' . $bfa_ata['h_blogtitle'] . '>'; 
				}
				
				if ( $bfa_ata['blog_tagline_show'] == "Yes" ) {
					echo '<p class="tagline">'; bloginfo('description'); echo '</p>'; 
				}
				
				echo '</td>';
			}

			// is any feed icon or link active?
			if ( $bfa_ata['show_posts_icon'] == "Yes" OR $bfa_ata['show_email_icon'] == "Yes" OR 
			$bfa_ata['show_comments_icon'] == "Yes" ) 
				echo '<td class="feed-icons" valign="middle" align="right"><div class="clearfix rss-box">';

			// COMMENT Feed link
			if ( $bfa_ata['show_comments_icon'] == "Yes" ) { 
				
				echo '<a class="comments-icon" '; 
				
				if ( $bfa_ata['nofollow'] == "Yes" ) 
					echo 'rel="nofollow" '; 
				
				echo 'href="'; bloginfo('comments_rss2_url'); echo '" title="' . 
				$bfa_ata['comment_feed_link_title'] . '">' . $bfa_ata['comment_feed_link'] . '</a>';			
			} 
			
			// Feedburner Email link
			if ( $bfa_ata['show_email_icon'] == "Yes" ) { 
				
				echo '<a class="email-icon" '; 
				
				if ( $bfa_ata['nofollow'] == "Yes" )  
					echo 'rel="nofollow" '; 
				
				echo 'href="http://' . ($bfa_ata['feedburner_old_new'] == 'New - at feedburner.google.com' ? 
				'feedburner.google.com/fb/a/mailverify?uri=' : 'www.feedburner.com/fb/a/emailverifySubmit?feedId=') . 
				$bfa_ata['feedburner_email_id'] . '&amp;loc=' . get_locale() . '" title="' . 
				$bfa_ata['email_subscribe_link_title'] . '">' . $bfa_ata['email_subscribe_link'] . '</a>';			
			} 
	
			// POSTS Feed link
			if ( $bfa_ata['show_posts_icon'] == "Yes" ) 
			{ 
				echo '<a class="posts-icon" '; 
				
				if ( $bfa_ata['nofollow'] == "Yes" ) 
					echo 'rel="nofollow" '; 
				
				echo 'href="'; bloginfo('rss2_url'); echo '" title="' . 
				$bfa_ata['post_feed_link_title'] . '">' . 
				$bfa_ata['post_feed_link'] . '</a>';	
			} 

			if ( $bfa_ata['show_posts_icon'] == "Yes" OR $bfa_ata['show_email_icon'] == "Yes" OR 
			$bfa_ata['show_comments_icon'] == "Yes" ) {
				echo '</div></td>';
				if ( $bfa_ata['show_search_box'] == "Yes" )  
					echo '</tr><tr>';
			}	

			// Search box
			if ( $bfa_ata['show_search_box'] == "Yes" ) 
			{ 
				echo '<td valign="bottom" class="search-box" align="right"><div class="searchbox">
					<form method="get" class="searchform" action="' . $homeURL . '/">
					<div class="searchbox-form">' . 
					// Since 3.6.8: Removed check whether get_search_query() exists and added esc_js 
						'<input type="text" class="text inputblur" onfocus="this.value=\'' .
						( get_search_query() ? esc_js(get_search_query()) : '' ) . '\'" 
						value="' . ( get_search_query() ? esc_js(get_search_query()) : esc_attr($bfa_ata['searchbox_text']) ) . 
						'" onblur="this.value=\'' . ( get_search_query() ? esc_js(get_search_query()) : 
						esc_attr($bfa_ata['searchbox_text']) ) . '\'" name="s" />' .
					'</div>
					</form>
				</div>
				</td>';
			} 

		echo '</tr></table>';	
		$logo_area = ob_get_contents(); 
		ob_end_clean();
	}

	// Header Image
	if ( strpos($header_items,'%image') !== FALSE ) {

		// force 'top left' alignment if rotating and fading is on
		if (($bfa_ata['header_image_javascript'] !== "0") AND
		($bfa_ata['crossslide_fade'] !== "0")) {
			$bfa_ata['headerimage_alignment'] = 'top left';
		}
		
		ob_start();
		$bfa_header_images = bfa_rotating_header_images();
		
		echo '<div id="imagecontainer-pre" class="header-image-container-pre">';

		echo '    <div id="imagecontainer" class="header-image-container" style="background: url(' . 
		$bfa_header_images[array_rand($bfa_header_images)] . ') ' . $bfa_ata['headerimage_alignment'] . 
		' no-repeat;">';
		echo '</div>';
		if ( $bfa_ata['header_image_clickable'] == "Yes" ) {
			echo '<div class="clickable"><a class="divclick" title="'; 
			bloginfo('name'); echo '" href ="' . $homeURL . '/">&nbsp;</a></div>';
		}

	// Header Code Overlay 
	if ( isset($bfa_ata['overlay_header_image']) ) {
	$overlay_image_code = $bfa_ata['overlay_header_image'];
	// Parse PHP code
		if ( strpos($overlay_image_code,'<?php ') !== FALSE ) {
			ob_start(); 
				bfa_incl('overlay_header_image');
				$overlay_image_code = ob_get_contents(); 
			ob_end_clean();
		}

		echo '<div class="codeoverlay">'; 			
		echo $overlay_image_code;
		echo '</div>';
	}

		if ( $bfa_ata['header_opacity_left'] != 0 AND $bfa_ata['header_opacity_left'] != '' )  
			echo '<div class="opacityleft">&nbsp;</div>';

		if ( $bfa_ata['header_opacity_right'] != 0 AND $bfa_ata['header_opacity_right'] != '' )  
			echo '<div class="opacityright">&nbsp;</div>';
		// END: If Header Opacity 

		if ( $bfa_ata['overlay_blog_title'] == "Yes" OR $bfa_ata['overlay_blog_tagline'] == "Yes" ) 
		{
			echo '<div class="titleoverlay">'; 
			
			if ( $bfa_ata['overlay_blog_title'] == "Yes" ) {
				echo '<h' . $bfa_ata['h_blogtitle'] . ' class="blogtitle"><a href="' . $homeURL . '/">'; 
				bloginfo('name'); echo '</a></h' . $bfa_ata['h_blogtitle'] . '>';
			}
			
			if ( $bfa_ata['overlay_blog_tagline'] == "Yes" ) { 
				echo '<p class="tagline">'; bloginfo('description'); echo '</p>';
			}
			echo '</div>';
		}

		echo '</div>';
		$header_image = ob_get_contents(); 
		ob_end_clean();
	}

	// Horizontal bar 1
	if ( strpos($header_items,'%bar1') !== FALSE ) 
		$horizontal_bar1 = '<div class="horbar1">&nbsp;</div>';

	// Horizontal bar 2
	if ( strpos($header_items,'%bar2') !== FALSE ) 
		$horizontal_bar2 = '<div class="horbar2">&nbsp;</div>';

	$header_item_numbers = array(
		"%pages", "%page-center", "%page-right", 
		"%cats", "%cat-center", "%cat-right", 
		"%logo", 
		"%image", 
		"%bar1", "%bar2"
	);
		
	$header_output = array(
		$page_menu_bar, $page_menu_bar, $page_menu_bar, 
		$cat_menu_bar, $cat_menu_bar, $cat_menu_bar, 
		$logo_area, 
		$header_image, 
		$horizontal_bar1, $horizontal_bar2
	);

	$header_items = trim($header_items);
	$final_header = str_replace($header_item_numbers, $header_output, $header_items);
	
	// Parse widget areas:
	$final_header = bfa_parse_widget_areas($final_header);

	return $final_header;
}
?>