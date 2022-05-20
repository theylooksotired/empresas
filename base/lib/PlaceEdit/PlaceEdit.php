<?php
class PlaceEdit extends Db_Object
{

    public function urlUploadTempImagePublic()
    {
        return url('lugar-imagen-temporal');
    }

    public function urlDeleteImagePublic($valueFile = '')
    {
        return url('lugar-imagen-borrar/' . $this->id());
    }

    public function loadCategories()
    {
        if (!isset($this->categories)) {
            $query = '
                SELECT DISTINCT c.*
                FROM ' . (new Category)->tableName . ' c
                JOIN ' . (new PlaceEditCategory)->tableName . ' pc ON c.id=pc.id_category AND pc.id_place_edit="' . $this->id() . '"';
            $this->categories = new ListObjects('Category', ['query' => $query]);
        }
    }

    public function encodeId()
    {
        return PlaceEdit::encodeIdSimple($this->id());
    }

    public static function encodeIdSimple($id)
    {
        return md5('plasticwebs_placeedit_' . $id);
    }

    public function saveCategories($values)
    {
        $this->saveCategory($values['id_category_1']);
        $this->saveCategory($values['id_category_2']);
        $this->saveCategory($values['id_category_3']);
    }

    public function saveCategory($nameCategory)
    {
        $category = (new Category)->readFirst(['where' => 'name=:name OR name_plural=:name'], ['name' => $nameCategory]);
        if ($category->id() != '') {
            $placeEditCategoryExists = (new PlaceEditCategory)->readFirst(['where' => 'id_place_edit=:id_place_edit AND id_category=:id_category'], ['id_place_edit' => $this->id(), 'id_category' => $category->id()]);
            if ($placeEditCategoryExists->id() == '') {
                $placeEditCategory = new PlaceEditCategory(['id_place_edit' => $this->id(), 'id_category' => $category->id()]);
                $placeEditCategory->persist();
            }
        }
    }

    public function saveOrder($redirect = 'modificar-gracias')
    {
        if ($this->get('promoted')) {
            $order = new Order([
                'id_place_edit' => $this->id(),
                'name' => $this->get('name_editor'),
                'email' => $this->get('email_editor'),
                'payment_type' => Order::PAYMENT_TYPE_PAYPAL,
                'price' => '10',
            ]);
            $order->persist();
            $this->sendEmail($this->get('email_editor'), 'welcome_place_paypal');
            $order->paypalRequest();
            exit();
        } else {
            $this->sendEmail($this->get('email_editor'), 'welcome_place_free');
            header('Location: ' . url($redirect));
            exit();
        }
    }

    public function sendEmail($emailTo, $typeEmail)
    {
        $subjects = [
            'modify_place_edit' => '',
            'published_place' => '',
            'welcome_place_free' => 'Su empresa ha sido registrada gratuitamente',
            'welcome_place_paypal' => 'Su empresa ha sido registrada usando PayPal',
            'welcome_place_transfer' => 'Su empresa ha sido registrada y esperamos su transferencia',
        ];
        $subject = (isset($subjects[$typeEmail])) ? $subjects[$typeEmail] : 'Notificación';
        HtmlMail::sendFromFile($emailTo, $subject, $typeEmail, [
            'NAME' => $this->get('name_editor'),
            'PLACE' => $this->showUi('Email'),
        ], 'basic');
    }

}
