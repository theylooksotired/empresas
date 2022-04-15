<?php
/**
 * @class PlaceReport
 *
 * This class represents a category for contact forms.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceReport extends Db_Object
{

    public function loadPlace()
    {
        $this->place = (new Place)->read($this->get('id_place'));
    }

}
