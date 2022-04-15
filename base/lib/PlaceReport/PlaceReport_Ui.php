<?php
/**
 * @class PlaceReportUi
 *
 * This class manages the UI for the PlaceReport objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceReport_Ui extends Ui
{

    public function renderMail()
    {
        $this->object->loadPlace();
        return '
			<strong>Lugar :</strong> ' . $this->object->place->link() . '<br/>
			<strong>Mensaje :</strong> ' . nl2br($this->object->get('message'));
    }

}
