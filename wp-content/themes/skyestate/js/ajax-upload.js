 ///////////////////////////////////////////////////////////////////////////////////////////  
  ////////  profile uploader
  ////////////////////////////////////////////////////////////////////////////////////////////   
  jQuery(document).ready(function($) {
     "use strict";
  
      if (typeof(plupload) !== 'undefined') {
          
      
            var uploader = new plupload.Uploader(ajax_vars.plupload);
        
            uploader.init();

            uploader.bind('FilesAdded', function (up, files) {
                
                jQuery.each(files, function (i, file) {
                 //   console.log('append'+file.id );
                    
                    
                    jQuery('#aaiu-upload-imagelist').append(
                        '<div id="' + file.id + '">' +
                            file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                            '</div>');
                });

                up.refresh(); // Reposition Flash/Silverlight
                uploader.start();
            });

            uploader.bind('UploadProgress', function (up, file) {
                jQuery('#' + file.id + " b").html(file.percent + "%");
            });



            // On erro occur
            uploader.bind('Error', function (up, err) {
                jQuery('#aaiu-upload-imagelist').append("<div>Error: " + err.code +
                    ", Message: " + err.message +
                    (err.file ? ", File: " + err.file.name : "") +
                    "</div>"
                );
                up.refresh(); // Reposition Flash/Silverlight
            });



            uploader.bind('FileUploaded', function (up, file, response) {
              
                var result = $.parseJSON(response.response);
               // console.log(result);
             
                jQuery('#' + file.id).remove();
                if (result.success) {      
					var profileimage = jQuery('#profile-image');
                    profileimage.css('background-image','url("'+result.html+'")');
                    profileimage.attr('data-profileurl',result.html);
                    jQuery('#profile-image_id').val(result.attach);
                    
                    var all_id= jQuery('#attachid').val();
                    all_id=all_id+","+result.attach;
                    jQuery('#attachid').val(all_id);
                    jQuery('#imagelist').append('<div class="uploaded_images" data-imageid="'+result.attach+'"><img src="'+result.html+'" alt="thumb" /><i class="fa deleter fa-trash-o"></i> </div>');
                    delete_binder();
                    thumb_setter();
                }
            });

     
            jQuery('#aaiu-uploader').click(function (e) {
                      uploader.start();
                      e.preventDefault();
                  });
            
            jQuery('#aaiu-uploader2').click(function (e) {
                      uploader.start();
                      e.preventDefault();
                  });
                     
 }
 
 });
 function thumb_setter(){
  
    jQuery('#imagelist img').click(function(){
    
        jQuery('#imagelist .uploaded_images .thumber').each(function(){
            jQuery(this).remove();
        });

        jQuery(this).parent().append('<i class="fa thumber fa-star"></i>')
        jQuery('#attachthumb').val(   jQuery(this).parent().attr('data-imageid') );
    });   
 }
 
 
 
 function delete_binder(){
      jQuery('#imagelist i').click(function(){
          var curent='';  
          jQuery(this).parent().remove();
          
          jQuery('#imagelist .uploaded_images').each(function(){
             curent=curent+','+jQuery(this).attr('data-imageid'); 
          });
          jQuery('#attachid').val(curent); 
          console.log('curent:'+curent);
          
      });
           
 }