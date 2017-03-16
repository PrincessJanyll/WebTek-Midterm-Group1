<?php
/**
 * Study Circle Theme Customizer
 *
 * @package Study Circle
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function study_circle_customize_register( $wp_customize ) {
	
	//Add a class for titles
    class study_circle_Info extends WP_Customize_Control {
        public $type = 'info';
        public $label = '';
        public function render_content() {
        ?>
			<h3><?php echo esc_html( $this->label ); ?></h3>
        <?php
        }
    }
	
	function study_circle_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}	

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	$wp_customize->add_setting('study_circle_color_scheme',array(
			'default'	=> '#e15e26',
			'sanitize_callback'	=> 'sanitize_hex_color'
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'study_circle_color_scheme',array(
			'label' => __('Color Scheme','study-circle'),			
			 'description'	=> __('Change color of theme','study-circle'),
			'section' => 'colors',
			'settings' => 'study_circle_color_scheme'
		))
	);
	
	// Slider Section		
	$wp_customize->add_section('study_circle_slider_section', array(
            'title' => __('Slider Settings', 'study-circle'),
            'priority' => null,
			'description'	=> __('Featured Image Size Should be same ( 1400x600 ) More slider settings available in PRO Version.','study-circle'),            			
        )
    );
	
	$wp_customize->add_setting('study_circle_page-setting7',array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('study_circle_page-setting7',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide one:','study-circle'),
			'section'	=> 'study_circle_slider_section'
	));	
	
	$wp_customize->add_setting('study_circle_page-setting8',array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('study_circle_page-setting8',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide two:','study-circle'),
			'section'	=> 'study_circle_slider_section'
	));	
	
	$wp_customize->add_setting('study_circle_page-setting9',array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('study_circle_page-setting9',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide three:','study-circle'),
			'section'	=> 'study_circle_slider_section'
	));	// Slider Section
	
	$wp_customize->add_setting('study_circle_disabled_slides',array(
			'default' => true,
			'sanitize_callback' => 'study_circle_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'study_circle_disabled_slides', array(
		   'settings' => 'study_circle_disabled_slides',
		   'section'   => 'study_circle_slider_section',
		   'label'     => __('Uncheck To Enable This Section','study-circle'),
		   'type'      => 'checkbox'
	 ));//Disable Slider Section	
	 
	 //Why Choose Us section
	$wp_customize->add_section('study_circle_section_first',array(
			'title'	=> __('Why Choose Us ','study-circle'),
			'description'	=> __('Add your details here','study-circle'),
			'priority'	=> null
	));	
	
	$wp_customize->add_setting('study_circle_why-page1',array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('study_circle_why-page1',array(
			'type' => 'dropdown-pages',			
			'section' => 'study_circle_section_first',
	));	
	
	$wp_customize->add_setting('study_circle_disabled_whychooseus',array(
			'default' => true,
			'sanitize_callback' => 'study_circle_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'study_circle_disabled_whychooseus', array(
		   'settings' => 'study_circle_disabled_whychooseus',
		   'section'   => 'study_circle_section_first',
		   'label'     => __('Uncheck To Enable This Section','study-circle'),
		   'type'      => 'checkbox'
	 )); //Disable Why Choose Us section
	
	// Home Three Boxes Section 	
	$wp_customize->add_section('study_circle_section_second', array(
			'title'	=> __('Homepage Three Boxes Section','study-circle'),
			'description'	=> __('Select Pages from the dropdown for homepage three boxes section','study-circle'),
			'priority'	=> null
	));	
	
	$wp_customize->add_setting('study_circle_page-column1',	array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
 
	$wp_customize->add_control(	'study_circle_page-column1',array(
			'type' => 'dropdown-pages',
			'section' => 'study_circle_section_second',
	));	
	
	$wp_customize->add_setting('study_circle_page-column2',	array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
 
	$wp_customize->add_control(	'study_circle_page-column2',array(
			'type' => 'dropdown-pages',
			'section' => 'study_circle_section_second',
	));
	
	$wp_customize->add_setting('study_circle_page-column3',	array(
			'default'	=> '0',			
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
 
	$wp_customize->add_control(	'study_circle_page-column3',array(
			'type' => 'dropdown-pages',
			'section' => 'study_circle_section_second',
	));//end four column page boxes
	
	$wp_customize->add_setting('study_circle_disabled_pgboxes',array(
			'default' => true,
			'sanitize_callback' => 'study_circle_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	));	 
	
	$wp_customize->add_control( 'study_circle_disabled_pgboxes', array(
		   'settings' => 'study_circle_disabled_pgboxes',
		   'section'   => 'study_circle_section_second',
		   'label'     => __('Uncheck To Enable This Section','study-circle'),
		   'type'      => 'checkbox'
	 ));//Disable Homepage boxes Section	
	
}
add_action( 'customize_register', 'study_circle_customize_register' );

function study_circle_custom_css(){
		?>
        	<style type="text/css"> 
					
					a, .blog_lists h2 a:hover,
					#sidebar ul li a:hover,									
					.blog_lists h3 a:hover,
					.cols-4 ul li a:hover, .cols-4 ul li.current_page_item a,
					.recent-post h6:hover,					
					.fourbox:hover h3,
					.footer-icons a:hover,
					.postmeta a:hover,
					.powerby a:hover
					{ color:<?php echo esc_html( get_theme_mod('study_circle_color_scheme','#e15e26')); ?>;}
					 
					
					.pagination ul li .current, .pagination ul li a:hover, 
					#commentform input#submit:hover,					
					.nivo-controlNav a.active,
					.ReadMore:hover,
					.appbutton:hover,					
					.slide_info .slide_more,				
					h3.widget-title,
					.sitenav ul li a:hover, .sitenav ul li.current_page_item a, 
					.sitenav ul li.current-menu-ancestor a.parent,					
					#sidebar .search-form input.search-submit,				
					.wpcf7 input[type='submit']					
					{ background-color:<?php echo esc_html( get_theme_mod('study_circle_color_scheme','#e15e26')); ?>;}
					
					
					.footer-icons a:hover							
					{ border-color:<?php echo esc_html( get_theme_mod('study_circle_color_scheme','#e15e26')); ?>;}					
					
					
			</style>
<?php                      
}
         
add_action('wp_head','study_circle_custom_css');	  

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function study_circle_customize_preview_js() {
	wp_enqueue_script( 'study_circle_customizer', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'study_circle_customize_preview_js' );