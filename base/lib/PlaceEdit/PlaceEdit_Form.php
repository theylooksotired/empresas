<?php
class PlaceEdit_Form extends Form
{

    public function createPublic()
    {
        $this->values['category_1'] = (isset($this->values['category_1'])) ? $this->values['category_1'] : '';
        $this->values['category_2'] = (isset($this->values['category_2'])) ? $this->values['category_2'] : '';
        $this->values['category_3'] = (isset($this->values['category_3'])) ? $this->values['category_3'] : '';
        $fields = '
        	<div class="form_pages" data-url="' . url('category-autocomplete') . '">
				<div class="form_pages_section">
					<p>Una vez que ingrese todos los datos los revisaremos manualmente y los publicaremos en un <strong>plazo máximo de 48 horas</strong>.<br/>
					Le informaremos sobre todo el proceso via email.</p>
				</div>
				<div class="form_pages_section">
					<div class="form_pages_section_title">Información personal</div>
					<div class="form_fields_2">
						' . $this->field('id') . '
						' . $this->field('name_editor', ['class' => 'form_icon form_icon-email']) . '
						' . $this->field('email_editor', ['class' => 'form_icon form_icon-email']) . '
					</div>
				</div>
				<div class="form_pages_section">
					<div class="form_pages_section_title">Información sobre la empresa</div>
					' . $this->field('title') . '
					' . $this->fieldImage() . '
                </div>
				<div class="form_pages_section">
					<div class="form_pages_section_title">Categorías de la empresa</div>
					<div class="autocomplete_item text_long form_field form_field_category_1 required ">
	                    <div class="form_field_ins">
	                        <label>Categoría principal</label>
	                        <div class="label_small">Busca una categoría que identifique mejor tu empresa o actividad</div>
	                        <input type="text_long" name="id_category_1" size="100" value="' . $this->values['category_1'] . '" placeholder="" required="required" autocomplete="">
	                    </div>
	                </div>
	                <div class="autocomplete_item text_long form_field form_field_category_2">
	                    <div class="form_field_ins">
	                        <label>Categoría adicional</label>
	                        <input type="text_long" name="id_category_2" size="100" value="' . $this->values['category_2'] . '" placeholder="" autocomplete="">
	                    </div>
	                </div>
	                <div class="autocomplete_item text_long form_field form_field_category_3">
	                    <div class="form_field_ins">
	                        <label>Categoría adicional</label>
	                        <input type="text_long" name="id_category_3" size="100" value="' . $this->values['category_3'] . '" placeholder="" autocomplete="">
	                    </div>
	                </div>
				</div>
				<div class="form_pages_section">
					<div class="form_pages_section_title">Información de contacto de la empresa</div>
					<div class="form_fields_2">
						' . $this->field('email', ['class' => 'form_icon form_icon-email']) . '
						' . $this->field('telephone', ['class' => 'form_icon form_icon-telephone']) . '
					</div>
					<div class="form_fields_2">
						' . $this->field('mobile', ['class' => 'form_icon form_icon-mobile']) . '
						' . $this->field('whatsapp', ['class' => 'form_icon form_icon-whatsapp']) . '
					</div>
					<div class="form_fields_2">
						' . $this->field('web', ['class' => 'form_icon form_icon-globe']) . '
					</div>
					<div class="form_fields_2">
						' . $this->field('facebook', ['class' => 'form_icon form_icon-facebook']) . '
						' . $this->field('twitter', ['class' => 'form_icon form_icon-twitter']) . '
					</div>
					<div class="form_fields_2">
						' . $this->field('instagram', ['class' => 'form_icon form_icon-instagram']) . '
						' . $this->field('youtube', ['class' => 'form_icon form_icon-youtube']) . '
					</div>
					' . $this->field('address', ['required' => true]) . '
					<div class="trigger_city_wrapper">
						<div class="trigger_city_select">
							' . $this->field('city') . '
							<div class="trigger_city">Haga click aquí si la ciudad no aparece en la lista</div>
						</div>
						<div class="trigger_city_info">
							' . FormField::show('text', ['name' => 'city_other', 'label' => 'Ciudad']) . '
						</div>
					</div>
				</div>
				<div class="form_pages_section">
					<div class="form_pages_section_title">Descripción de la empresa</div>
					' . $this->field('short_description', ['required' => true]) . '
					' . $this->field('description') . '
				</div>
				<div class="form_choices">
					<div class="form_choices_item form_promotion_item form_payment_item_promoted" data-value="1">
						<div class="form_choices_item_ins">
							<i class="icon ' . (($this->object->get('promoted')) ? 'icon-checked' : '') . '"></i>
							<div class="form_choices_info">
								<strong>Deseo promocionar a mi empresa por un pago único de 10$USD (dólares americanos)</strong>
								<span>Aparecerá en los primeros lugares de las búsquedas y tendrá una página libre de publicidad. El pago, que se realiza utilizando <a href="https://www.paypal.com" target="_blank">PayPal</a> es único y no tiene limite de duración.</span>
							</div>
						</div>
					</div>
					<div class="form_choices_item form_promotion_item form_promotion_item-not-promoted" data-value="0">
						<div class="form_choices_item_ins">
							<i class="icon ' . ((!$this->object->get('promoted')) ? 'icon-checked' : '') . '"></i>
							<div class="form_choices_info">
								<strong>Deseo inscribir a mi empresa en el directorio de forma gratuita</strong>
							</div>
						</div>
					</div>
					' . FormField::show('hidden', ['name' => 'promoted', 'value' => $this->object->get('promoted')]) . '
				</div>
				<div class="form_page_disclaimer">
					<p>Antes de proceder le recomendamos leer nuestros <a href="' . url('terminos-condiciones') . '" target="_blank">Términos y Condiciones de Uso</a>.</p>
					<p>Para cualquier información adicional puede contactarse con <a href="http://www.plasticwebs.com/contacto" target="_blank">Plasticwebs</a>.</p>
				</div>
			</div>';
        return '
        	<div class="form_pages_wrapper form_pages_wrapper_simple">
				' . Form::createForm($fields, ['submit' => 'Enviar', 'class' => 'form_simple form_place_edit', 'recaptchav3' => true]) . '
			</div>';
    }

    public function fieldImage()
    {
        $search = [
            'maximum_size',
            'select_drag_image_here',
            'image_reduced_dimensions',
            'file_loading',
            'file_saving',
            'file_saved_as',
            'file_to_upload',
            'select_drag_file_here',
            'are_you_sure_delete',
        ];
        $replace = [
            'Tamaño máximo',
            'Seleccione o arrastre una imagen aquí',
            'La imagen se reducirá a un máximo de ' . ASTERION_WIDTH_HUGE . ' x ' . ASTERION_HEIGHT_MAX_HUGE . ' píxeles',
            'Cargando el archivo...',
            'Guardando el archivo...',
            'Archivo guardado como:',
            'Archivo para cargar',
            'Seleccione o arrastre un archivo aquí',
            '¿Estás segura o seguro de que quieres borrar?',
        ];
        return str_replace($search, $replace, $this->field('image'));
    }

}
