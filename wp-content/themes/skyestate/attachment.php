<?php
/**
 * The template for displaying attachments.
 *
 * @package WordPress
 * @subpackage Skyestate
 * @since Skyestate 1.0
 */

get_header(); ?>

        <div id="postattachment">
        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="entry-utility">
                <?php
                    printf(__('<span class="%1$s">By</span> %2$s', THE_LANG ),
                        'meta-prep meta-prep-author',
                        sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
                            get_author_posts_url( get_the_author_meta( 'ID' ) ),
                            sprintf( esc_attr__( 'View all posts by %s', THE_LANG ), get_the_author() ),
                            get_the_author()
                        )
                    );
                ?>
                <span class="meta-sep">|</span>
                <?php
                    printf( __('<span class="%1$s">Published</span> %2$s', THE_LANG ),
                        'meta-prep meta-prep-entry-date',
                        sprintf( '<span class="entry-date">%2$s</span>',
                            esc_attr( get_the_time() ),
                            get_the_date()
                        )
                    );
                    if ( wp_attachment_is_image() ) {
                        echo ' <span class="meta-sep">|</span> ';
                        $nvr_metadata = wp_get_attachment_metadata();
                        printf( __( 'Full size is %s pixels', THE_LANG ),
                            sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
                                wp_get_attachment_url(),
                                esc_attr( __('Link to full-size image', THE_LANG ) ),
                                $nvr_metadata['width'],
                                $nvr_metadata['height']
                            )
                        );
                    }
                ?>
                <div class="clearfix"></div>
            </div><!-- .entry-utility -->

            <div class="entry-content">
                <div class="entry-attachment">
                    <?php if ( wp_attachment_is_image() ) :
                        $nvr_attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
                        foreach ( $nvr_attachments as $k => $nvr_attachment ) {
                            if ( $nvr_attachment->ID == $post->ID )
                                break;
                        }
                        $k++;
                        // If there is more than 1 image attachment in a gallery
                        if ( count( $nvr_attachments ) > 1 ) {
                            if ( isset( $nvr_attachments[ $k ] ) )
                                // get the URL of the next image attachment
                                $nvr_next_attachment_url = get_attachment_link( $nvr_attachments[ $k ]->ID );
                            else
                                // or get the URL of the first image attachment
                                $nvr_next_attachment_url = get_attachment_link( $nvr_attachments[ 0 ]->ID );
                        } else {
                            // or, if there's only 1 image attachment, get the URL of the image
                            $nvr_next_attachment_url = wp_get_attachment_url();
                        }
                    ?>
                    <p class="attachment"><a href="<?php echo esc_url( $nvr_next_attachment_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
                    $nvr_attachment_size = apply_filters( 'novaro_attachment_size', 940 );
                    echo wp_get_attachment_image( $post->ID, array( $nvr_attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
                    ?></a></p>

                    <div id="nav-below" class="navigation">
                        <div class="nav-previous"><?php previous_image_link( true ); ?></div>
                        <div class="nav-next"><?php next_image_link( true ); ?></div>
                    </div><!-- #nav-below -->
                
                    <?php else : ?>
                    <a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div><!-- .entry-attachment -->
                
                <h3><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></h3>

                <?php the_content( __( 'Read More', THE_LANG ) ); ?>
                <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', THE_LANG ), 'after' => '</div>' ) ); ?>
                <?php edit_post_link( __( 'Edit', THE_LANG ), '<span class="edit-link">', '</span>' ); ?>
                <div class="clearfix"></div>
            </div><!-- .entry-content -->
            <div class="clearfix"></div>
        </div><!-- #post-## -->

        <?php comments_template(); ?>

        <?php endwhile; ?>
            <div class="clearfix"></div>
        </div><!-- postattachment -->
                    
<?php get_footer(); ?>