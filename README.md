SilverStripe 3.1 CMS Tweaks
===========================
Module to add a series of tweaks/modifications to the SilverStripe CMS.
The goal is to make the CMS slightly less confusing for non-admin administration.

##Modifications (css/JavaScript) include:
* Move MetaDescription and ExtraMeta to it's own "Advanced" tab
* Always have the Advanced tab on the right (floated after all other tabs)
* JavaScript word/character count for MetaDescription
* Clean MetaDescription for any new lines and double-spaces upon save
* Dependent pages (if any) on Advanced tab
* Hide add/delete page buttons when user has no SITETREE_REORGANISE permissions
* Hide CMS ErrorPages to non-admin users in CMS as well as file/link selectors
* Remove SiteTree drag handle for when user has no SITETREE_REORGANISE permissions
* Prevent accidental form submission in CMS when enter is used
* Add "Remove formatting" button to TinyMCE to clean code
* Add some invalid elements to TinyMCE
* Optinal "noenter" class can be added to TextFields to prevent new lines (eg: MetaDescription)
* Link logo in CMS to webiste homepage rather than silverstripe.org
* Remove "Help" link

##Requirements
* SilverStripe 3.1