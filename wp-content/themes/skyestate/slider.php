<?php /* Called from header.php */

$nvr_shortname = THE_SHORTNAME;
$nvr_initial = THE_INITIAL;

$nvr_sliderarrange = nvr_get_option( $nvr_shortname . '_slider_arrange');
$nvr_sliderDisableText = nvr_get_option( $nvr_shortname . '_slider_disable_text');

$nvr_pid = nvr_get_postid();
$nvr_custom = nvr_get_customdata($nvr_pid);
$nvr_cf_sliderType 		= (isset($nvr_custom["slider_type"][0]))? $nvr_custom["slider_type"][0] : "";
$nvr_cf_sliderCategory 	= (isset($nvr_custom["slider_category"][0]))? $nvr_custom["slider_category"][0] : "";
$nvr_cf_imagegallery	= (isset($nvr_custom[$nvr_initial."_imagesgallery"][0]))? $nvr_custom[$nvr_initial."_imagesgallery"][0] : "";
$nvr_cf_sliderLayer		= (isset($nvr_custom["slider_layerslider"][0]))? $nvr_custom["slider_layerslider"][0] : "";

/* CUSTOM SETTING IN SINGLE PEOPLE */
if(is_singular('peoplepost')){
	$nvr_cf_sliderType = 'mapslider';
}
/* ENDCUSTOM SETTING IN SINGLE PEOPLE */
?>
<!-- SLIDER -->
<div id="outerslider">
	<?php if($nvr_cf_sliderType=='mapslider'){ ?>
    
    	<?php get_template_part( 'slider-maps'); ?>
        
    <?php }else{ ?>
    
		<?php if($nvr_cf_sliderLayer==''){ ?>
        	
            <?php if(is_singular('propertys')){ ?>
                <div id="slidercontainer">
                    <section id="slider" class="propertyslider">
                        <div id="slideritems" class="flexsliderprop preloader">
                            <ul class="slides">
                                <?php
                                $nvr_attachments = $nvr_cf_imagegallery;
                                
								if($nvr_attachments!=''){
									$nvr_attachmentids = explode(",",$nvr_attachments);
									foreach ( $nvr_attachmentids as $nvr_attachmentid ) {
									
										if($nvr_attachmentid==''){continue;}
										$nvr_getimage = wp_get_attachment_image_src($nvr_attachmentid, 'full', true);
										if($nvr_getimage){
											$nvr_sliderimage = $nvr_getimage[0];
											$nvr_alttext = get_post_meta( $nvr_attachmentid , '_wp_attachment_image_alt', true);
		
											$nvr_output = $nvr_style ="";
											
											$nvr_style .= 'background-image:url( '.$nvr_sliderimage.' );';
											$nvr_output .='<li style="'.esc_attr( $nvr_style ).'">';
		
											//slider images
											$nvr_output .= '<a href="'.esc_url( $nvr_sliderimage ).'" data-rel="prettyPhoto[slider]" title="'.esc_attr( $nvr_alttext ).'" class="prettylink"><img src="'.esc_url( THE_TEMPLATEURI.'images/transslider.png' ).'" alt="'.esc_attr($nvr_alttext).'" /><i class="fa fa-search-plus"></i></a>';
												
											$nvr_output .='</li>';
											
											echo $nvr_output;
										}
									
									}
								}
                                ?>
                            </ul>
                        </div>
                        
                        <?php
						$nvr_attachments = $nvr_cf_imagegallery;
						$nvr_usecarousel = false;
						$nvr_licar = '';
						if($nvr_attachments!=''){
							$nvr_caritem = 0;
							$nvr_attachmentids = explode(",",$nvr_attachments);
							foreach ( $nvr_attachmentids as $nvr_attachmentid ) {
								
								if($nvr_attachmentid==''){continue;}
								$nvr_caritem++;
								$nvr_getimage = wp_get_attachment_image_src($nvr_attachmentid, 'thumbnail', true);
								$nvr_sliderimage = $nvr_getimage[0];
								$nvr_alttext = get_post_meta( $nvr_attachmentid , '_wp_attachment_image_alt', true);
	
								$nvr_output = $nvr_style ="";
								
								$nvr_style .= 'background-image:url( '.$nvr_sliderimage.' );';
								$nvr_output .='<li style="'.esc_attr( $nvr_style ).'">';
	
								//slider images
								$nvr_output .= '<img src="'.esc_url( THE_TEMPLATEURI.'images/transslider.png' ).'" alt="'.esc_attr($nvr_alttext).'" />';
									
								$nvr_output .='</li>';
								
								$nvr_licar .= $nvr_output;
								
							}
							
							if($nvr_caritem>1){
								$nvr_usecarousel = true;
							}
						}
						wp_reset_query();
						
						if($nvr_usecarousel){
						?>
                        <div id="carouselitems" class="flexsliderprop <?php echo esc_attr( 'caritem'.$nvr_caritem ); ?>">
                        	<ul class="slides">
                            	<?php echo $nvr_licar; ?>
                            </ul>
                        </div>
                        <?php
						}
						?>
                        <div class="clearfix"></div>
                    </section>
                    <div class="clearfix"></div>
                </div>
            <?php }else{ ?>
            	<div id="slidercontainer">
                    <section id="slider">
                        <div id="slideritems" class="flexslider preloader">
                            <ul class="slides">
                                <?php
                                $nvr_catSlider = get_term_by('slug', $nvr_cf_sliderCategory, "slidercat");
								$nvr_catSliderInclude = '';
                                if($nvr_cf_sliderCategory!=""){
                                    $nvr_catSliderInclude .= '&slidercat='. $nvr_catSlider->slug ;
                                }
                                
                                query_posts('post_type=slider-view'.$nvr_catSliderInclude.'&post_status=publish&showposts=-1&order=' . $nvr_sliderarrange);
                                while ( have_posts() ) : the_post();

                                $nvr_custom = get_post_custom( get_the_ID() );
                                $nvr_thumbid = get_post_thumbnail_id( get_the_ID() );
								$nvr_getimage = wp_get_attachment_image_src($nvr_thumbid, 'full', true);
								$nvr_sliderimage = $nvr_getimage[0];
								$nvr_alttext = get_post_meta( $nvr_thumbid , '_wp_attachment_image_alt', true);
            
                                $nvr_cf_slideurl = (isset($nvr_custom["external_link"][0]))?$nvr_custom["external_link"][0] : "";
                                $nvr_cf_thumb = (isset($nvr_custom["image_url"][0]))? $nvr_custom["image_url"][0] : "";
                                $nvr_cf_toptext1 = (isset($nvr_custom["top_text1"][0]))? $nvr_custom["top_text1"][0] : "";
								$nvr_cf_toptext2 = (isset($nvr_custom["top_text2"][0]))? $nvr_custom["top_text2"][0] : "";
                                $nvr_cf_subtitle = (isset($nvr_custom["subtitle"][0]))? $nvr_custom["subtitle"][0] : "";
								$nvr_cf_bottomtext = (isset($nvr_custom["bottom_text"][0]))? $nvr_custom["bottom_text"][0] : "";
								
                                $nvr_output = $nvr_style ="";
								
								$nvr_style .= 'background-image:url( '.$nvr_sliderimage.' );';
								$nvr_output .='<li style="'.esc_attr( $nvr_style ).'">';
                                    if($nvr_cf_slideurl!=""){
                                        $nvr_output .= '<a href="'.esc_url( $nvr_cf_slideurl ).'">';
                                    }
                                   
                                    //slider images
                          			$nvr_output .= '<img src="'.esc_url( THE_TEMPLATEURI.'images/transslider.png' ).'" alt="'.esc_attr($nvr_alttext).'" />';
                                        
                                    if($nvr_cf_slideurl!=""){
                                        $nvr_output .= '</a>';
                                    }
                                    
                                   //slider text
                                   if($nvr_sliderDisableText!=true){
                                       $nvr_output .='<div class="flex-caption">';
                                        $nvr_output .='<div class="text-caption container">';
                                            $nvr_output .='<div class="caption-content row">';
											if($nvr_cf_toptext1!="" || $nvr_cf_toptext2!=""){
												$nvr_output .= '<div class="toptext">';
													if($nvr_cf_toptext1!=""){
														$nvr_output .= '<span class="toptext1">'.$nvr_cf_toptext1.'</span>';
													}
													if($nvr_cf_toptext2!=""){
														$nvr_output .= '<span class="toptext2">'.$nvr_cf_toptext2.'</span>';
													}
													$nvr_output .= '<div class="clearfix"></div>';
												$nvr_output .= '</div>';
											}
											
											$nvr_output .= '<div class="slidertext">';
												if($nvr_cf_slideurl!=""){
													$nvr_output .='<h2><a href="'.esc_url( $nvr_cf_slideurl ).'">'.get_the_title().'</a></h2>';
												}else{
													$nvr_output .='<h2>'.get_the_title().'</h2>';
												}
												
												if($nvr_cf_subtitle!=""){
													$nvr_output .= '<div class="subtitle">'.$nvr_cf_subtitle.'</div>';
												}					
			
												$nvr_output .='<div class="slidercontent">'.get_the_excerpt().'</div>';
												
												if($nvr_cf_bottomtext!=""){
													$nvr_output .= '<div class="bottomtext">'.$nvr_cf_bottomtext.'</div>';
												}
											$nvr_output .= '</div>';
                                       
                                            $nvr_output .='</div>';
                                            $nvr_output .='<div class="clearfix"></div>';
                                        $nvr_output .='</div>';
                                       $nvr_output .='</div>';
                                   }
                                    
                                $nvr_output .='</li>';
                                
                                echo $nvr_output;
                                
                                endwhile;
                                wp_reset_query();
                                ?>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </section>
                    <div class="clearfix"></div>
                </div>
            <?php }?>
        <?php }else{ ?>
            <?php 
            if($nvr_cf_sliderLayer!=""){
                echo do_shortcode($nvr_cf_sliderLayer);
            }
            ?>
        <?php } ?>
        
	<?php } ?>
</div>
<!-- END SLIDER -->