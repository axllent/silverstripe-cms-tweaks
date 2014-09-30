<?php

/*
 * NoticeField allows one to set persistent additional notice messages
 * above the main tabs on any modeladmin, directly from within the getCMSFields();
 * On load, javascript will move the element to the top directly below
 * the existing #Form_EditForm_error field
 *
 * <b>Usage</b>
 *
 * <code>
 * new NoticeField (
 *    $name = "noticefield",
 *    $content = '<b>some bold text</b> and <a href="http://silverstripe.com">a link</a>',
 *    $type = [notice|warning|error|good]
 * )
 * </code>
 */

class NoticeField extends LiteralField {

    /**
     * @var string $content
     */
    protected $content;

    public function __construct($name, $content, $class = 'notice') {

        $content = '<p class="noticefield message ' . $class . '">' . $content . '</p>';

        parent::__construct($name, $content);
    }

}
