(function( $ ) {
	'use strict';
        $(document).ready(function () {
            if(typeof(vpc)!='undefined'){
                vpc_load_custom_color_picker();
                create_vpc_text_container();
                load_text();
            }
            
            function vpc_load_custom_color_picker(){
                $('[id$="color-selector"]').each(function ()
                {   
                    
                    var id=$(this).attr('id');
                    var field_id=$(this).data('field');
                    var field_settings=vpc.text_settings[field_id];
                    var field_datas=$.parseJSON(field_settings);
                    //console.log(field_datas.palettes);
                    $('#' + id).qtip({
                        content: "<div class='wpc-custom-colors-container' data-id='" + id + "'>"+field_datas.palettes+"</div>",
                        position: {
                            my: 'middle left',
                        },
                        style: {
                            tip: false,
                            width: 200,
                            classes: 'qtip-rounded qtip-light text-color',
                        },
                        show: 'click',
                        hide:{
                            event: 'unfocus'
                        },
                        events: {
                            show: function() {
                                // Tell the tip itself to not bubble up clicks on it
                                $($(this).qtip('api').elements.tooltip).click(function() { return true; });
                 
                                // Tell the document itself when clicked to hide the tip and then unbind
                                // the click event (the .one() method does the auto-unbinding after one time)
                                $(document).one("click", function() { $('.vpc-action-buttons .btn_share').qtip('hide'); });
                            }
                        },
                    });
                });
            }
            
        function create_vpc_text_container(){
            var result = "";
            $('[id$="-field"]').each(function ()
            {
                var id=$(this).attr('id');
                var field_settings=vpc.text_settings[id];
                var field_datas=$.parseJSON(field_settings);
                var angle="rotate("+field_datas.angle+"deg)";
                result+='<div id="'+field_datas.container+'" class="text_field jtextfill" style="font-size:'+field_datas.size+'px;font-family:'+field_datas.font+';top:'+field_datas.top+'%;transform:'+angle+';left:'+field_datas.left+'%;"><span></span></div>';
                
                //set_vpc_text_size(field_datas.container);
            });
            $("#text_panel").html(result);
        }
        
        function load_text(){
            $('[id$="-field"]').each(function ()
            {
                //console.log($(this).val().length);
                 if ($(this).val().length > 0){
                    var text =$(this).val();
                    var current_field_id=$(this).attr('id');
                    add_text_on_preview(text,current_field_id);
                 }
                    
            });
        }

        $(document).on("keyup",'[id$="-field"]',function (e)
        {
            var text =$(this).val();
            var current_field_id=$(this).attr('id');
            add_text_on_preview(text,current_field_id);
            window.vpc_build_preview();
        });
        
        wp.hooks.addFilter('vpc.total_price', update_total_price);

        function update_total_price(price) {
            $('[id$="-field"]').each(function ()
            {

                if ($(this).val().length > 0) {
                    var option_price = $(this).attr('data-price');
                    price += parseFloat(option_price);
                }
            });
            return price;
        }

        function set_vpc_text_size(selector_id) {
            $('#' + selector_id).textfill({
                    maxFontPixels: 150,
                    minFontPixels: 13,
                    debug: true, 
                    innerTag: 'span'
            });
        }
        
        function add_text_on_preview(text,field_id){
            var field_settings=vpc.text_settings[field_id];
            var field_datas=$.parseJSON(field_settings);
            $('#'+field_datas.container+' span').html('');
            if (text.length>0){
                $('#'+field_datas.container+' span').html(text);
                $('#'+field_datas.container).css('font-family',field_datas.default_font);
                $('#'+field_datas.container).css('color',field_datas.default_color);
                //set_vpc_text_size(field_datas.container);
            }
        }
        
        $(document).on("change", '.font-selector', function ()
        {
            var current_font=$(this).val();
            var field_id=$(this).data('field');
            var field_settings=vpc.text_settings[field_id];
            var field_datas=$.parseJSON(field_settings);
            var hidden_id=field_datas.hidden_field_id;
            var color=$('#'+field_datas.color_selector_id).data('color');
            $('#'+field_datas.container).css('font-family',current_font);
            get_text_properties(current_font,color,hidden_id);
        });

        $(document).on("click", '.vpc-custom-color', function ()
        {
            var selector =$(this).data('selector');
            var color =$(this).data('color');
            var field_settings=vpc.text_settings[selector];
            var field_datas=$.parseJSON(field_settings);
            if($('#'+selector).hasClass('monogram_text')){
                var monogram_class=field_datas.option_id+'-monogram';
                var font=$('.'+monogram_class).find('.active').data('font');
                font=get_font_family(font,selector);
            }
            else    
                var font=$('#'+field_datas.font_selector_id).val();
            var hidden_id=field_datas.hidden_field_id;
            $('#'+field_datas.color_selector_id).css('background-color',color);
            $('#'+field_datas.container).css('color',color);
            get_text_properties(font,color,hidden_id);
        });
            
        function get_text_properties(font,color,hidden_id){
            var properties="font-family:"+font+" <br> color :"+color;
            $('#'+hidden_id).val(properties);
        }
        
        
           wp.hooks.addAction('vpc.ajax_loading_complete',function() {
               create_vpc_text_container();
               vpc_load_custom_color_picker();
               load_text();
           });

           /*remove component text selected image icon*/
        $('.vpc-single-option-wrap.textfield').parents('.vpc-component').find('span.vpc-selected-icon').hide(); 
            
        });

})( jQuery );
