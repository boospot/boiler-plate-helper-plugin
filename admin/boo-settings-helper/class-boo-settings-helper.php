<?php

/**
 * The class helps in creating admin settings page
 *
 *
 * @package    Boo_Settings_Helper
 * @author     BooSpot <boospotmanager@gmail.com>
 */
class Boo_Settings_Helper {

	/**
	 * The ID of this plugin. Unique identifier of the plugin.
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $unique_id;

	private $config;

	private $fields;

	private $is_top_level_menu;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $unique_id, array $config, array $fields ) {

		$this->unique_id = $unique_id;
		$this->config = wp_parse_args( $config, $this->get_config_array_default() );

		$this->setup_properties();

		$this->setup_hooks();
//		var_dump_die($this->config);

	}


	public function get_config_array_default() {

		$config = array(
			// Menu
			'page_title'  => esc_html__( 'Plugin Name', 'plugin-name' ),
			'menu_title'  => esc_html__( 'Plugin Name', 'plugin-name' ),
			'capability'  => 'manage_options',
			'menu_slug'   => sanitize_key( $this->unique_id ),
			'callback'    => array( $this, 'display_settings_page' ),
			'icon_url'    => 'dashicons-admin-generic',
			'position'    => null,
			'top_level'   => false,
			'parent_slug' => 'options-general.php',
			'menu_slug'   => sanitize_key( $this->unique_id )

		);

		return $config;
	}

	public function setup_properties() {
		$this->is_top_level_menu = $this->config['top_level'];
	}

	public function setup_hooks() {

		add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );

	}


	public function setup_admin_menu() {

		if ( $this->config['top_level'] ) {
			add_menu_page(
				esc_html__( $this->config['page_title'] ),
				esc_html__( $this->config['menu_title'] ),
				$this->config['capability'],
				$this->config['menu_slug'],
				$this->config['callback'],
				$this->config['icon_url'],
				$this->config['position']
			);

		} else {

			add_submenu_page(
				$this->config['parent_slug'],
				esc_html__( $this->config['page_title'] ),
				esc_html__( $this->config['menu_title'] ),
				$this->config['capability'],
				sanitize_key( $this->config['menu_slug'] ),
				$this->config['callback']
			);

		}

	}

	public function display_settings_page() {
		// check if user is allowed access
		if ( ! current_user_can( $this->config['capability'] ) ) {
			return;
		}

		?>

		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">

				<?php

				// output security fields
				settings_fields( $this->unique_id.'_options_group_identifier' );

				// output setting sections
				do_settings_sections( $this->unique_id.'_sections' );

				// submit button
				submit_button();

				?>

			</form>
		</div>

		<?php

	}


}
