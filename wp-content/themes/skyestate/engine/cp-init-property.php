<?php
function nvr_post_type_propertys() {
	register_post_type( 'propertys',
                array( 
				'label' => __('Properties', THE_LANG ), 
				'labels' => array(
					'add_new_item' => 'Add New Property',
					'edit_item' => 'Edit Property',
				),
				'public' => true, 
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'properties', 'with_front' => false ),
				'hierarchical' => true,
				'menu_position' => 5,
				'has_archive' => true,
				'exclude_from_search' =>true,
				'supports' => array(
				                     'title',
									 'editor',
                                     'thumbnail',
                                     'excerpt',
                                     'revisions',
									 'custom-fields',
									 'comments',
									 'page-attributes')
					) 
				);
	
	$nvr_taxonomyargs = array(
		'query_var' => true,
		'hierarchical' => true, 
		'label' =>  __('Property Categories', THE_LANG ), 
		'singular_name' => __('Category', THE_LANG )
	);
	register_taxonomy('property_category', 'propertys', $nvr_taxonomyargs );
	
	$nvr_taxonomyargs = array(
		'query_var' => true,
		'hierarchical' => true, 
		'label' =>  __('Property Purposes', THE_LANG ), 
		'singular_name' => __('Purposes', THE_LANG )
	);
	register_taxonomy('property_purpose', 'propertys', $nvr_taxonomyargs );
	
	$nvr_taxonomyargs = array(
		'query_var' => true,
		'hierarchical' => true, 
		'label' =>  __('Property City', THE_LANG ), 
		'singular_name' => __('Cities', THE_LANG )
	);
	register_taxonomy('property_city', 'propertys', $nvr_taxonomyargs );
}

add_action('init', 'nvr_post_type_propertys');
add_filter('manage_edit-propertys_columns', 'nvr_propertys_add_list_columns');
add_action('manage_propertys_posts_custom_column', 'nvr_propertys_manage_column');
add_action( 'restrict_manage_posts', 'nvr_propertys_add_taxonomy_filter');

function nvr_propertys_add_list_columns($propertys_columns){
		
	$nvr_new_columns = array();
	$nvr_new_columns['cb'] = '<input type="checkbox" />';
	
	$nvr_new_columns['title'] = __('Title', THE_LANG);
	$nvr_new_columns['images'] = __('Images', THE_LANG);
	$nvr_new_columns['author'] = __('Author', THE_LANG);
	
	$nvr_new_columns['property_category'] = __('Categories', THE_LANG);
	$nvr_new_columns['property_purpose'] = __('Purposes', THE_LANG);
	$nvr_new_columns['property_city'] = __('Cities', THE_LANG);
	
	$nvr_new_columns['date'] = __('Date', THE_LANG);
	
	return $nvr_new_columns;
}

function nvr_propertys_manage_column($column_name){
	global $post;
	$nvr_posttype = 'propertys';
	$nvr_taxonom = 'property_category';
	$nvr_taxonom2 = 'property_purpose';
	$nvr_taxonom3 = 'property_city';
	
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
		
		case 'property_category':
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
		
		case 'property_purpose':
			$nvr_postterms = get_the_terms($nvr_id, $nvr_taxonom2);
			if($nvr_postterms){
				$nvr_termlists = array();
				foreach($nvr_postterms as $nvr_postterm){
					$nvr_termlists[] = '<a href="'.esc_url( admin_url('edit.php?'.$nvr_taxonom2.'='.$nvr_postterm->slug.'&post_type='.$nvr_posttype) ).'">'.$nvr_postterm->name.'</a>';
				}
				if(count($nvr_termlists)>0){
					$nvr_termtext = implode(", ",$nvr_termlists);
					echo $nvr_termtext;
				}
			}
			
			break;
		
		case 'property_city':
			$nvr_postterms = get_the_terms($nvr_id, $nvr_taxonom3);
			if($nvr_postterms){
				$nvr_termlists = array();
				foreach($nvr_postterms as $nvr_postterm){
					$nvr_termlists[] = '<a href="'.esc_url( admin_url('edit.php?'.$nvr_taxonom3.'='.$nvr_postterm->slug.'&post_type='.$nvr_posttype) ).'">'.$nvr_postterm->name.'</a>';
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
function nvr_propertys_add_taxonomy_filter() {
	global $typenow;
	$nvr_posttype = 'propertys';
	$nvr_taxonomy = array('property_category','property_purpose','property_city');
	if( $typenow==$nvr_posttype){
		$nvr_filters = $nvr_taxonomy;
		foreach ($nvr_filters as $nvr_tax_slug) {
			$nvr_tax_obj = get_taxonomy($nvr_tax_slug);
			$nvr_tax_name = $nvr_tax_obj->labels->name;
			$nvr_terms = get_terms($nvr_tax_slug);
			echo "<select name='$nvr_tax_slug' id='$nvr_tax_slug' class='postform'>";
			echo "<option value=''>".__('View All',THE_LANG)." ". $nvr_tax_name ."</option>";
			if(count($nvr_terms)){
				foreach ($nvr_terms as $nvr_term) { 
					$nvr_selectedstr = '';
					if(isset($_GET[$nvr_tax_slug]) && $_GET[$nvr_tax_slug] == $nvr_term->slug){
						$nvr_selectedstr = ' selected="selected"';
					}
					echo '<option value="'. esc_attr( $nvr_term->slug). '" '. $nvr_selectedstr . '>' . $nvr_term->name .' (' . $nvr_term->count .')</option>'; 
				}
			}
			echo "</select>";
		}
	}
}

function nvr_change_posttype_propertys() {
  if( is_tax('property_category') && !is_admin() ) {
    set_query_var( 'post_type', array( 'post', 'propertys' ) );
  }
  
  if( is_tax('property_city') && !is_admin() ) {
    set_query_var( 'post_type', array( 'post', 'propertys' ) );
  }
  
  if( is_tax('property_purpose') && !is_admin() ) {
    set_query_var( 'post_type', array( 'post', 'propertys' ) );
  }
  return;
}
add_action( 'parse_query', 'nvr_change_posttype_propertys' );

class NVR_Property_Taxonomies {

	public function __construct() {

		// Add form
		add_action( 'property_category_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'property_category_edit_form_fields', array( $this, 'edit_category_fields' ), 10, 2 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );

		// Add columns
		add_filter( 'manage_edit-property_category_columns', array( $this, 'property_category_columns' ) );
		add_filter( 'manage_property_category_custom_column', array( $this, 'property_category_column' ), 10, 3 );

		// Maintain hierarchy of terms
		add_filter( 'wp_terms_checklist_args', array( $this, 'disable_checked_ontop' ) );
		
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	}

	public function add_category_fields() {
		?>
		<div class="form-field">
			<label><?php _e( 'Thumbnail', THE_LANG ); ?></label>
			<div id="property_category_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url( nvr_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="property_category_thumbnail_id" name="property_category_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', THE_LANG ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', THE_LANG ); ?></button>
			</div>
			<script type="text/javascript">

				 // Only show the "remove image" button when needed
				 if ( ! jQuery('#property_category_thumbnail_id').val() )
					 jQuery('.remove_image_button').hide();

				// Uploading files
				var file_frame;

				jQuery(document).on( 'click', '.upload_image_button', function( event ){

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', THE_LANG ); ?>',
						button: {
							text: '<?php _e( 'Use image', THE_LANG ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#property_category_thumbnail_id').val( attachment.id );
						jQuery('#property_category_thumbnail img').attr('src', attachment.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery(document).on( 'click', '.remove_image_button', function( event ){
					jQuery('#property_category_thumbnail img').attr('src', '<?php echo esc_js( nvr_placeholder_img_src() ); ?>');
					jQuery('#property_category_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}

	public function edit_category_fields( $term, $taxonomy ) {

		$image 			= '';
		$option = get_option('property_category_thumbnail_id');
		$thumbnail_id = ( $option && isset( $option[$term->term_id] ) ) ? $option[$term->term_id] : '';
		if ( $thumbnail_id )
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
			$image = nvr_placeholder_img_src();
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', THE_LANG ); ?></label></th>
			<td>
				<div id="property_category_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height:60px;">
					<input type="hidden" id="property_category_thumbnail_id" name="property_category_thumbnail_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
					<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', THE_LANG ); ?></button>
					<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', THE_LANG ); ?></button>
				</div>
				<script type="text/javascript">

					// Uploading files
					var file_frame;

					jQuery(document).on( 'click', '.upload_image_button', function( event ){

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( 'Choose an image', THE_LANG ); ?>',
							button: {
								text: '<?php _e( 'Use image', THE_LANG ); ?>',
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							attachment = file_frame.state().get('selection').first().toJSON();

							jQuery('#property_category_thumbnail_id').val( attachment.id );
							jQuery('#property_category_thumbnail img').attr('src', attachment.url );
							jQuery('.remove_image_button').show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery(document).on( 'click', '.remove_image_button', function( event ){
						jQuery('#property_category_thumbnail img').attr('src', '<?php echo esc_js( nvr_placeholder_img_src() ); ?>');
						jQuery('#property_category_thumbnail_id').val('');
						jQuery('.remove_image_button').hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}

	public function save_category_fields( $term_id, $tt_id, $taxonomy ) {
		
		$option = get_option('property_category_thumbnail_id');

		if ( isset( $_POST['property_category_thumbnail_id'] ) ) {
			$option[$term_id] = $_POST['property_category_thumbnail_id'];
			update_option( 'property_category_thumbnail_id', $option );
		}
	}

	public function property_category_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', THE_LANG );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}

	public function property_category_column( $columns, $column, $id ) {

		if ( $column == 'thumb' ) {

			$image 			= '';
			$option = get_option('property_category_thumbnail_id');
			$thumbnail_id = ( $option && isset( $option[$id] ) ) ? $option[$id] : '';

			if ($thumbnail_id)
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			else
				$image = nvr_placeholder_img_src();

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . __( 'Thumbnail', THE_LANG ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}

	public function disable_checked_ontop( $args ) {
		if ( 'product_cat' == $args['taxonomy'] ) {
			$args['checked_ontop'] = false;
		}
		return $args;
	}
}

new NVR_Property_Taxonomies();