SilverStripe 3.0 CMS Tweaks
===========================
Module to add a series of tweaks/modifications to the SilverStripe CMS.
The goal is to make the CMS slightly less confusing for non-admin administration.

##Modifications (css/JavaScript) include:
* Move MetaTitle, MetaKeywords, MetaDescription and ExtraMeta to it's own "Advanced" tab
* Always have the Advanced tab on the right (after all other tabs)
* JavaScript word/character count for MetaTitle & MetaDescription
* Split MetaKeywords to new lines (easy overview) - saved correctly in database
* Clean MetaDescription for any new lines and double-spaces upon save
* Dependent pages (if any) on Advanced tab
* Hide add/delete page buttons when user has no SITETREE_REORGANISE permissions
* Hide CMS ErrorPages to non-admin users in CMS as well as file/link selectors
* Remove SiteTree drag handle for when user has no SITETREE_REORGANISE permissions
* Prevent accidental form submission in CMS when enter is used
* Add "Remove formatting" button to TinyMCE to clean code
* Add some invalid elements to TinyMCE

##Requirements
* SilverStripe 3.0