<?php if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Haet_MB_ContentType
{
	/**
	 * @var string
	 */
	protected $_name  = '';

	/**
	 * @var string
	 */
	protected $_nicename = '';

	/**
	 * @var int
	 */
	protected $_priority = 10;

	/**
	 * @var bool
	 * contenttype can be used once per email
	 */
	protected $_once = false;

	/**
	 * @var string 
	 * Dashicon or image url
	 */
	protected $_icon = 'dashicons-screenoptions';


	public function __construct(){
		add_filter( 'haet_mail_content_types', array( $this, 'register_contenttype'), $this->_priority, 2 );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		add_action( 'haet_mail_content_template', array($this, 'admin_render_contentelement_template'), $this->_priority );
	}





	public function register_contenttype( $content_types, $current_email ){
		$content_types[$this->_name] = array(
				'name'		=>	$this->_name,
				'nicename'	=>	$this->_nicename,
				'icon'		=>	$this->_icon,
				'once'		=>	$this->_once,
				'elementclass'	=>	get_called_class()
			);
		return $content_types;
	}




	protected function admin_print_element_start(){
		?>
		<div class="mb-contentelement mb-contentelement-<?php echo $this->_name; ?> clearfix" data-type="<?php echo $this->_name; ?>">
		<?php
		$this->admin_print_element_title_bar(); 
	}




	protected function admin_print_element_title_bar(){
		?>
		<div class="mb-title-bar">
			<?php echo ( false !== strpos( $this->_icon,'dashicons-') ? '<span class="dashicons '.$this->_icon.'"></span>' : '<img class="mb-type-icon" src="'.$this->_icon.'">' ); ?>
			<span class="mb-title">
				<?php echo $this->_nicename; ?>
			</span>
			<a href='#' class="mb-remove-element">
				<span class="dashicons dashicons-trash"></span>
			</a>
		</div>
		<?php
	}




	protected function admin_print_element_end(){
		?>
		</div>
		<?php
	}



	public abstract function enqueue_scripts_and_styles($page);

	public abstract function admin_render_contentelement_template( $current_email );

	public abstract function print_content( $element_content, $settings );
}

