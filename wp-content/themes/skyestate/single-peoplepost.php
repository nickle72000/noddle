<?php
/**
 * The Template for displaying all single posts.
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

    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    	<?php
		$nvr_agentid = get_the_ID();
		$nvr_agent_data['title'] = get_the_title();
		$nvr_agent_data['content'] = get_the_content();
		$nvr_agent_data['id'] = $nvr_agentid;
		
		$nvr_agent_custom = get_post_custom($nvr_agentid);
		$nvr_agent_data['info'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_info'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_info'][0] : "";
		$nvr_agent_data['thumb'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_thumb'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_thumb'][0] : "";
		$nvr_agent_data['pinterest']= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_pinterest'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_pinterest'][0] : "";
		$nvr_agent_data['facebook']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_facebook'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_facebook'][0] : "";
		$nvr_agent_data['twitter'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_twitter'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_twitter'][0] : "";
		$nvr_agent_data['email']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_email'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_email'][0] : "";
		$nvr_agent_data['skype']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_skype'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_skype'][0] : "";
		$nvr_agent_data['phone'] 	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_phone'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_phone'][0] : "";
		$nvr_agent_data['linkedin'] = (isset($nvr_agent_custom['_'.$nvr_initial.'_people_linkedin'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_linkedin'][0] : "";
		$nvr_agent_data['youtube']	= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_youtube'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_youtube'][0] : "";
		$nvr_agent_data['instagram']= (isset($nvr_agent_custom['_'.$nvr_initial.'_people_instagram'][0]))? $nvr_agent_custom['_'.$nvr_initial.'_people_instagram'][0] : "";
		
		if($nvr_agent_data['thumb']){
			$nvr_agent_data['image'] = '<img src="'.esc_url( $nvr_agent_data['thumb'] ).'" alt="'.esc_attr( $nvr_agent_data['title'] ).'" title="'. esc_attr( $nvr_agent_data['title'] ) .'" class="scale-with-grid" />';
		}elseif( has_post_thumbnail( $nvr_agentid ) ){
			$nvr_agent_data['image'] = get_the_post_thumbnail($nvr_agentid, 'thumbnail', array('class' => 'scale-with-grid'));
		}else{
			$nvr_agent_data['image'] = '<img src="'.esc_url( get_template_directory_uri().'/images/testi-user.png' ).'" alt="'.esc_attr( $nvr_agent_data['title'] ).'" title="'. esc_attr( $nvr_agent_data['title'] ) .'" class="scale-with-grid" />';
		}
		?>
    	<div class="people-single-agent">
            <div class="agent-thumbnail">
                <?php echo $nvr_agent_data['image']; ?>
            </div>
            <div class="agent-summary">
                <h4 class="agent-title"><?php echo $nvr_agent_data['title']; ?></h4>
                <span class="agent-info"><?php echo $nvr_agent_data['info']; ?></span>
                <div class="agent-social">
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
    	</div>
        <div class="people-single-content">
        	<h3 class="people-single-title"><?php _e('About The Agent', THE_LANG); ?></h3>
            <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', THE_LANG ), 'after' => '</div>' ) ); ?>
                <?php edit_post_link( __( 'Edit', THE_LANG ), '<span class="edit-link">', '</span>' ); ?>
                <div class="clearfix"></div>
            </div><!-- .entry-content -->
        </div>
        <div class="people-single-form">
        	<h3 class="people-single-title"><?php _e('Contact The Agent', THE_LANG); ?></h3>
            <form id="people-contact-agent" name="people-contact-agent" class="agent-form" action="">
            <div class="row">
                <div class="six columns">
                    <input class="inputtext" name="contact-name" type="text" id="contact-name" value="" title="<?php esc_attr_e('Please enter your name!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Name', THE_LANG); ?>" />
                    <input class="inputtext" name="contact-phone" type="text" id="contact-phone" value="" title="<?php esc_attr_e('Please enter your phone!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Phone', THE_LANG); ?>" />
                    <input class="inputtext" name="contact-email" type="text" id="contact-email" value="" title="<?php esc_attr_e('Please enter your email!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Email', THE_LANG); ?>" />
                </div>
                <div class="six columns">
                    <textarea class="inputtext" name="contact-message" id="contact-message" title="<?php esc_attr_e('Please enter your message!', THE_LANG); ?>" placeholder="<?php esc_attr_e('Message', THE_LANG); ?>"></textarea>
                    <input class="button" type="submit" name="contact-button" id="contact-button" value="<?php esc_attr_e('Send Message', THE_LANG); ?>" />
                </div>
            </div>
			<?php $nvr_nonce = wp_create_nonce("nvr_propdetailcontactagent_nonce"); ?>
            <input name="contact-nonce" id="contact-nonce" type="hidden" value="<?php echo esc_attr( $nvr_nonce ); ?>" />
            <input name="contact-agentemail" id="contact-agentemail" type="hidden" value="<?php echo esc_attr( $nvr_agent_data['email'] ); ?>" />
            <input name="contact-propid" id="contact-propid" type="hidden" value="<?php the_ID(); ?>" />
            <br /><br />
            <div id="alert-agent-contact"></div>
            </form>
        </div>
        
        <?php
		
		$nvr_idnum = 0;
		
		$nvr_pagelayout = nvr_get_sidebar_position($nvr_agentid);
	
		if($nvr_pagelayout!='one-col'){
			$nvr_column = 2;
		}else{
			$nvr_column = 3;
		}
		$nvr_pfcontainercls = "nvr-pf-col-".$nvr_column;
		$nvr_imgsize = "property-image";
		
		$nvr_argquery = nvr_property_mapquery();
	
		query_posts($nvr_argquery); 
		global $post, $wp_query;
		?>
        <div class="people-single-listing">
        	<h3 class="people-single-title"><?php _e('Agent Listings', THE_LANG); ?></h3>
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
    </div><!-- #post -->
    
    <?php comments_template( '', true ); ?>
    
    <?php endwhile; ?>
	<div class="clearfix"></div><!-- clear float --> 
                   
<?php get_footer(); ?>