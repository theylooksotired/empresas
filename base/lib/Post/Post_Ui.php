<?php
/**
 * @class PostUi
 *
 * This class manages the UI for the Post objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Post_Ui extends Ui
{

    public function renderPublic()
    {
        $image = $this->object->getImageWidth('image', 'small');
        return '
            <div class="post">
                <a class="post_ins" href="' . $this->object->url() . '">
                    <div class="post_image">' . $image . '</div>
                    <div class="post_information">
                        <div class="post_title">' . $this->object->getBasicInfo() . '</div>
                        <div class="post_date">' . Navigation_Ui::date(Date::sqlText($this->object->get('publish_date'))) . '</div>
                        <div class="post_short_description">' . $this->object->get('short_description') . '</div>
                    </div>
                </a>
            </div>';
    }

    public function renderIntro()
    {
        $image = $this->object->getImageWidth('image', 'small');
        return '
            <div class="post">
                <a class="post_ins" href="' . $this->object->url() . '">
                    <div class="post_image">' . $image . '</div>
                    <div class="post_information">
                        <div class="post_title">' . $this->object->getBasicInfo() . '</div>
                        <div class="post_short_description">' . $this->object->get('short_description') . '</div>
                        <div class="post_date">
                            <i class="fa fa-calendar"></i>
                            <span>' . Navigation_Ui::date(Date::sqlText($this->object->get('publish_date'))) . '</span>
                        </div>
                        <div class="post_reading_time">
                            <i class="fa fa-clock-o"></i>
                            <span>' . Text::readingTime($this->object->get('description')) . '</span>
                        </div>
                    </div>
                </a>
            </div>';
    }

    public function renderSide()
    {
        $image = $this->object->getImageWidth('image', 'small');
        return '
            <div class="post_side">
                <a class="post_side_ins" href="' . $this->object->url() . '">
                    <div class="post_image">' . $image . '</div>
                    <div class="post_information">
                        <div class="post_title">' . $this->object->getBasicInfo() . '</div>
                        <div class="post_date">
                            <i class="fa fa-calendar"></i>
                            <span>' . Navigation_Ui::date(Date::sqlText($this->object->get('publish_date'))) . '</span>
                        </div>
                        <div class="post_short_description">' . $this->object->get('short_description') . '</div>
                    </div>
                </a>
            </div>';
    }

    public function renderComplete()
    {
        $this->object->loadMultipleValues();
        $share = $this->share(['share' => [['key' => 'facebook', 'icon' => '<i class="icon icon-facebook"></i>'], ['key' => 'twitter', 'icon' => '<i class="icon icon-twitter"></i>']]]);
        $image = $this->object->getImageWidth('image', 'small');
        return '
            <div class="post_complete">
                <div class="post_short_description">' . nl2br($this->object->get('short_description')) . '</div>
                <div class="post_short_info">
                    <div class="post_short_info_left">
                        <div class="post_date">' . Navigation_Ui::date(Date::sqlText($this->object->get('publish_date'))) . '</div>
                    </div>
                    <div class="post_short_info_right">' . $share . '</div>
                </div>
                <div class="post_image">' . $image . '</div>
                <div class="editorial">' . $this->object->get('description') . '</div>
            </div>
            <div class="item_complete_share">
                <div class="item_complete_share_title">Ayúdanos compartiendo este artículo</div>
                ' . $share . '
                ' . Navigation_Ui::facebookComments($this->object->url()) . '
            </div>
            ' . $this->renderRelated();
    }

    public function renderRelated()
    {
        $items = new ListObjects('Post', ['where' => 'id!=:id AND publish_date<=NOW() AND active="1"', 'limit' => 12], ['id' => $this->object->id()]);
        return '<div class="related">
                    <div class="related_title">Otros artículos</div>
                    <div class="posts">' . $items->showList() . '</div>
                </div>';
    }

    // Overwrite this function for the public form
    public function linkDeleteImage($valueFile)
    {
        return url('cuenta/borrar-imagen-articulo/' . $this->object->id());
    }

    public static function intro()
    {
        $items = new ListObjects('Post', ['where' => 'publish_date<=NOW() AND active="1"', 'order' => 'publish_date DESC', 'limit' => 3]);
        return '
            <div class="posts_intro">
                <div class="posts_intro_title">Últimos artículos</div>
                <div class="posts_intro_items">' . $items->showList(['function' => 'Intro']) . '</div>
            </div>';
    }

    public static function menuSide()
    {
        $items = new ListObjects('Post', ['where' => 'publish_date<=NOW() AND active="1"', 'order' => 'publish_date DESC', 'limit' => 6]);
        return '<div class="posts_side">' . $items->showList(['function' => 'Side']) . '</div>';
    }

    public function renderEdit()
    {
        $html = '
            <div class="itemEditInsWrapper">
                <div class="itemEditImage">' . $this->object->getImage('image', 'small') . '</div>
                <div class="itemEditInformation">
                    <div class="itemEditTitle">' . $this->object->getBasicInfo() . '</div>
                    <div class="itemEditCreated">Creado : ' . Navigation_Ui::date(Date::sqlText($this->object->get('created'))) . '</div>
                    <div class="itemEditModified">Actualizado : ' . Navigation_Ui::date(Date::sqlText($this->object->get('modified'))) . '</div>
                </div>
            </div>';
        if ($this->object->get('active') == '1') {
            return '
                <div class="itemEdit">
                    <div class="itemEditIns">
                        <a href="' . $this->object->url() . '" target="_blank">' . $html . '</a>
                    </div>
                </div>';
        }
        return '
            <div class="itemEdit">
                <div class="itemEditIns">
                    <a href="' . url('cuenta/editar-articulo/' . $this->object->id()) . '">' . $html . '</a>
                </div>
                    <div class="itemEditDelete">
                        <a href="' . url('cuenta/borrar-articulo/' . $this->object->id()) . '" class="confirm" data-confirm="' . __('are_you_sure_delete') . '">
                            <i class="fa fa-times"></i>
                            <span>Borrar</span>
                        </a>
                    </div>
            </div>';
    }

    public static function introConnected()
    {
        $login = User_Login::getInstance();
        $itemsNotActive = new ListObjects('Post', ['where' => 'id_user=:id_user AND (active!="1" OR active IS NULL)', 'order' => 'created DESC'], ['id_user' => $login->id()]);
        $itemsActive = new ListObjects('Post', ['where' => 'id_user=:id_user AND active="1"', 'order' => 'created DESC'], ['id_user' => $login->id()]);
        if ($itemsNotActive->isEmpty() && $itemsActive->isEmpty()) {
            return '<div class="message">No tiene artículos subidos en la plataforma.</div>';
        }
        return '
            <div class="group_connected_wrapper">

                ' . ((!$itemsNotActive->isEmpty()) ? '
                <div class="group_connected">
                    <div class="group_connected_title">Artículos a editar</div>
                    <div class="group_connected_items">
                        ' . $itemsNotActive->showList(['function' => 'Edit']) . '
                    </div>
                </div>' : '') . '

                ' . ((!$itemsActive->isEmpty()) ? '
                <div class="group_connected">
                    <div class="group_connected_title">Artículos aprobados</div>
                    <div class="group_connected_items">
                        ' . $itemsActive->showList(['function' => 'Edit']) . '
                    </div>
                </div>' : '') . '

            </div>';
    }

    public static function menuConnected($options = [])
    {
        return '
            <div class="group_connected_top">
                ' . ((in_array('upload', $options)) ? '
                    <a href="' . url('cuenta/subir-articulo') . '" class="group_connected_insert">
                        <i class="fa fa-plus"></i>
                        <span>Añadir un artículo</span>
                    </a>
                ' : '') . '
                ' . ((in_array('list', $options)) ? '
                    <a href="' . url('cuenta/articulos') . '">
                        <i class="fa fa-list"></i>
                        <span>Ver la lista</span>
                    </a>
                ' : '') . '
            </div>';
    }

    public function renderJsonHeader()
    {
        $image = $this->object->getImageUrl('image', 'huge');
        $image = ($image == '') ? $this->object->getImageUrl('image', 'web') : $image;
        $info = [
            '@context' => 'http://schema.org',
            '@type' => 'Article',
            'headline' => $this->object->getBasicInfo(),
            'image' => $image,
            'author' => [
                '@type' => 'Organization',
                'name' => Parameter::code('meta_title_page'),
                'url' => url(''),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Parameter::code('meta_title_page'),
                'logo' => Parameter::code('meta_image'),
            ],
            'datePublished' => $this->object->get('publish_date'),
            'dateModified' => $this->object->get('publish_date'),
        ];
        return '<script type="application/ld+json">' . json_encode($info) . '</script>';
    }

}
