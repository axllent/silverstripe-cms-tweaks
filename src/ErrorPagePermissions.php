<?php

namespace Axllent\CMSTweaks;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Extension;
use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\ORM\DataQuery;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\Security\Permission;

class ErrorPagePermissions extends Extension
{
    /**
     * Hide error pages for non admin users in SiteTree
     */
    public function augmentSQL(SQLSelect $query, ?DataQuery $dataQuery = null): void
    {
        if (Permission::check('ADMIN') || !Controller::curr() || !Controller::curr() instanceof LeftAndMain) {
            return;
        }

        $query->addWhere(['"SiteTree"."ClassName" != ?' => ErrorPage::class]);
    }
}
