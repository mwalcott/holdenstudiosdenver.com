<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.orionorigin.com/
 * @since      1.0.0
 *
 * @package    Vpc_Cta
 * @subpackage Vpc_Cta/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vpc_Cta
 * @subpackage Vpc_Cta/admin
 * @author     Orion <help@orionorigin.com>
 */
class Vpc_Cta_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vpc_Cta_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vpc_Cta_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vpc-cta-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style("vpc-cta-colorpicker-css", plugin_dir_url( __FILE__ ) . 'js/colorpicker/css/colorpicker.min.css', array(), $this->version, 'all');
	
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vpc_Cta_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vpc_Cta_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vpc-cta-admin.js', array( 'jquery' ), $this->version, false );
                wp_enqueue_script("vpc-cta-colorpicker-js", plugin_dir_url( __FILE__ ) . 'js/colorpicker/js/colorpicker.min.js', array('jquery'), $this->version, false);
	
	}

        function add_vpc_cta_behaviour($behaviours){
            $behaviours['text']=__('Textfield','vpc-cta');
            return $behaviours;
        }
        
        function add_vpc_cta_submenu(){
                $parent_slug = "edit.php?post_type=vpc-config";
                add_submenu_page($parent_slug, __('Add Fonts', 'vpc-cta'), __('Add Fonts', 'vpc-cta'), 'manage_product_terms', 'vpc_cta_add_fonts', array($this, 'vpc_cta_add_fonts'));
                add_submenu_page($parent_slug, __('Add Colors', 'vpc-cta'), __('Add Colors', 'vpc-cta'), 'manage_product_terms', 'vpc_cta_add_colors', array($this, 'vpc_cta_add_colors'));
    
        }
        
        function vpc_cta_add_fonts(){
            include_once( VPC_CTA_DIR . '/includes/vpc-add-fonts.php' );
            woocommerce_vpc_cta_add_fonts();
        }
        
        function vpc_cta_add_colors(){
            include_once( VPC_CTA_DIR . '/includes/vpc-add-colors.php' );
            woocommerce_vpc_cta_add_colors();
        }
        
        function add_vpc_cta_text_options($options_fields){
            $option_top=array(
                'title' => __('Text Top position (%)', 'vpc-csa'),
                'name' => 'text-top',
                'type' => 'number',
                'class'=>'custom_text_top',
            );
            
            $option_left=array(
                'title' => __('Text Left position (%)', 'vpc-csa'),
                'name' => 'text-left',
                'type' => 'number',
                'class'=>'custom_text_left',
            );
            
            $option_text_transform=array(
                'title' => __('Text rotation(&deg;)', 'vpc-csa'),
                'name' => 'angle',
                'type' => 'number',
                'class'=>'custom_text_rotation',
            );

            $option_font_size=array(
                'title' => __('Font Size', 'vpc-csa'),
                'name' => 'size',
                'type' => 'number',
                'class'=>'custom_text_font_size',
            );

            $max_characters=array(
                'title' => __('Max characters', 'vpc-csa'),
                'name' => 'max_char',
                'type' => 'number',
                'class'=>'custom_text_size',
            );

            array_push($options_fields['fields'],$option_top);
            array_push($options_fields['fields'],$option_left);
            array_push($options_fields['fields'],$max_characters);
            array_push($options_fields['fields'], $option_text_transform);  
            array_push($options_fields['fields'], $option_font_size); 
            return $options_fields;
        }
        
      
}
