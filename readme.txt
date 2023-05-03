# Network Template Parts
Contributors: happyprime, jeremyfelt, slocker, philcable
Tags: comments
Requires at least: 6.2
Tested up to: 6.2
Stable tag: 1.0.0
License: GPLv2 or later
Requires PHP: 7.4

Share responsibility for the look and feel of websites on a multisite network.

## Description

When managing a brand across many sites, it is often necessary to ship changes to the look and feel with the certainty that they will be applied immediately.

In its current iteration, the full site editor allows individual site administrators to make changes to a theme's templates and template parts. Once a template or a template part has been edited on an individual site, it no longer receives updates from the theme automatically, which may in turn cause unintended consequences.

This plugin provides a way for site administrators to edit parts of the site in the site without accidentally overwriting pieces that should remain consistent.

Network Template Parts works in tandem with [Restrict Network Templates](https://github.com/happyprime/restrict-network-templates), which restricts the editing of templates and network-level parts on individual sites.

## Assumptions

### Templates provided by the theme should not be edited.

The core HTML structure of the theme is provided by templates. (e.g. `home.html`)

It should be possible to set a structure in these templates and assume that they will not be overridden on individual sites.

### Network-level templates and parts are managed by network administrators.

Parts of a theme's structure rely on data from the main site on a network. (e.g. global navigation)

It should not be possible to override network-level templates and parts from an individual site.

### Site-level templates and parts are managed by site or network administrators.

Parts of a theme's structure rely on data from individual sites on a network. (e.g. site navigation, content)

It should be possible to override site-level template parts from an individual site.

## Definitions

### Template

A template is a file stored in a theme's `templates/` directory.

### Network Templates

A network template is a file prefixed as `network-templates-` and stored in the theme's `parts/` directory. (e.g. `parts/network-templates-home.html`)

The Network Template block renders network templates in the context of the network's main site. These are intended to be used in place of the templates normally found in a theme's `templates/` directory, but with network-level data.

The template selection interface provided by this block will remove `network-templates-` from the name and allow you to select from a list like: home, index, 404, etc…

### Site Templates

A site template is a file prefixed as `site-templates-` and stored in the theme's `parts/` directory. (e.g. `parts/site-templates-home.html`)

The Site Template block renders a template part in the context of the current site. These are intended to be used in place of the templates normally found in a theme's `templates/` directory.

The template selection interface provided by this block will remove `site-templates-` from the name and allow you to select from a list like: home, index, 404, etc…

### Network Template Parts

A network template part is a file prefixed as `network-parts-` and stored in the theme's `parts/` directory. (e.g. `parts/network-parts-header.html`)

The Network Template Parts block renders a template part in the context of the network's main site. These are the same as any template part found in a theme's `parts/` directory, but with the intent to render network-level data.

The template selection interface provided by this block will remove `network-parts-` from the name and allow you to select from a list like: header, header-main, etc…

### Site Template Parts

A site template part is a file prefixed as `site-parts-` and stored in the theme's `parts/` directory. (e.g. `parts/site-parts-header-navigation.html`)

The Site Template Part block renders a template part in the context of the current site. These are the same as any template part found in a theme's `parts/` directory, but with the intent to always render site-level data.

The template selection interface provided by this block will remove `site-parts-` from the name and allow you to select from a list like: header, header-main, etc…

## Examples

Often, a network will provide common header and footer areas while leaving the site content between to site administrators. With Network Template Parts, this can be accomplished as so:

A `templates/index.html` file may contain:

<code>
<!-- wp:ntp/network-template-part {"slug":"header"} /-->
<!-- wp:ntp/network-template {"slug":"index"} /-->
<!-- wp:ntp/network-template-part {"slug":"footer"} /-->
</code>

This loads `parts/network-parts-header.html`, `parts/network-templates-index.html`, and `parts/network-parts-footer.html`.

Skipping ahead to the main content, the `parts/network-templates-index.html` file may contain:

<code>
<!-- wp:group {"tagName":"main"} -->
<main class="wp-block-group">
	<!-- wp:ntp/site-template {"slug":"index"} /-->
</main>
<!-- /wp:group -->
</code>

This provides a common wrapping container around a site-level template part, `parts/site-templates-index.html`. The site-level part can be left as is or overwritten by an individual site administrator through the full site editor to provide a custom layout while keeping the network's header and footer intact.

The `parts/network-parts-header.html` file main contain:

<code>
<!-- wp:ntp/network-template-part {"slug":"header-top"} /-->
<!-- wp:ntp/network-template-part {"slug":"header-main"} /-->
</code>

This defines two areas in the header to be managed at the network level. The `parts/network-parts-header-main.html` may contain something like:

<code>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:group {"tagName":"header"} -->
	<header class="wp-block-group">
		<!-- wp:ntp/network-template-part {"slug":"header-main-network-logo"} /-->
		<!-- wp:ntp/site-template-part {"slug":"header-main-navigation"} /-->
	</header>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
</code>

This provides some common HTML structure, loads a network-level logo, and also provides a site-level navigation in `parts/site-parts-header-main-navigation.html`.

Now, an individual site administrator can make changes to a navigation menu while also receiving updates from the theme and the network if the look and feel of the broader network changes.

## Changelog

### 0.1.1

* Provide a select interface for site and network template parts.
* Improve plugin documentation.
* Update `@wordpress/scripts` dependency to 26.3.0.

### 0.1.0

Initial release
