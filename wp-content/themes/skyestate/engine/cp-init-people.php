<?php
function nvr_post_type_people() {
	register_post_type( 'peoplepost',
                array( 
				'label' => __('People', THE_LANG ), 
				'labels' => array(
					'add_new_item' => 'Add New People',
					'edit_item' => 'Edit People',
				),
				'public' => true, 
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'people', 'with_front' => false ),
				'hierarchical' => true,
				'menu_position' => 5,
				'has_archive' => true,
				'exclude_from_search' =>false,
				'supports' => array(
				                     'title',
									 'editor',
                                     'thumbnail',
									 'custom-fields')
					) 
				);
				
	register_taxonomy('peoplecat', 'peoplepost',array('query_var' => true, 'hierarchical' => true, 'label' =>  __('People Categories', THE_LANG ), 'singular_name' => __('Category', THE_LANG ))
	);
}
add_action('init', 'nvr_post_type_people');
add_filter('manage_edit-peoplepost_columns', 'nvr_people_add_list_columns');
add_action('manage_peoplepost_posts_custom_column', 'nvr_people_manage_column');
add_action( 'restrict_manage_posts', 'nvr_people_add_taxonomy_filter');


function nvr_people_add_list_columns($peoplepost_columns){
		
	$nvr_new_columns = array();
	$nvr_new_columns['cb'] = '<input type="checkbox" />';
	
	$nvr_new_columns['title'] = __('Title', THE_LANG);
	$nvr_new_columns['images'] = __('Images', THE_LANG);
	$nvr_new_columns['author'] = __('Author', THE_LANG);
	
	$nvr_new_columns['peoplecat'] = __('Categories', THE_LANG);
	
	$nvr_new_columns['date'] = __('Date', THE_LANG);
	
	return $nvr_new_columns;
}

function nvr_people_manage_column($column_name){
	global $post;
	$nvr_posttype = 'peoplepost';
	$nvr_taxonom = 'peoplecat';
	
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
		
		case 'peoplecat':
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
function nvr_people_add_taxonomy_filter() {
	global $typenow;
	$nvr_posttype = 'peoplepost';
	$nvr_taxonomy = 'peoplecat';
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