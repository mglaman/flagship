### Flagship theme is a vanilla, mobile first, parent theme framework to extend WordPress site building.

[Flagship Theme](http://glamanate.com/flagship-theme/) is released under a GPLv2 License.

####Built in CSS Minification & Caching
Flagship aggregates and condenses the core stylesheets used by the framework. Developers can utilize framework hooks to add child theme stylesheets into the cached and minified stylesheet.

 * Provides hook for developers.
 * Replaces 'url(../)' with child theme's relative directory, or can be specified (for plugins).
 * Ability to easily re-build frameworks stylesheet cache.

####Content Zones
 * Utilizes three content areas - header, content, footer.
 * Zones can be added or removed, via template code.
 * Zones are assigned to one of these content areas
 * Displayed by assigned weight order.
 * Zones utilize column sizes, with ability to pad left or right.
 * Zones can be added or removed through (coming) interface.

####Configuration Files
Flagship utilizes configuration files in JSON format. This allows theme developers to easily create zone templates, export them, and have them instantly available when creating new child themes.

####Updates
 * **Version 0.2:** Begin implementation for child theme integration and expand on responsive design.
 * **Version 0.1:** The beginning! Created zone templating system,CSS minification system, dashboard. interfaces.