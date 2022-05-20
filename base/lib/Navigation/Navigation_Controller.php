<?php
/**
 * @class NavigationController
 *
 * This is the controller for all the public actions of the website.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Navigation_Controller extends Controller
{

    /**
     * Main function to control the public actions.
     */
    public function getContent()
    {
        $this->login = User_Login::getInstance();
        $this->ui = new Navigation_Ui($this);
        $this->meta_image = ASTERION_BASE_URL . 'visual/img/cover-' . Parameter::code('site_code') . '.jpg';
        switch ($this->action) {
            default:
                if ($this->action != '') {
                    $this->layout_page = 'place';
                    $info = explode('-', $this->action);
                    $item = (new Place)->read($info[0]);
                    if ($item->id() != '') {
                        $this->mode = 'amp';
                        $this->head = $item->showUi('JsonHeader');
                        $this->title_page = $item->getBasicInfo();
                        $this->meta_url = $item->url('');
                        $this->meta_image = $item->getImageUrl('image', 'web');
                        $this->meta_description = $item->getMetaDescription();
                        $this->meta_keywords = $item->getMetaKeywords();
                        $this->content = $item->showUi('Complete');
                        $this->layout_page = ($item->get('promoted') == '1') ? 'promoted' : $this->layout_page;
                        $this->bread_crumbs = [
                            url('ciudad') => 'Ciudades',
                            url('ciudad/' . $item->get('city_url')) => $item->get('city'),
                            $item->url() => $item->getBasicInfo(),
                        ];
                        return $this->ui->render();
                    }
                }
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . url(''));
                exit();
                break;
            case 'intro':
                $this->mode = 'amp';
                $this->layout_page = 'intro';
                $this->content = '
                    <div class="search_main_wrapper" style="background-image: url(' . $this->meta_image . ');">
                        <div class="search_main">
                            <div class="search_main_ins">
                                ' . Navigation_Ui::searchAmp() . '
                            </div>
                        </div>
                    </div>
                    <div class="content_wrapper content_wrapper_intro">
                        ' . Adsense::amp() . '
                        <div class="intro_page">
                            <div class="intro_page_left">
                                <div class="intro_page_left_top">
                                    <h1>' . Parameter::code('meta_title_page') . '</h1>
                                    ' . str_replace('#LINK_SUSCRIBE', url('inscribir'), str_replace('#COUNTRY', Parameter::code('country'), HtmlSection::showFile('intro'))) . '
                            </div>
                                <div class="intro_page_left_top">
                                    ' . Place_Ui::introPlaces() . '
                                </div>
                            </div>
                            <div class="intro_page_right">
                                ' . Adsense::ampInline() . '
                                ' . $this->ui->contentSide() . '
                            </div>
                        </div>
                    </div>';
                return $this->ui->render();
                break;
            case 'ciudad':
                $this->mode = 'amp';
                if ($this->extraId != '') {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . url($this->action . '/' . $this->id));
                    exit();
                }
                $items = new ListObjects('Place', ['where' => 'city_url=:city_url AND city_url!=""', 'order' => 'promoted DESC, title_url', 'results' => '10'], ['city_url' => $this->id]);
                if ($items->isEmpty()) {
                    $this->layout_page = 'clean';
                    $place = new Place();
                    $this->title_page = 'Ciudades de ' . Parameter::code('country');
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action);
                    $this->bread_crumbs = [url('ciudad') => 'Ciudades'];
                    $this->content = '
                        <h1>' . $this->title_page . '</h1>
                        <div class="city_list">' . $place->showUi('CitiesComplete') . '</div>';
                } else {
                    $item = $items->list[0];
                    $page = (isset($_GET['pagina']) && $_GET['pagina'] != '') ? ' - Página ' . (intval($_GET['pagina'])) : '';
                    $this->title_page = 'Teléfonos y direcciones de empresas en ' . $item->get('city') . ', ' . Parameter::code('country');
                    $this->title_page_html = '<span>Teléfonos y direcciones de empresas en</span> ' . $item->get('city') . ' <em>' . Parameter::code('country') . '</em>';
                    $this->head = $items->metaNavigation();
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action . '/' . $this->id);
                    $this->bread_crumbs = [
                        url('ciudad') => 'Ciudades',
                        url('ciudad/' . $item->get('city_url')) => $item->get('city'),
                    ];
                    $this->content = $items->showListPager(['function' => 'Public', 'middle' => Adsense::ampInline(), 'showResults' => false]);
                }
                return $this->ui->render();
                break;
            case 'ciudad-tag':
                $this->mode = 'amp';
                $item = (new Place)->readFirst(['where' => 'city_url=:city_url AND city_url!=""'], ['city_url' => $this->id]);
                if ($item->id() != '') {
                    $this->layout_page = 'clean';
                    $this->title_page = 'Empresas y negocios en ' . $item->get('city') . ', ' . Parameter::code('country');
                    $this->title_page_html = '<span>Empresas y negocios en</span> ' . $item->get('city') . ', ' . Parameter::code('country');
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action);
                    $this->content = Tag_Ui::intro(['place' => $item, 'title_page' => $this->title_page_html]);
                    return $this->ui->render();
                } else {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . url('ciudad/' . $this->id));
                    exit();
                }
                break;
            case 'tag':
                $this->mode = 'amp';
                $page = (isset($_GET['pagina']) && $_GET['pagina'] != '') ? ' - Página ' . (intval($_GET['pagina'])) : '';
                $info = explode('-', $this->id);
                $item = (new Tag)->read($info[0]);
                if ($item->id() != '') {
                    if ($this->extraId != '') {
                        $city = (new Place)->readFirst(['where' => 'city_url=:city_url'], ['city_url' => $this->extraId]);
                        $this->title_page = $item->getBasicInfo() . ' en ' . $city->get('city') . ', ' . Parameter::code('country') . $page;
                        $this->title_page_html = '<span>' . $city->get('city') . ', ' . Parameter::code('country') . '</span> ' . $item->getBasicInfo();
                        $query = 'SELECT p.* FROM ' . (new Place)->tableName . ' p
                                JOIN ' . (new PlaceTag)->tableName . ' pt ON p.id=pt.id_place
                                WHERE pt.id_tag="' . $item->id() . '"
                                AND p.city_url="' . $this->extraId . '"
                                ORDER BY p.promoted DESC, p.title_url';
                        $queryCount = 'SELECT COUNT(p.id) as count_results FROM ' . (new Place)->tableName . ' p
                                        JOIN ' . (new PlaceTag)->tableName . ' pt ON p.id=pt.id_place
                                        WHERE pt.id_tag="' . $item->id() . '"
                                        AND p.city_url="' . $this->extraId . '"';
                        $this->bread_crumbs = [$item->url() => $item->getBasicInfo(), $item->url() . '/' . $city->get('city_url') => $city->get('city')];
                    } else {
                        $this->title_page = 'Teléfonos y direcciones en ' . Parameter::code('country') . ' sobre ' . strtolower($item->getBasicInfo()) . $page;
                        $this->title_page_html = '<span>Teléfonos y direcciones en ' . Parameter::code('country') . ' sobre</span> ' . $item->getBasicInfo();
                        $query = 'SELECT p.* FROM ' . (new Place)->tableName . ' p
                                JOIN ' . (new PlaceTag)->tableName . ' pt ON p.id=pt.id_place
                                WHERE pt.id_tag="' . $item->id() . '"
                                ORDER BY p.promoted DESC, p.title_url';
                        $queryCount = 'SELECT COUNT(p.id) as count_results FROM ' . (new Place)->tableName . ' p
                                        JOIN ' . (new PlaceTag)->tableName . ' pt ON p.id=pt.id_place
                                        WHERE pt.id_tag="' . $item->id() . '"';
                        $this->bread_crumbs = [$item->url() => $item->getBasicInfo()];
                    }
                    $items = new ListObjects('Place', ['query' => $query, 'queryCount' => $queryCount, 'results' => '10']);
                    if ($items->isEmpty()) {
                        header("HTTP/1.1 301 Moved Permanently");
                        header('Location: ' . url(''));
                        exit();
                    }
                    $this->head = $items->metaNavigation();
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action . '/' . $this->id);
                    $this->content = '
                        ' . (($this->extraId != '') ? '' : '' . $item->showUi('Cities')) . '
                        ' . $items->showListPager(['function' => 'Public', 'middle' => Adsense::ampInline(), 'showResults' => false]);
                    return $this->ui->render();
                } else {
                    $this->layout_page = 'clean';
                    $this->title_page = 'Empresas y negocios en ' . Parameter::code('country');
                    $this->title_page_html = '<span>Empresas y negocios en</span> ' . Parameter::code('country');
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action);
                    $this->content = Tag_Ui::intro(['title_page' => $this->title_page_html]);
                    return $this->ui->render();
                }
                break;
            case 'ciudad-categoria':
                $this->mode = 'amp';
                $item = (new Place)->readFirst(['where' => 'city_url=:city_url AND city_url!=""'], ['city_url' => $this->id]);
                if ($item->id() != '') {
                    $this->layout_page = 'clean';
                    $this->title_page = 'Empresas y negocios en ' . $item->get('city') . ', ' . Parameter::code('country');
                    $this->title_page_html = '<span>Empresas y negocios en</span> ' . $item->get('city') . ', ' . Parameter::code('country');
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action);
                    $this->content = Category_Ui::intro(['place' => $item, 'title_page' => $this->title_page_html]);
                    return $this->ui->render();
                } else {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . url('ciudad/' . $this->id));
                    exit();
                }
                break;
            case 'categoria':
                $this->mode = 'amp';
                $page = (isset($_GET['pagina']) && $_GET['pagina'] != '') ? ' - Página ' . (intval($_GET['pagina'])) : '';
                $info = explode('-', $this->id);
                $item = (new Category)->read($info[0]);
                if ($item->id() != '') {
                    if ($this->extraId != '') {
                        $city = (new Place)->readFirst(['where' => 'city_url=:city_url'], ['city_url' => $this->extraId]);
                        $this->title_page = $item->getBasicInfo() . ' en ' . $city->get('city') . ', ' . Parameter::code('country') . $page;
                        $this->title_page_html = '<span>' . $city->get('city') . ', ' . Parameter::code('country') . '</span> ' . $item->getBasicInfo();
                        $query = 'SELECT p.* FROM ' . (new Place)->tableName . ' p
                                JOIN ' . (new PlaceCategory)->tableName . ' pt ON p.id=pt.id_place
                                WHERE pt.id_category="' . $item->id() . '"
                                AND p.city_url="' . $this->extraId . '"
                                ORDER BY p.promoted DESC, p.title_url';
                        $queryCount = 'SELECT COUNT(p.id) as count_results FROM ' . (new Place)->tableName . ' p
                                        JOIN ' . (new PlaceCategory)->tableName . ' pt ON p.id=pt.id_place
                                        WHERE pt.id_category="' . $item->id() . '"
                                        AND p.city_url="' . $this->extraId . '"';
                        $this->bread_crumbs = [$item->url() => $item->getBasicInfo(), $item->url() . '/' . $city->get('city_url') => $city->get('city')];
                    } else {
                        $this->title_page = 'Teléfonos y direcciones en ' . Parameter::code('country') . ' sobre ' . strtolower($item->getBasicInfo()) . $page;
                        $this->title_page_html = '<span>Teléfonos y direcciones en ' . Parameter::code('country') . ' sobre</span> ' . $item->getBasicInfo();
                        $query = 'SELECT p.* FROM ' . (new Place)->tableName . ' p
                                JOIN ' . (new PlaceCategory)->tableName . ' pt ON p.id=pt.id_place
                                WHERE pt.id_category="' . $item->id() . '"
                                ORDER BY p.promoted DESC, p.title_url';
                        $queryCount = 'SELECT COUNT(p.id) as count_results FROM ' . (new Place)->tableName . ' p
                                        JOIN ' . (new PlaceCategory)->tableName . ' pt ON p.id=pt.id_place
                                        WHERE pt.id_category="' . $item->id() . '"';
                        $this->bread_crumbs = [$item->url() => $item->getBasicInfo()];
                    }
                    $items = new ListObjects('Place', ['query' => $query, 'queryCount' => $queryCount, 'results' => '10']);
                    if ($items->isEmpty()) {
                        header("HTTP/1.1 301 Moved Permanently");
                        header('Location: ' . url(''));
                        exit();
                    }
                    $this->head = $items->metaNavigation();
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action . '/' . $this->id);
                    $this->content = '
                        ' . (($this->extraId != '') ? '' : '' . $item->showUi('Cities')) . '
                        ' . $items->showListPager(['function' => 'Public', 'middle' => Adsense::ampInline(), 'showResults' => false]);
                    return $this->ui->render();
                } else {
                    $this->layout_page = 'clean';
                    $this->title_page = 'Empresas y negocios en ' . Parameter::code('country');
                    $this->title_page_html = '<span>Empresas y negocios en</span> ' . Parameter::code('country');
                    $this->meta_description = $this->title_page;
                    $this->meta_url = url($this->action);
                    $this->content = Category_Ui::intro(['title_page' => $this->title_page_html]);
                    return $this->ui->render();
                }
                break;
            case 'buscar':
                if (isset($this->values['search']) && $this->values['search'] != '') {
                    $search = Text::simpleUrl($this->values['search']);
                    header('Location: ' . url('buscar/' . $search));
                    exit();
                }
                if (isset($_GET['search']) && $_GET['search'] != '') {
                    $search = Text::simpleUrl($_GET['search']);
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . url('buscar/' . $search));
                    exit();
                }
                if ($this->id != '') {
                    $this->mode = 'amp';
                    $search = str_replace('-', ' ', Text::simpleUrl($this->id));
                    $this->title_page = 'Resultados de la busqueda - ' . ucwords($search);
                    $this->title_page_html = '<span>Resultados de la busqueda</span> ' . ucwords($search);
                    $items = new ListObjects('Place', ['where' => 'MATCH (search) AGAINST (:search) OR search LIKE :searchLike', 'order' => 'promoted DESC, MATCH (search) AGAINST (:search) DESC', 'results' => '10'], ['search' => $search, 'searchLike' => '%' . $search . '%']);
                    if ($items->isEmpty()) {
                        $items = new ListObjects('Place', ['where' => 'search LIKE :search', 'order' => 'promoted DESC, title_url', 'results' => '10'], ['search' => '%' . $search . '%']);
                    }
                    $this->head = $items->metaNavigation();
                    $this->content = $items->showListPager(['function' => 'Public', 'message' => '<div class="message">Lo sentimos, pero no encontramos resultados para su busqueda.</div>', 'middle' => Adsense::ampInline(), 'showResults' => false]);
                    return $this->ui->render();
                } else {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . url(''));
                }
                break;
            case 'articulos':
                $this->mode = 'amp';
                $post = (new Post)->readFirst(['where' => 'title_url=:title_url'], ['title_url' => $this->id]);
                if ($post->id() != '') {
                    $this->title_page = $post->getBasicInfo();
                    $this->meta_description = $post->get('short_description');
                    $this->meta_url = $post->url();
                    $this->meta_image = $post->getImageUrl('image', 'huge');
                    $this->head = '
                        ' . $post->showUi('JsonHeader') . '
                        ' . $this->ampFacebookCommentsHeader();
                    $this->content = $post->showUi('Complete');
                } else {
                    $this->title_page = 'Artículos';
                    $items = new ListObjects('Post', ['where' => 'publish_date<=NOW() AND active="1"', 'order' => 'publish_date DESC', 'results' => '10']);
                    $this->content = '<div class="posts">' . $items->showListPager(['showResults' => false]) . '</div>';
                }
                return $this->ui->render();
                break;
            case 'terminos-condiciones':
                $this->layout_page = 'simple';
                $this->title_page = 'Términos y condiciones';
                $this->meta_description = 'Términos y condiciones del sitio ' . Parameter::code('meta_title_page');
                $this->meta_keywords = 'terminos, condiciones, legal, directorio, empresas';
                $this->meta_url = url($this->action);
                $this->content = HtmlSection::showFile('terms');
                return $this->ui->render();
                break;

            /**
                 * PUBLIC ADMIN
                 */
            case 'inscribir':
                $this->layout_page = 'form';
                $this->title_page = 'Inscriba a su empresa en nuestro directorio';
                $this->meta_description = 'Inscriba de forma totalmente gratuita a su empresa en nuestro directorio.';
                $this->activateJS();
                $form = new PlaceEdit_Form();
                if (count($this->values) > 0) {
                    $this->values['city'] = (isset($this->values['city_other']) && $this->values['city_other'] != '') ? $this->values['city_other'] : (isset($this->values['city']) ? $this->values['city'] : '');
                    $this->values['description'] = (isset($this->values['description'])) ? Text::cleanHtml($this->values['description']) : '';
                    $placeEdit = new PlaceEdit($this->values);
                    if (count($placeEdit->validateReCaptchaV3()) > 0) {
                        Session::flashError('Hay un error de verificación de su identidad, por favor vuelva a intentarlo.');
                    } else {
                        $persist = $placeEdit->persist();
                        if ($persist['status'] == StatusCode::OK) {
                            $placeEdit->saveCategories($this->values);
                            $placeEdit->saveOrder('inscribir-gracias');
                        } else {
                            Session::flashError('Hay errores en el formulario, por favor revíselos.');
                            $form = new PlaceEdit_Form($persist['values'], $persist['errors']);
                        }
                    }
                }
                $this->content = $form->createPublic();
                return $this->ui->render();
                break;
            case 'modificar':
                $this->layout_page = 'simple';
                $this->activateJS();
                $this->head .= '<meta name="robots" content="noindex,nofollow"/>';
                $place = (new Place)->read($this->id);
                if ($place->id() != '' && $place->get('promoted') != '1') {
                    $placeEditValues = $place->valuesArray();
                    unset($placeEditValues['name_editor']);
                    unset($placeEditValues['email_editor']);
                    $place->loadMultipleValuesSingleAttribute('categories');
                    $categories = $place->get('categories');
                    $placeEditValues['category_1'] = (isset($categories[0])) ? $categories[0]->getBasicInfo() : '';
                    $placeEditValues['category_2'] = (isset($categories[1])) ? $categories[1]->getBasicInfo() : '';
                    $placeEditValues['category_3'] = (isset($categories[2])) ? $categories[2]->getBasicInfo() : '';
                    $form = new PlaceEdit_Form($placeEditValues);
                    if (count($this->values) > 0) {
                        $placeEdit = new PlaceEdit($this->values);
                        if (count($placeEdit->validateReCaptchaV3()) > 0) {
                            Session::flashError('Hay un error de verificación de su identidad, por favor vuelva a intentarlo.');
                        } else {
                            $persist = $placeEdit->persist();
                            if ($persist['status'] == StatusCode::OK) {
                                $placeEdit->saveCategories($this->values);
                                $placeEdit->saveOrder();
                            } else {
                                Session::flashError('Lo sentimos, pero hay errores en el formulario que debe corregir.');
                                $form = new PlaceEdit_Form($persist['values'], $persist['errors']);
                            }
                        }
                    }
                    $this->title_page = 'Actualizar la información de ' . $place->getBasicInfo();
                    $this->title_page_html = '<span>Actualizar la información de</span> ' . $place->getBasicInfo();
                    $this->content = $form->createPublic(['update' => true]);
                } elseif ($place->id() != '' && $place->get('promoted') == '1') {
                    $this->layout_page = 'message';
                    $this->message_image = 'sadthanks';
                    $this->title_page = 'Su empresa ya está promocionada';
                    $this->message = '<p>Cualquier modificación se realizará por email.</p>';
                } else {
                    $this->layout_page = 'message';
                    $this->message_image = 'sadthanks';
                    $this->title_page = 'La empresa no existe';
                }
                return $this->ui->render();
                break;
            case 'tag-autocomplete':
                $this->mode = 'json';
                $autocomplete = (isset($_GET['term'])) ? $_GET['term'] : '';
                if ($autocomplete != '' && strlen($autocomplete) >= 3) {
                    $query = '
                        SELECT t.id as id, t.name as info, COUNT(t.id) as number_items
                        FROM ' . (new Tag)->tableName . ' t
                        JOIN ' . (new PlaceTag)->tableName . ' pt ON t.id=pt.id_tag
                        WHERE t.name LIKE :autocomplete
                        GROUP BY t.id
                        ORDER BY number_items DESC, t.name_url
                        LIMIT 5';
                    $results = [];
                    foreach (Db::returnAll($query, ['autocomplete' => '%' . $autocomplete . '%']) as $result) {
                        $results[] = [
                            'id' => $result['id'],
                            'value' => $result['info'],
                            'label' => $result['info'] . ' (' . $result['number_items'] . ')',
                        ];
                    }
                    return json_encode($results);
                }
                break;
            case 'category-autocomplete':
                $this->mode = 'json';
                $autocomplete = (isset($_GET['term'])) ? $_GET['term'] : '';
                if ($autocomplete != '' && strlen($autocomplete) >= 2) {
                    $query = '
                        SELECT c.id as id, c.name as info
                        FROM ' . (new Category)->tableName . ' c
                        WHERE c.name LIKE :autocomplete
                        LIMIT 20';
                    $results = [];
                    foreach (Db::returnAll($query, ['autocomplete' => '%' . $autocomplete . '%']) as $result) {
                        $results[] = [
                            'id' => $result['id'],
                            'value' => $result['info'],
                            'label' => $result['info'],
                        ];
                    }
                    return json_encode($results);
                }
                break;
            case 'paypal':
                $this->desactivateRobots();
                $order = (new Order)->readCoded($this->extraId);
                if ($order->id() != '' && $this->id == 'pagado') {
                    $order->modifySimple('payed', '1');
                    HtmlMail::sendFromFile($order->get('email'), 'Hemos recibido su pago', 'payed_thanks', [
                        'NAME' => $order->get('name'),
                    ], 'basic');
                    header('Location: ' . url('pago-espera-gracias/' . $order->encodeId()));
                } elseif ($order->id() != '' && $this->id == 'anulado') {
                    header('Location: ' . url('pago-anulado-gracias/' . $order->encodeId()));
                } else {
                    header('Location: ' . url(''));
                }
                exit();
                break;
            case 'transferencia':
            case 'inscribir-gracias':
            case 'pago-gracias':
            case 'pago-espera-gracias':
            case 'pago-anulado':
            case 'pago-confirmacion':
            case 'pago-espera-anulado':
            case 'modificar-gracias':
            case 'pedido-ya-pagado':
                $this->desactivateRobots();
                $this->layout_page = 'message';
                $order = (new Order)->readCoded($this->id);
                switch ($this->action) {
                    case 'transferencia':
                        $this->title_page = 'Gracias por la inscripción';
                        $this->message_image = 'happythanks';
                        $this->message = '
                            <p>Le hemos enviado a su email los datos para realizar la transferencia o giro bancario.</p>
                            <p>Estaremos a la espera para activar la promoción de su empresa.</p>';
                        $this->content = '<div class="message_simple">' . HtmlSection::showFile('transfer') . '</div>';
                        break;
                    case 'inscribir-gracias':
                        $this->title_page = 'Gracias por la inscripción';
                        $this->message_image = 'happythanks';
                        $this->message = '
                            <p>Vamos a revisar la información y la publicaremos lo antes posible.</p>
                            <p>Le informaremos sobre el proceso via email.</p>';
                        break;
                    case 'modificar-gracias':
                        $this->title_page = 'Gracias por la actualización';
                        $this->message_image = 'happythanks';
                        $this->message = '
                            <p>Vamos a revisar la información y la publicaremos lo antes posible.</p>
                            <p>Le informaremos sobre el proceso via email.</p>';
                        break;
                    case 'pago-gracias':
                        $place = (new Place)->read($order->get('id_place'));
                        $this->message_image = 'happythanks';
                        $this->title_page = 'Gracias por su pago';
                        $this->message = '
                            <p>Puede ver su empresa haciendo click <a href="' . $place->url() . '">aquí</a>.</p>';
                        break;
                    case 'pago-espera-gracias':
                        $placeEdit = (new PlaceEdit)->read($order->get('id_place_edit'));
                        $this->message_image = 'happythanks';
                        $this->title_page = 'Gracias por su pago';
                        $this->message = '
                            <p>Vamos a revisar la información y la publicaremos lo antes posible.</p>
                            <p>Le informaremos sobre el proceso via email.</p>';
                        break;
                    case 'pago-anulado':
                    case 'pago-espera-anulado':
                        $placeEdit = (new PlaceEdit)->read($order->get('id_place_edit'));
                        $this->title_page = 'Su pago no se realizó';
                        $this->message_image = 'sadthanks';
                        $this->message = '
                            <p>Lo sentimos, no pudimos recibir su pago.</p>
                            <p>Si tuvo un problema con el sistema de pagos, puede volver a intentarlo haciendo click en:</p>
                            <div class="retry_payments">
                                <div class="retry_payment retry_payment_paypal">
                                    <a href="' . url('paypal-order/' . $this->id) . '" target="_blank">PayPal</a>
                                </div>
                            </div>';
                        break;
                    case 'pedido-ya-pagado':
                        $this->title_page = 'El pedido ya ha sido pagado';
                        $this->message_image = 'happythanks';
                        $this->message_info = '<p>Muchas gracias por realizar su pago, pero este pedido ya ha sido pagado.</p>';
                        break;
                }
                return $this->ui->render();
                break;
            case 'paypal-order':
                $order = (new Order)->readCoded($this->id);
                if ($order->get('payed') == '1') {
                    header('Location: ' . url('pedido-ya-pagado'));
                } else {
                    $order->paypalRequest();
                }
                break;

            /**
             * PRIVATE ADMIN
             */
            case 'lugar-imagen-temporal':
                $this->mode = 'json';
                $response = File::uploadTempImage($this->values);
                return json_encode($response);
                break;
            case 'info':
                $this->checkAuthorization();
                $this->mode = 'json';
                $response = ['status'=>StatusCode::OK, 'places'=>[], 'reports'=>[]];
                $places = (new PlaceEdit)->readList(['where'=>'published!="1"', 'order'=>'id DESC']);
                foreach ($places as $place) {
                    $response['places'][] = $place->values;
                }
                $reports = (new PlaceReport)->readList(['order'=>'id DESC']);
                foreach ($reports as $report) {
                    $response['reports'][] = $report->values;
                }
                $comments = (new PlaceComment)->readList(['where'=>'active!="1"', 'order'=>'id DESC']);
                foreach ($comments as $comment) {
                    $response['comments'][] = $comment->values;
                }
                return json_encode($response);
                break;
            case 'lugar-editar-borrar':
            case 'lugar-editar-publicar':
            case 'lugar-editar-publicar-promocionar':
                $this->checkAuthorization();
                $this->mode = 'json';
                $placeEdit = (new PlaceEdit)->read($this->id);
                $response = ['status'=>StatusCode::NOK];
                if ($placeEdit->id() != '') {
                    switch ($this->action) {
                        case 'lugar-editar-borrar':
                            $placeEdit->delete();
                            $response = ['status'=>StatusCode::OK];
                            break;
                        case 'lugar-editar-publicar':
                        case 'lugar-editar-publicar-promocionar':
                            $placeEdit->loadCategories();
                            $values = $placeEdit->values;
                            $values['image'] = $placeEdit->getImageUrl('image', 'web');
                            $place = new Place($values);
                            $persist = $place->persist();
                            if ($persist['status']==StatusCode::OK) {
                                foreach ($placeEdit->categories->list as $category) {
                                    $placeCategory = new PlaceCategory([
                                            'id_place' => $place->id(),
                                            'id_category' => $category->id(),
                                        ]);
                                    $placeCategory->persist();
                                }
                                $place->sendEmail($placeEdit->get('email_editor'), 'published_place');
                                if ($this->action == 'lugar-editar-publicar-promocionar') {
                                    $place->persistSimple('promoted', '1');
                                }
                                $placeEdit->persistSimple('published', '1');
                                $response = ['status'=>StatusCode::OK];
                            }
                            break;
                    }
                }
                return json_encode($response);
                break;
            case 'reporte-ignorar':
            case 'reporte-borrar':
                $this->checkAuthorization();
                $this->mode = 'json';
                $placeReport = (new PlaceReport)->read($this->id);
                $response = ['status'=>StatusCode::NOK];
                if ($placeReport->id() != '') {
                    if ($this->action == 'reporte-borrar') {
                        $place = (new Place)->read($placeReport->get('id_place'));
                        $place->delete();
                    }
                    $placeReport->delete();
                    $response = ['status'=>StatusCode::OK];
                }
                return json_encode($response);
                break;
            case 'comentario-borrar':
            case 'comentario-publicar':
                $this->checkAuthorization();
                $this->mode = 'json';
                $placeComment = (new PlaceComment)->read($this->id);
                $response = ['status'=>StatusCode::NOK];
                if ($placeComment->id() != '') {
                    if ($this->action == 'comentario-borrar') {
                        $placeComment->delete();
                    }
                    if ($this->action == 'comentario-publicar') {
                        $placeComment->persistSimple('active', '1');
                    }
                    $response = ['status'=>StatusCode::OK];
                }
                return json_encode($response);
                break;

            // Cache
            case 'cache_all':
                $response = ['status'=>StatusCode::NOK];
                if ($this->checkAuthorization()) {
                    Cache::cacheAll();
                    $response = ['status'=>StatusCode::OK];
                }
                return json_encode($response);
                break;

        }
    }

    public function desactivateRobots()
    {
        $this->head = '<meta name="robots" content="noindex,nofollow"/>';
    }

    public function activateJS()
    {
        $this->head = Navigation_Controller::activateJsHeader();
    }

    public function ampFacebookCommentsHeader()
    {
        return '<script async custom-element="amp-facebook-comments" src="https://cdn.ampproject.org/v0/amp-facebook-comments-0.1.js"></script>';
    }

    public static function activateJsHeader()
    {
        return '
            ' . Recaptcha::head() . '
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
            <script type="text/javascript" src="' . ASTERION_APP_URL . 'helpers/ckeditor/ckeditor.js"></script>
            <script type="text/javascript" src="' . ASTERION_BASE_URL . 'libjs/public.js?v=17"></script>';
    }

    public function checkAuthorization()
    {
        $headers = apache_request_headers();
        if (!isset($headers) || !isset($headers['Authorization']) || $headers['Authorization'] != 'plastic') {
            header('Location: ' . url(''));
            exit();
        }
    }

}
