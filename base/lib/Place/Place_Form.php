<?php
class Place_Form extends Form{

	public function createFormFieldsPublic($options = []) {
		return '
			<div class="formWrapper">
				'.$this->field('id').'
				'.$this->field('title').'
				'.$this->field('address').'
				<div class="formLine formLine2">
					'.$this->field('telephone').'
					'.$this->field('web').'
					'.$this->field('email').'
					'.$this->field('city').'
				</div>
				'.$this->field('short_description').'
				'.$this->field('description').'
				<div class="formLine formLine2">
					'.$this->field('name_editor').'
					'.$this->field('email_editor').'
				</div>
				<div class="formLine formLine2">
					'.$this->field('image').'
					'.$this->field('color_background').'
				</div>
				'.$this->field('promoted').'
			</div>';
	}

	public function createPublic() {
		return Form::createForm($this->createFormFieldsPublic(), ['class'=>'form_public']);
	}

}
?>