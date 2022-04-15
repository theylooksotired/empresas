<?php
/**
 * @class CategoryUi
 *
 * This class manages the UI for the Category objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Category_Ui extends Ui
{

    public function renderSimple()
    {
        return $this->object->getBasicInfo() . ', ';
    }

    public function renderComplete()
    {
        $postCategory = new PostCategory();
        $items = new ListObjects('Post', ['join' => 'PostCategory', 'where' => $postCategory->tableName . '.id_category=:id AND active="1"'], ['id' => $this->object->id()]);
        return '<div class="posts">' . $items->showList() . '</div>';
    }

    public function renderCities()
    {
        $query = '
            SELECT DISTINCT p.city
            FROM ' . (new Place)->tableName . ' p
            JOIN ' . (new PlaceCategory)->tableName . ' pt ON p.id=pt.id_place AND pt.id_category="' . $this->object->id() . '"';
        $items = Db::returnAllColumn($query);
        $info = '';
        if (count($items) > 0) {
            asort($items);
            foreach ($items as $item) {
                $info .= (trim($item)!='') ? '<a href="' . $this->object->url() . '/' . Text::simple($item) . '" class="cityLink">' . $item . '</a> ' : '';
            }
        }
        return ($info != '') ? '
            <div class="city_links">
                <p>Ver <strong>' . $this->object->getBasicInfo() . '</strong> en :</p>
                <div class="city_links_items">' . $info . '</div>
            </div>' : '';
    }

    public static function intro($options = [])
    {
        $query = '
            SELECT t.id, t.name, t.name_url, COUNT(p.id) as count_places
            FROM ' . (new Category)->tableName . ' t
            JOIN ' . (new PlaceCategory)->tableName . ' pt ON t.id=pt.id_category
            JOIN ' . (new Place)->tableName . ' p ON p.id=pt.id_place
            GROUP BY t.id
            ORDER BY t.name_url';
        $isCity = (isset($options['place'])) ? true : false;
        if ($isCity) {
            $query = '
                SELECT t.id, t.name, t.name_url, COUNT(p.id) as count_places
                FROM ' . (new Category)->tableName . ' t
                JOIN ' . (new PlaceCategory)->tableName . ' pt ON t.id=pt.id_category
                JOIN ' . (new Place)->tableName . ' p ON (p.id=pt.id_place AND p.city_url="' . $options['place']->get('city_url') . '")
                GROUP BY t.id
                ORDER BY t.name_url';
        }
        $items = Db::returnAll($query);
        $groupsCategories = [];
        foreach ($items as $item) {
            $url = ($isCity) ? url('categoria/' . $item['id'] . '-' . $item['name_url'] . '/' . $options['place']->get('city_url')) : url('categoria/' . $item['id'] . '-' . $item['name_url']);
            $link = '<a href="' . $url . '">' . $item['name'] . ' <span>(' . $item['count_places'] . ')</span></a>';
            $letter = substr(strtoupper($item['name_url']), 0, 1);
            if (!isset($groupsCategories[$letter])) {
                $groupsCategories[$letter] = '';
            }
            $groupsCategories[$letter] .= $link;
        }
        $html = '';
        foreach ($groupsCategories as $key => $categories) {
            $html .= '
                <div class="categories_simple_block">
                    <div class="categories_simple_title">' . $key . '</div>
                    <div class="categories_simple">' . $categories . '</div>
                </div>';
        }
        return '
            <div class="categories_simple_wrapper">
                <h1>' . $options['title_page'] . '</h1>
                ' . $html . '
            </div>';
    }

}
