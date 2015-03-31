<?php
/**
 * The Template for displaying single property posts.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */
get_header(); 

$nvr_initial = THE_INITIAL;
$nvr_shortname = THE_SHORTNAME;

$nvr_cursymbol = nvr_get_option( $nvr_shortname . '_currency_symbol');
$nvr_curplace = nvr_get_option( $nvr_shortname . '_currency_place');
$nvr_unit = nvr_get_option( $nvr_shortname . '_measurement_unit');
?>

	<?php 
	if ( have_posts() ) while ( have_posts() ) : the_post(); 
	
		$nvr_custom = nvr_get_customdata( get_the_ID() );
		echo "<pre>";print_r($nvr_custom);
		$nvr_status = (isset($nvr_custom[$nvr_initial."_status"][0]))? $nvr_custom[$nvr_initial."_status"][0] : '';
		$nvr_price = (isset($nvr_custom[$nvr_initial."_price"][0]))? $nvr_custom[$nvr_initial."_price"][0] : '';
		$nvr_plabel = (isset($nvr_custom[$nvr_initial."_price_label"][0]))? $nvr_custom[$nvr_initial."_price_label"][0] : '';
		$nvr_bed = (isset($nvr_custom[$nvr_initial."_room"][0]))? $nvr_custom[$nvr_initial."_room"][0] : '';
		$nvr_bath = (isset($nvr_custom[$nvr_initial."_bathroom"][0]))? $nvr_custom[$nvr_initial."_bathroom"][0] : '';
		$nvr_size = (isset($nvr_custom[$nvr_initial."_size"][0]))? $nvr_custom[$nvr_initial."_size"][0] : '';
		$nvr_lotsize = (isset($nvr_custom[$nvr_initial."_lot_size"][0]))? $nvr_custom[$nvr_initial."_lot_size"][0] : '';
		$nvr_amenities = (isset($nvr_custom[$nvr_initial.'_amenities'][0]))? $nvr_custom[$nvr_initial.'_amenities'][0] : '';
		$nvr_agent = (isset($nvr_custom[$nvr_initial.'_agent'][0]))? $nvr_custom[$nvr_initial.'_agent'][0] : '';
		 $nvr_siteplan=(isset($nvr_custom['site_plan'][0]))? $nvr_custom['site_plan'][0] : '';
		$nvr_address = (isset($nvr_custom[$nvr_initial."_address"][0]))? $nvr_custom[$nvr_initial."_address"][0] : '';
		$nvr_state = (isset($nvr_custom[$nvr_initial."_state"][0]))? $nvr_custom[$nvr_initial."_state"][0] : '';
		$nvr_country = (isset($nvr_custom[$nvr_initial."_country"][0]))? $nvr_custom[$nvr_initial."_country"][0] : '';
		
		$nvr_prop_cat = 'property_category';
		$nvr_types = get_the_terms( get_the_ID(), $nvr_prop_cat );
		
		$nvr_typearr = $nvr_typeslugs = array();
		if ( !empty( $nvr_types ) ) {
			foreach ( $nvr_types as $nvr_type ) {
				$nvr_typearr[] = $nvr_type->name;
				$nvr_typeslugs[] = $nvr_type->slug;
			}
		}
		
		$nvr_prop_purpose = 'property_purpose';
		$nvr_purposes = get_the_terms( get_the_ID(), $nvr_prop_purpose );
		$nvr_purposearr = array();
		if ( !empty( $nvr_purposes ) ) {
			foreach ( $nvr_purposes as $nvr_purpose ) {
				$nvr_purposearr[] = $nvr_purpose->name;
			}
		}
		
		$nvr_purpose = implode(', ', $nvr_purposearr);
		
		$nvr_prop_city = 'property_city';
		$nvr_cities = get_the_terms( get_the_ID(), $nvr_prop_city );
		$nvr_cityarr = array();
		if ( !empty( $nvr_cities ) ) {
			foreach ( $nvr_cities as $nvr_city ) {
				$nvr_cityarr[] = $nvr_city->name;
			}
		}
		
		$nvr_city = implode(', ', $nvr_cityarr);
		
		$nvr_complete_address = $nvr_address.' '.$nvr_city.' '.$nvr_state.' '.$nvr_country;
		
		$nvr_has_agent = false;
		$nvr_agent_data = array();
		if($nvr_agent!=''){
			$nvr_get_agent = new WP_Query(array(
				'post_type' => 'peoplepost',
				'name' => $nvr_agent
			));
			
			if($nvr_get_agent->have_posts()){
				while($nvr_get_agent->have_posts()){
				
					$nvr_get_agent->next_post();
					$nvr_agentid = $nvr_get_agent->post->ID;
					$nvr_agent_data['title'] = $nvr_get_agent->post->post_title;
					$nvr_agent_data['content'] = $nvr_get_agent->post->post_content;
					$nvr_agent_data['id'] = $nvr_agentid;
					$nvr_agent_data['idlink'] = get_permalink($nvr_agentid);
					
					$nvr_agent_custom = get_post_custom($nvr_agentid);
					$nvr_agent_data['info'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_info'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_info'][0] : "";
					$nvr_agent_data['thumb'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_thumb'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_thumb'][0] : "";
					$nvr_agent_data['pinterest']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_pinterest'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_pinterest'][0] : "";
					$nvr_agent_data['facebook']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_facebook'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_facebook'][0] : "";
					$nvr_agent_data['twitter'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_twitter'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_twitter'][0] : "";
					$nvr_agent_data['email']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_email'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_email'][0] : "";
					$nvr_agent_data['skype']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_skype'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_skype'][0] : "";
					$nvr_agent_data['phone'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_phone'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_phone'][0] : "";
					$nvr_agent_data['linkedin']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_linkedin'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_linkedin'][0] : "";
					$nvr_agent_data['youtube']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_youtube'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_youtube'][0] : "";
					$nvr_agent_data['instagram']= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_instagram'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_instagram'][0] : "";
					
					if($nvr_agent_data['thumb']){
						$nvr_agent_data['image'] = '<img src="'.esc_url( $nvr_agent_data['thumb'] ).'" alt="'.esc_attr( $nvr_agent_data['title'] ).'" title="'. esc_attr( $nvr_agent_data['title'] ) .'" class="scale-with-grid" />';
					}elseif( has_post_thumbnail( $nvr_agentid ) ){
						$nvr_agent_data['image'] = get_the_post_thumbnail($nvr_agentid, 'thumbnail', array('class' => 'scale-with-grid'));
					}else{
						$nvr_agent_data['image'] = '<img src="'.esc_url( get_template_directory_uri().'/images/testi-user.png' ).'" alt="'.esc_attr( $nvr_agent_data['title'] ).'" title="'. esc_attr( $nvr_agent_data['title'] ) .'" class="scale-with-grid" />';
					}
					
					$nvr_has_agent = true;
				}
			}
			wp_reset_postdata();
			wp_reset_query();
		}
		?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
            	<div class="prop-single-head">
                	<?php /*<div class="prop-price-container">
                    	<div class="prop-single-purpose"><?php echo '<i class="fa fa-tag"></i> '.$nvr_purpose; ?></div>
                        <div class="prop-single-price"><?php echo nvr_show_price($nvr_price, $nvr_cursymbol, $nvr_curplace).' '.$nvr_plabel;?></div>
                    </div>*/?>
                    <div class="prop-single-summary">
                    	<h1 class="prop-title"><?php the_title(); ?></h1>
                        <div class="prop-category"><?php //echo implode(", ",$nvr_typearr); ?></div>
                        <div class="prop-address"><?php //'<i class="fa-map-marker fa"></i> ' 
						echo $nvr_complete_address; ?></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            	<div class="prop-single-desc">
                <?php /*	<h3 class="prop-single-title"><?php _e('Property Description', THE_LANG); ?></h3>*/?>
					<div class="prop-single-content"><?php the_content(); ?></div>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', THE_LANG ), 'after' => '</div>' ) ); ?>
                </div>
				<div class="site_plan">
				
				<?php echo wp_get_attachment_image($nvr_siteplan,'full');?>
				</div>
                <?php /*<div class="prop-single-detail">
                	<h3 class="prop-single-title"><?php _e('Property Details', THE_LANG); ?></h3>
                    <div class="row">
                    	<div class="three columns"><?php _e('Price', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo nvr_show_price($nvr_price).' '.$nvr_plabel; ?></span></div>
                    	<div class="three columns"><?php _e('Type', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo implode("<br />", $nvr_typearr); ?></span></div>
                        <div class="three columns"><?php _e('Purpose', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo implode("<br />", $nvr_purposearr); ?></span></div>
                        <div class="three columns"><?php _e('Status', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo $nvr_status; ?></span></div>
                    </div>
                    <div class="row">
                    	<div class="three columns"><?php _e('Property Size', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo $nvr_size.' '.$nvr_unit; ?></span></div>
                        <div class="three columns"><?php _e('Property Lot Size', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo $nvr_lotsize.' '.$nvr_unit; ?></span></div>
                        <div class="three columns"><?php _e('Bedroom', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo $nvr_bed; ?></span></div>
                        <div class="three columns"><?php _e('Bathroom', THE_LANG); ?>:<br /> <span class="detailvalue"><?php echo $nvr_bath; ?></span></div>
                    </div>
                    <?php
					$nvr_propcustoms = nvr_get_option($nvr_shortname.'_property_custom');
					if($nvr_propcustoms){
						echo '<div class="row">';
						for($i=0;$i<count($nvr_propcustoms);$i++){
							if($i%4==0 && $i>0){
								echo '</div><div class="row">';
							}
							$nvr_propcustom = $nvr_propcustoms[$i];
							$nvr_cusslug = nvr_gen_slug($nvr_propcustom);
							$nvr_cusval = (isset($nvr_custom[$nvr_initial.'_custom_'.$nvr_cusslug][0]))? $nvr_custom[$nvr_initial.'_custom_'.$nvr_cusslug][0] : '';
							if($nvr_cusval){
								echo '<div class="three columns">'.__($nvr_propcustom, THE_LANG).':<br /> <span class="detailvalue">'.$nvr_cusval.'</span></div>';
							}
						}
						echo '</div>';
					}
					?>
                    <div class="clearfix"></div>
                </div>
                
                <?php 
				if($nvr_amenities!=''){
				$nvr_amenityarr = explode(",",$nvr_amenities);
				
				?>
                    <div class="prop-single-feature">
                        <h3 class="prop-single-title"><?php _e('Property Features &amp; Amenities', THE_LANG); ?></h3>
                        
                        <?php
                        $nvr_propamenities = nvr_get_option($nvr_shortname.'_property_amenities');
                        
                        if($nvr_propamenities){
							echo '<div class="row">';
                            for($i=0;$i<count($nvr_propamenities);$i++){
								if($i%4==0 && $i>0){
									echo '</div><div class="row">';
								}
                                $nvr_propamenity = $nvr_propamenities[$i];
								if(in_array($nvr_propamenity,$nvr_amenityarr)){
									$nvr_sign = 'fa-check';
								}else{
									$nvr_sign = 'fa-times';
								}
								echo '<div class="three columns"><i class="fa '.esc_attr( $nvr_sign ).'"></i> '.__($nvr_propamenity, THE_LANG).'</div>';
                            }
							echo '</div>';
                        }
                        ?>
                        
                    </div>
                <?php
				}/* end if $nvr_amenities!='' */
				?>
                
                <div class="prop-single-maps">
                	<h3 class="prop-single-title"><?php _e('Location of this Property', THE_LANG); ?></h3>
                    <div class="nvr_googlemap">
						<div id="gMapsContainer"></div>
					</div>
                </div>
                
                <?php if($nvr_has_agent){ ?>
                <div class="prop-single-agent">
                	<h3 class="prop-single-title"><?php _e('Contact The Agent', THE_LANG); ?></h3>
                    <div class="nvr_agent_detail">
                    	<div class="row">
                        	<div class="six columns">
                            	<div class="agent-thumbnail">
                                	<?php echo $nvr_agent_data['image']; ?>
                                </div>
                                <div class="agent-summary">
                                	<h4 class="agent-title"><?php echo $nvr_agent_data['title']; ?></h4>
                                    <span class="agent-info"></span>
                                    <div class="agent-social">
                                        <span class="agent-data"><i class="fa fa-user"></i> <?php echo $nvr_agent_data['info']; ?></span>
										<?php if($nvr_agent_data['phone']!=''){ ?>
                                        <span class="agent-data"><i class="fa fa-phone"></i> <?php echo $nvr_agent_data['phone']; ?></span>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['email']!=''){ ?>
                                        <span class="agent-data"><i class="fa fa-envelope"></i> <?php echo $nvr_agent_data['email']; ?></span>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['skype']!=''){ ?>
                                        <span class="agent-data"><i class="fa fa-skype"></i> <?php echo $nvr_agent_data['skype']; ?></span>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['pinterest']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( 'http://www.pinterest.com/'.$nvr_agent_data['pinterest'] ); ?>"><i class="fa fa-pinterest"></i> <?php echo $nvr_agent_data['pinterest']; ?></a>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['facebook']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( 'http://www.facebook.com/'.$nvr_agent_data['facebook'] ); ?>"><i class="fa fa-facebook"></i> <?php echo $nvr_agent_data['facebook']; ?></a>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['twitter']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( 'http://www.twitter.com/'.$nvr_agent_data['twitter'] ); ?>"><i class="fa fa-twitter"></i> @<?php echo $nvr_agent_data['twitter']; ?></a>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['linkedin']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( $nvr_agent_data['linkedin'] ); ?>"><i class="fa fa-linkedin"></i> <?php _e('LinkedIn', THE_LANG); ?></a>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['youtube']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( 'http://www.youtube.com/user/'.$nvr_agent_data['youtube'] ); ?>"><i class="fa fa-youtube"></i> <?php echo $nvr_agent_data['youtube']; ?></a>
                                        <?php } ?>
                                        <?php if($nvr_agent_data['instagram']!=''){ ?>
                                        <a class="agent-data" href="<?php echo esc_url( 'http://www.instagram.com/'.$nvr_agent_data['instagram'] ); ?>"><i class="fa fa-instagram"></i> @<?php echo $nvr_agent_data['instagram']; ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="agent-content">
                                	<?php echo $nvr_agent_data['content']; ?>
                                </div>
                                 <div class="agent-link"><a href="<?php echo esc_url( $nvr_agent_data['idlink'] ); ?>"><?php _e('Agent Listings', THE_LANG); ?></a></div>
                            </div>
                            <div class="six columns">
                                <form id="prop-contact-agent" name="prop-contact-agent" class="agent-form" action="">
                                        <input class="inputtext" name="contact-name" type="text" id="contact-name" value="" title="<?php esc_attr_e('Please enter your name!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Name', THE_LANG); ?>" />
                                        <input class="inputtext" name="contact-phone" type="text" id="contact-phone" value="" title="<?php esc_attr_e('Please enter your phone!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Phone', THE_LANG); ?>" />
                                        <input class="inputtext" name="contact-email" type="text" id="contact-email" value="" title="<?php esc_attr_e('Please enter your email!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Email', THE_LANG); ?>" />
                                    
                                        <textarea class="inputtext" name="contact-message" id="contact-message" title="<?php esc_attr_e('Please enter your message!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Message', THE_LANG); ?>"></textarea>
										<input class="button" type="submit" name="contact-button" id="contact-button" value="<?php esc_attr_e('Send Message', THE_LANG); ?>" />
                                        <?php $nvr_nonce = wp_create_nonce("nvr_propdetailcontactagent_nonce"); ?>
                                        <input name="contact-nonce" id="contact-nonce" type="hidden" value="<?php echo esc_attr( $nvr_nonce ); ?>" />
                                        <input name="contact-agentemail" id="contact-agentemail" type="hidden" value="<?php echo esc_attr( $nvr_agent_data['email'] ); ?>" />
                                        <input name="contact-propid" id="contact-propid" type="hidden" value="<?php the_ID(); ?>" />
                                        <br /><br />
                                        <div id="alert-agent-contact"></div>
                                    
								</form>
                            </div>
                        </div>
					</div>
                </div>
                <?php } ?>
                
                <?php
		
				$nvr_idnum = 0;
				
				$nvr_pagelayout = nvr_get_sidebar_position(get_the_ID());
			
				if($nvr_pagelayout!='one-col'){
					$nvr_column = 2;
				}else{
					$nvr_column = 3;
				}
				$nvr_pfcontainercls = "nvr-pf-col-".$nvr_column;
				$nvr_imgsize = "property-image";
				$nvr_curid = get_the_ID();
				$nvr_argquery = array(
					'post_type'         =>  'propertys',
					'posts_per_page'	=> 6,
					'orderby'			=> 'rand',
					'post__not_in'		=> array($nvr_curid)
				);
				
				if(count($nvr_typeslugs)>0){
					$nvr_argquery['tax_query'] = array(
						array(
							'taxonomy' => 'property_category',
							'field' => 'slug',
							'terms' => $nvr_typeslugs
						)
					);
				}
			
				query_posts($nvr_argquery); 
				global $post, $wp_query;
				?>
                <div class="prop-single-listing">
                	<h3 class="prop-single-title"><?php _e('Similar Listings', THE_LANG); ?></h3>
                    <div class="property_filter">
                        <div class="nvr-prop-container row">
                            <ul class="<?php echo esc_attr( $nvr_pfcontainercls ); ?>">
                            <?php
                            while ( have_posts() ) : the_post(); 
                                    
                                    $nvr_idnum++;
                                    if($nvr_column=="4"){
                                        $nvr_classpf = 'three columns ';
                                    }elseif($nvr_column=="2"){
                                        $nvr_classpf = 'six columns ';
                                    }else{
                                        $nvr_classpf = 'four columns ';
                                    }
                                    
                                    if(($nvr_idnum%$nvr_column) == 1){ $nvr_classpf .= "first ";}
                                    if(($nvr_idnum%$nvr_column) == 0){$nvr_classpf .= "last ";}
                                    
                                    echo nvr_prop_get_box( $nvr_imgsize, get_the_ID(), $nvr_classpf, $nvr_unit, $nvr_cursymbol, $nvr_curplace );
                                        
                                    $nvr_classpf=""; 
                                        
                            endwhile; // End the loop. Whew.
                            wp_reset_query();
                            ?>
                            <li class="nvr-prop-clear"></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div><!-- end .nvr-property-container -->
                    </div>
                </div>
                
				<?php edit_post_link( __( 'Edit', THE_LANG ), '<span class="edit-link">', '</span>' ); ?>
				<div class="clearfix"></div>
			</div><!-- .entry-content -->
			
		</div><!-- #post -->
    
    <?php 
		comments_template( '', true ); 
	endwhile; 
	?>
	<div class="clearfix"></div><!-- clear float --> 
                
<?php get_footer(); ?>