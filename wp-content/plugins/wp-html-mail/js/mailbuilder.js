var haet_mailbuilder = haet_mailbuilder || {};


haet_mailbuilder.serialize_content = function(){
    var $ = jQuery;
    var content_array = {};
    
    $('#mailbuilder-content .mb-contentelement').each(function(){
        var $contentelement = $(this);
        var element_content_array = $contentelement.find('input,select,textarea').serializeArray();
        
        var indexed_element_content_array = {};

        for (var i = 0; i < element_content_array.length; i++) {
            indexed_element_content_array[ element_content_array[i]['name'] ] = element_content_array[i]['value'];
        }

        content_array[$contentelement.attr('id')] = {
            id:         $contentelement.attr('id'),
            type:       $contentelement.data('type'),
            content:    indexed_element_content_array 
        };
    });
    $('#mailbuilder_json').val( JSON.stringify( content_array ) );
};




haet_mailbuilder.add_content_element = function( type, element_id, content_array ){
    var $ = jQuery;
    var $contentelement = $('#mailbuilder-templates .mb-contentelement-' + type)
        .clone()
        .attr('id',element_id)
        .appendTo('#mailbuilder-content');


    if( haet_mailbuilder['create_content_' + type] )
        haet_mailbuilder['create_content_' + type]( $contentelement, element_id, content_array );

};




haet_mailbuilder.read_serialized_content = function(){
    var $ = jQuery;
    var raw_content = $('#mailbuilder_json').val();
    //console.log(raw_content);
    var content_array = [];

    if( raw_content.length )
        content_array = JSON.parse( raw_content );

    for( var i in content_array ){
        haet_mailbuilder.add_content_element( content_array[i]['type'], content_array[i]['id'], content_array[i]['content']);
    }
};


haet_mailbuilder.get_attachment_preview = function( attachment ){
    return '<div class="mb-attachment-preview">\
                <img src="' + attachment.icon + '" class="icon">\
                <div class="mb-attachment-details">\
                    <a href="' + attachment.url + '" target="_blank" class="filename">' + attachment.filename + '</a><br>\
                    <span class="filesize">' + attachment.filesizeHumanReadable + '</span>\
                    <a class="remove-attachment" data-id="' + attachment.id + '"><span class="dashicons dashicons-no"></span></a>\
                </div>\
            </div>';
};


haet_mailbuilder.get_attachment_ids = function(){
    var $ = jQuery;
    var raw_attachments = $('#mailbuilder_attachments').val();

    var attachment_ids = [];

    if( raw_attachments.length )
        attachment_ids = JSON.parse( raw_attachments );

    for (var i = 0; i < attachment_ids.length; i++) {
        if ( attachment_ids[i] == '' ) {         
            attachment_ids.splice(i, 1);
            i--;
        }
    }
    return attachment_ids;
}

jQuery(document).ready(function($) {
    haet_mailbuilder.read_serialized_content();

    $( '#mailbuilder-content' ).sortable({
        stop: function( event, ui ) {
            haet_mailbuilder.serialize_content();
        }
    });

    // open the "add"-Menu
    $('.mb-add').on('click', function( e ){
        e.preventDefault();
        $('.mb-add-types').slideToggle(300);
        $('.mb-add-types a[data-once="once"]').each(function(){
            var type = $(this).data('type');
            if( $('#mailbuilder-content .mb-contentelement-'+type).length )    
                $(this).addClass('disabled');
            else
                $(this).removeClass('disabled');
        })
    });

    // add content element
    $('.mb-add-types a').on( 'click', function(e){
        e.preventDefault();
        if( !$(this).hasClass('disabled') ){
            var type = $(this).data('type');
            var element_id = 'mb-' + Date.now();
            haet_mailbuilder.add_content_element( type, element_id, {} );
            
            $('.mb-add-types').slideUp(300);
        }
    });

    // remove content element
    $('#mailbuilder-content').on('click', '.mb-remove-element', function(e){
        e.preventDefault();
        $(this).parents('.mb-contentelement').slideUp(500,function(){
            $(this).remove();
            haet_mailbuilder.serialize_content();
        })
    });

    
    // make content editable
    $('#mailbuilder-content').on('click', '.mb-contentelement-content', function(e){
        
        var $element_content = $( this );

        // WYSIWYG Editor
        $element_content.find('.mb-edit-wysiwyg').each(function(){
            var $textarea = $(this).find('textarea'); 

            tinymce.editors['mb_wysiwyg_editor'].setContent( $textarea.val() );
            $( 'body' ).addClass( 'mb-overlay' );
            var $popup = $( '#mb-wysiwyg-popup' );
            $popup.fadeIn(300);

            $popup.find('.mb-apply').one('click', function(){
                    $popup.fadeOut( 200 );
                    $( 'body' ).removeClass( 'mb-overlay' );

                    mb_text.apply_content( $textarea, tinymce.editors['mb_wysiwyg_editor'].getContent() );

                    haet_mailbuilder.serialize_content();
                    tinymce.editors['mb_wysiwyg_editor'].setContent('');

                });

            $popup.find('.mb-cancel').one('click', function(){
                    $popup.fadeOut( 200 );
                    $( 'body' ).removeClass( 'mb-overlay' );
                    tinymce.editors['mb_wysiwyg_editor'].setContent('');
                });
            
        });
    });





    // Uploading files
    var attachments_file_frame;
    $('.upload_attachment_button').live('click', function( event ){
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( attachments_file_frame ) {
            attachments_file_frame.open();
            return;
        }
        // Create the media frame.
        attachments_file_frame = wp.media.frames.attachments_file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: true // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        attachments_file_frame.on( 'select', function() {
            var attachment_ids = haet_mailbuilder.get_attachment_ids();

            $.each( attachments_file_frame.state().get('selection').models, function( idx, attachment ){
                //console.log( attachment.attributes );
                if( attachment_ids.indexOf( attachment.attributes.id.toString() ) == -1 ){
                    attachment_ids.push( attachment.attributes.id.toString() );
                    $('.mb-preview-attachments').append( haet_mailbuilder.get_attachment_preview( attachment.attributes ) );

                    $('#mailbuilder_attachments').val( JSON.stringify( attachment_ids )  ) ;    
                }
            });

        });
        // Finally, open the modal
        attachments_file_frame.open();
    });

    $('.mb-preview-attachments').on( 'click', '.remove-attachment', function(e){
        e.preventDefault();
        var $button = $(this);
        var attachment_id = $button.data('id');
        $button.parents( '.mb-attachment-preview' ).hide( 400 );
        
        var attachment_ids = haet_mailbuilder.get_attachment_ids();
        attachment_index = attachment_ids.indexOf( attachment_id.toString() );
        if( attachment_index > -1 )
            attachment_ids.splice( attachment_index, 1 );
        $('#mailbuilder_attachments').val( JSON.stringify( attachment_ids ) );
    } );



    // preview saved attachments
    var attachment_ids = haet_mailbuilder.get_attachment_ids();
    if( attachment_ids.length ){
        $.each(attachment_ids,function( idx, id ){
            wp.media.attachment(id).fetch().then(function (data) {
              // preloading finished
              // after this you can use your attachment normally
              //wp.media.attachment(id).get('url');
              $('.mb-preview-attachments').append( haet_mailbuilder.get_attachment_preview( wp.media.attachment(id).attributes ) );
              console.log(wp.media.attachment(id));
            });
        });
    }
    
});