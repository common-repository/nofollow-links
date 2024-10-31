<?php
/**
 * Nofollow Links Plugin
 *
 * @package Nofollow_Links
 * @subpackage Plugin
 * @author Andrew Shell <andrew@andrewshell.org>
 * @copyright 2008-2016 Andrew Shell
 * @license GPL2
 */

/*
Plugin Name: Nofollow Links
Plugin URI: http://blog.andrewshell.org/nofollow-links/
Description: Select which links in your blogroll you want to nofollow.
Version: 1.0.12
Author: Andrew Shell
Author URI: http://blog.andrewshell.org/
Text Domain: nofollow-links
Domain Path: /languages/
License: GPL2

Copyright (c) 2013 Andrew Shell  (email : andrew@andrewshell.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Init plugin
 */
function nofollow_links_init() {
	load_plugin_textdomain( 'nofollow-links', false, 'nofollow-links/languages' );
	add_action( 'admin_menu', 'nofollow_links_admin_menu' );
	add_filter( 'get_bookmarks', 'nofollow_links_get_bookmarks', 10, 2 );
	add_filter( 'pre_option_link_manager_enabled', '__return_true' );
}
add_action( 'plugins_loaded', 'nofollow_links_init' );

/**
 * No follow links admin menu
 */
function nofollow_links_admin_menu() {
	add_management_page(
		__( 'Nofollow Links', 'nofollow-links' ),
		__( 'Nofollow Links', 'nofollow-links' ),
		'manage_options',
		'link-nofollow',
		'nofollow_links_manage'
	);
	add_submenu_page(
		'link-manager.php',
		__( 'Nofollow Links', 'nofollow-links' ),
		__( 'Nofollow Links', 'nofollow-links' ),
		'manage_options',
		'link-nofollow',
		'nofollow_links_manage'
	);
}

/**
 * Management page for admin
 */
function nofollow_links_manage() {
	if ( isset( $_POST['nofollowbookmarks'] ) ) { // Input var okay.
		check_admin_referer( 'nofollow_links_manage' );

		$linkcheck = isset( $_POST['linkcheck'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['linkcheck'] ) ) : array(); // Input var okay.
		$nofollow  = array_combine( $linkcheck, $linkcheck );

		update_option( 'nofollow_links', wp_json_encode( $nofollow ) );
		/* translators: %d is number of links selected */
		echo '<div style="background-color: rgb(207, 235, 247);" id="message" class="updated fade"><p>' . sprintf( esc_html( _n( '%d link marked nofollow.', '%d links marked nofollow.', count( $nofollow ), 'nofollow-links' ) ), count( $nofollow ) ) . '</p></div>' . "\n";
	}

	$s_nofollow_links = get_option( 'nofollow_links' );
	if ( ! $s_nofollow_links ) {
		$s_nofollow_links = wp_json_encode( array() );
	}
	if ( is_string( $s_nofollow_links ) ) {
		$u_nofollow_links = json_decode( $s_nofollow_links, true );
		if ( null === $u_nofollow_links ) {
			$u_nofollow_links = unserialize( $s_nofollow_links );
		}
	} elseif ( is_array( $s_nofollow_links ) ) {
		$u_nofollow_links = $s_nofollow_links;
	}

	$links = get_bookmarks();
	?>
	<script type="text/javascript">
	<!--
	function checkAll(form, checkboxId)
	{
		var checkAllChecked = document.getElementById(checkboxId).checked;
		for (i = 0, n = form.elements.length; i < n; i++) {
			if(form.elements[i].type == "checkbox") {
				form.elements[i].checked = checkAllChecked;
			}
		}
	}
	//-->
	</script>

	<div class="wrap nosubsub">

	<div id="icon-link-manager" class="icon32"><br></div>
	<h2><?php esc_html_e( 'Nofollow Links', 'nofollow-links' ); ?></h2>
	<form id="links" name="pages-form" action="<?php echo isset( $_SERVER['PHP_SELF'] ) ? esc_url( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) : ''; // Input var okay. ?>?page=link-nofollow" method="post">

	<p class="search-box">&nbsp;</p>
	<?php
	if ( function_exists( 'wp_nonce_field' ) ) {
		wp_nonce_field( 'nofollow_links_manage' );
	}
	?>

	<div class="tablenav top">
		<div class="alignleft actions">
		<input type="submit" class="button action" name="nofollowbookmarks" id="nofollowbookmarks" value="<?php esc_html_e( 'Mark Links Nofollow &raquo;', 'nofollow-links' ); ?>" />
		</div>
		<br class="clear">
	</div>

	<table class="wp-list-table widefat">
	<thead>
	<tr>
		<td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input type="checkbox" onclick="checkAll(document.getElementById('links', 'cb-select-all-1'));" id="cb-select-all-1">
		</td>
		<th scope="col" class="manage-column" width="45%"><?php esc_html_e( 'Name', 'nofollow-links' ); ?></th>
		<th scope="col" class="manage-column"><?php esc_html_e( 'URL', 'nofollow-links' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$alt = true;
	foreach ( $links as $link ) {
		$short_url = str_replace( 'http://', '', $link->link_url );
		$short_url = str_replace( 'www.', '', $short_url );
		if ( '/' === substr( $short_url, -1 ) ) {
			$short_url = substr( $short_url, 0, -1 );
		}
		if ( strlen( $short_url ) > 35 ) {
			$short_url = substr( $short_url, 0, 32 ) . '...';
		}

		echo '    <tr valign="middle"' . ( $alt ? ' class="alternate"' : '' ) . ">\n";
		echo '        <th class="check-column"><input type="checkbox" name="linkcheck[]" value="' . esc_attr( $link->link_id ) . '"' . ( isset( $u_nofollow_links[ $link->link_id ] ) ? ' checked="checked"' : '' ) . " /></th>\n";
		echo '        <td><strong>' . esc_html( $link->link_name ) . '</strong><br />' . esc_html( $link->link_description ) . "</td>\n";
		/* translators: %s is the name of the link */
		echo '        <td><a href="' . esc_url( $link->link_url ) . ' title="' . sprintf( esc_attr__( 'Visit %s', 'nofollow-links' ), esc_url( $link->link_name ) ) . '">' . esc_url( $short_url ) . "</a></td>\n";
		echo "    </tr>\n";

		$alt = ! $alt;
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
			<input type="checkbox" onclick="checkAll(document.getElementById('links', 'cb-select-all-2'));" id="cb-select-all-2" />
		</td>
		<th scope="col" class="manage-column" width="45%"><?php esc_html_e( 'Name', 'nofollow-links' ); ?></th>
		<th scope="col" class="manage-column"><?php esc_html_e( 'URL', 'nofollow-links' ); ?></th>
	</tr>
	</tfoot>
	</table>

	</form>

	</div>
	<?php
}

/**
 * Filter to add nofollow to bookmarks
 *
 * @param array $bookmarks List of bookmarks.
 * @param array $args Arguments.
 */
function nofollow_links_get_bookmarks( $bookmarks, $args ) {
	$s_nofollow_links = get_option( 'nofollow_links' );

	if ( ! $s_nofollow_links ) {
		$s_nofollow_links = wp_json_encode( array() );
	}

	if ( is_string( $s_nofollow_links ) ) {
		$u_nofollow_links = json_decode( $s_nofollow_links, true );
		if ( null === $u_nofollow_links ) {
			$u_nofollow_links = unserialize( $s_nofollow_links );
		}
	} elseif ( is_array( $s_nofollow_links ) ) {
		$u_nofollow_links = $s_nofollow_links;
	}

	if ( is_array( $bookmarks ) ) {
		foreach ( array_keys( $bookmarks ) as $i ) {
			if ( isset( $u_nofollow_links[ $bookmarks[ $i ]->link_id ] ) ) {
				$bookmarks[ $i ]->link_rel .= ' nofollow';
				$bookmarks[ $i ]->link_rel  = trim( $bookmarks[ $i ]->link_rel );
			}
		}
	}

	return $bookmarks;
}
