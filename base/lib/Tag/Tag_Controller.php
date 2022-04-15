<?php
/**
 * @class TagController
 *
 * This class is the controller for the Tag objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Tag_Controller extends Controller
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
            case 'clean_case':
                $table = '';
                foreach ((new Tag)->readList(['order' => 'name']) as $item) {
                    $name = ucfirst(strtolower($item->get('name')));
                    $item->persistSimpleArray([
                        'name' => $name,
                        'name_url' => Text::simple($name),
                    ]);
                }
                echo 1;
                exit();
                break;
            case 'clean_doubles':
                $query = 'SELECT name_url, COUNT(name_url) FROM ' . (new Tag)->tableName . ' t GROUP BY name_url HAVING COUNT(name_url) > 1';
                foreach (Db::returnAllColumn($query) as $nameTag) {
                    echo $nameTag . '<br/>';
                    $tags = (new Tag)->readList(['where' => 'name_url=:name_url'], ['name_url' => $nameTag]);
                    $tagMain = $tags[0];
                    foreach ($tags as $tag) {
                        Db::execute('UPDATE ' . (new PlaceTag)->tableName . ' SET id_tag="' . $tagMain->id() . '" WHERE id_tag="' . $tag->id() . '"');
                        if ($tag->id() != $tagMain->id()) {
                            Db::execute('DELETE FROM ' . (new Tag)->tableName . ' WHERE id="' . $tag->id() . '"');
                        }
                    }
                }
                echo 1;
                exit();
                break;
            case 'clean_categories':
                foreach ((new Tag)->readList(['order' => 'name']) as $item) {
                    $category = new Category();
                    if ($item->get('id_category') == '') {
                        $category = (new Category)->readFirst(['where' => 'name_url=:name_url OR name_plural_url=:name_url'], ['name_url' => $item->get('name_url')]);
                    }
                    if ($category->id() == '') {
                        $category = (new Category)->readFirst(['where' => 'MATCH (name) AGAINST (:name)', 'order' => 'MATCH (name) AGAINST (:name) DESC'], ['name' => $item->get('name')]);
                    }
                    if ($category->id() != '' && $item->get('id_category') == '') {
                        $item->persistSimple('id_category', $category->id());
                    }
                }
                echo 1;
                exit();
                break;
            case 'clean':
                $table = '';
                foreach ((new Tag)->readList(['order' => 'name']) as $item) {
                    $table .= '
                        <tr class="form" data-id="' . $item->id() . '">
                            <td><input name="name" value="' . $item->get('name') . '" style="width:100%"/></td>
                            <td><button>Guardar</button></td>
                        </tr>';
                }
                $this->content = '<table class="table_admin small" style="width:100%" data-action="' . url('tag/clean-ajax', true) . '">' . $table . '</table>';
                $this->head = $this->loadFormAjax();
                return $this->ui->render();
                break;
            case 'clean-ajax':
                $tag = (new Tag)->read($this->values['id']);
                if ($tag->id() != '') {
                    $tag->persistSimple('name', $this->values['name']);
                    $tag->persistSimple('name_url', Text::simple($this->values['name']));
                    echo 1;
                }
                echo 0;
                exit();
                break;
            case 'categories':
                $table = '';
                foreach ((new Tag)->readList(['order' => 'name']) as $item) {
                    $category = (new Category)->read($item->get('id_category'));
                    $categoryHtml = $category->getBasicInfo();
                    $table .= '
                        <tr class="form" data-id="' . $item->id() . '">
                            <td><input name="tagname" value="' . $item->get('name') . '"  style="width:100%"/></td>
                            <td><input name="category" value="' . $categoryHtml . '" style="width:100%"/></td>
                            <td><button>Guardar</button></td>
                        </tr>';
                }
                $this->content = '<table class="table_admin table_categories small" style="width:100%" data-action="' . url('tag/categories-ajax', true) . '" data-autocomplete="' . url('category-autocomplete') . '">' . $table . '</table>';
                $this->head = $this->loadFormAjax();
                return $this->ui->render();
                break;
            case 'categories_empty':
                $table = '';
                foreach ((new Tag)->readList(['where'=>'id_category="" OR id_category IS NULL', 'order' => 'name']) as $item) {
                    $category = (new Category)->read($item->get('id_category'));
                    $categoryHtml = $category->getBasicInfo();
                    $table .= '
                        <tr class="form" data-id="' . $item->id() . '">
                            <td><input name="tagname" value="' . $item->get('name') . '"  style="width:100%"/></td>
                            <td><input name="category" value="' . $categoryHtml . '" style="width:100%"/></td>
                            <td><button>Guardar</button></td>
                        </tr>';
                }
                $this->content = '<table class="table_admin table_categories small" style="width:100%" data-action="' . url('tag/categories-ajax', true) . '" data-autocomplete="' . url('category-autocomplete') . '">' . $table . '</table>';
                $this->head = $this->loadFormAjax();
                return $this->ui->render();
                break;
            case 'categories-ajax':
                $tag = (new Tag)->read($this->values['id']);
                if ($tag->id() != '') {
                    $category = (new Category)->readFirst(['where' => 'name=:name'], ['name' => $this->values['category']]);
                    if ($category->id() != '') {
                        $tag->persistSimple('id_category', $category->id());
                        echo 1;
                    }
                }
                echo 0;
                exit();
                break;
        }
    }

    public function menuInsideItems()
    {
        return parent::menuInsideItems() . '
            ' . Ui::menuAdminInside($this->type . '/clean', 'list', 'clean') . '
            ' . Ui::menuAdminInside($this->type . '/categories', 'list', 'categories');
    }

    public function menuInsideItemsListElements()
    {
        return ['insert_view', 'insert_view_ajax', 'insert_check', 'modify_view', 'modify_view_ajax', 'modify_view_check', 'clean', 'categories'];
    }

    public function loadFormAjax()
    {
        return '
            <script type="text/javascript">
                $(function() {
                    $(\'.table_categories input[name="category"]\').autocomplete({
                      source: $(\'.table_categories\').data(\'autocomplete\'),
                      minLength: 2,
                      select: function(event, ui) {
                        console.log(item);
                      }
                    });

                    $(document).on(\'click\', \'.form button\', function(evt){
                        var form = $(this).parents(\'.form\');
                        var data = {
                            "id":form.data(\'id\'),
                            "name":form.find(\'input[name="name"]\').val(),
                            "category":form.find(\'input[name="category"]\').val()
                        };
                        $.ajax({
                            type: "POST",
                            url: $(\'.table_admin\').data(\'action\'),
                            data: data,
                            success: function(data) {
                                if (data == "10") {
                                    form.find(\'button\').css(\'background\', \'green\');
                                }
                            }
                        });
                    });
                });
            </script>';
    }

}
