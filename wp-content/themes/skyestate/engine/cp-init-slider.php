<?php
function nvr_post_type_slider() {
	register_post_type( 'slider-view',
                array( 
				'label' => __('Sliders', THE_LANG ), 
				'labels' => array(
					'add_new_item' => 'Add New Slider',
					'edit_item' => 'Edit Slider',
				),
				'public' => true, 
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'slider-view', 'with_front' => false ),
				'hierarchical' => true,
				'menu_position' => 5,
				'exclude_from_search' =>true,
				'supports' => array(
				                     'title',
									 'editor',
                                     'thumbnail',
									 'custom-fields')
					) 
				);
				
	register_taxonomy('slidercat', 'slider-view',array('hierarchical' => true, 'label' =>  __('Slider Categories', THE_LANG ), 'singular_name' => __('Category', THE_LANG ))
	);
}
add_action('init', 'nvr_post_type_slider');
add_filter('manage_edit-slider-view_columns', 'nvr_slider_add_list_columns');
add_action('manage_slider-view_posts_custom_column', 'nvr_slider_manage_column');
//add_action( 'restrict_manage_posts', 'nvr_slider_add_taxonomy_filter');

function nvr_slider_add_list_columns($slider_columns){
		
	$nvr_new_columns = array();
	$nvr_new_columns['cb'] = '<input type="checkbox" />';
	
	$nvr_new_columns['title'] = __('Title', THE_LANG);
	$nvr_new_columns['images'] = __('Images', THE_LANG);
	$nvr_new_columns['author'] = __('Author', THE_LANG);
	
	$nvr_new_columns['slidercat'] = __('Categories', THE_LANG);
	
	$nvr_new_columns['date'] = __('Date', THE_LANG);
	
	return $nvr_new_columns;
}

function nvr_slider_manage_column($column_name){
	global $post;
	$nvr_posttype = 'slider-view';
	$nvr_taxonom = 'slidercat';
	
	$nvr_id = $post->ID;
	$nvr_title = $post->post_title;
	switch($column_name){
		case 'images':
			$nvr_thumbnailid = get_post_thumbnail_id($nvr_id);
			$nvr_imagesrc = wp_get_attachment_image_src($nvr_thumbnailid, 'thumbnail');
			if($nvr_imagesrc){
				echo '<img src="'.esc_url( $nvr_imagesrc[0] ).'" width="50" alt="'.esc_attr( $nvr_title ).'" />';
			}else{
				_e('No Featured Image', THE_LANG);
			}
			break;
		
		case 'slidercat':
			$nvr_postterms = get_the_terms($nvr_id, $nvr_taxonom);
			if($nvr_postterms){
				$nvr_termlists = array();
				foreach($nvr_postterms as $nvr_postterm){
					$nvr_termlists[] = '<a href="'.esc_url( admin_url('edit.php?'.$nvr_taxonom.'='.$nvr_postterm->slug.'&post_type='.$nvr_posttype) ).'">'.$nvr_postterm->name.'</a>';
				}
				if(count($nvr_termlists)>0){
					$nvr_termtext = implode(", ",$nvr_termlists);
					echo $nvr_termtext;
				}
			}
			
			break;
	}
}

/* Filter Custom Post Type Categories */
function nvr_slider_add_taxonomy_filter() {
	global $typenow;
	$nvr_posttype = 'slider-view';
	$nvr_taxonomy = 'slidercat';
	if( $typenow==$nvr_posttype){
		$nvr_filters = array($nvr_taxonomy);
		foreach ($nvr_filters as $nvr_tax_slug) {
			$nvr_tax_obj = get_taxonomy($nvr_tax_slug);
			$nvr_tax_name = $nvr_tax_obj->labels->name;
			$nvr_terms = get_terms($nvr_tax_slug);
			echo '<select name="'.esc_attr( $nvr_tax_slug ).'" id="'.esc_attr( $nvr_tax_slug ).'" class="postform">';
			echo '<option value="">'.__('View All',THE_LANG)." ". $nvr_tax_name .'</option>';
			if(count($nvr_terms)){
				foreach ($nvr_terms as $nvr_term) { 
					$nvr_selectedstr = '';
					if(isset($_GET[$nvr_tax_slug]) && $_GET[$nvr_tax_slug] == $nvr_term->slug){
						$nvr_selectedstr = ' selected="selected"';
					}
					echo '<option value="'. esc_attr( $nvr_term->slug) .'" '.$nvr_selectedstr.'>' . $nvr_term->name .' (' . $nvr_term->count .')</option>'; 
				}
			}
			echo "</select>";
		}
	}
}