<?php 

$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

global $wpdb;

$nvr_optsidebar = array(
	$nvr_shortname . "-sidebar" => "Sidebar", 
);
$nvr_optionsidebarval = get_option( $nvr_shortname . '_sidebar');
	if(is_array($nvr_optionsidebarval)){
		
		foreach($nvr_optionsidebarval as $ids => $val){
			$nvr_optsidebar[$ids] = $val;
		}
		
	}

$nvr_peoplequery = new WP_Query(array(
	'post_type' => 'peoplepost',
	'posts_per_page' => -1
));

$nvr_optpropagent = array(
	'none' => __('None', THE_LANG)
);

while($nvr_peoplequery->have_posts() ){
	$nvr_peoplequery->next_post();
	$nvr_optpropagent[$nvr_peoplequery->post->post_name] = $nvr_peoplequery->post->post_title;
}
wp_reset_postdata();

$nvr_propstatuses = nvr_get_option($nvr_shortname.'_property_status');
$nvr_optpropstatus = array(
	'Normal' => __('Normal', THE_LANG)
);
if($nvr_propstatuses){
	for($i=0;$i<count($nvr_propstatuses);$i++){
		$nvr_propstatus = $nvr_propstatuses[$i];
		$nvr_optpropstatus[$nvr_propstatus] = $nvr_propstatus;
	}
}

$nvr_propamenities = nvr_get_option($nvr_shortname.'_property_amenities');
$nvr_optpropamenities = array();

if($nvr_propamenities){
	for($i=0;$i<count($nvr_propamenities);$i++){
		$nvr_propamenity = $nvr_propamenities[$i];
		$nvr_optpropamenities[$nvr_propamenity] = $nvr_propamenity;
	}
}

$nvr_propcustoms = nvr_get_option($nvr_shortname.'_property_custom');
$nvr_textpropcustomfields = array();

if($nvr_propcustoms){
	for($i=0;$i<count($nvr_propcustoms);$i++){
		$nvr_propcustom = $nvr_propcustoms[$i];
		$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
		$nvr_textpropcustomfields[] = array(
			'name' => $nvr_propcustom,
			'id' => $nvr_initial.'_custom_'.$nvr_cusslug,
			'type' => 'text',
			'desc' => '',
			'std' => ''
		);
		
	}
}
/* Option */
$nvr_optonoff = array(
	'true' => 'On',
	'false' => 'Off'
);

$nvr_optyesno = array(
	'true' => 'Yes',
	'false' => 'No'
);

$nvr_optslidertype = array(
	'slider' => 'Slider',
	'mapslider' => 'Maps Slider'
);

$nvr_optlayout = array(
	'' => 'Default',
	'left' => 'Left',
	'right' => 'Right'
);

$nvr_optbloglayout = array(
	'' => 'Default',
	'3col-masonry' => 'Masonry 3 Columns',
	'2col-masonry' => 'Masonry 2 Columns'
);

$nvr_opttextalign = array(
	'left' => 'Left',
	'right' => 'Right'
);

$nvr_optslidertextalign = array(
	'top' => 'Top',
	'left' => 'Left',
	'right' => 'Right'
);

$nvr_optpcolumns = array(
	'' => 'Default',
	'classic-2-space' => 'Classic Two Columns',
	'classic-3-space' => 'Classic Three Columns',
	'classic-4-space' => 'Classic Four Columns',
	'masonry-3-space' => 'Masonry Three Columns with space',
	'masonry-4-space' => 'Masonry Four Columns with space',
	'masonry-5-space' => 'Masonry Five Columns with space',
	'masonry-3-nospace' => 'Masonry Three Columns with no space',
	'masonry-4-nospace' => 'Masonry Four Columns with no space',
	'masonry-5-nospace' => 'Masonry Five Columns with no space',
	'grid-3-space'	=> 'Grid Three Columns with space',
	'grid-4-space'	=> 'Grid Four Columns with space',
	'grid-5-space'	=> 'Grid Five Columns with space',
	'grid-3-nospace'	=> 'Grid Three Columns with no space',
	'grid-4-nospace'	=> 'Grid Four Columns with no space',
	'grid-5-nospace'	=> 'Grid Five Columns with no space'
);

$nvr_optpcontainer = array(
	'' => 'Default',
	'nvr-fullwidthwrap' => '100% Full-Width'
);

$nvr_optpitemtype = array(
	'' => 'Default',
	'square' => 'Square',
	'portrait' => 'Portrait',
	'landscape' => 'Landscape'
);

$nvr_countries = nvr_country_list();
$nvr_optcountry = array(
	'' => __('Select Country', THE_LANG)
);
for($i=0;$i<count($nvr_countries);$i++){
	$nvr_country = $nvr_countries[$i];
	$nvr_optcountry[$nvr_country] = $nvr_country;
}

$nvr_optarrange = array(
	'ASC' => 'Ascending',
	'DESC' => 'Descending'
);

$nvr_optbgrepeat = array(
	'' => 'Default',
	'repeat' => 'repeat',
	'no-repeat' => 'no-repeat',
	'repeat-x' => 'repeat-x',
	'repeat-y' => 'repeat-y'
);

$nvr_optbgattch = array(
	'' => 'Default',
	'scroll' => 'scroll',
	'fixed' => 'fixed'
);

$nvr_imagepath =  get_template_directory_uri() . '/images/backendimage/';
$nvr_optlayoutimg = array(
	'default' => $nvr_imagepath.'mb-default.png',
	'one-col' => $nvr_imagepath.'mb-1c.png',
	'two-col-left' => $nvr_imagepath.'mb-2cl.png',
	'two-col-right' => $nvr_imagepath.'mb-2cr.png'
);

$nvr_opttimeunit = array(
	'Day' => __('Day', THE_LANG),
	'Week' => __('Week', THE_LANG),
	'Month' => __('Month', THE_LANG),
	'Year' => __('Year', THE_LANG)
);

$nvr_optinvoicetype = array(
	'Listing' => __('Listing', THE_LANG),
	'Upgrade to Featured' => __('Upgrade to Featured', THE_LANG),
	'Publish Listing with Featured' => __('Publish Listing with Featured', THE_LANG),
	'Package' => __('Package', THE_LANG)
);

$nvr_optinvoiceperiod = array(
	'One Time' => __('One Time', THE_LANG),
	'Recurring' => __('Recurring', THE_LANG)
);

// Create meta box slider
global $nvr_meta_boxes;
$nvr_meta_boxes = array();

$nvr_meta_boxes[] = array(
	'id' => 'post-option-meta-box',
	'title' => __('Post Options',THE_LANG),
	'page' => 'post',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific post/page. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		),
		array(
			'name' => __('External URL',THE_LANG),
			'desc' => '<em>'.__('Input your external link in here. if you use "Link" format.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_external_url',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Audio File URL',THE_LANG),
			'desc' => '<em>'.__('Input your audio file URL in here. ',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_audio_url',
			'type' => 'textarea',
			'std' => ''
		),
		array(
			'name' => __('Video File URL / Video Link',THE_LANG),
			'desc' => '<em>'.__('Input your video file URL or video link like youtube or vimeo in here. ',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_video_url',
			'type' => 'textarea',
			'std' => ''
		)
	)
);


$nvr_meta_boxes[] = array(
	'id' => 'page-option-meta-box',
	'title' => __('Page Options',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific post/page. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		),
		array(
			'name' => __('Disable Page Title',THE_LANG),
			'desc' => '<em>'.__('Choose \'Yes\' if you want to remove the page title.',THE_LANG).'</em>',
			'id' => 'disable_title',
			'type' => 'select',
			'options' => $nvr_optyesno,
			'std' => 'false'
		),
		array(
			'name' => __('Background Header',THE_LANG),
			'desc' => '<em>'.__('Input the image URL in this textbox if you want to change the background image on the header.',THE_LANG).'</em>',
			'id' => 'bg_header',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Background Color Maincontent',THE_LANG),
			'desc' => '<em>'.__('Input the hexcolor in this textbox if you want to change the background color of your content.',THE_LANG).'</em>',
			'id' => 'bg_color_maincontent',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Page Description',THE_LANG),
			'desc' => '<em>'.__('Input your own page description here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_pagedesc',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'page-sidebar-meta-box',
	'title' => __('Sidebar Option',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'side',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Registered Sidebar',THE_LANG),
			'desc' => '<em>'.__('Please choose the sidebar for this page',THE_LANG).'</em>',
			'options' => $nvr_optsidebar,
			'id' => '_'.$nvr_initial.'_sidebar',
			'type' => 'select',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'page-slider-option-meta-box',
	'title' => __('Page Slider Options',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Enable Slider',THE_LANG),
			'desc' => '<em>'.__('Choose \'On\' if you want to show the slider.',THE_LANG).'</em>',
			'id' => 'enable_slider',
			'type' => 'select',
			'options' => $nvr_optonoff,
			'std' => 'false'
		),
		array(
			'name' => __('Slider Type',THE_LANG),
			'desc' => '<em>'.__('Choose the type of the slider',THE_LANG).'</em>',
			'id' => 'slider_type',
			'type' => 'select',
			'options' => $nvr_optslidertype,
			'std' => ''
		),
		array(
			'name' => __('Slider Category',THE_LANG),
			'desc' => '<em>'.__('You need to select the slider category to make the slider works.',THE_LANG).'</em>',
			'id' => 'slider_category',
			'type' => 'select-slider-category',
			'std' => ''
		),
		array(
			'name' => __('External Slider Shortcode',THE_LANG),
			'desc' => '<em>'.__('You can put the layerslider or revolution slider shortcode in here. It will overwrite the slider category.',THE_LANG).'</em>',
			'id' => 'slider_layerslider',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Number of Pins in Slider Maps',THE_LANG),
			'desc' => '<em>'.__('Please input the number of pins that you want to show (For Slider Maps type only). ',THE_LANG).'</em>',
			'id' => 'slider_numpins',
			'type' => 'text',
			'std' => '12'
		),
		array(
			'name' => __('Zoom',THE_LANG),
			'desc' => '<em>'.__('You can input the custom zoom value for the maps in here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_mapzoom',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'page-blog-option-meta-box',
	'title' => __('Page Blog Options',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Blog Categories',THE_LANG),
			'desc' => '<em>'.__('You need to tick the blog categories to make the template blog works.',THE_LANG).'</em>',
			'id' => 'blog_category',
			'type' => 'checkbox-blog-categories',
			'std' => ''
		),
		array(
			'name' => __('Blog Type',THE_LANG),
			'desc' => '<em>'.__('Choose the type of the blog that you want to show.',THE_LANG).'</em>',
			'id' => 'blog_layout',
			'type' => 'select',
			'options' => $nvr_optbloglayout,
			'std' => ''
		),
		array(
			'name' => __('Use Infinite Scrolls?',THE_LANG),
			'desc' => '<em>'.__('Choose \'On\' if you want to use infinite scrolls.',THE_LANG).'</em>',
			'id' => 'blog_infscrolls',
			'type' => 'select',
			'options' => $nvr_optonoff,
			'std' => 'false'
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'page-portfolio-option-meta-box',
	'title' => __('Page Portfolio Options',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Portfolio Type',THE_LANG),
			'desc' => '<em>'.__('Select the type of your portfolio.',THE_LANG).'</em>',
			'id' => 'p_type',
			'type' => 'select',
			'options' => $nvr_optpcolumns,
			'std' => '3'
		),
		array(
			'name' => __('Portfolio Container',THE_LANG),
			'desc' => '<em>'.__('Select the type of container for your portfolio.',THE_LANG).'</em>',
			'id' => 'p_container',
			'type' => 'select',
			'options' => $nvr_optpcontainer,
			'std' => ''
		),
		array(
			'name' => __('Portfolio Categories',THE_LANG),
			'desc' => '<em>'.__('Select more than one portfolio category to make the portfolio filter works.',THE_LANG).'</em>',
			'id' => 'p_categories',
			'type' => 'checkbox-portfolio-categories',
			'std' => ''
		),
		array(
			'name' => __('Use Auto Load More?',THE_LANG),
			'desc' => '<em>'.__('Tick this checkbox if you want to use a Load More functionality.',THE_LANG).'</em>',
			'id' => 'p_loadmore',
			'type' => 'checkbox',
			'std' => ''
		),
		array(
			'name' => __('Portfolio Showposts',THE_LANG),
			'desc' => '<em>'.__('Input the number of portfolio items that you want to show per page.',THE_LANG).'</em>',
			'id' => 'p_showpost',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Portfolio Order By',THE_LANG),
			'desc' => '<em>'.__('(optional). Sort retrieved portfolio items by parameter. Defaults to \'date\'',THE_LANG).'</em>',
			'id' => 'p_orderby',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Portfolio Order',THE_LANG),
			'desc' => '<em>'.__('(optional). Designates the ascending or descending order of the \'Portfolio Order By\' parameter. Defaults to \'DESC\'.',THE_LANG).'</em>',
			'id' => 'p_sort',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'page-property-option-meta-box',
	'title' => __('Page Property Options',THE_LANG),
	'page' => 'page',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Property Categories',THE_LANG),
			'desc' => '<em>'.__('Select more than one property category to make the property filter works.',THE_LANG).'</em>',
			'id' => 'prop_categories',
			'type' => 'checkbox-property-categories',
			'std' => ''
		),
		array(
			'name' => __('Property Showposts',THE_LANG),
			'desc' => '<em>'.__('Input the number of property items that you want to show per page.',THE_LANG).'</em>',
			'id' => 'prop_showpost',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Property Order By',THE_LANG),
			'desc' => '<em>'.__('(optional). Sort retrieved property items by parameter. Defaults to \'date\'',THE_LANG).'</em>',
			'id' => 'prop_orderby',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Property Order',THE_LANG),
			'desc' => '<em>'.__('(optional). Designates the ascending or descending order of the \'Property Order By\' parameter. Defaults to \'DESC\'.',THE_LANG).'</em>',
			'id' => 'prop_sort',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'portfolio-option-meta-box',
	'title' => __('Portfolio Options',THE_LANG),
	'page' => 'portofolio',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific post/page. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		),
		array(
			'name' => __('Image Size',THE_LANG),
			'desc' => '<em>'.__('Select the image size for your portfolio item.',THE_LANG).'</em>',
			'options' => $nvr_optpitemtype,
			'id' => '_'.$nvr_initial.'_pimgsize',
			'type' => 'select',
			'std' => ''
		),
		array(
			'name' => __('Custom Thumbnail',THE_LANG),
			'desc' => '<em>'.__('(optional). You can input the custom image URL to override the \'Set Featured Image\'',THE_LANG).'</em>',
			'id' => 'custom_thumb',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('External Link',THE_LANG),
			'desc' => '<em>'.__('(optional). You can input the URL if you want to link the portfolio item to another website.',THE_LANG).'</em>',
			'id' => 'external_link',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'portfolio-gallery-option-meta-box',
	'title' => __('Portfolio Gallery',THE_LANG),
	'page' => 'portofolio',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Portfolio Images Gallery',THE_LANG),
			'desc' => '<em>'.__('You can select the images for your portfolio from here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_imagesgallery',
			'type' => 'imagegallery',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-option-meta-box',
	'title' => __('Property Options',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific post/page. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		),
		array(
			'name' => __('Featured Property?',THE_LANG),
			'desc' => '<em>'.__('Select Yes if you want this property to be featured.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_featured',
			'type' => 'select',
			'options' => $nvr_optyesno,
			'std' => 'no'
		),
		array(
			'name' => __('Property Status',THE_LANG),
			'desc' => '<em>'.__('Select the status of the property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_status',
			'type' => 'select',
			'options' => $nvr_optpropstatus,
			'std' => 'Normal'
		),
		array(
			'name' => __('Agent responsible',THE_LANG),
			'desc' => '<em>'.__('Select the agent of the property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_agent',
			'type' => 'select',
			'options' => $nvr_optpropagent,
			'std' => 'none'
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-detail-meta-box',
	'title' => __('Property Detail',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Price',THE_LANG),
			'desc' => '<em>'.__('Input the price of your property',THE_LANG).'</em>',
			'id' => $nvr_initial.'_price',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Price Label',THE_LANG),
			'desc' => '<em>'.__('Input after price label. Example: per month',THE_LANG).'</em>',
			'id' => $nvr_initial.'_price_label',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Size',THE_LANG),
			'desc' => '<em>'.__('Input the size of your property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_size',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Lot Size',THE_LANG),
			'desc' => '<em>'.__('Input the lot size of your property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_lot_size',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Bed Rooms',THE_LANG),
			'desc' => '<em>'.__('Input the number of rooms in your property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_room',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Bath Rooms',THE_LANG),
			'desc' => '<em>'.__('Input the number of bath rooms in your property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_bathroom',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-address-option-meta-box',
	'title' => __('Property Address',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Address',THE_LANG),
			'desc' => '<em>'.__('Input the property address.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_address',
			'type' => 'textarea',
			'std' => ''
		),
		array(
			'name' => __('County',THE_LANG),
			'desc' => '<em>'.__('Input the property county.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_county',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('State/Province',THE_LANG),
			'desc' => '<em>'.__('Input the property state or province.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_state',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Country',THE_LANG),
			'desc' => '<em>'.__('Select the property country.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_country',
			'type' => 'select',
			'options' => $nvr_optcountry,
			'std' => ''
		),
		array(
			'name' => __('Zip/Post Code',THE_LANG),
			'desc' => '<em>'.__('Input the property ZIP or post code.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_zipcode',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-gmaps-option-meta-box',
	'title' => __('Google Maps Settings',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Place Pin to Point Your Property',THE_LANG),
			'id' => $nvr_initial.'_gmaps',
			'type' => 'gmaps'
		),
		array(
			'name' => __('Latitude',THE_LANG),
			'desc' => '<em>'.__('You can input the latitude of your property here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_latitude',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Longitude',THE_LANG),
			'desc' => '<em>'.__('You can input the longitude of your property here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_longitude',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Zoom',THE_LANG),
			'desc' => '<em>'.__('You can input the custom zoom value for the maps in here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_mapzoom',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-gallery-option-meta-box',
	'title' => __('Properties Gallery',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Property Images Gallery',THE_LANG),
			'desc' => '<em>'.__('You can select the images for your property from here.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_imagesgallery',
			'type' => 'imagegallery',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-features-option-meta-box',
	'title' => __('Properties Features and Amenities',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Property Features and Amenities',THE_LANG),
			'desc' => '<em>'.__('You can select the features and amenities for your property.',THE_LANG).'</em>',
			'id' => $nvr_initial.'_amenities',
			'type' => 'checkbox-multiple',
			'options' => $nvr_optpropamenities,
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-slider-option-meta-box',
	'title' => __('Properties Slider Options',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Enable Slider',THE_LANG),
			'desc' => '<em>'.__('Choose \'On\' if you want to show the slider.',THE_LANG).'</em>',
			'id' => 'enable_slider',
			'type' => 'select',
			'options' => $nvr_optonoff,
			'std' => 'false'
		),
		array(
			'name' => __('External Slider Shortcode',THE_LANG),
			'desc' => '<em>'.__('You can put the layerslider or revolution slider shortcode in here. It will overwrite the default slider.',THE_LANG).'</em>',
			'id' => 'slider_layerslider',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'propertys-custom-option-meta-box',
	'title' => __('Properties Custom Fields',THE_LANG),
	'page' => 'propertys',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => $nvr_textpropcustomfields
);

$nvr_meta_boxes[] = array(
	'id' => 'people-option-meta-box',
	'title' => __('People Options',THE_LANG),
	'page' => 'peoplepost',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific people post. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		),
		array(
			'name' => __('People Information',THE_LANG),
			'desc' => '<em>'.__('Input your own people post information here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_info',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Mobile Phone',THE_LANG),
			'desc' => '<em>'.__('Input the people mobile phone information here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_phone',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Email',THE_LANG),
			'desc' => '<em>'.__('Input the people email information in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_email',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Skype',THE_LANG),
			'desc' => '<em>'.__('Input the people skype userid in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_skype',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Facebook',THE_LANG),
			'desc' => '<em>'.__('Input the people facebook id here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_facebook',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Pinterest',THE_LANG),
			'desc' => '<em>'.__('Input the people pinterest username in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_pinterest',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Twitter',THE_LANG),
			'desc' => '<em>'.__('Input the people twitter username in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_twitter',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Linkedin',THE_LANG),
			'desc' => '<em>'.__('Input the people linkedin link information here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_linkedin',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Youtube',THE_LANG),
			'desc' => '<em>'.__('Input the people youtube username in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_youtube',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Instagram',THE_LANG),
			'desc' => '<em>'.__('Input the people instagram username in here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_people_instagram',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Enable GMaps in People Detail?',THE_LANG),
			'desc' => '<em>'.__('Choose \'On\' if you want to show the maps in the people detail page.',THE_LANG).'</em>',
			'id' => 'enable_slider',
			'type' => 'select',
			'options' => $nvr_optonoff,
			'std' => 'true'
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'testimonial-option-meta-box',
	'title' => __('Testimonial Options',THE_LANG),
	'page' => 'testimonialpost',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Testimonial Information',THE_LANG),
			'desc' => '<em>'.__('Input your own testimonial post information here.',THE_LANG).'</em>',
			'id' => '_'.$nvr_initial.'_testi_info',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'brand-option-meta-box',
	'title' => __('Brand Options',THE_LANG),
	'page' => 'brand',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('External Link',THE_LANG),
			'desc' => '<em>'.__('Input the external link for your brand post in here. (optional)',THE_LANG).'</em>',
			'id' => 'external_link',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'slider-option-meta-box',
	'title' => __('Slider Options',THE_LANG),
	'page' => 'slider-view',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('External Link',THE_LANG),
			'desc' => '<em>'.__('(optional). You can input the URL if you want to link the slider image to another website.',THE_LANG).'</em>',
			'id' => 'external_link',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Custom Image URL',THE_LANG),
			'desc' => '<em>'.__('(optional). You can input the custom image URL to override the \'Set Featured Image\'',THE_LANG).'</em>',
			'id' => 'image_url',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Top Text 1',THE_LANG),
			'desc' => '<em>'.__('Input the text in here if you want to show the text on top of the title.',THE_LANG).'</em>',
			'id' => 'top_text1',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Top Text 2',THE_LANG),
			'desc' => '<em>'.__('Input the text in here if you want to show the text on top of the title.',THE_LANG).'</em>',
			'id' => 'top_text2',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Subtitle',THE_LANG),
			'desc' => '<em>'.__('Input the text in here if you want to show the text just below the title.',THE_LANG).'</em>',
			'id' => 'subtitle',
			'type' => 'text',
			'std' => ''
		),
		array(
			'name' => __('Bottom Text',THE_LANG),
			'desc' => '<em>'.__('Input the text in here if you want to show the text just below the content.',THE_LANG).'</em>',
			'id' => 'bottom_text',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_currency = nvr_get_option( $nvr_shortname."_submission_currency", "USD");
$nvr_meta_boxes[] = array(
	'id' => 'membership-option-meta-box',
	'title' => __('Package Details',THE_LANG),
	'page' => 'membership_package',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Billing Time Unit',THE_LANG),
			'desc' => '<em>'.__('Select the billing unit for this package.',THE_LANG).'</em>',
			'options' => $nvr_opttimeunit,
			'id' => 'billing_period',
			'type' => 'select',
			'std' => ''
		),
		
		array(
			'name' => __('Bill Every X Unit',THE_LANG),
			'desc' => '<em>'.__('Please input the number of x unit.',THE_LANG).'</em>',
			'id' => 'billing_freq',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Included Listings',THE_LANG),
			'desc' => '<em>'.__('How many listings are included? Please put -1 to make it unlimited',THE_LANG).'</em>',
			'id' => 'pack_listings',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Included Featured Listings',THE_LANG),
			'desc' => '<em>'.__('How many featured listings are included? Please put -1 to make it unlimited',THE_LANG).'</em>',
			'id' => 'pack_featured_listings',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Is Visible?',THE_LANG),
			'desc' => '<em>'.__('Please select Yes if you want to make it visible.',THE_LANG).'</em>',
			'id' => 'pack_visible',
			'type' => 'select',
			'options' => $nvr_optyesno,
			'std' => ''
		),
		
		array(
			'name' => __('Package Price In',THE_LANG).' '.$nvr_currency,
			'desc' => '<em>'.__('Please input the price of the package.',THE_LANG).'</em>',
			'id' => 'pack_price',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Package Stripe id',THE_LANG),
			'desc' => '<em>'.__('Please input the package stripe id in here.(ex:gold-pack)',THE_LANG).'</em>',
			'id' => 'pack_stripe_id',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'invoice-option-meta-box',
	'title' => __('Invoice Details',THE_LANG),
	'page' => 'novaro_invoice',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Billing For',THE_LANG),
			'desc' => '<em>'.__('Select the type of this invoice.',THE_LANG).'</em>',
			'options' => $nvr_optinvoicetype,
			'id' => 'invoice_type',
			'type' => 'select',
			'std' => ''
		),
		
		array(
			'name' => __('Billing Type',THE_LANG),
			'desc' => '<em>'.__('Select the billing type for this invoice.',THE_LANG).'</em>',
			'options' => $nvr_optinvoiceperiod,
			'id' => 'biling_type',
			'type' => 'select',
			'std' => ''
		),
		
		array(
			'name' => __('Item ID',THE_LANG),
			'desc' => '<em>'.__('Please input the Item ID for this invoice.',THE_LANG).'</em>',
			'id' => 'item_id',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Item Price',THE_LANG),
			'desc' => '<em>'.__('Please input the Item Price for this invoice.',THE_LANG).'</em>',
			'id' => 'item_price',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('Purchase Date',THE_LANG),
			'desc' => '<em>'.__('Please input the purchase date for this invoice.',THE_LANG).'</em>',
			'id' => 'purchase_date',
			'type' => 'text',
			'std' => ''
		),
		
		array(
			'name' => __('User ID',THE_LANG).' '.$nvr_currency,
			'desc' => '<em>'.__('Please input the User ID for this invoice.',THE_LANG).'</em>',
			'id' => 'buyer_id',
			'type' => 'text',
			'std' => ''
		)
	)
);

$nvr_meta_boxes[] = array(
	'id' => 'product-option-meta-box',
	'title' => __('Product Layout',THE_LANG),
	'page' => 'it_exchange_prod',
	'showbox' => 'meta_option_show_box',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => __('Layout',THE_LANG),
			'desc' => '<em>'.__('Select the layout you want on this specific post/page. Overrides default site layout.',THE_LANG).'</em>',
			'options' => $nvr_optlayoutimg,
			'id' => '_'.$nvr_initial.'_layout',
			'type' => 'selectimage',
			'std' => ''
		)
	)
);
?>