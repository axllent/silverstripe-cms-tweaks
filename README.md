# SilverStripe 3 CMS Tweaks
Module to add a series of tweaks/modifications to the SilverStripe CMS.
The goal is to make the CMS less confusing for non-technical users, removing
functionality some they can't use (ie: non SITETREE_REORGANISE users).

## Modifications (PHP,css/JavaScript) includes
- Move MetaDescription and ExtraMeta to it's own "Advanced tab"
- Adds MetaKeywords for those wanting to use them
- Page name, URL Segment, Navigation label not shown to users without `SITETREE_REORGANISE` permissions
  - Meta Title is displayed on the Advanced tab if no `SITETREE_REORGANISE` permissions
  - "Page name" renamed "Meta Title" to avoid confusion

- Always have the Advanced tab on the right (floated after all other tabs)
- JavaScript word/character count for MetaDescription
- Clean MetaDescription for any new lines and double-spaces upon save
- Dependent pages (if any) displayed on Advanced tab
- Hide add/Archive page buttons when user has no `SITETREE_REORGANISE` permissions
- Hide CMS ErrorPages to non-admin users in CMS as well as file/link selectors
- Remove SiteTree drag handle when user has no `SITETREE_REORGANISE` permissions
- Remove "Duplicate" content menu when user has no `SITETREE_REORGANISE` permissions
- Prevent accidental form submission in CMS when the enter key is used
- Add "Remove formatting" button to TinyMCE to clean code
- Add some invalid elements to TinyMCE
- Optional "noenter" class can be added to any TextFields to prevent new lines (eg: MetaDescription)
- Link logo in CMS to website homepage rather than silverstripe.org
- Remove "Help" link
- Add timestamps to TinyMCE's `content_css` css files to ensure changed css doesn't get cached

## Requirements
- SilverStripe ~3.3
