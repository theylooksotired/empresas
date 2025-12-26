<?php
class Place_Ui extends Ui
{

    public function renderInfo($withCity = false)
    {
        return '
			<h2>' . $this->object->getBasicInfo() . '</h2>
			' . (($this->object->get('short_description') != '') ? '<p class="short_description">' . $this->object->get('short_description') . '</p>' : '') . '
			' . (($this->object->get('address') != '') ? '<p class="address"><i class="icon icon-address"></i> ' . $this->object->get('address') . '</p>' : '') . '
			' . (($this->object->get('telephone') != '') ? '<p class="telephone"><i class="icon icon-telephone"></i> ' . $this->renderTelephone($this->object->get('telephone')) . '</p>' : '') . '
			' . (($withCity && $this->object->get('city') != '') ? '<p class="citySimple"><i class="icon icon-city"></i> <span>' . $this->object->get('city') . ', ' . Parameter::code('country') . '</span></p>' : '');
    }

    public function renderCity()
    {
        return ($this->object->get('city') != '') ? '<p class="city"><i class="icon icon-city"></i> <span><a href="' . url('ciudad-categoria/' . $this->object->get('city_url')) . '">' . $this->object->get('city') . '</a>, ' . Parameter::code('country') . '</span></p>' : '';
    }

    public function renderPublic()
    {
        if ($this->object->get('promoted') == '1') {
            return $this->renderPublicPromoted();
        }
        $image = $this->object->getImageWidth('image', 'small');
        return '
        	<div class="item_public">
				<div class="item_public_info">
					<a href="' . $this->object->url() . '">
						' . (($image != '') ? '
							<div class="item_public_wrapper">
								<div class="item_public_wrapper_left">' . $image . '</div>
								<div class="item_public_wrapper_right">' . $this->renderInfo() . '</div>
							</div>
						' : $this->renderInfo()) . '
					</a>
					' . $this->renderCity() . '
				</div>
				<div class="categories_place">' . $this->renderCategoriesPublic() . '</div>
			</div>';
    }

    public function renderSimple()
    {
        return '
        	<div class="item_public">
				<div class="item_public_info">
					<a href="' . $this->object->url() . '">' . $this->renderInfo(true) . '</a>
				</div>
			</div>';
    }

    public function renderPublicPromoted()
    {
        $image = $this->object->getImageWidth('image', 'small');
        return '
        	<div class="item_public item_public_promoted">
				<div class="item_public_info">
					<a href="' . $this->object->url() . '">
						' . (($image != '') ? '
							<div class="item_public_wrapper">
								<div class="item_public_wrapper_left">' . $image . '</div>
								<div class="item_public_wrapper_right">' . $this->renderInfo() . '</div>
							</div>
						' : '<div class="item_public_wrapper_simple">' . $this->renderInfo() . '</div>') . '
						<div class="item_public_promoted_star">
							<i class="icon icon-promotion"></i>
							<span>Promocionada</span>
						</div>
					</a>
				</div>
				<div class="categories_place">' . $this->renderCategoriesPublic() . '</div>
			</div>';
    }

    public function renderComplete()
    {
        if ($this->object->get('promoted') == '1') {
            return $this->renderCompletePromoted();
        }
        $short_description = ($this->object->get('short_description') != '') ? '<p class="short_description">' . $this->object->get('short_description') . '</p>' : '';
        $image = $this->object->getImageWidth('image', 'small');
        $image = ($image != '') ? '<div class="description_topImage">' . $image . '</div>' : '';
        return '
        	<div class="item_complete">
				<h1>' . $this->object->getBasicInfo() . '</h1>
				' . Adsense::responsive('top') . '
				<div class="item_complete_info">
					' . (($image != '' || $short_description != '') ? '
					<div class="description_top">
						' . $image . '
						' . $short_description . '
					</div>
					' : '') . '
					<div class="info_blocks info_blocks_address">
						' . $this->renderInfoBlock('address', 'Dirección') . '
						' . $this->renderInfoBlock('city', 'Ciudad') . '
					</div>
					<div class="info_blocks">
						' . $this->renderInfoBlock('telephone', 'Teléfono') . '
						' . $this->renderInfoBlock('mobile', 'Móvil') . '
						' . $this->renderInfoBlock('whatsapp', 'Whatsapp') . '
					</div>
					<div class="info_blocks info_blocks_web">
						' . $this->renderInfoBlock('email', 'Email') . '
						' . $this->renderInfoBlock('web', 'Sitio web') . '
					</div>
					' . $this->renderInfoBlockIcons() . '
					' . (($this->object->get('description') != '') ? '<div class="description">' . $this->object->get('description') . '</div>' : '') . '
					' . $this->share(['title' => 'Compartir en: ', 'share' => [['key' => 'facebook', 'icon' => '<i class="icon icon-facebook"></i>'], ['key' => 'twitter', 'icon' => '<i class="icon icon-twitter"></i>'], ['key' => 'linkedin', 'icon' => '<i class="icon icon-linkedin"></i>']]]) . '
				</div>
				<div class="categories_place">' . $this->renderCategoriesPublic() . '</div>
				' . $this->renderComments() . '
				<div class="actions_place">
					<div class="action_place action_place_comment">
						<a rel="nofollow" href="' . url('comentarios/comentar/' . $this->object->id()) . '">
							<div class="action_place_image">
								<img width="300" height="300" src="' . ASTERION_BASE_URL . 'visual/img/comment.svg"/>
							</div>
							<div class="action_place_ins">
								<h3>¿Desea escribir un comentario?</h3>
								<p>Ayude a otros usuarios dejando un comentario sobre esta empresa o negocio. Su mensaje será público previa moderación.</p>
								<span class="actions_place_button_wrapper">
									<span class="actions_place_button"><i class="icon icon-edit"></i> Comentar</span>
								</span>
							</div>
						</a>
					</div>
					<div class="action_place action_place_update">
						<a rel="nofollow" href="' . url('modificar/' . $this->object->id()) . '">
							<div class="action_place_image">
								<img width="300" height="300" src="' . ASTERION_BASE_URL . 'visual/img/owner.svg"/>
							</div>
							<div class="action_place_ins">
								<h3>¿Esta empresa es de su propiedad?</h3>
								<p>Actualice la información de la misma de forma gratuita o promocione a su empresa para que salga en los primeros resultados de búsqueda de nuestro sitio.</p>
								<span class="actions_place_button_wrapper">
									<span class="actions_place_button"><i class="icon icon-edit"></i> Editar</span>
								</span>
							</div>
						</a>
					</div>
					<div class="action_place action_place_report">
						<a rel="nofollow" href="' . url('reportes/reportar/' . $this->object->id()) . '">
							<div class="action_place_image">
								<img width="300" height="300" src="' . ASTERION_BASE_URL . 'visual/img/warning.svg"/>
							</div>
							<div class="action_place_ins">
								<h3>¿Esta información es incorrecta o la empresa no existe?</h3>
								<p>Por favor, escríbanos si los datos de esta empresa no corresponden o si esta información le molesta de alguna manera.</p>
								<span class="actions_place_button_wrapper">
									<span class="actions_place_button"><i class="icon icon-warning"></i> Reportar</span>
								</span>
							</div>
						</a>
					</div>
				</div>
			</div>
			' . $this->renderRelated();
    }

    public function renderCompletePromoted()
    {
        $short_description = ($this->object->get('short_description') != '') ? '<p class="short_description">' . $this->object->get('short_description') . '</p>' : '';
        $image = $this->object->getImageWidth('image', 'small');
        $image = ($image != '') ? '<div class="description_topImage">' . $image . '</div>' : '';
        return '
        	<div class="item_complete item_complete_promoted">
				<div class="item_complete_ins">
					<h1 >' . $this->object->getBasicInfo() . '</h1>
					<div class="item_complete_info">
						<div class="description_top">
							' . $image . '
							' . $short_description . '
						</div>
						<div class="info_blocks info_blocks_address">
							' . $this->renderInfoBlock('address', 'Dirección') . '
							' . $this->renderInfoBlock('city', 'Ciudad') . '
						</div>
						<div class="info_blocks">
							' . $this->renderInfoBlock('telephone', 'Teléfono') . '
							' . $this->renderInfoBlock('mobile', 'Móvil') . '
							' . $this->renderInfoBlock('whatsapp', 'Whatsapp') . '
						</div>
						<div class="info_blocks">
							' . $this->renderInfoBlock('email', 'Email') . '
							' . $this->renderInfoBlock('web', 'Sitio web') . '
						</div>
						' . $this->renderInfoBlockIcons() . '
						' . (($this->object->get('description') != '') ? '<div class="description">' . $this->object->get('description') . '</div>' : '') . '
						' . $this->share(['title' => 'Compartir en: ', 'share' => [['key' => 'facebook', 'icon' => '<i class="icon icon-facebook"></i>'], ['key' => 'twitter', 'icon' => '<i class="icon icon-twitter"></i>'], ['key' => 'linkedin', 'icon' => '<i class="icon icon-linkedin"></i>']]]) . '
					</div>
					<div class="categories_place">' . $this->renderCategoriesPublic() . '</div>
				</div>
			</div>';
    }

    public function renderComments()
    {
        $items = new ListObjects('PlaceComment', ['where' => 'id_place=:id_place AND active="1"', 'order' => 'created DESC'], ['id_place' => $this->object->id()]);
        if (!$items->isEmpty()) {
            return '
            	<div class="comments">
					<div class="comments_title">Comentarios sobre ' . $this->object->getBasicInfo() . '</div>
					<div class="comments_ins">' . $items->showList() . '</div>
				</div>';
        }
    }

    public function renderTelephone($telephone, $link = false)
    {
        $telephoneCode = Parameter::code('telephone_code');
        $telephone = str_replace(';', ',', $telephone);
        $telephone = str_replace('-', ',', $telephone);
        $html = '';
        foreach (explode(',', $telephone) as $item) {
            $item = preg_replace('/[^0-9.]+/', '', $item);
            $item = (substr($item, 0, strlen($telephoneCode)) == $telephoneCode) ? substr($item, strlen($telephoneCode)) : $item;
            if ($link) {
                $html .= '<a href="tel:+' . $telephoneCode . $item . '" class="info_blockNoWrap" target="_blank">(+' . $telephoneCode . ') ' . $item . '</a> ';
            } else {
                $html .= '(+' . $telephoneCode . ') ' . $item . ' ';
            }
        }
        return $html;
    }

    public function renderInfoBlock($attribute, $label)
    {
        $value = $this->object->get($attribute);
        if ($attribute == 'telephone' || $attribute == 'mobile') {
            $value = $this->renderTelephone($value, true);
        }
        if ($attribute == 'email') {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $value = '<a href="mailto:' . $value . '" target="_blank">' . $value . '</a>';
            }
        }
        if ($attribute == 'web') {
            $value = '<a href="' . Url::format($value) . '" target="_blank">' . Url::format($value) . '</a>';
        }
        if ($attribute == 'whatsapp') {
            $value = $this->renderTelephone($value, false);
            $value = '<a href="https://api.whatsapp.com/send?phone=' . urlencode($value) . '" target="_blank">' . $value . '</a>';
        }
        return ($this->object->get($attribute) != '' && $value != '') ? '
			<div class="info_block">
				<i class="icon icon-' . $attribute . '"></i>
				<div class="info_block_ins">
					<strong>' . $label . ' :</strong>
					<span>' . $value . '</span>
				</div>
			</div>' : '';
    }

    public function renderInfoBlockIcons()
    {
        if ($this->object->get('facebook') != '' || $this->object->get('instagram') != '' || $this->object->get('youtube') != '' || $this->object->get('twitter') != '') {
            return '
            	<div class="info_blocks_icons">
					' . $this->renderInfoBlockIcon('facebook', 'Facebook') . '
					' . $this->renderInfoBlockIcon('instagram', 'Instragram') . '
					' . $this->renderInfoBlockIcon('youtube', 'Youtube') . '
					' . $this->renderInfoBlockIcon('twitter', 'Twitter') . '
				</div>';
        }
    }

    public function renderInfoBlockIcon($attribute, $label)
    {
        $value = $this->object->get($attribute);
        $url = Url::format($value);
        return ($this->object->get($attribute) != '') ? '
			<div class="info_block_icon">
				<a href="' . $url . '" class="info_block_icon_ins" target="_blank" rel=”nofollow”>
					<i class="icon icon-' . $attribute . '"></i>
					<span>' . $label . '</span>
				</a>
			</div>' : '';
    }

    public function renderCategoriesPublic()
    {
        $this->object->loadMultipleValuesSingleAttribute('categories');
        $html = '';
        foreach ($this->object->get('categories') as $category) {
            $html .= $category->showUi('Link');
        }
        return $html;
    }

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
			<div style="width: 120px; height: 10px; background: #efefef; margin: 30px 0;"> </div>';
    }

    public function renderRelated()
    {
        $where = '1=1';
        if ($this->object->get('related') != '') {
            $whereRelated = [];
            foreach (explode(',', $this->object->get('related')) as $item) {
                $whereRelated[] = 'id="' . $item . '"';
            }
			$where = (count($whereRelated) > 0) ? join(' OR ', $whereRelated) : $where;
        }
        $items = new ListObjects('Place', ['where' => $where, 'limit' => '5']);
        return '
        	<div class="related">
				<h3 class="title">Empresas similares</h3>
				' . $items->showList() . '
			</div>';
    }

    public static function introPlaces()
    {
        $items = new ListObjects('Place', ['where' => 'promoted = 1', 'order' => 'RAND()', 'limit' => 5]);
        if ($items->isEmpty()) {
            $items = new ListObjects('Place', ['order' => 'RAND()', 'limit' => 5]);
        }
		$itemsTop = new ListObjects('Place', ['order' => 'views DESC', 'limit' => 5]);
        return '
        	<div class="intro_places">
				<h3>Algunas de las empresas en nuestro directorio</h3>
				' . $items->showList() . '
				<h3>Las empresas más vistas</h3>
				' . $itemsTop->showList() . '
			</div>';
    }

    public function renderJsonHeader()
    {
        $info = [
            "@context" => "http://schema.org/",
            "@type" => "LocalBusiness",
			"@id" => url(''),
            "name" => $this->object->getBasicInfo(),
            "image" => $this->object->getImageUrl('image', 'web', ASTERION_BASE_URL . 'visual/img/directorio.jpg'),
            "telephone" => $this->renderTelephone($this->object->get('telephone')),
			"url" => url(''),
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $this->object->get('address'),
                "addressLocality" => $this->object->get('city'),
                "addressRegion" => Parameter::code('country'),
            ],
        ];
        return '<script type="application/ld+json">' . json_encode($info) . '</script>';
    }

    /**
     * @cache
     */
    public static function renderCities()
    {
        $query = '
        	SELECT p.city, p.city_url, COUNT(p.id) as countitems
			FROM ' . (new Place)->tableName . ' p
			GROUP BY p.city_url
			HAVING countitems>20
			ORDER BY countitems DESC
			LIMIT 24';
        $items = Db::returnAll($query);
        $html = '';
        foreach ($items as $item) {
            $html .= ($item['city'] != '') ? '<a href="' . url('ciudad-categoria/' . $item['city_url']) . '">' . $item['city'] . '</a> ' : '';
        }
        return $html;
    }

    /**
     * @cache
     */
    public static function renderCitiesComplete()
    {
        $query = '
        	SELECT p.city, p.city_url, COUNT(p.id) as countitems
			FROM ' . (new Place)->tableName . ' p
			GROUP BY p.city_url
			HAVING countitems>2
			ORDER BY p.city';
        $items = Db::returnAll($query);
        $html = '';
        foreach ($items as $item) {
            $html .= ($item['city'] != '') ? '<a href="' . url('ciudad-categoria/' . $item['city_url']) . '">' . $item['city'] . '</a> ' : '';
        }
        return $html;
    }

    /**
     * @cache
     */
    public static function renderCategories()
    {
        $query = 'SELECT c.id, c.name_plural, c.name_plural_url, COUNT(c.id) as countitems
					FROM ' . (new Category)->tableName . ' c, ' . (new PlaceCategory)->tableName . ' pc
					WHERE c.id=pc.id_category
					GROUP BY c.name_plural_url
					ORDER BY countitems DESC
					LIMIT 10';
        $items = Db::returnAll($query);
        $html = '';
        foreach ($items as $item) {
            $html .= '<a href="' . url('categoria/' . $item['id'] . '-' . $item['name_plural_url']) . '">' . $item['name_plural'] . '</a> ';
        }
        return $html;
    }

    public function renderElement($attribute, $label)
    {
        if ($this->object->get($attribute) != '') {
            return '
            	<p style="margin: 0 0 5px; padding: 0;">
					<span style="color:#999999;  font-size: 0.8rem;">' . $label . ' : </span><br/>
					<span>' . $this->object->get($attribute) . '</span>
				</p>';
        }
    }

}
