<?php
class Place extends Db_Object
{

    public function encodeId()
    {
        return Place::encodeIdSimple($this->id());
    }

    public static function encodeIdSimple($id)
    {
        return md5('plasticwebs_place_' . $id);
    }

    public static function readCoded($code)
    {
        return (new Place)->readFirst(['where' => 'MD5(CONCAT("plasticwebs_place_",idPlace))=:code'], ['code' => $code]);
    }

    public function loadCategories()
    {
        if (!isset($this->categories)) {
            $query = '
                SELECT DISTINCT c.*
                FROM ' . (new Category)->tableName . ' c
                JOIN ' . (new PlaceCategory)->tableName . ' pc ON c.id=pc.id_category AND pc.id_place="' . $this->id() . '"';
            $this->categories = new ListObjects('Category', ['query' => $query]);
        }
    }

    public function getMetaDescription()
    {
        return 'Dirección, teléfonos, email e información de ' . $this->getBasicInfo() . ' en ' . $this->get('city') . ' ' . Parameter::code('country');
    }

    public function getMetaKeywords()
    {
        return 'directorio, directorio de empresas, guía, guía empresarial, dirección, teléfonos, email, empresa, ' . $this->getBasicInfo() . ', ' . $this->get('city') . ', ' . Parameter::code('country');
    }

    public function persist($persistMultiple = true)
    {
        $persist = parent::persist($persistMultiple);
        if ($persist['status'] == StatusCode::OK) {
            $this->updateInformation();
        }
        return $persist;
    }

    public function updateInformation()
    {
        $search = $this->get('title') . ' ' . $this->get('address') . ' ' . $this->get('telephone') . ' ' . $this->get('web') . ' ' . $this->get('email') . ' ' . $this->get('city') . ' ' . $this->get('short_description') . ' ' . strip_tags($this->get('description'));
        $query = '
            SELECT DISTINCT c.id, c.name
            FROM ' . (new Category)->tableName . ' c
            JOIN ' . (new PlaceCategory)->tableName . ' pc ON c.id=pc.id_category AND pc.id_place="' . $this->id() . '"';
        $categories = Db::returnAll($query);
        foreach ($categories as $item) {
            $search .= ' ' . $item['name'];
        }
        $this->persistSimple('search', $search);
        if (count($categories) > 0) {
            $query = '
                SELECT p.id
                FROM ' . (new Place)->tableName . ' p
                LEFT JOIN ' . (new PlaceCategory)->tableName . ' pc ON p.id=pc.id_place
                WHERE pc.id_category="' . $categories[0]['id'] . '" AND p.id_place!="' . $this->id() . '"
                LIMIT 5';
            $places = [];
            foreach (Db::returnAll($query) as $place) {
                $places[] = $place['id'];
            }
            $this->persistSimple('related', implode(',', $places));
        }
    }

    public function sendEmail($emailTo, $typeEmail)
    {
        $subjects = [
            'published_place' => 'Hemos publicado su empresa',
            'payed_thanks' => 'Hemos recibido su pago',
        ];
        $subject = (isset($subjects[$typeEmail])) ? $subjects[$typeEmail] : 'Notificación';
        HtmlMail::sendFromFile($emailTo, $subject, $typeEmail, [
            'NAME' => $this->get('name_editor'),
            'PLACE_LINK' => $this->url(),
            'LINK_MODIFY' => url('modificar/' . $this->id()),
            'LINK_PROMOTE' => url('lugar-promocionar/' . $this->encodeId()),
            'LINK_DEPROMOTE' => url('lugar-depromocionar/' . $this->encodeId()),
            'PLACE' => $this->showUi('Email'),
        ], 'basic');
    }

}
