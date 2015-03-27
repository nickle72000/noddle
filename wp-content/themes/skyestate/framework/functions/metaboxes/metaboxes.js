jQuery(document).ready(function(){ 
	jQuery('.nvr_add_section_button').click(function(evt){
		evt.preventDefault();
		var currentid = jQuery(this).attr('href');
		var currentsection = '#sectionbuilder'+currentid;
		var sectionrowcounter = jQuery('#sectionrowcounter'+ currentid);
		var counterval = parseInt(sectionrowcounter.val());
		var currentcounter = parseInt(counterval)+1;
		var section = jQuery(currentsection);
		
		var sectionskeleton = '';
		sectionskeleton += '<div class="nvr_sectionrow" id="nvr_sectionrow'+currentid+'_'+currentcounter+'">';
		sectionskeleton += '<a class="button nvr_remove_section_button" href="#nvr_sectionrow'+currentid+'_'+currentcounter+'">Remove Section</a>';
		sectionskeleton += '<table class="nvr_sectiontable" cellpadding="0" cellspacing="0" border="0">';
			sectionskeleton += '<tr>';
				sectionskeleton += '<td class="small"><label>BG Color</label><br /><input type="text" name="'+currentid+'['+counterval+'][backgroundcolor]" value="" /></td>';
				sectionskeleton += '<td class="large"><label>BG Image</label><br /><input type="text" name="'+currentid+'['+counterval+'][background]" value="" /></td>';
				sectionskeleton += '<td class="small"><label>Custom Class</label><br /><input type="text" name="'+currentid+'['+counterval+'][customclass]" value=""></td>';
			sectionskeleton += '</tr>';
			sectionskeleton += '<tr>';
				sectionskeleton += '<td colspan="3"><label>Content</label><br /><textarea name="'+currentid+'['+counterval+'][content]" rows="6"></textarea></td>';
			sectionskeleton += '</tr>';
		sectionskeleton += '</table>';
		sectionskeleton += '</div>';
		
		section.append(sectionskeleton);
		sectionrowcounter.val(currentcounter);
		section_remove_button();
	});
	
	section_remove_button();
	
	// Product gallery file uploads
	var product_gallery_frame;
	var $product_images = jQuery('#nvrpost_images_container ul.nvrpost_images');
	var $image_gallery_ids = $product_images.next();
	
	jQuery('.add_nvrpost_images').on( 'click', 'a', function( event ) {
		var $el = jQuery(this);
		var attachment_ids = $image_gallery_ids.val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( product_gallery_frame ) {
			product_gallery_frame.open();
			return;
		}

		// Create the media frame.
		product_gallery_frame = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		product_gallery_frame.on( 'select', function() {

			var selection = product_gallery_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
				attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

				$product_images.append('\
					<li class="image" data-attachment_id="' + attachment.id + '">\
						<img src="' + attachment.url + '" />\
						<ul class="actions">\
							<li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li>\
						</ul>\
					</li>');
				}

			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		product_gallery_frame.open();
	});

	// Remove images
	jQuery('#nvrpost_images_container').on( 'click', 'a.delete', function() {
		jQuery(this).closest('li.image').remove();

		var attachment_ids = '';

		jQuery('#nvrpost_images_container ul li.image').css('cursor','default').each(function() {
			var attachment_id = jQuery(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		return false;
	});
});

function section_remove_button(){
	jQuery('.nvr_remove_section_button').click(function(evt){
		evt.preventDefault();
		var currentsectionid = jQuery(this).attr('href');
		jQuery(currentsectionid).remove();
	});
}