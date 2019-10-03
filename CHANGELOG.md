# Changelog

Notable changes to this project will be documented in this file.

## [2.1.1]

- Contain `UploadField` previews
- Use same field label casing as SS4.4


## [2.1.0]

- Revert merging of `HtmlEditorConfig:extended_valid_elements`
- Set `CMSTweaks:extended_valid_elements` & `CMSTweaks:invalid_elements` via yaml config


## [2.0.14]

- Allow merging of `HtmlEditorConfig` [extended_valid_elements](https://github.com/axllent/silverstripe-cms-tweaks/pull/7)
- Code cleanup / PSR2


## [2.0.13]

- Make hide_help compatible with SilverStripe 4.3


## [2.0.12]

- Remove duplicate 'removeformat' button


## [2.0.11]

- Fix typo in array count


## [2.0.10]

- Allow `$parent->canAddChildren()` to determine editing capabilities


## [2.0.9]

- Fix gridfield relation editor link button


## [2.0.8]

- Remove TinyMCE branding patch
- Update composer.json


## [2.0.7]

- Remove Firefox focus dotted outlines


## [2.0.6]

- Switch to silverstripe-vendormodule


## [2.0.5]

- Simplify for users that cannot create. MenuTitle & URLSegment are removed, title is optionally moved to advanced tab


## [2.0.4]

- Prevent wrapping when SiteStree pages panel becomes scrollable


## [2.0.3]

- Hide TinyMCE branding


## [2.0.2]

- Changes for SilverStripe 4-beta3
- CSS for flex tab right alignment (order: 99)
- Force scroll in `.cms-content-fields.panel--scrollable`


## [2.0.1]

- Switch TinyMCE modifications to onAfterInit()
- Correctly timestamp TinyMCE style files


## [2.0.0]

- SilverStripe 4
- Timestamp editor_css & content_css
- Meta data character / word counters
- Update dependencies
- Split custom formfields into separate requirement axllent/silverstripe-form-fields
