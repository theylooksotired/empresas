<?php
/**
 * @class PlaceReportForm
 *
 * This class manages the forms for the PlaceReport objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceReport_Form extends Form
{

    /**
     * Render the public form.
     */
    public function createPublic()
    {
        $fields = '
			<div class="form_pages">
				' . $this->createFormFields() . '
			</div>';
        return '
			<div class="form_pages_wrapper form_pages_wrapper_simple">
                ' . Form::createForm($fields, ['class' => 'form_simple formPlaceComment', 'recaptchav3' => true, 'submit' => 'Reportar']) . '
			</div>';
    }

}
