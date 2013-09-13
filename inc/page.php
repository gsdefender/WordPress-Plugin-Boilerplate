<?php
/**
 * An abstract class for creating WordPress pages.
 *
 * @author    Christopher Lamm chris@theantichris.com
 * @copyright 2013 Christopher Lamm
 * @license   GNU General Public License, version 3
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link      http://www.theantichris.com
 */

/*
 * TODO: Replace "WordPress_Plugin_Framework" with "Plugin_Name".
 */

abstract class Page {
	/** @var string User readable title for the page and menu item. */
	protected $page_title;
	/** @var string Unique ID for the page. */
	protected $page_slug;
	/** @var string The capability required for the menu item to be displayed to the user. */
	protected $capability = 'manage_options';
	/** @var string|null The URL to the icon to be used for the menu item. */
	protected $icon_url = null;
	/** @var integer|null The position in the menu this page should appear. */
	protected $position = null;
	/** @var string Path to the view file the page will use to display content. */
	protected $view_path;
	/** @var array Any variables that the templates need access to in an associative array. */
	protected $view_data = array();

	/**
	 * Class constructor.
	 *
	 * @since 4.0.0
	 *
	 * @param string      $page_title User readable title for the page and menu item.
	 * @param string      $view_path  Path to the view file the page will use to display content.
	 * @param string      $capability The capability required for the menu item to be displayed to the user.     *
	 * @param string|null $icon_url   The URL to the icon to be used for the menu item.
	 * @param string|null $position   The position in the menu this page should appear.     *
	 * @param array       $view_data  Any variables that the templates need access to in an associative array.
	 *
	 * @return Page
	 */
	public function __construct( $page_title, $view_path, $capability = null, $icon_url = null, $position = null, $view_data = array() ) {
		$this->page_title = $page_title;
		$this->page_slug  = WordPress_Plugin_Framework::make_slug( $page_title );

		$this->view_path = $view_path;

		if ( !empty( $capability ) ) {
			$this->capability = $capability;
		}

		if ( !empty( $icon_url ) ) {
			$this->icon_url = $icon_url;
		}

		if ( !empty( $position ) ) {
			$this->position = $position;
		}

		if ( !empty( $view_data ) ) {
			$this->view_data = $view_data;
		}

		$this->view_data[ 'page_title' ] = $this->page_title;

		add_action( 'admin_menu', array( $this, 'register_page' ) );
	}

	/**
	 * Registers the page with WordPress.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	abstract public function register_page();

	/**
	 * Displays the HTML output of the page.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function display_page() {
		if ( !current_user_can( $this->capability ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wordpress-plugin-framework' ) );
		}

		echo View::render( $this->view_path, $this->view_data );
	}
}