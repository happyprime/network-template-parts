# Network Template Parts

Network Template Parts provides a way to share responsibility for the look and feel of websites on a multisite network.

## Overview

The full site editor in WordPress provides a powerful way to manage the look and feel of a website. This can become complicated when that look and feel is shared across many websites on a multisite network.

When managing a brand across many sites, it is often necessary to ship changes to the look and feel with the certainty that they will be applied immediately.

In its current iteration, the full site editor allows all individual site administrators to make changes to a theme's templates and parts. Once a template or a template part has been edited on an individual site, it stops receiving updates from the theme automatically, which may in turn cause unintended consequences.

This plugin attempts to provide site administrators access to parts of a site in the full site editor without accidentally overwriting pieces that should remain consistent.

This plugin works in tandem with [Restrict Network Templates](https://github.com/happyprime/restrict-network-templates), which restricts the editing of templates and network-level parts on individual sites.

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

Network templates can be added to content with the Network Template block provided by this plugin.

### Site Templates

A site template is a file prefixed as `site-templates-` and stored in the theme's `parts/` directory. (e.g. `parts/site-templates-home.html`)

Site templates can be added to content with the Site Template block provided by this plugin.

### Network Template Parts

A network template part is a file prefixed as `network-parts-` and stored in the theme's `parts/` directory. (e.g. `parts/network-parts-header.html`)

Network template parts can be added to content with the Network Template Part block provided by this plugin.

### Site Template Parts

A site template part is a file prefixed as `site-parts-` and stored in the theme's `parts/` directory. (e.g. `parts/site-parts-header-navigation.html`)

Site template parts can be added to content with the Site Template Part block provided by this plugin.

### An example theme template structure

Often, a network will provide common header and footer areas while leaving the site content between to site administrators. With Network Template Parts, this can be accomplished as so:

A `templates/index.html` file may contain:

```html
<!-- wp:ntp/network-template-part {"slug":"header"} /-->
<!-- wp:ntp/network-template {"slug":"index"} /-->
<!-- wp:ntp/network-template-part {"slug":"footer"} /-->
```

This loads `parts/network-parts-header.html`, `parts/network-templates-index.html`, and `parts/network-parts-footer.html`.

Skipping ahead to the main content, the `parts/network-templates-index.html` file may contain:

```html
<!-- wp:group {"tagName":"main"} -->
<main class="wp-block-group">
	<!-- wp:ntp/site-template {"slug":"index"} /-->
</main>
<!-- /wp:group -->
```

This provides a common wrapper container around a site-level template part, `parts/site-templates-index.html`. This file can be left as is or taken over by an individual site administrator to provide a custom layout while keeping the network's header and footer intact.

The `parts/network-parts-header.html` file main contain:

```html
<!-- wp:ntp/network-template-part {"slug":"header-top"} /-->
<!-- wp:ntp/network-template-part {"slug":"header-main"} /-->
```

This defines two areas in the header to be managed at the network level. The `parts/network-parts-header-main.html` may contain something like:

```html
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
```

This provides some common HTML structure, loads a network-level logo, and also provides a site-level navigation in `parts/site-parts-header-main-navigation.html`.

Now, an individual site administrator can make changes to a navigation menu while also receiving updates from the theme and the network if the look and feel of the broader network changes.

## Blocks

### Network Template

The Network Template block renders a template part in the context of the network's main site. These are intended to be used in place of templates normally found in a theme's `templates/` directory as a way to render network level data. They must be stored in the `parts/` directory and prefixed as `network-templates-`.

#### Example

A theme's `parts/network-templates-404.html` will appear as "404" in the Network Template block's select menu.

### Network Template Part

The Network Template block renders a template part in the context of the network's main site. These are the same as any template part found in a theme's `parts/` directory, but with the intent to render network-level data. They must be stored in the `parts/` directory and prefixed as `network-parts-`.

#### Example

A theme's `parts/network-parts-header.html` will appear as "header" in the Network Template Part block's select menu.

### Site Template

The Site Template block renders a template part in the context of the current site. These are intended to be used in place of the templates normally found in a theme's `templates/` directory. They must be stored in the `parts/` directory and prefixed as `site-templates-`.

#### Example

A theme's `parts/site-templates-archive.html` will appear as "archive" in the Site Template block's select menu.

### Site Template Part

The Site Template Part block renders a template part in the context of the current site. These are the same as any template part found in a theme's `parts/` directory, but with the intent to always render site-level data. They must be stored in the `parts/` directory and prefixed as `site-parts-`.

### Example

A theme's `parts/site-parts-footer.html` will appear as "footer" in the Site Template Part block's select menu.

## Changelog

### 1.0.0

* Provide a select interface for site and network template parts.
* Improve plugin documentation.
* Update `@wordpress/scripts` dependency to 26.3.0.

### 0.1.0

Initial release
