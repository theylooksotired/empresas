<?php
/**
 * @class Category
 *
 * This class defines the users.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Category extends Db_Object
{

    public function getBasicInfo()
    {
        return $this->get('name_plural');
    }

}
