<?php
/**
 * Seeds a two-site network for local testing
 *
 * Both sites get template parts with the same slugs and different text, so
 * the demo page shows which site each Network Template Part block rendered
 * from. Safe to run more than once.
 *
 * Run with: npm run env:seed
 *
 * @package network-template-parts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_CLI' ) || ! is_multisite() ) {
	return;
}

/**
 * Returns the ID of the second site, creating it when missing.
 *
 * @return int The site ID, or 0 on failure.
 */
function ntp_dev_second_site_id(): int {
	$network = get_network();

	if ( ! $network ) {
		return 0;
	}

	$existing = get_sites(
		[
			'domain' => $network->domain,
			'path'   => '/site-two/',
			'fields' => 'ids',
			'number' => 1,
		]
	);

	if ( [] !== $existing ) {
		return (int) $existing[0];
	}

	$site_id = wp_insert_site(
		[
			'domain'  => $network->domain,
			'path'    => '/site-two/',
			'title'   => 'Site Two',
			'user_id' => 1,
		]
	);

	return is_wp_error( $site_id ) ? 0 : $site_id;
}

/**
 * Creates or updates a template part for the current site's theme.
 *
 * @param string $slug    The template part slug.
 * @param string $title   The template part title.
 * @param string $content The template part block markup.
 */
function ntp_dev_upsert_part( string $slug, string $title, string $content ): void {
	$theme    = get_stylesheet();
	$existing = get_block_template( $theme . '//' . $slug, 'wp_template_part' );
	$args     = [
		'post_type'    => 'wp_template_part',
		'post_status'  => 'publish',
		'post_name'    => $slug,
		'post_title'   => $title,
		'post_content' => $content,
	];

	if ( $existing && $existing->wp_id ) {
		$args['ID'] = $existing->wp_id;
	}

	// wp_insert_post() unslashes, which would strip the quotes in block attribute JSON.
	$post_id = wp_insert_post( wp_slash( $args ), true );

	if ( is_wp_error( $post_id ) ) {
		WP_CLI::warning( "Could not save template part {$slug}: " . $post_id->get_error_message() );
		return;
	}

	wp_set_post_terms( $post_id, $theme, 'wp_theme' );
	wp_set_post_terms( $post_id, 'uncategorized', 'wp_template_part_area' );
}

/**
 * Creates or updates the demo page and makes it the front page.
 *
 * @param string $content The page block markup.
 */
function ntp_dev_upsert_front_page( string $content ): void {
	$existing = get_page_by_path( 'network-template-parts-demo' );
	$args     = [
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_name'    => 'network-template-parts-demo',
		'post_title'   => 'Network Template Parts demo',
		'post_content' => $content,
	];

	if ( $existing ) {
		$args['ID'] = $existing->ID;
	}

	$page_id = wp_insert_post( wp_slash( $args ), true );

	if ( is_wp_error( $page_id ) ) {
		WP_CLI::warning( 'Could not save the demo page: ' . $page_id->get_error_message() );
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_id );
}

/**
 * Names the current site and turns on the theme and pretty permalinks.
 *
 * @param string $title The site title.
 */
function ntp_dev_prepare_site( string $title ): void {
	update_option( 'blogname', $title );

	if ( 'twentytwentyfive' !== get_stylesheet() ) {
		switch_theme( 'twentytwentyfive' );
	}

	update_option( 'permalink_structure', '/%postname%/' );

	// Rules regenerate on the next request to each site.
	delete_option( 'rewrite_rules' );
}

/**
 * Returns the markup for a Network Template Part block.
 *
 * @param string $slug    The template part slug.
 * @param string $context Either "site" or "network".
 * @return string The block markup.
 */
function ntp_dev_block( string $slug, string $context ): string {
	$attributes = wp_json_encode(
		[
			'slug'    => $slug,
			'context' => $context,
		]
	);

	return '<!-- wp:ntp/network-template-part ' . $attributes . ' /-->';
}

/**
 * Returns a group with a heading, a paragraph, and the site title.
 *
 * @param string $text The paragraph text.
 * @return string The block markup.
 */
function ntp_dev_part_content( string $text ): string {
	return '<!-- wp:group {"style":{"border":{"width":"2px","style":"dashed"},"spacing":{"padding":{"top":"1rem","bottom":"1rem","left":"1rem","right":"1rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-style:dashed;border-width:2px;padding-top:1rem;padding-right:1rem;padding-bottom:1rem;padding-left:1rem"><!-- wp:paragraph -->
<p>' . esc_html( $text ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group -->';
}

$ntp_dev_page = '<!-- wp:paragraph -->
<p>Each dashed box is a template part. The site title inside it shows which site it rendered from.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Banner, network context</h2>
<!-- /wp:heading -->

' . ntp_dev_block( 'ntp-demo-banner', 'network' ) . '

<!-- wp:heading -->
<h2 class="wp-block-heading">Banner, site context</h2>
<!-- /wp:heading -->

' . ntp_dev_block( 'ntp-demo-banner', 'site' ) . '

<!-- wp:heading -->
<h2 class="wp-block-heading">Network header with a site-context part inside</h2>
<!-- /wp:heading -->

' . ntp_dev_block( 'ntp-demo-header', 'network' );

$ntp_dev_header = '<!-- wp:group {"style":{"border":{"width":"2px","style":"solid"},"spacing":{"padding":{"top":"1rem","bottom":"1rem","left":"1rem","right":"1rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-style:solid;border-width:2px;padding-top:1rem;padding-right:1rem;padding-bottom:1rem;padding-left:1rem"><!-- wp:paragraph -->
<p>Network header, stored on the main site only.</p>
<!-- /wp:paragraph -->

<!-- wp:site-title {"level":0} /-->

' . ntp_dev_block( 'ntp-demo-nav', 'site' ) . '</div>
<!-- /wp:group -->';

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! is_plugin_active_for_network( 'network-template-parts/plugin.php' ) ) {
	activate_plugin( 'network-template-parts/plugin.php', '', true );
}

ntp_dev_prepare_site( 'Main Site' );
ntp_dev_upsert_part( 'ntp-demo-banner', 'NTP demo: banner', ntp_dev_part_content( 'Banner stored on the main site.' ) );
ntp_dev_upsert_part( 'ntp-demo-nav', 'NTP demo: site navigation', ntp_dev_part_content( 'Site navigation part stored on the main site.' ) );
ntp_dev_upsert_part( 'ntp-demo-header', 'NTP demo: network header', $ntp_dev_header );
ntp_dev_upsert_front_page( $ntp_dev_page );

$ntp_dev_site_id = ntp_dev_second_site_id();

if ( 0 === $ntp_dev_site_id ) {
	WP_CLI::error( 'Could not create the second site.' );
}

switch_to_blog( $ntp_dev_site_id );
ntp_dev_prepare_site( 'Site Two' );
ntp_dev_upsert_part( 'ntp-demo-banner', 'NTP demo: banner', ntp_dev_part_content( 'Banner stored on Site Two.' ) );
ntp_dev_upsert_part( 'ntp-demo-nav', 'NTP demo: site navigation', ntp_dev_part_content( 'Site navigation part stored on Site Two.' ) );
ntp_dev_upsert_front_page( $ntp_dev_page );
restore_current_blog();

WP_CLI::success( 'Seeded the main site and ' . get_home_url( $ntp_dev_site_id, '/' ) );
