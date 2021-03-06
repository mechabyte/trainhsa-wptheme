<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('admin_folder_Redux_Framework_config')) {

    class admin_folder_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('General Settings', 'redux-framework-demo'),
                'fields'    => array(
                    
                    array(
                        'id'        => 'opt-textarea',
                        'type'      => 'textarea',
                        'required'  => array('layout', 'equals', '1'),
                        'title'     => __('Tracking Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'redux-framework-demo'),
                        'validate'  => 'js',
                        'desc'      => 'Validate that it\'s javascript!',
                    ),
                    array(
                        'id'        => 'opt-custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste any custom CSS code here.', 'redux-framework-demo'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                    ),
                    array(
                        'id'        => 'opt-custom-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste any custom JS code here.', 'redux-framework-demo'),
                        'mode'      => 'javascript',
                        'theme'     => 'chrome',
                        'default'   => "jQuery(document).ready(function(){\n\n});"
                    ),
                    array(
                            'id'        => 'opt-analytics',
                            'type'      => 'text',
                            'title'     => __('Google Analytics Tracking ID', 'redux-framework-demo'),
                            'subtitle'  => __('UA-XXXXXXXX-X'),
                            'default'   => 'UA-23195150-1',
                        ),
                )
            );

            $this->sections[] = array(
                'title'     => __('Slider Settings', 'redux-framework-demo'),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    /*
                    array(
                        'id'        => 'opt-web-fonts',
                        'type'      => 'media',
                        'title'     => __('Web Fonts', 'redux-framework-demo'),
                        'compiler'  => 'true',
                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                        'hint'      => array(
                            //'title'     => '',
                            'content'   => 'This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here.',
                        )
                    ),
                    array(
                        'id'        => 'section-media-start',
                        'type'      => 'section',
                        'title'     => __('Media Options', 'redux-framework-demo'),
                        'subtitle'  => __('With the "section" field you can create indent option sections.', 'redux-framework-demo'),
                        'indent'    => true // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'opt-media',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Media w/ URL', 'redux-framework-demo'),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Basic media uploader with disabled URL input field.', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                        'default'   => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                        //'hint'      => array(
                        //    'title'     => 'Hint Title',
                        //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                        //)
                    ),
                    array(
                        'id'        => 'section-media-end',
                        'type'      => 'section',
                        'indent'    => false // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'media-no-url',
                        'type'      => 'media',
                        'title'     => __('Media w/o URL', 'redux-framework-demo'),
                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'media-no-preview',
                        'type'      => 'media',
                        'preview'   => false,
                        'title'     => __('Media No Preview', 'redux-framework-demo'),
                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-gallery',
                        'type'      => 'gallery',
                        'title'     => __('Add/Edit Gallery', 'so-panels'),
                        'subtitle'  => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'            => 'opt-slider-label',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 1', 'redux-framework-demo'),
                        'subtitle'      => __('This slider displays the value as a label.', 'redux-framework-demo'),
                        'desc'          => __('Slider description. Min: 1, max: 500, step: 1, default value: 250', 'redux-framework-demo'),
                        'default'       => 250,
                        'min'           => 1,
                        'step'          => 1,
                        'max'           => 500,
                        'display_value' => 'label'
                    ),
                    array(
                        'id'            => 'opt-slider-text',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 2 with Steps (5)', 'redux-framework-demo'),
                        'subtitle'      => __('This example displays the value in a text box', 'redux-framework-demo'),
                        'desc'          => __('Slider description. Min: 0, max: 300, step: 5, default value: 75', 'redux-framework-demo'),
                        'default'       => 75,
                        'min'           => 0,
                        'step'          => 5,
                        'max'           => 300,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'opt-slider-select',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 3 with two sliders', 'redux-framework-demo'),
                        'subtitle'      => __('This example displays the values in select boxes', 'redux-framework-demo'),
                        'desc'          => __('Slider description. Min: 0, max: 500, step: 5, slider 1 default value: 100, slider 2 default value: 300', 'redux-framework-demo'),
                        'default'       => array(
                            1 => 100,
                            2 => 300,
                        ),
                        'min'           => 0,
                        'step'          => 5,
                        'max'           => '500',
                        'display_value' => 'select',
                        'handles'       => 2,
                    ),
                    array(
                        'id'            => 'opt-slider-float',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 4 with float values', 'redux-framework-demo'),
                        'subtitle'      => __('This example displays float values', 'redux-framework-demo'),
                        'desc'          => __('Slider description. Min: 0, max: 1, step: .1, default value: .5', 'redux-framework-demo'),
                        'default'       => .5,
                        'min'           => 0,
                        'step'          => .1,
                        'max'           => 1,
                        'resolution'    => 0.1,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'opt-spinner',
                        'type'      => 'spinner',
                        'title'     => __('JQuery UI Spinner Example 1', 'redux-framework-demo'),
                        'desc'      => __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'redux-framework-demo'),
                        'default'   => '40',
                        'min'       => '20',
                        'step'      => '20',
                        'max'       => '100',
                    ),
                    array(
                        'id'        => 'switch-on',
                        'type'      => 'switch',
                        'title'     => __('Switch On', 'redux-framework-demo'),
                        'subtitle'  => __('Look, it\'s on!', 'redux-framework-demo'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'switch-off',
                        'type'      => 'switch',
                        'title'     => __('Switch Off', 'redux-framework-demo'),
                        'subtitle'  => __('Look, it\'s on!', 'redux-framework-demo'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'switch-custom',
                        'type'      => 'switch',
                        'title'     => __('Switch - Custom Titles', 'redux-framework-demo'),
                        'subtitle'  => __('Look, it\'s on! Also hidden child elements!', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'switch-fold',
                        'type'      => 'switch',
                        'required'  => array('switch-custom', '=', '1'),
                        'title'     => __('Switch - With Hidden Items (NESTED!)', 'redux-framework-demo'),
                        'subtitle'  => __('Also called a "fold" parent.', 'redux-framework-demo'),
                        'desc'      => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'redux-framework-demo'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'opt-patterns',
                        'type'      => 'image_select',
                        'tiles'     => true,
                        'title'     => __('Images Option (with pattern=>true)', 'redux-framework-demo'),
                        'subtitle'  => __('Select a background pattern.', 'redux-framework-demo'),
                        'default'   => 0,
                        'options'   => $sample_patterns,
                    ),
                    array(
                        'id'        => 'opt-homepage-layout',
                        'type'      => 'sorter',
                        'title'     => 'Layout Manager Advanced',
                        'subtitle'  => 'You can add multiple drop areas or columns.',
                        'compiler'  => 'true',
                        'options'   => array(
                            'enabled'   => array(
                                'highlights'    => 'Highlights',
                                'slider'        => 'Slider',
                                'staticpage'    => 'Static Page',
                                'services'      => 'Services'
                            ),
                            'disabled'  => array(
                            ),
                            'backup'    => array(
                            ),
                        ),
                        'limits' => array(
                            'disabled'  => 1,
                            'backup'    => 2,
                        ),
                    ),
                    */

                    /* My Stuff */
                    array(
                        'id'        => 'opt-slides',
                        'type'      => 'slides',
                        'title'     => __('Slider Options', 'redux-framework-demo'),
                        'subtitle'  => __('Easily manage the slides that display on your homepage.', 'redux-framework-demo'),
                        'desc'      => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo'),
                        'placeholder'   => array(
                            'title'         => __('Slide Title', 'redux-framework-demo'),
                            'description'   => __('Slide Description', 'redux-framework-demo'),
                            'url'           => __('Link: Wordpress URL or YouTube Video', 'redux-framework-demo'),
                        ),
                    ),

                    /*
                    array(
                        'id'            => 'opt-typography',
                        'type'          => 'typography',
                        'title'         => __('Typography', 'redux-framework-demo'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
                        'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography option with each property can be called individually.', 'redux-framework-demo'),
                        'default'       => array(
                            'color'         => '#333',
                            'font-style'    => '700',
                            'font-family'   => 'Abel',
                            'google'        => true,
                            'font-size'     => '33px',
                            'line-height'   => '40px'),
                        'preview' => array('text' => 'ooga booga'),
                    ),
                    */
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Call to Action Boxes','redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-cta-layout',
                        'type'      => 'sorter',
                        'title'     => '"Call to Action" Layout Manager',
                        'subtitle'      => 'Organize how you want the CTA boxes to appear beneath the homepage slider',
                        'compiler'  => 'true',
                        'options'   => array(
                            'enabled'   => array(
                                'training'      => 'Training',
                                'leagues'       => 'Leagues',
                                'camps'         => 'Camps & Clinics',
                                'tournaments'   => 'Tournaments',
                                'afterschool'   => 'After School Programs',
                                'homeschool'    => 'Home School Programs',
                                'travelteams'   => 'Travel Teams'
                            ),
                            'disabled'  => array()
                        ),
                        'limits' => array(
                            'enabled'  => 4,
                        ),
                    ),

                    array(
                        'id'        => 'opt-cta-training-text',
                        'type'      => 'textarea',
                        'title'     => __('Training Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-training-link-text',
                            'type'      => 'text',
                            'title'     => __('Training Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-training-trial-boolean',
                            'type'      => 'switch',
                            'title'     => __('Popup Trial Form?', 'redux-framework-demo'),
                            'default'   => 1,
                            'on'        => 'Enabled',
                            'off'       => 'Disabled',
                        ),

                        array(
                            'id'        => 'opt-cta-signup-form',
                            'type'      => 'text',
                            'required'  => array('opt-cta-training-trial-boolean', 'equals', '1'),
                            'title'     => __('Popup Trial Form Shortcode', 'redux-framework-demo'),
                            'subtitle'      => __('Select what shortcode you\'d like to use to display the free trial form', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-training-trial-text',
                            'type'      => 'text',
                            'title'     => __('Popup Trial Form - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-signup-form-alert',
                            'type'      => 'text',
                            'required'  => array('opt-cta-training-trial-boolean', 'equals', '1'),
                            'title'     => __('Popup Trial Form Success Message', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-training-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            //'required'  => array('opt-cta-training-trial-boolean', 'equals', '0'),
                            'title'     => __('Training Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-leagues-text',
                        'type'      => 'textarea',
                        'title'     => __('Leagues Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-leagues-link-text',
                            'type'      => 'text',
                            'title'     => __('Leagues Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-leagues-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('Leagues Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-camps-text',
                        'type'      => 'textarea',
                        'title'     => __('Camps & Clinics Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-camps-link-text',
                            'type'      => 'text',
                            'title'     => __('Camps & Clinics Box - Link Text', 'redux-framework-demo'),
                        ),


                        array(
                            'id'        => 'opt-cta-camps-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('Camps & Clinics Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-tournaments-text',
                        'type'      => 'textarea',
                        'title'     => __('Tournaments Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-tournaments-link-text',
                            'type'      => 'text',
                            'title'     => __('Tournaments Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-tournaments-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('Tournaments Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-homeschool-text',
                        'type'      => 'textarea',
                        'title'     => __('Home School Programs Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-homeschool-link-text',
                            'type'      => 'text',
                            'title'     => __('Home School Programs Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-homeschool-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('Home School Programs Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-afterschool-text',
                        'type'      => 'textarea',
                        'title'     => __('After School Programs Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-afterschool-link-text',
                            'type'      => 'text',
                            'title'     => __('After School Programs Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-afterschool-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('After School Programs Box - Link To?', 'redux-framework-demo'),
                        ),

                    array(
                        'id'        => 'opt-cta-travelteams-text',
                        'type'      => 'textarea',
                        'title'     => __('Travel Teams Box - Body Text', 'redux-framework-demo')
                    ),

                        array(
                            'id'        => 'opt-cta-travelteams-link-text',
                            'type'      => 'text',
                            'title'     => __('Travel Teams Box - Link Text', 'redux-framework-demo'),
                        ),

                        array(
                            'id'        => 'opt-cta-travelteams-link',
                            'type'      => 'select',
                            'data'      => 'pages',
                            'title'     => __('Travel Teams Box - Link To?', 'redux-framework-demo'),
                        ),

                ),
            );

            $this->sections[] = array(
                'title'     => __('Social Networking', 'redux-framework-demo'),
                'icon'      => 'el-icon-group',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                            'id'        => 'opt-social-facebook',
                            'type'      => 'text',
                            'title'     => __('Facebook URL', 'redux-framework-demo'),
                        ),
                    array(
                            'id'        => 'opt-social-twitter',
                            'type'      => 'text',
                            'title'     => __('Twitter Username', 'redux-framework-demo'),
                        ),
                    array(
                            'id'        => 'opt-social-instagram',
                            'type'      => 'text',
                            'title'     => __('Instagram Username', 'redux-framework-demo'),
                        ),
                    array(
                            'id'        => 'opt-social-youtube',
                            'type'      => 'text',
                            'title'     => __('YouTube Username', 'redux-framework-demo'),
                        ),
                    )
            );

            /**
             *  Note here I used a 'heading' in the sections array construct
             *  This allows you to use a different title on your options page
             * instead of reusing the 'title' value.  This can be done on any
             * section - kp
             */
            /*
            $this->sections[] = array(
                'icon'      => 'el-icon-bullhorn',
                'title'     => __('Field Validation', 'redux-framework-demo'),
                'heading'   => __('Validate ALL fields within Redux.', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-text-email',
                        'type'      => 'text',
                        'title'     => __('Text Option - Email Validated', 'redux-framework-demo'),
                        'subtitle'  => __('This is a little space under the Field Title in the Options table, additional info is good in here.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'email',
                        'msg'       => 'custom error message',
                        'default'   => 'test@test.com',
//                        'text_hint' => array(
//                            'title'     => 'Valid Email Required!',
//                            'content'   => 'This field required a valid email address.'
//                        )
                    ),
                    array(
                        'id'        => 'opt-text-post-type',
                        'type'      => 'text',
                        'title'     => __('Text Option with Data Attributes', 'redux-framework-demo'),
                        'subtitle'  => __('You can also pass an options array if you want. Set the default to whatever you like.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'data'      => 'post_type',
                    ),
                    array(
                        'id'        => 'opt-multi-text',
                        'type'      => 'multi_text',
                        'title'     => __('Multi Text Option - Color Validated', 'redux-framework-demo'),
                        'validate'  => 'color',
                        'subtitle'  => __('If you enter an invalid color it will be removed. Try using the text "blue" as a color.  ;)', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-text-url',
                        'type'      => 'text',
                        'title'     => __('Text Option - URL Validated', 'redux-framework-demo'),
                        'subtitle'  => __('This must be a URL.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'url',
                        'default'   => 'http://reduxframework.com',
//                        'text_hint' => array(
//                            'title'     => '',
//                            'content'   => 'Please enter a valid <strong>URL</strong> in this field.'
//                        )
                    ),
                    array(
                        'id'        => 'opt-text-numeric',
                        'type'      => 'text',
                        'title'     => __('Text Option - Numeric Validated', 'redux-framework-demo'),
                        'subtitle'  => __('This must be numeric.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'numeric',
                        'default'   => '0',
                    ),
                    array(
                        'id'        => 'opt-text-comma-numeric',
                        'type'      => 'text',
                        'title'     => __('Text Option - Comma Numeric Validated', 'redux-framework-demo'),
                        'subtitle'  => __('This must be a comma separated string of numerical values.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'comma_numeric',
                        'default'   => '0',
                    ),
                    array(
                        'id'        => 'opt-text-no-special-chars',
                        'type'      => 'text',
                        'title'     => __('Text Option - No Special Chars Validated', 'redux-framework-demo'),
                        'subtitle'  => __('This must be a alpha numeric only.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'no_special_chars',
                        'default'   => '0'
                    ),
                    array(
                        'id'        => 'opt-text-str_replace',
                        'type'      => 'text',
                        'title'     => __('Text Option - Str Replace Validated', 'redux-framework-demo'),
                        'subtitle'  => __('You decide.', 'redux-framework-demo'),
                        'desc'      => __('This field\'s default value was changed by a filter hook!', 'redux-framework-demo'),
                        'validate'  => 'str_replace',
                        'str'       => array(
                            'search'        => ' ', 
                            'replacement'   => 'thisisaspace'
                        ),
                        'default'   => 'This is the default.'
                    ),
                    array(
                        'id'        => 'opt-text-preg_replace',
                        'type'      => 'text',
                        'title'     => __('Text Option - Preg Replace Validated', 'redux-framework-demo'),
                        'subtitle'  => __('You decide.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'preg_replace',
                        'preg'      => array(
                            'pattern'       => '/[^a-zA-Z_ -]/s', 
                            'replacement'   => 'no numbers'
                         ),
                        'default'   => '0'
                    ),
                    array(
                        'id'                => 'opt-text-custom_validate',
                        'type'              => 'text',
                        'title'             => __('Text Option - Custom Callback Validated', 'redux-framework-demo'),
                        'subtitle'          => __('You decide.', 'redux-framework-demo'),
                        'desc'              => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate_callback' => 'redux_validate_callback_function',
                        'default'           => '0'
                    ),
                    array(
                        'id'        => 'opt-textarea-no-html',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - No HTML Validated', 'redux-framework-demo'),
                        'subtitle'  => __('All HTML will be stripped', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'no_html',
                        'default'   => 'No HTML is allowed in here.'
                    ),
                    array(
                        'id'        => 'opt-textarea-html',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - HTML Validated', 'redux-framework-demo'),
                        'subtitle'  => __('HTML Allowed (wp_kses)', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                        'default'   => 'HTML is allowed in here.'
                    ),
                    array(
                        'id'            => 'opt-textarea-some-html',
                        'type'          => 'textarea',
                        'title'         => __('Textarea Option - HTML Validated Custom', 'redux-framework-demo'),
                        'subtitle'      => __('Custom HTML Allowed (wp_kses)', 'redux-framework-demo'),
                        'desc'          => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'      => 'html_custom',
                        'default'       => '<p>Some HTML is allowed in here.</p>',
                        'allowed_html'  => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
                    ),
                    array(
                        'id'        => 'opt-textarea-js',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - JS Validated', 'redux-framework-demo'),
                        'subtitle'  => __('JS will be escaped', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'validate'  => 'js'
                    ),
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-check',
                'title'     => __('Radio/Checkbox Fields', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-checkbox',
                        'type'      => 'checkbox',
                        'title'     => __('Checkbox Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'default'   => '1'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opt-multi-check',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                        //Must provide key => value pairs for multi checkbox options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        
                        //See how std has changed? you also don't need to specify opts that are 0.
                        'default'   => array(
                            '1' => '1', 
                            '2' => '0', 
                            '3' => '0'
                        )
                    ),
                    array(
                        'id'        => 'opt-checkbox-data',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-checkbox-sidebar',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with sidebar data)', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'data'      => 'sidebars'
                    ),
                    array(
                        'id'        => 'opt-radio',
                        'type'      => 'radio',
                        'title'     => __('Radio Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                         //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-radio-data',
                        'type'      => 'radio',
                        'title'     => __('Multi Checkbox Option (with menu data)', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-image-select',
                        'type'      => 'image_select',
                        'title'     => __('Images Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
                            '2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
                            '3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
                            '4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-image-select-layout',
                        'type'      => 'image_select',
                        'title'     => __('Images Option for Layout', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This uses some of the built in images, you can use them for layout options.', 'redux-framework-demo'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('alt' => '1 Column',        'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                            '5' => array('alt' => '3 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                            '6' => array('alt' => '3 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ), 
                        'default' => '2'
                    ),
                    array(
                        'id'        => 'opt-sortable',
                        'type'      => 'sortable',
                        'title'     => __('Sortable Text Option', 'redux-framework-demo'),
                        'subtitle'  => __('Define and reorder these however you want.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                    array(
                        'id'        => 'opt-check-sortable',
                        'type'      => 'sortable',
                        'mode'      => 'checkbox', // checkbox or text
                        'title'     => __('Sortable Text Option', 'redux-framework-demo'),
                        'subtitle'  => __('Define and reorder these however you want.', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Select Fields', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-select',
                        'type'      => 'select',
                        'title'     => __('Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-multi-select',
                        'type'      => 'select',
                        'multi'     => true,
                        'title'     => __('Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'required'  => array('select', 'equals', array('1', '3')),
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-select-image',
                        'type'      => 'select_image',
                        'title'     => __('Select Image', 'redux-framework-demo'),
                        'subtitle'  => __('A preview of the selected image will appear underneath the select box.', 'redux-framework-demo'),
                        'options'   => $sample_patterns,
                        // Alternatively
                        //'options'   => Array(
                        //                'img_name' => 'img_path'
                        //             )
                        'default' => 'tree_bark.png',
                    ),
                    array(
                        'id'    => 'opt-info',
                        'type'  => 'info',
                        'desc'  => __('You can easily add a variety of data from WordPress.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-categories',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'title'     => __('Categories Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-categories-multi',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'multi'     => true,
                        'title'     => __('Categories Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'title'     => __('Pages Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'multi'     => true,
                        'title'     => __('Pages Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'title'     => __('Tags Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'multi'     => true,
                        'title'     => __('Tags Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-menus',
                        'type'      => 'select',
                        'data'      => 'menus',
                        'title'     => __('Menus Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-menus',
                        'type'      => 'select',
                        'data'      => 'menu',
                        'multi'     => true,
                        'title'     => __('Menus Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'title'     => __('Post Type Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'title'     => __('Post Type Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-sortable',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'sortable'  => true,
                        'title'     => __('Post Type Multi Select Option + Sortable', 'redux-framework-demo'),
                        'subtitle'  => __('This field also has sortable enabled!', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'title'     => __('Posts Select Option2', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-multi-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'multi'     => true,
                        'title'     => __('Posts Multi Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-roles',
                        'type'      => 'select',
                        'data'      => 'roles',
                        'title'     => __('User Role Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-capabilities',
                        'type'      => 'select',
                        'data'      => 'capabilities',
                        'multi'     => true,
                        'title'     => __('Capabilities Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'opt-select-elusive',
                        'type'      => 'select',
                        'data'      => 'elusive-icons',
                        'title'     => __('Elusive Icons Select Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('Here\'s a list of all the elusive icons by name and icon.', 'redux-framework-demo'),
                    ),
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'redux-framework-demo') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'redux-framework-demo') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'redux-framework-demo') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'redux-framework-demo') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }
            
            // You can append a new section at any time.
            $this->sections[] = array(
                'icon'      => 'el-icon-eye-open',
                'title'     => __('Additional Fields', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-datepicker',
                        'type'      => 'date',
                        'title'     => __('Date Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo')
                    ),
                    array(
                        'id'    => 'opt-divide',
                        'type'  => 'divide'
                    ),
                    array(
                        'id'        => 'opt-button-set',
                        'type'      => 'button_set',
                        'title'     => __('Button Set Option', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-button-set-multi',
                        'type'      => 'button_set',
                        'title'     => __('Button Set, Multi Select', 'redux-framework-demo'),
                        'subtitle'  => __('No validation can be done on this field type', 'redux-framework-demo'),
                        'desc'      => __('This is the description field, again good for additional info.', 'redux-framework-demo'),
                        'multi'     => true,
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-info-field',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'desc'      => __('This is the info field, if you want to break sections up.', 'redux-framework-demo')
                    ),
                    array(
                        'id'    => 'opt-info-warning',
                        'type'  => 'info',
                        'style' => 'warning',
                        'title' => __('This is a title.', 'redux-framework-demo'),
                        'desc'  => __('This is an info field with the warning style applied and a header.', 'redux-framework-demo')
                    ),
                    array(
                        'id'    => 'opt-info-success',
                        'type'  => 'info',
                        'style' => 'success',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', 'redux-framework-demo'),
                        'desc'  => __('This is an info field with the success style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'    => 'opt-info-critical',
                        'type'  => 'info',
                        'style' => 'critical',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', 'redux-framework-demo'),
                        'desc'  => __('This is an info field with the critical style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-raw_info',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'raw_html'  => true,
                        'desc'      => $sampleHTML,
                    ),
                    array(
                        'id'        => 'opt-info-normal',
                        'type'      => 'info',
                        'notice'    => true,
                        'title'     => __('This is a title.', 'redux-framework-demo'),
                        'desc'      => __('This is an info notice field with the normal style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-notice-info',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'info',
                        'title'     => __('This is a title.', 'redux-framework-demo'),
                        'desc'      => __('This is an info notice field with the info style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-notice-warning',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'warning',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'redux-framework-demo'),
                        'desc'      => __('This is an info notice field with the warning style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-notice-success',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'success',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'redux-framework-demo'),
                        'desc'      => __('This is an info notice field with the success style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-notice-critical',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'critical',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', 'redux-framework-demo'),
                        'desc'      => __('This is an notice field with the critical style applied, a header and an icon.', 'redux-framework-demo')
                    ),
                    array(
                        'id'        => 'opt-custom-callback',
                        'type'      => 'callback',
                        'title'     => __('Custom Field Callback', 'redux-framework-demo'),
                        'subtitle'  => __('This is a completely unique field type', 'redux-framework-demo'),
                        'desc'      => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'redux-framework-demo'),
                        'callback'  => 'redux_my_custom_field'
                    ),
                )
            );

            */

            $this->sections[] = array(
                'title'     => __('Import / Export', 'redux-framework-demo'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'redux-framework-demo'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'redux-framework-demo'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'trainhsa',
                'page_slug' => '_options',
                'page_title' => 'Theme Options',
                'update_notice' => '1',
                'intro_text' => '<p>This text is displayed above the options panel. It isn\\’t required, but more info is always better! The intro_text field accepts all HTML.</p>’',
                'footer_text' => '<p>This text is displayed below the options panel. It isn\\’t required, but more info is always better! The footer_text field accepts all HTML.</p>',
                'admin_bar' => '1',
                'menu_type' => 'menu',
                'menu_title' => 'Theme Options',
                'allow_sub_menu' => '1',
                'page_parent_post_type' => 'your_post_type',
                'hints' => 
                array(
                  'icon' => 'el-icon-question-sign',
                  'icon_position' => 'right',
                  'icon_size' => 'normal',
                  'tip_style' => 
                  array(
                    'color' => 'light',
                  ),
                  'tip_position' => 
                  array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                  ),
                  'tip_effect' => 
                  array(
                    'show' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseover',
                    ),
                    'hide' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseleave unfocus',
                    ),
                  ),
                ),
                'output' => '1',
                'output_tag' => '1',
                'compiler' => '1',
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => '1',
                'show_import_export' => '1',
                'transient_time' => '3600',
                'network_sites' => '1',
              );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new admin_folder_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('admin_folder_my_custom_field')):
    function admin_folder_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('admin_folder_validate_callback_function')):
    function admin_folder_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
