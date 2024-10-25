# Network Template Parts
Contributors: happyprime, jeremyfelt, slocker, philcable
Tags: site-editor, templates, multisite
Requires at least: 6.3
Tested up to: 6.6
Stable tag: 1.0.4
License: GPLv2 or later
Requires PHP: 7.4

Render template parts in a site or network context.

## Description

When managing a brand across many sites, it is often necessary to ship changes to the look and feel with the certainty that they will be applied immediately.

In its current iteration, the WordPress site editor allows individual site administrators to make changes to a theme's templates and template parts. Once a template or a template part has been edited on an individual site, it no longer receives updates from the theme automatically, which may in turn cause unintended consequences.

This plugin provides a way for site administrators to edit parts of the site without accidentally overwriting pieces that should remain consistent.

Network Template Parts works in tandem with [Restrict Network Templates](https://github.com/happyprime/restrict-network-templates), which restricts the editing of templates on individual sites.

## Assumptions

### Templates provided by the theme should not be edited.

A theme's core structure is defined by templates. (e.g. `templates/home.html`)

It should be possible to set a structure in these templates and assume that they will not be overridden on individual sites.

### Network-level template parts are managed by network administrators.

Parts of a theme's structure rely on data from the main site on a network. (e.g. global navigation)

It should be possible for a template to specify that a section of a page is rendered in the context of the main site.

### Site-level template parts are managed by site administrators.

Parts of a theme's structure rely on data from individual sites on a network. (e.g. site navigation, content)

It should be possible to override site-level template parts from an individual site.

## Block

The Network Template Part block renders a selected template part in either a "site" or "network" context.

Attributes:

* `slug` determines which template part to load. Template parts are located in a theme's `parts/` directory.
* `context` determines the context in which the part should render.

## Examples

Often, a network will provide common header and footer areas while leaving the site content between to site administrators. With Network Template Parts, this can be accomplished as so:

A `templates/index.html` file may contain:

<code>
<!-- wp:ntp/network-template-part {"slug":"header","context":"network"} /-->
<!-- wp:ntp/network-template-part {"slug":"main-index","context":"site"} /-->
<!-- wp:ntp/network-template-part {"slug":"footer","context":"network"} /-->
</code>>

This loads `parts/header.html` from the main site on the network, `parts/main-index.html` from the current site, and `parts/footer.html` from the main site on the network.

The `parts/header.html` file main contain:

<code>
<!-- wp:ntp/network-template-part {"slug":"header-top","context":"network"} /-->
<!-- wp:ntp/network-template-part {"slug":"header-main","context":"network"} /-->
</code>>

This defines two areas in the header to be managed at the network level. The `parts/header-main.html` may contain something like:

<code>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:group {"tagName":"header"} -->
	<header class="wp-block-group">
		<!-- wp:ntp/network-template-part {"slug":"header-main-network-logo","context":"network"} /-->
		<!-- wp:ntp/network-template-part {"slug":"header-main-site-navigation","context":"site"} /-->
	</header>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
</code>>

This provides some common HTML structure, loads a network-level logo, and also provides a site-level navigation in `parts/header-main-site-navigation.html`.

Now, an individual site administrator can make changes to a navigation menu while also receiving updates from the theme and the network if the look and feel of the broader network changes.

## Changelog

### 1.0.4

* Confirm WordPress 6.6 compatibility.
* Raise minimum supported WordPress to 6.3.
* Update `block.json` API version to 3.
* Improve build tooling, coding standards.
* No functional changes.

### 1.0.3

* Adjust deployment scripting.

### 1.0.2

* Update `@wordpress/scripts` dependency to 26.8.0.
* Include built files so that the block actually works.

### 1.0.1

* Update `@wordpress/scripts` dependency to 26.6.0.
* Add POT file.
* Initial wp.org release.

### 1.0.0

* Consolidate into one block: Network Template Part.
* Add "context" attribute.

### 0.1.1

* Provide a select interface for site and network template parts.
* Improve plugin documentation.
* Update `@wordpress/scripts` dependency to 26.3.0.

### 0.1.0

Initial release
