<?php if ( ! defined( 'ABSPATH' ) ) exit;
require_once HAET_MAIL_PATH . 'includes/class-contenttype.php';
require_once HAET_MAIL_PATH . 'includes/class-contenttype-text.php';
require_once HAET_MAIL_PATH . 'includes/class-contenttype-twocol.php';
//require_once HAET_MAIL_PATH . 'includes/class-contenttype-socialicons.php';

final class Haet_Mail_Builder
{
    const VERSION = '';

    private static $instance;

    /**
     * Plugin DIRECTORY
     */
    public static $dir = '';

    /**
     * Plugin URL
     */
    public static $url = '';
    
    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof Haet_Mail_Builder)) {
            self::$instance = new Haet_Mail_Builder();

            self::$dir = plugin_dir_path(__FILE__);

            self::$url = plugin_dir_url(__FILE__);
        }

        return self::$instance;
    }

    public function __construct(){
        add_action( 'init', array($this,'register_post_type') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles') );
        add_action( 'save_post', array($this,'save_post') );
        add_filter( 'tiny_mce_before_init', array($this, 'customize_editor_toolbar' ) );  
    }

    public function register_post_type(){        
        $labels = array(
            'name'                => __( 'custom emails', 'wp-html-mail' ),
            'singular_name'       => __( 'custom email', 'wp-html-mail' ),
            'add_new'             => __( 'Add New Custom Email', 'wp-html-mail' ),
            'add_new_item'        => __( 'Add New Custom Email', 'wp-html-mail' ),
            'edit_item'           => __( 'Edit Custom Email', 'wp-html-mail' ),
            'new_item'            => __( 'New Custom Email', 'wp-html-mail' ),
            'view_item'           => __( 'View Custom Email', 'wp-html-mail' ),
            'search_items'        => __( 'Search Custom Emails', 'wp-html-mail' ),
            'not_found'           => __( 'No Custom Emails found', 'wp-html-mail' ),
            'not_found_in_trash'  => __( 'No Custom Emails found in Trash', 'wp-html-mail' ),
            'parent_item_colon'   => __( 'Parent Custom Email:', 'wp-html-mail' ),
            'menu_name'           => __( 'Custom Emails', 'wp-html-mail' ),
        );
    
        $args = array(
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'description',
            'taxonomies'          => array(),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => null,
            'menu_icon'           => null,
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => false,
            'can_export'          => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'register_meta_box_cb'=> array($this,'setup_meta_boxes'),
            'supports'            => array('')
        );
    
        register_post_type( 'wphtmlmail_mail', $args );
    }





    public function setup_meta_boxes(){
        add_meta_box( 'header', __( 'Message', 'wp-html-mail' ),
                        array($this,'render_header_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );
        add_meta_box( 'subject', __( 'Email Subject', 'wp-html-mail' ),
                        array($this,'render_subject_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );
        add_meta_box( 'mailbuilder', __( 'Email Content Builder', 'wp-html-mail' ),
                        array($this,'render_mailbuilder_meta_box'), 'wphtmlmail_mail', 'normal', 'high' );

        add_meta_box ( 'mb_attachments_metabox', __( 'Attach files', 'haet_mail' ), array($this,'render_attachments_meta_box'), 'wphtmlmail_mail', 'side', 'default' );
    }





    public function render_header_meta_box(){
        ?>
        <h1>WooCommerce <?php echo str_replace('_',' ', str_replace('WC_Email_', '', get_the_title( ) ) ); ?></h1>
        <?php
    }




    public function render_subject_meta_box(){
        global $post;
        $value = get_post_meta( $post->ID, 'subject', true );
        echo "<input type=\"text\" name=\"subject\" id=\"subject\" value='".$value."'>";
    }


    public function render_attachments_meta_box(){
        global $post;
        $attachments = get_post_meta( $post->ID, 'mailbuilder_attachments', true );
        $attachments = str_replace( "'", "&apos;", $attachments );
        ?>
        <div class="mb-preview-attachments"></div>
        <input type="hidden" name="mb_attachments" id="mb_attachments" value="0">
        <div class="uploader">
            <input id="mailbuilder_attachments" name="mailbuilder_attachments" type="hidden" value='<?php echo $attachments; ?>'/>
            <button class="button upload_attachment_button" type="button">
                <span class="dashicons dashicons-paperclip"></span>
                <?php _e('Add attachments','haet_mail'); ?>
            </button>
        </div>
        <?php
    }


    public function render_mailbuilder_meta_box(){
        global $post;
        $value = get_post_meta( $post->ID, 'mailbuilder_json', true );
        $value = str_replace( "'", "&apos;", $value );
        wp_nonce_field( 'save_mailbuilder', 'mailbuilder_nonce' );
        ?>
        <input type="hidden" name="mailbuilder_json" id="mailbuilder_json" value='<?php echo $value; ?>'>
        <div id="mailbuilder-content">

        </div>
        <div id="mailbuilder-templates">
            <?php
            do_action( 'haet_mail_content_template', $post->post_title );
            ?>
        </div>
        <div id="mb-wysiwyg-popup">
            <?php wp_editor( '', 'mb_wysiwyg_editor', array(
                        'wpautop'       =>  false,
                        'textarea_rows' =>  5,
                        'quicktags'     =>  false
                    ) ); ?>
            <div class="mb-popup-buttons">
                <button class="mb-apply button button-primary" type="button">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Apply', 'wp-html-mail'); ?>
                </button>
                <button class="mb-cancel button button-secondary" type="button">
                    <span class="dashicons dashicons-no-alt"></span>
                    <?php _e('Cancel', 'wp-html-mail'); ?>
                </button>
            </div>
        </div>
        <?php
        $this->print_add_content_button();
    }




    public function save_post( $post_id ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
            
        if ( ! isset( $_POST[ 'mailbuilder_nonce' ] ) ||
            ! wp_verify_nonce( $_POST[ 'mailbuilder_nonce' ], 'save_mailbuilder' ) )
            return;
        
        if ( ! current_user_can( 'edit_posts' ) )
            return;

        elseif( isset( $_POST['mailbuilder_json'] ) )
            update_post_meta( $post_id, 'mailbuilder_json', $_POST['mailbuilder_json'] );

        if( isset( $_POST['subject'] ) )
            update_post_meta( $post_id, 'subject', $_POST['subject'] );

        if( isset( $_POST['mailbuilder_attachments'] ) )
                    update_post_meta( $post_id, 'mailbuilder_attachments', $_POST['mailbuilder_attachments'] );
    }




    
    private function print_add_content_button(){
        ?>
        <div class="mb-add-wrap">
            <a class="button button-primary button-large mb-add" href="#">
                <?php _e('Add Content Element','wp-html-mail'); ?>
            </a> 
        </div>
        <?php
        global $post;
        $content_types = array();
        $content_types = apply_filters( 'haet_mail_content_types', $content_types, $post->post_title );
        ?>

        <ul class="mb-add-types">
            <?php foreach ($content_types as $content_type): ?>
                <li>
                    <a href="#" data-type="<?php echo $content_type['name']; ?>" <?php echo ($content_type['once']? 'data-once="once"':''); ?>>
                        <?php echo ( false !== strpos( $content_type['icon'],'dashicons-') ? '<span class="dashicons '.$content_type['icon'].'"></span>' : '<img class="mb-type-icon" src="'.$content_type['icon'].'">' ); ?>
                        <?php echo $content_type['nicename']; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <?php
    }



    public function enqueue_scripts_and_styles($page){
        if( false !== strpos($page, 'post.php')){
            global $post;
            $post_type = get_post_type( $post->ID );
            if ( $post_type == 'wphtmlmail_mail' ){
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script('haet_mailbuilder_js',  HAET_MAIL_URL.'/js/mailbuilder.js', array( 'wp-color-picker', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery'),false, true);
                wp_enqueue_style('haet_mailbuilder_css',  HAET_MAIL_URL.'/css/mailbuilder.css');
                wp_enqueue_style('haet_mail_admin_style',  HAET_MAIL_URL.'/css/style.css');

                $enqueue_data = array(
                        'translations'  =>  array(),
                        'placeholders'  =>  array(),
                        'placeholder_menu'  =>  array(),
                    );
                $enqueue_data = apply_filters( 'haet_mail_enqueue_js_data', $enqueue_data );

                wp_localize_script( 
                    'haet_mailbuilder_js', 
                    'haet_mb_data',
                    $enqueue_data
                );
            }
        }
    }





    public function get_email_post_id( $email_name ){
        $options = Haet_Mail()->get_options();
        if( !isset($options['email_post_ids']) )
            $options['email_post_ids'] = array();

        if( !isset( $options['email_post_ids'][$email_name] ) )
            $options['email_post_ids'][$email_name] = $this->create_email( $email_name );

        if( !$options['email_post_ids'][$email_name] || !get_post( $options['email_post_ids'][$email_name] ) )
            $options['email_post_ids'][$email_name] = $this->create_email( $email_name ); 

        update_option('haet_mail_options', $options );

        $email_post_id = $options['email_post_ids'][$email_name];

        // if WPML is in use we have a look for translations
        if( $email_post_id && defined( 'ICL_LANGUAGE_CODE' ) ){ 
            $translated_email_post_id = apply_filters( 'wpml_object_id',  $email_post_id, 'wphtmlmail_mail', FALSE );
            if( !$translated_email_post_id ){
                $translated_email_post_id = $this->create_email( $email_name );
                
                $wpml_element_type = apply_filters( 'wpml_element_type', 'wphtmlmail_mail' );
                
                $get_language_args = array('element_id' => $email_post_id, 'element_type' => 'wphtmlmail_mail' );
                $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );

                $set_language_args = array(
                    'element_id'    => $translated_email_post_id,
                    'element_type'  => $wpml_element_type,
                    'trid'   => $original_post_language_info->trid,
                    'language_code'   => ICL_LANGUAGE_CODE,
                    'source_language_code' => $original_post_language_info->language_code
                );
         
                do_action( 'wpml_set_element_language_details', $set_language_args );
            }

            return $translated_email_post_id;
        }

        return $options['email_post_ids'][$email_name];
    }





    private function create_email( $email_name ){
        $postarr = array(
                'post_title'    =>  $email_name,
                'post_status'   =>  'publish',
                'post_type'     =>  'wphtmlmail_mail'
            );

        $post_id = wp_insert_post( $postarr );

        do_action( 'haet_mailbuilder_create_email', $post_id, $email_name );

        return $post_id;
    }




    public function customize_editor_toolbar( $initArray ) {  
        global $post;
        if( $post ){
            $post_type = get_post_type( $post->ID );
            if( 'wphtmlmail_mail' == $post_type && $initArray['selector'] == '#mb_wysiwyg_editor' ){
                $initArray['block_formats'] = 'Headline=h1;Subheadline=h2;Paragraph=p;';

                $fonts = Haet_Mail()->get_fonts();
                $initArray['font_formats'] = "";
                foreach ($fonts as $font => $display_name) {
                    $initArray['font_formats'] .= "$display_name=$font;";
                }
                $initArray['font_formats'] = trim($initArray['font_formats'],';');

                $initArray['toolbar1'] = 'formatselect,fontselect,fontsizeselect,|,bold,italic,|,alignleft,aligncenter,alignright,|,pastetext,removeformat,|,undo,redo,|,bullist,numlist,|,link,unlink,forecolor,|,spellchecker,fullscreen';
                // Font size
                $initArray['fontsize_formats'] = "10px 11px 12px 14px 16px 18px 20px 22px 24px 28px 32px";
                
                $initArray['toolbar2'] = '';    
            }
        }


        return $initArray;  
      
    } 




    public function get_contenttype_object( $type, $email_name ){
        $content_types = array();
        $content_types = apply_filters( 'haet_mail_content_types', $content_types, $email_name );

        if ( array_key_exists( $type , $content_types ) ){
            $content_class = $content_types[$type]['elementclass'];
            return $content_class();
        }
        return null;
    }




    public function print_email($email_name,$settings){
        $email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
        $mailbuilder_json = get_post_meta( $email_id, 'mailbuilder_json', true );
        $mailbuilder_array = json_decode( $mailbuilder_json );
        if ( $mailbuilder_array != null ):
            echo '<!--mailbuilder[' . $email_name . ']-->';
            echo '<!--mailbuilder-content-start-->';
            foreach ( $mailbuilder_array as $element_content ):
                $content_element = Haet_Mail_Builder()->get_contenttype_object( $element_content->type, $email_name );
                if( $content_element )
                    $content_element->print_content( $element_content, $settings );
            endforeach;
            echo '<!--mailbuilder-content-end-->';
        endif;
    }
}



function Haet_Mail_Builder()
{
    return Haet_Mail_Builder::instance();
}

Haet_Mail_Builder();