# Network Template Parts

Network Template Parts provides a way to share responsibility for the look and feel of websites on a multisite network.

General workflow assumptions:

* Templates are provided by the theme and should not be edited.
* Network-level templates and parts are managed by network administrators.
* Site-level templates and parts are managed by site or network administrators.
* Once a template part has been edited in WordPress, it stops receiving updates from the theme.

Plugin definitions:

* Template: files stored in `templates/`.
* Network Templates: files stored as `parts/network-templates-*.html`.
* Site Templates: files stored as `parts/site-templates-*.html`.
* Network Template Parts: files stored as `parts/network-parts-*.html`.
* Site Template Parts: files stored as `parts/site-parts-*.html`.

This plugin works in tandem with Restrict Network Templates to restrict the editing of templates on individual sites.

## Why is this necessary?

The WordPress site editor makes it possible for individual site administrators to make changes to a theme's templates and parts. This is a useful way for people to manage their website, though it may cause unintended consequences when the theme is also used as a way to deliver a consistent brand across a network of sites.

This plugin attempts to provide site administrators editor access to pieces of a site without accidentally overwriting pieces that should stay consistent.

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
