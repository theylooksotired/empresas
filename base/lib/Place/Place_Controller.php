<?php
/**
 * @class PlaceController
 *
 * This class is the controller for the Place objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Place_Controller extends Controller
{

    public function getContent()
    {
        $this->mode = 'admin';
        $this->object = new $this->objectType;
        $this->title_page = __((string) $this->object->info->info->form->title);
        $this->layout = (string) $this->object->info->info->form->layout;
        $this->layout_page = '';
        $this->menu_inside = $this->menuInside();
        $this->login = UserAdmin_Login::getInstance();
        $this->ui = new NavigationAdmin_Ui($this);
        LogAdmin::log($this->action . '_' . $this->objectType, $this->values);
        switch ($this->action) {
            default:
                return parent::getContent();
                break;
			case 'no_tags':
                $query = '
                    SELECT p.id, p.title, COUNT(pt.id_tag) as counttags FROM '.(new Place)->tableName.' p
                    LEFT JOIN '.(new PlaceTag)->tableName.' pt ON p.id=pt.id_place
                    GROUP BY pt.id
                    HAVING counttags = 0';
                $list = new ListObjects('Place', ['query'=>$query]);
                $this->content = '
                	<div class="list_items reload_list_items list_items">
		                ' . $list->showListPager(['function' => 'Admin', 'message' => '<div class="message">' . __('no_items') . '</div>']) . '
		            </div>';
		        $this->title_page = __('no_tags');
		        return $this->ui->render();
                break;
            case 'clean_tags':
                $placesIds = Db::returnAllColumn('SELECT p.id, p.title, COUNT(pt.id_tag) FROM '.(new Place)->tableName.' p JOIN '.(new PlaceTag)->tableName.' pt ON p.id=pt.id_place GROUP BY p.id HAVING COUNT(pt.id_tag) > 5 ORDER BY COUNT(pt.id_tag) DESC');
                foreach ($placesIds as $placesId) {
                    $tags = Db::returnAllColumn('SELECT pt.id FROM '.(new PlaceTag)->tableName.' pt JOIN '.(new Place)->tableName.' p ON p.id=pt.id_place AND pt.id_place="'.$placesId.'"');
                    Db::execute('DELETE FROM '.(new PlaceTag)->tableName.' WHERE id IN ('.implode(',', array_slice($tags, 5)).')');
                }
                echo 1;
                exit();
            break;
            case 'clean_categories':
                $placesIds = Db::returnAllColumn('SELECT p.id, p.title, COUNT(pt.id_category) FROM '.(new Place)->tableName.' p JOIN '.(new PlaceCategory)->tableName.' pt ON p.id=pt.id_place GROUP BY p.id HAVING COUNT(pt.id_category) > 5 ORDER BY COUNT(pt.id_category) DESC');
                foreach ($placesIds as $placesId) {
                    $categories = Db::returnAllColumn('SELECT pt.id FROM '.(new PlaceCategory)->tableName.' pt JOIN '.(new Place)->tableName.' p ON p.id=pt.id_place AND pt.id_place="'.$placesId.'"');
                    Db::execute('DELETE FROM '.(new PlaceCategory)->tableName.' WHERE id IN ('.implode(',', array_slice($categories, 5)).')');
                }
                echo 1;
                exit();
            break;
        }
    }

    public function menuInsideItems()
    {
        return parent::menuInsideItems() . '
            ' . Ui::menuAdminInside($this->type . '/no_tags', 'list', 'no_tags');
    }

    public function menuInsideItemsListElements()
    {
        return ['insert_view', 'insert_view_ajax', 'insert_check', 'modify_view', 'modify_view_ajax', 'modify_view_check', 'no_tags'];
    }

}