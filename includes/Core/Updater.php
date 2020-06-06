<?php
/**
 * This file implements the plugin updates
 *
 * @author      Alessio Catania
 * @since       1.0.0
 * @package     Wubtitle\Core
 */

namespace Wubtitle\Core;

/**
 * This class implements the plugin updates
 */
class Updater {
	/**
	 * Plugin data.
	 *
	 * @var array
	 */
	private $plugin_data;
	/**
	 * Github username.
	 *
	 * @var string
	 */
	private $username;
	/**
	 * Github repository.
	 *
	 * @var string
	 */
	private $repo;
	/**
	 * Github response.
	 *
	 * @var object
	 */
	private $release_info;

	/**
	 * Init actions
	 */
	public function run() {
		$this->plugin_data = get_plugin_data( WUBTITLE_FILE_URL );
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_transient' ) );
		add_filter( 'plugins_api', array( $this, 'set_release_info' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );
	}
	/**
	 * Get release info from github repository.
	 */
	private function get_release_info() {
		if ( ! empty( $this->release_info ) ) {
			return;
		}
		$repo_url        = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases/latest";
		$github_response = wp_remote_get( $repo_url );
		if ( is_wp_error( $github_response ) || empty( $github_response ) ) {
			return;
		}
		$code_response = wp_remote_retrieve_response_code( $github_response );
		if ( 200 !== $code_response ) {
			return;
		}
		$this->release_info = json_decode( wp_remote_retrieve_body( $github_response ) );
	}
	/**
	 * Set transient for release details.
	 *
	 * @param object $transient contains the release info.
	 */
	public function set_transient( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		$this->get_release_info();
		if ( ! property_exists( $this->release_info, 'tag_name' ) ) {
			return $transient;
		}
		$do_update = version_compare( $this->release_info->tag_name, $transient->checked[ WUBTITLE_NAME ], '>' );
		if ( $do_update ) {
			$package                              = $this->release_info->zipball_url;
			$plugin_url                           = $this->plugin_data['PluginURI'];
			$transient_obj                        = array(
				'slug'        => WUBTITLE_NAME,
				'new_version' => $this->release_info->tag_name,
				'url'         => $plugin_url,
				'package'     => $package,
			);
			$transient->response[ WUBTITLE_NAME ] = $transient_obj;
		}
		return $transient;
	}
	/**
	 * Push information to get the update information.
	 *
	 * @param array ...$args plugin info.
	 */
	public function set_release_info( ...$args ) {
		$response = $args[2];
		$this->get_release_info();
		if ( ! array_key_exists( 'slug', $response ) || WUBTITLE_NAME !== $response->slug ) {
			return false;
		}
		$response->last_updated  = $this->release_info->published_at;
		$response->slug          = WUBTITLE_NAME;
		$response->plugin_name   = $this->plugin_data['Name'];
		$response->version       = $this->release_info->tag_name;
		$response->author        = $this->plugin_data['AuthorName'];
		$response->homepage      = $this->plugin_data['PluginURI'];
		$response->download_link = $this->release_info->zipball_url;
		$response->sections      = array(
			'description' => $this->plugin_data['Description'],
			'changelog'   => $this->release_info->body,
		);
		return $response;
	}
	/**
	 * Reactivate the plugin and rename the folder with the original name.
	 *
	 * @param array ...$args installation result data.
	 */
	public function post_install( ...$args ) {
		$result        = $args[2];
		$was_activated = is_plugin_active( WUBTITLE_NAME );

		global $wp_filesystem;
		$wp_filesystem->move( $result['destination'], WUBTITLE_DIR );
		$result['destination'] = WUBTITLE_DIR;

		// if the plug-in was activated, it must be reactivated after installation.
		if ( $was_activated ) {
			activate_plugin( WUBTITLE_NAME );
		}
		return $result;
	}
}