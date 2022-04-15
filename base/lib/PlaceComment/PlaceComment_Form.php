<?php
/**
 * @class PlaceCommentForm
 *
 * This class manages the forms for the PlaceComment objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion
 * @version 3.0.1
 */
class PlaceComment_Form extends Form
{

    /**
     * Render the public form.
     */
    public function createPublic()
    {
        $rating = (isset($this->values['rating'])) ? intval($this->values['rating']) : 0;
        $fields = '
            <div class="form_pages">
                <div class="rating_all_wrapper">
                    ' . $this->field('rating') . '
                    ' . PlaceComment_Ui::renderRating($rating) . '
                </div>
                ' . $this->field('email') . '
                ' . $this->field('comment') . '
            </div>';
        return '
            <div class="form_pages_wrapper form_pages_wrapper_simple">
                ' . Form::createForm($fields, ['class' => 'form_simple formPlaceComment', 'recaptchav3' => true, 'submit' => 'Comentar']) . '
            </div>';
    }

}
