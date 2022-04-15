<?php
/**
 * @class PlaceComment
 *
 * This class represents a category for contact forms.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceComment extends Db_Object
{

    public function loadPlace()
    {
        $this->place = (new Place)->read($this->get('id_place'));
    }

    public function encodeId()
    {
        return (new PlaceComment)->encodeIdSimple($this->id());
    }

    public static function encodeIdSimple($id)
    {
        return md5('plasticwebs_comment_' . $id);
    }

    public static function readCoded($code)
    {
        return (new PlaceComment)->readFirst(['where' => 'MD5(CONCAT("plasticwebs_comment_",id_place_comment))=:code'], ['code'=>$code]);
    }

}
