<?php
class Navigation_Ui extends Ui
{

    public function render()
    {
        $layout_page = (isset($this->object->layout_page)) ? $this->object->layout_page : '';
        $title_page = (isset($this->object->title_page)) ? '<h1>' . $this->object->title_page . '</h1>' : '';
        $title_page = (isset($this->object->title_page_html)) ? '<h1>' . $this->object->title_page_html . '</h1>' : $title_page;
        $title_page = (isset($this->object->hide_title_page)) ? '' : $title_page;
        $message = (isset($this->object->message) && $this->object->message != '') ? $this->object->message : Session::getFlashInfo();
        $message_info = (isset($this->object->message_info) && $this->object->message_info != '') ? $this->object->message_info : Session::getFlashInfo();
        $message_alert = (isset($this->object->message_alert) && $this->object->message_alert != '') ? $this->object->message_alert : Session::getFlashAlert();
        $message_error = (isset($this->object->message_error) && $this->object->message_error != '') ? $this->object->message_error : Session::getFlashError();
        $message = ($message != '') ? '<div class="message">' . $message . '</div>' : '';
        $message_info = ($message_info != '') ? '<div class="message message_info">' . $message_info . '</div>' : '';
        $message_alert = ($message_alert != '') ? '<div class="message message_alert">' . $message_alert . '</div>' : '';
        $message_error = ($message_error != '') ? '<div class="message message_error">' . $message_error . '</div>' : '';
        $content = (isset($this->object->content)) ? $this->object->content : '';
        $content_top = (isset($this->object->content_top)) ? '<div class="content_top">' . $this->object->content_top . '</div>' : '';
        $contentExtra = (isset($this->object->contentExtra)) ? $this->object->contentExtra : '';
        $idInside = (isset($this->object->idInside)) ? $this->object->idInside : '';
        $amp = ($layout_page == 'amp' || (isset($this->object->mode) && $this->object->mode == 'amp')) ? true : false;
        switch ($layout_page) {
            default:
                return '
                    ' . $this->header($amp) . '
                    <div class="content_wrapper">
                        <div class="content_left">
                            ' . $this->breadCrumbs() . '
                            ' . $title_page . '
                            ' . $message . '
                            ' . $message_error . '
                            ' . $message_info . '
                            ' . (($amp) ? Adsense::amp() : Adsense::responsive()) . '
                            ' . $content . '
                        </div>
                        <div class="content_right">
                            ' . (($amp) ? Adsense::ampInline() : Adsense::responsive()) . '
                            ' . $this->contentSide() . '
                        </div>
                    </div>
                    ' . $this->footer();
                break;
            case 'intro':
                return '
                    ' . $this->header($amp) . '
                    ' . $message . '
                    ' . $message_error . '
                    ' . $message_info . '
                    ' . $content . '
                    ' . $this->footer();
                break;
            case 'place':
                return '
                    ' . $this->header(true) . '
                    <div class="content_wrapper">
                        <div class="content_left">
                            ' . $this->breadCrumbs() . '
                            ' . $message . '
                            ' . $message_error . '
                            ' . $message_info . '
                            ' . $content . '
                        </div>
                        <div class="content_right">
                            ' . Adsense::ampInline() . '
                            ' . $this->contentSide() . '
                        </div>
                    </div>
                    ' . $this->footer();
                break;
            case 'promoted':
                return '
                    <div class="page_promoted">
                        ' . $this->header(true) . '
                        ' . $content . '
                        ' . $this->footer() . '
                    </div>';
                break;
            case 'simple':
            case 'form':
                $amp = (isset($this->object->mode) && $this->object->mode == 'amp') ? true : false;
                return '
                    <div class="content_all_wrapper content_all_wrapper_clean">
                        ' . $this->header($amp) . '
                        <div class="content_wrapper content_wrapper_simple content_wrapper_clean">
                            <div class="content_wrapper_ins">
                                ' . $message . '
                                ' . $message_error . '
                                ' . $message_info . '
                                ' . $content . '
                            </div>
                        </div>
                        ' . $this->footer() . '
                    </div>';
                break;
            case 'clean':
                $amp = (isset($this->object->mode) && $this->object->mode == 'amp') ? true : false;
                return '
                    ' . $this->header($amp) . '
                    <div class="content_wrapper content_wrapper_simple content_wrapper_clean">
                        <div class="content_wrapper_simple_ins">
                            ' . (($amp) ? Adsense::amp() : Adsense::responsive()) . '
                            ' . $message . '
                            ' . $message_error . '
                            ' . $message_info . '
                            ' . $content . '
                        </div>
                    </div>
                    ' . $this->footer();
                break;
            case 'empty':
                $amp = (isset($this->object->mode) && $this->object->mode == 'amp') ? true : false;
                return '
                    <div class="content_all_wrapper content_all_wrapper_empty">
                        ' . $this->header($amp) . '
                        <div class="content_wrapper content_wrapper_simple content_wrapper_clean">
                            ' . $message . '
                            ' . $message_error . '
                            ' . $message_info . '
                            ' . $content . '
                        </div>
                    </div>';
                break;
            case 'message':
                $amp = (isset($this->object->mode) && $this->object->mode == 'amp') ? true : false;
                return '
                    <div class="content_all_wrapper content_all_wrapper_clean">
                        ' . $this->header($amp) . '
                        <div class="content_wrapper content_wrapper_message content_wrapper_clean">
                            ' . (isset($this->object->message_image) ? '<div class="message_image"><img src="' . ASTERION_BASE_URL . 'visual/img/' . $this->object->message_image . '.svg"/></div>' : '') . '
                            <div class="message">
                                ' . $title_page . '
                                <div class="messageIns">
                                    ' . (isset($this->object->message) ? $this->object->message : '') . '
                                    <p class="message_mail">Para cualquier duda o consulta escríbanos a <a href="mailto:info@plasticwebs.com">info@plasticwebs.com</a></p>
                                </div>
                            </div>
                            <div class="button_home">
                                <a href="' . url('') . '">
                                    <span>Ir a la página de inicio</span>
                                    <i class="icon icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        ' . $this->footer() . '
                    </div>';
                break;
        }
    }

    public function header($amp = false)
    {
        $subscribe_top = '';
        $layoutPage = (isset($this->object->layoutPage)) ? $this->object->layoutPage : '';
        if ($this->object->action != 'inscribir' && $layoutPage != 'simple') {
            $subscribe_top = '
                <div class="subscribe_top">
                    <a href="' . url('inscribir') . '"><i class="icon icon-edit"></i> Inscribir a mi empresa</a>
                </div>';
        }
        return '
            <header class="header">
                <div class="header_ins">
                    <div class="header_left">
                        <div class="logo">
                            <a href="' . url('') . '">
                                <strong>' . Parameter::code('meta_title_page') . '</strong>
                                <span>' . Parameter::code('country') . '</span>
                            </a>
                        </div>
                    </div>
                    <div class="header_right">
                        ' . $subscribe_top . '
                        <div class="search_top">
                            ' . (($amp) ? Navigation_Ui::searchAmp() : Navigation_Ui::search()) . '
                        </div>
                    </div>
                </div>
            </header>';
    }

    public function footer()
    {
        $place = new Place();
        return '
            <footer class="footer">
                <div class="footer_ins">
                    <div class="footer_list_wrapper">
                        <div class="footer_list footer_cities">
                            <div class="footer_title">Ciudades de ' . Parameter::code('country') . '</div>
                            <div class="footer_list_items">
                                ' . $place->showUi('Cities') . '
                            </div>
                        </div>
                        <div class="footer_list footer_countries">
                            <div class="footer_title">Otros directorios</div>
                            <div class="footer_list_items">
                                <a href="http://www.argentina-directorio.com" target="_blank" title="Directorio de empresas de Argentina">Argentina</a>
                                <a href="http://www.directorio.com.bo" target="_blank" title="Directorio de empresas de Bolivia">Bolivia</a>
                                <a href="http://www.directorio-chile.com" target="_blank" title="Directorio de empresas de Chile">Chile</a>
                                <a href="http://www.colombia-directorio.com" target="_blank" title="Directorio de empresas de Colombia">Colombia</a>
                                <a href="http://www.ecuador-directorio.com" target="_blank" title="Directorio de empresas de Ecuador">Ecuador</a>
                                <a href="http://www.directorio-guatemala.com" target="_blank" title="Directorio de empresas de Guatemala">Guatemala</a>
                                <a href="http://www.directorio-honduras.com" target="_blank" title="Directorio de empresas de Honduras">Honduras</a>
                                <a href="http://www.mexico-directorio.com" target="_blank" title="Directorio de empresas de México">México</a>
                                <a href="http://www.directorio-panama.com" target="_blank" title="Directorio de empresas de Panamá">Panamá</a>
                                <a href="http://www.paraguay-directorio.com" target="_blank" title="Directorio de empresas de Paraguay">Paraguay</a>
                                <a href="http://www.peru-directorio.com" target="_blank" title="Directorio de empresas de Perú">Perú</a>
                                <a href="http://www.telefono.do" target="_blank" title="Directorio de empresas de República Dominicana">República Dominicana</a>
                                <a href="http://www.uruguay-directorio.com" target="_blank" title="Directorio de empresas de Uruguay">Uruguay</a>
                                <a href="http://www.venezuela-directorio.com" target="_blank" title="Directorio de empresas de Venezuela">Venezuela</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer_down">
                        <div class="button_contact">
                            <a href="' . url('inscribir') . '">Inscribir a mi empresa</a>
                        </div>
                        <div class="footer_down_ins">
                            ' . str_replace('#EMAIL', Parameter::code('email'), str_replace('#COUNTRY', Parameter::code('country'), HtmlSection::showFile('footer'))) . '
                        </div>
                    </div>
                </div>
            </footer>';
    }

    public function contentSide()
    {
        $place = new Place();
        return '
            <aside>
                ' . (($this->object->action != 'articulos') ? '
                <div class="content_side content_side_articles">
                    <h2>Artículos</h2>
                    ' . Post_Ui::menuSide() . '
                    <div class="content_side_button">
                        <a href="' . url('articulos') . '">Ver todos los artículos</a>
                    </div>
                </div>
                ' : '') . '
                <div class="content_side content_side_cities">
                    <h2>Ciudades</h2>
                    <div class="content_side_items">
                        ' . $place->showUi('Cities') . '
                    </div>
                    <div class="content_side_button">
                        <a href="' . url('ciudad') . '">Ver todas las ciudades</a>
                    </div>
                </div>
                <div class="content_side content_side_categories">
                    <h2>Categorías más buscadas</h2>
                    <div class="content_side_items">
                        ' . $place->showUi('Categories') . '
                    </div>
                    <div class="content_side_button">
                        <a href="' . url('categoria') . '">Ver todas las categorías</a>
                    </div>
                </div>
            </aside>';
    }

    public static function search()
    {
        return '
            <form action="' . url('buscar') . '" method="post" enctype="multipart/form-data" class="form_search_simple" accept-charset="UTF-8">
                <fieldset>
                    <div class="text form_field">
                        <input type="search" name="search" size="50" placeholder="Buscar..."/>
                    </div>
                    <div class="form_field_submit">
                        <button type="submit" class="form_submit">
                            <i class="icon icon-search"></i>
                            <span>Buscar</span>
                        </button>
                    </div>
                </fieldset>
            </form>';
    }

    public static function searchAmp()
    {
        return '
            <form accept-charset="UTF-8" class="form_search_simple" action="' . url('buscar') . '" method="GET" target="_top">
                <fieldset>
                    <div class="text form_field">
                        <input type="search" name="search" size="50" placeholder="Buscar..."/>
                    </div>
                    <div class="form_field_submit">
                        <button type="submit" class="form_submit">
                            <i class="icon icon-search"></i>
                            <span>Buscar</span>
                        </button>
                    </div>
                </fieldset>
            </form>';
    }

    public function breadCrumbs()
    {
        $html = '';
        if (isset($this->object->bread_crumbs) && is_array($this->object->bread_crumbs)) {
            $html .= '
                <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                    <a href="' . url('') . '" itemprop="url"><span itemprop="title">Inicio</span></a>
                </span> &raquo;';
            foreach ($this->object->bread_crumbs as $url => $title) {
                $html .= '
                    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a href="' . $url . '" itemprop="url"><span itemprop="title">' . $title . '</span></a>
                    </span> &raquo;';
            }
            return '<div class="breadcrumbs">' . substr($html, 0, -8) . '</div>';
        }
    }

    public static function analytics()
    {
        return '
            <script async src="https://www.googletagmanager.com/gtag/js?id=' . Parameter::code('google_analytics') . '"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag(\'js\', new Date());
              gtag(\'config\', \'' . Parameter::code('google_analytics') . '\');
            </script>';
    }

    public static function analyticsAmp()
    {
        return '
            <amp-analytics type="googleanalytics">
                <script type="application/json">{"vars": {"account": "' . Parameter::code('google_analytics') . '"}, "triggers": { "trackPageview": { "on": "visible", "request": "pageview"}}}</script>
            </amp-analytics>';
    }

    public static function facebookComments($url)
    {
        return '<amp-facebook-comments layout="responsive" height="300" width="600" data-href="' . $url . '"></amp-facebook-comments>';
    }

    public static function date($date)
    {
        $search = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
        $replace = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        return str_replace($search, $replace, $date);
    }

}
