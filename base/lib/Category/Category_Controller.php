<?php
/**
 * @class CategoryController
 *
 * This class is the controller for the Category objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Category_Controller extends Controller
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
            case 'load-first':
                $filename = ASTERION_BASE_FILE . 'data/categories.data';
                $categoriesRaw = explode("\n", file_get_contents($filename));
                foreach ($categoriesRaw as $categoryRaw) {
                    if (trim($categoryRaw) != '') {
                        $category = new Category([
                            'name' => $categoryRaw,
                        ]);
                        // $category->persist();
                    }
                }
                dumpExit($categoriesRaw);
                break;
            case 'plurals':
                $table = '';
                foreach ((new Category)->readList(['order' => 'name']) as $item) {
                    $plural = $item->get('name_plural');
                    if ($item->get('name_plural') == '') {
                        $name = explode(' ', $item->get('name'));
                        if (in_array(substr($name[0], -1), ['a', 'e', 'i', 'o', 'u'])) {
                            $name[0] = $name[0] . 's';
                        } elseif (substr($name[0], -1) == 'z') {
                            $name[0] = substr($name[0], 0, -1) . 'ces';
                        } else {
                            $name[0] = $name[0] . 'es';
                        }
                        $plural = implode(' ', $name);
                    }
                    $table .= '
                        <tr class="form" data-id="' . $item->id() . '">
                            <td><input name="name" value="' . $item->get('name') . '" style="width:100%"/></td>
                            <td><input name="plural" value="' . $plural . '" style="width:100%"/></td>
                            <td><button>Guardar</button></td>
                        </tr>';
                }
                $this->content = '<table class="table_admin small" style="width:100%" data-action="'.url('category/plurals-ajax', true).'">' . $table . '</table>';
                $this->head = $this->loadFormAjax();
                return $this->ui->render();
                break;
            case 'plurals-ajax':
                $category = (new Category)->read($this->values['id']);
                if ($category->id() != '') {
                    $category->persistSimple('name', $this->values['name']);
                    $category->persistSimple('name_url', Text::simple($this->values['name']));
                    $category->persistSimple('name_plural', $this->values['plural']);
                    $category->persistSimple('name_plural_url', Text::simple($this->values['plural']));
                    echo 1;
                }
                echo 0;
                exit();
                break;
        }
    }

    public function menuInsideItems()
    {
        return parent::menuInsideItems() . '
            ' . Ui::menuAdminInside($this->type . '/plurals', 'list', 'plurals');
    }

    public function menuInsideItemsListElements()
    {
        return ['insert_view', 'insert_view_ajax', 'insert_check', 'modify_view', 'modify_view_ajax', 'modify_view_check', 'plurals'];
    }

    public function loadFormAjax()
    {
        return '
            <script type="text/javascript">
                $(function() {
                    $(document).on(\'click\', \'.form button\', function(evt){
                        var data = {"id":$(this).parents(\'.form\').data(\'id\'), "name":$(this).parents(\'.form\').find(\'input[name="name"]\').val(), "plural":$(this).parents(\'.form\').find(\'input[name="plural"]\').val()}
                        $.ajax({
                            type: "POST",
                            url: $(\'.table_admin\').data(\'action\'),
                            data: data,
                            success: function(data) { console.log(data); }
                        });
                    });
                });
            </script>';
    }

}
