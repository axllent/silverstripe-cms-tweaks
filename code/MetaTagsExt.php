<?php
/**
 * MetaTags via ContentController & Template
 * =========================================
 *
 * Extension to move MetaTage from SiteTree into ContentController
 * and use a template rather than hardcoding for more flexibility.
 *
 * @license: MIT-style license http://opensource.org/licenses/MIT
 * @author: Techno Joy development team (www.technojoy.co.nz)
 */
class MetaTagsExt extends Extension {

    public function MetaTags($includeTitle = true) {
        $customFields = array();

        if ($includeTitle === true || $includeTitle == 'true') {
            $customFields['AddHeaderTitle'] = true;
        }

        $customFields['Charset'] = ContentNegotiator::get_encoding();

        if (Permission::check('CMS_ACCESS_CMSMain')
            && in_array('CMSPreviewable', class_implements($this->owner->data()))
            && !$this->owner->data() instanceof ErrorPage
            && $this->owner->data()->ID > 0
        ) {
            $customFields['CMSAccess'] = 1;
            $customFields['XPageID'] = $this->owner->data()->ID;
            $customFields['XCMSEditLink'] = $this->owner->data()->CMSEditLink();
        }

        $metadata = $this->owner->renderWith('MetaTags', $customFields);

        /* strip blank lines */
        $metadata = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $metadata);

        /* add tabs to neaten code */
        $metadata = preg_replace('/\r?\n</', "\n\t<", $metadata);

        return trim($metadata);
    }

}