<?php
class PlaceEdit_Ui extends Ui
{

    public function renderEmail()
    {
    	$this->object->loadCategories();
        $image = ($this->object->getImageUrl('image', 'small') != '') ? '<img src="' . $this->object->getImageUrl('image', 'small') . '" style="width: 120px; margin:0 0 20px; display:block; padding: 5px; background: #fff; border: 1px solid #eee;"/>' : '';
        return '
        	<div style="width: 120px; height: 10px; background: #efefef; margin: 30px 0;"> </div>
			' . $image . '
			<h2 style="font-weight:bold;padding: 0 0 10px;margin: 0; color:#FFA12C; font-size: 1.6rem;"> ' . $this->object->getBasicInfo() . '</h2>
			<p style="color: #666666; margin: 0 0 20px; padding: 0;">' . nl2br($this->object->get('short_description')) . '</p>
			' . $this->renderElement('address', 'Dirección') . '
			' . $this->renderElement('city', 'Ciudad') . '
			' . $this->renderElement('telephone', 'Teléfono') . '
			' . $this->renderElement('mobile', 'Móvil') . '
			' . $this->renderElement('whatsapp', 'Whatsapp') . '
			' . $this->renderElement('email', 'Email') . '
			' . $this->renderElement('web', 'Sitio web') . '
			' . $this->renderElement('facebook', 'Facebook') . '
			' . $this->renderElement('twitter', 'Twitter') . '
			' . $this->renderElement('instagram', 'Instagram') . '
			' . $this->renderElement('youtube', 'YouTube') . '
			' . $this->renderElement('description', 'Descripción') . '
			' . ((!$this->object->categories->isEmpty()) ? '
			<p style="margin: 0 0 5px; padding: 0;">
				<span style="color:#999999;  font-size: 0.8rem;">Categorías : </span><br/>
				<span>' . substr($this->object->categories->showList(['function' => 'Simple']), 0, -2) . '</span>
			</p>
			' : '') . '
			<p style="margin: 0 0 5px; padding: 0;">
				<span style="color:#999999;  font-size: 0.8rem;">Editor : </span><br/>
				' . $this->object->get('name_editor') . ' ' . $this->object->get('email_editor') . '
			</p>
			<div style="width: 120px; height: 10px; background: #efefef; margin: 30px 0;"> </div>';
    }

    public function renderElement($attribute, $label)
    {
        if ($this->object->get($attribute) != '') {
            return '<p style="margin: 0 0 5px; padding: 0;">
						<span style="color:#999999;  font-size: 0.8rem;">' . $label . ' : </span><br/>
						<span>' . $this->object->get($attribute) . '</span>
					</p>';
        }
    }

}
