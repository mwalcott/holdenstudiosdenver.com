<?php

function vpc_cta_create_text_option($option,$tooltip,$price,$config_to_load){
    $first_font=get_vpc_cta_first_font();
    vpc_cta_create_text_field($option,$tooltip,$price,$first_font,$config_to_load);
}

function get_vpc_cta_first_font(){
    $fonts=get_option('vpc-cta-fonts');
    foreach($fonts as $font){
        return $font[0];
    }
}
function get_vpc_cta_first_color(){
    $colors=get_option('vpc-cta-colors');
    foreach ($colors as $color) {
        return $color[1];
    }
}

function vpc_cta_create_text_field($option,$tooltip,$price,$font,$config_to_load,$monogram=false){
    $sanitized_name = sanitize_title($option["name"]);
    $first_color=get_vpc_cta_first_color();
    $opt_name=get_proper_value($option, "name","");
    $opt_max_char=get_proper_value($option, "max_char",10);
    $fonts=get_option('vpc-cta-fonts');
    $class=$text_value="";
    if(isset($config_to_load[$option["name"]]))
        $text_value=$config_to_load[$option["name"]];
    $hidden_field_name=$opt_name.' properties';
    if(isset($config_to_load[$hidden_field_name])){
        $properties=explode('<br>',$config_to_load[$hidden_field_name]);
        $font_properties=explode(':',$properties[0]);
        $font=trim($font_properties[1]);
        $color_properties=explode(':',$properties[1]);
        $first_color=trim($color_properties[1]);
    }
         
    if($monogram)
        $class="monogram_text";
         ?>
        <div class="vpc-single-option-wrap textfield" data-oid="" >
            <label><?php echo $tooltip;?></label>
            <input type="hidden" name="<?php echo $opt_name;?> properties" value="font-family:<?php echo $font; ?> <br> color:<?php echo $first_color; ?>" id="<?php echo $sanitized_name;?>-properties"/>

            <div class="vpc-textfield">
                <div class="vpc-textfield-color">
                    <span class="vpc-textfield-label">Color</span>
                    <span id="<?php echo $sanitized_name;?>-color-selector"  data-field="<?php echo $sanitized_name;?>-field" style="background-color:<?php echo $first_color; ?>" data-color="<?php echo $first_color; ?>"></span>
                    <span class="color-code">
                <?php echo $first_color; ?></span>
                </div>
            <?php
            if(!$monogram){
                ?>
                <div class="vpc-textfield-font">
                    <span class="vpc-textfield-label">Font</span>
                    <select id="<?php echo $sanitized_name;?>-font-selector" data-field="<?php echo $sanitized_name;?>-field" class="font-selector text-element-border">
                            <?php
                            foreach ($fonts as $font_value) {
                                $font_label = $font_value[0];
                                echo '<option value="'.$font_label.'" style="font-family: '.$font_label.',sans-serif">'.$font_label.'</option>';
                            }
                            ?>
                    </select> 
                </div>
            <?php
            }
            ?>
            </div>

            <div class="textfield-box">
                <input id="<?php  echo $sanitized_name;?>-field"  name="<?php echo $opt_name;?>" class="<?php echo $class; ?>" type="text"  value="<?php echo $text_value;?>" maxlength="<?php echo $opt_max_char;?>"  data-price='<?php echo $price;?>'/>
            </div>
            <?php
                $field_selector=$sanitized_name."-field";
                $field_datas=array(
                    'container'=>$sanitized_name."-container",
                    'opt_name'=>$opt_name,
                    'top' => !empty(get_proper_value($option, "text-top"))?get_proper_value($option, "text-top"):0,
                    'left' => !empty(get_proper_value($option, "text-left"))?get_proper_value($option, "text-left"):0,
                    'angle' =>!empty(get_proper_value($option, "angle"))?get_proper_value($option, "angle"):0,
                    'size'=> !empty(get_proper_value($option, "size"))?get_proper_value($option, "size"):15,
                    'option_id'=>$sanitized_name,
                    'font'=>$font,
                    'price'=>$price,
                    'hidden_field_id'=>$sanitized_name.'-properties',
                    'color_selector_id'=>$sanitized_name.'-color-selector',
                    'font_selector_id'=>$sanitized_name.'-font-selector',
                    'palettes'=>get_vpc_cta_colors_palette($field_selector),
                    'default_color'=>$first_color,
                    'default_font'=>$font,
                );
            ?>
            <script>
                vpc.text_settings["<?php echo $sanitized_name.'-field';?>"]='<?php echo json_encode($field_datas);?>';
            </script>
        </div>
        <?php 
        
}

function get_vpc_cta_colors_palette($selector){
    $palette="";
    $colors=get_option('vpc-cta-colors');
    foreach ($colors as $color) {
       // $hex = str_replace("#", "", $color[1]);
        $palette.='<span data-selector=\"'.$selector.'\" style=\"background-color: ' . $color[1] . '\" data-color=\"' . $color[1] . '\" data-name=\"' . $color[0] . '\" class=\"vpc-custom-color\"></span>';
    }
    return $palette;
}