<?php

namespace Axllent\CMSTweaks;

use SilverStripe\Core\Extension;
use SilverStripe\ErrorPage\ErrorPage;

class InternalLinks extends Extension
{
    /**
     * Exclude error pages from TreeDropdownFields
     *
     * @param FieldList      $fields
     * @param RequestHandler $controller
     * @param string         $name
     * @param array          $context
     *
     * @return void
     */
    public function updateFormFields(&$fields, $controller, $name, $context)
    {
        $pageField = $fields->dataFieldByName('PageID');
        if ($pageField) {
            $pageField->setFilterFunction(fn ($node) => !($node instanceof ErrorPage));
        }
    }
}
