(function( $ ) {
	'use strict';
        $(document).ready(function () {
            
            $(document).on('keyup', '.o-color', function () {
               $(this).css('background-color', $(this).val());
            });
            
            $(document).on("click", ".o-add-font-file", function (e) {
                e.preventDefault();
                var uploader = wp.media({
                    title: 'Please set the picture',
                    button: {
                        text: "Select picture(s)"
                    },
                    multiple: false
                })
                    .on('select', function () {
                        var selection = uploader.state().get('selection');
                        selection.map(
                                function (attachment) {
                                    attachment = attachment.toJSON();
                                    var new_rule_index = $(".font_style_table tbody tr").length;
                                    var font_tpl = $("#wpd-font-tpl").val();
                                    var tpl = font_tpl.replace(/{index}/g, new_rule_index);
                                    $('.font_style_table tbody').prepend(tpl);
                                    $('#file_data_' + new_rule_index).find("input[type=hidden]").val(attachment.id);
                                    $('#file_data_' + new_rule_index).parent().find(".media-name").html(attachment.filename);
                                }
                        );
                    })
                    .open();
        });

        $(document).on("click", ".o-remove-font-file", function (e) {
            e.preventDefault();
            $(this).parent().find("input[type=hidden]").val("");
            $(this).parent().parent().find(".media-name").html("");
            $(this).parent().parent().remove();
        });
        
         $(document).on('change', '#font', function () {
            var name = $('#font  option:selected').text();
            var url = $('#font   option:selected').val();
            $('.font_auto_name').val(name);
            $('.font_auto_url').val(url);

        });
        
        load_color_picker();
            
            function load_color_picker(){
                $('[id$="color-selector"]').each(function ()
                {
                    var selector=$(this);
                    selector.css('background-color', '#8f3f8f');
                    selector.val('#8f3f8f');
                    selector.ColorPicker({
                        color: '#8f3f8f',
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            /*var selected_object = wpd_editor.canvas.getActiveObject();
                            if ((selected_object != null))
                            {
                                wpd_editor.save_canvas();
                            }*/
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            selector.css('background-color', '#' + hex);
                            selector.attr('data-color','#' + hex);
                            selector.val('#' + hex);
                        }
                    });
                });
            }
           
        //*********** déclencher le traitement à l'ajout d'un composant **************/
            $(document).on('click', '#vpc-config-container a.add-rf-row:last', function () {
                setTimeout(function () {
                    $('.vpc-behaviour').trigger('change');
                 },50);
            });
            
        //*********** ajout des classes sur la fenetre modal en fonction du behaviour selectionné **************/
            function add_custom_class(){
                $('.vpc-behaviour').each(function ()
                {
                    $(this).parent().parent().find('.omodal-body').removeClass('custom_text_bloc');
                     $(this).parent().parent().find('.omodal-body').removeClass('default_bloc');
                    if($(this).val()=='text')
                        $(this).parent().parent().find('.omodal-body').addClass('custom_text_bloc');
                    
                    if($(this).val()=='radio' || $(this).val()=='checkbox')
                        $(this).parent().parent().find('.omodal-body').addClass('default_bloc');
                    
                });
            }
            
            //*********** traitement sur la selection du behavior **************/
            $(document).on('change', '.vpc-behaviour', function () {
                add_custom_class();
                show_bloc_by_behaviour($(this).val(),$(this));
            });
            
            $(document).on('click', '.custom_text_bloc .add-rf-row', function () {
                
                setTimeout(function () {
                    $('.custom_text_bloc tbody td:nth-child(5)').hide();
                    $('.custom_text_bloc tbody td:nth-child(6)').hide();
                    var view_bloc_index=$('.custom_text_bloc .views').parent().index();
                    if(view_bloc_index>0){
                        $('.custom_text_bloc').find('thead td:nth('+view_bloc_index+')').hide();
                        $('.custom_text_bloc .views').parent().hide();
                    }
                    $('.custom_text_bloc .custom_image_top').parent().hide();
                    $('.custom_text_bloc .custom_image_left').parent().hide();
                    $('.custom_text_bloc .custom_image_width').parent().hide();
                    $('.custom_text_bloc .custom_image_height').parent().hide();
                    var container_class='.custom_text_bloc';
                    hide_upload_fields(container_class);
                },30);
               
            });
            
            $(document).on('click', '.default_bloc .add-rf-row', function () {
                setTimeout(function () {
                    var text_top_bloc_index=$('.default_bloc .custom_text_top').parent().index();
                    var text_left_bloc_index=$('.default_bloc .custom_text_left').parent().index();
                    var text_len_bloc_index=$('.default_bloc .custom_text_size').parent().index();
                    var text_rotation_bloc_index=$('.default_bloc .custom_text_rotation').parent().index();
                    var text_size_bloc_index=$('.default_bloc .custom_text_font_size').parent().index();
                    
                   
                    
                    if(text_top_bloc_index>0){
                        $('.default_bloc').find('thead td:nth('+text_top_bloc_index+')').hide();
                        $('.default_bloc .custom_text_top').parent().hide();
                    }
                    
                    if(text_left_bloc_index>0){
                        $('.default_bloc').find('thead td:nth('+text_left_bloc_index+')').hide();
                        $('.default_bloc .custom_text_left').parent().hide();
                    }
                    
                    if(text_len_bloc_index>0){
                        $('.default_bloc').find('thead td:nth('+text_len_bloc_index+')').hide();
                        $('.default_bloc .custom_text_size').parent().hide();
                    }
                    if(text_size_bloc_index>0){
                        $('.default_bloc').find('thead td:nth('+text_size_bloc_index+')').hide();
                        $('.default_bloc .custom_text_font_size').parent().hide();
                    }
                    
                    if(text_rotation_bloc_index>0){
                        $('.default_bloc').find('thead td:nth('+text_rotation_bloc_index+')').hide();
                        $('.default_bloc .custom_text_rotation').parent().hide();
                    }
                    var container_class='.default_bloc';
                    hide_upload_fields(container_class);
                   
                },30);
               
            });
            
            
            function hide_upload_fields($class){
                var image_top_bloc_index=$($class+' .custom_image_top').parent().index();
                var image_left_bloc_index=$($class+' .custom_image_left').parent().index();
                var image_width_bloc_index=$($class+' .custom_image_width').parent().index();
                var image_heigth_bloc_index=$($class+' .custom_image_height').parent().index();
                if(image_top_bloc_index>0){
                    $($class).find('thead td:nth('+image_top_bloc_index+')').hide();
                    $($class+' .custom_image_top').parent().hide();
                }

                if(image_left_bloc_index>0){
                    $($class).find('thead td:nth('+image_left_bloc_index+')').hide();
                    $($class+' .custom_image_left').parent().hide();
                }

                if(image_width_bloc_index>0){
                    $($class).find('thead td:nth('+image_width_bloc_index+')').hide();
                    $($class+' .custom_image_width').parent().hide();
                }

                if(image_heigth_bloc_index>0){
                    $($class).find('thead td:nth('+image_heigth_bloc_index+')').hide();
                    $($class+' .custom_image_height').parent().hide();
                }
            }
            
            function hide_vpc_cta_useless_fields(){
                $('.vpc-behaviour').each(function ()
                {
                    show_bloc_by_behaviour($(this).val(),$(this));
                });
            }
            
            hide_vpc_cta_useless_fields();
            
            add_custom_class();
            
            function show_bloc_by_behaviour(behaviour,e){
                var parent=e.parent().parent();
                var td_top_index=parent.find('.custom_text_top').parent().index()+1;
                var td_left_index=parent.find('.custom_text_left').parent().index()+1;
                var td_len_index=parent.find('.custom_text_size').parent().index()+1;
                var td_rotation_index=parent.find('.custom_text_rotation').parent().index()+1;
                var td_size_index=parent.find('.custom_text_font_size').parent().index()+1;
                
                if(behaviour=='text'){
                    show_text_modal(e,parent,td_top_index,td_left_index,td_size_index,td_rotation_index,td_len_index);
                }
                if(e.val()=="radio" || e.val()=="checkbox" || e.val()=="upload"){
                    show_default_modal(e,parent,td_top_index,td_left_index,td_size_index,td_rotation_index,td_len_index);
                }
            }
            
            function show_default_modal(that,parent,td_top_index,td_left_index,td_size_index,td_rotation_index,td_len_index){
                var view_bloc_index=$('.custom_text_bloc .views').parent().index();
                parent.find('thead td:nth('+view_bloc_index+')').show();
                parent.find('.views').parent().show();
                parent.find('thead td:nth-child('+td_top_index+')').hide();
                parent.find('tbody .custom_text_top').parent().hide();
                parent.find('thead td:nth-child('+td_left_index+')').hide();
                parent.find('tbody .custom_text_left').parent().hide();
                parent.find('thead td:nth-child('+td_len_index+')').hide();
                parent.find('tbody .custom_text_size').parent().hide();
                parent.find('thead td:nth-child('+td_rotation_index+')').hide();
                parent.find('tbody .custom_text_rotation').parent().hide();
                parent.find('thead td:nth-child('+td_size_index+')').hide();
                parent.find('tbody .custom_text_font_size').parent().hide();
                that.parent().parent().find('thead td:nth-child(5)').show();
                that.parent().parent().find('tbody td:nth-child(5)').show();
                that.parent().parent().find('thead td:nth-child(6)').show();
                that.parent().parent().find('tbody td:nth-child(6)').show();
                
            }
            
            function show_text_modal(that,parent,td_top_index,td_left_index,td_size_index,td_rotation_index,td_len_index){
                
                setTimeout(function () {
                    var view_bloc_index=$('.custom_text_bloc .views').parent().index();
                        if(view_bloc_index>0){
                            $('.custom_text_bloc').find('thead td:nth('+view_bloc_index+')').hide();
                            $('.custom_text_bloc .views').parent().hide();
                        }
                        that.parent().parent().find('.custom_text_bloc thead td:nth-child(5)').hide();
                        that.parent().parent().find('.custom_text_bloc tbody td:nth-child(5)').hide();
                        that.parent().parent().find('.custom_text_bloc thead td:nth-child(6)').hide();
                        that.parent().parent().find('.custom_text_bloc tbody td:nth-child(6)').hide();
                        
                        parent.find('thead td:nth-child('+td_top_index+')').show();
                        parent.find('tbody .custom_text_top').parent().show();
                        parent.find('thead td:nth-child('+td_left_index+')').show();
                        parent.find('tbody .custom_text_left').parent().show();
                        parent.find('thead td:nth-child('+td_len_index+')').show();
                        parent.find('tbody .custom_text_size').parent().show();
                        parent.find('thead td:nth-child('+td_rotation_index+')').show();
                        parent.find('tbody .custom_text_rotation').parent().show();
                        parent.find('thead td:nth-child('+td_size_index+')').show();
                        parent.find('tbody .custom_text_font_size').parent().show();
                        
                     },10);
            }
            
        });
})( jQuery );
