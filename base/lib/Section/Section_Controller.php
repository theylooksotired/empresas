<?php
/**
 * @class SectionController
 *
 * This class is the controller for the Section objects.
 *
 * @author Leano Martinet <info@asterion-cms.com>
 * @package Asterion\Base
 * @version 4.0.0
 */
class Section_Controller extends Controller
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
            case 'categories':
                if (count($this->values) > 0) {
                    $categories = [];
                    foreach ($this->values as $key => $value) {
                        if (substr($key, 0, 4) == 'cat_') {
                            $categories[] = substr($key, 4);
                        }
                    }
                    Db::execute('UPDATE ' . (new Category)->tableName . ' SET id_section="' . $this->values['section'] . '" WHERE id IN (' . implode(',', $categories) . ')');
                    header('Location: ' . url('section/' . $this->action, true));
                    exit();
                }
                $table = '';
                $sections = ['' => ['id' => '', 'label' => 'Sin sección', 'number' => 0, 'items' => '']];
                foreach ((new Section)->readList() as $section) {
                    $sections[$section->id()] = ['id' => $section->id(), 'number' => 0, 'label' => $section->getBasicInfo(), 'items' => ''];
                }
                foreach ((new Category)->readList(['order' => 'name']) as $item) {
                    $sections[$item->get('id_section')]['number']++;
                    $sections[$item->get('id_section')]['items'] .= '
                        <tr>
                            <td><input type="checkbox" name="cat_' . $item->id() . '" id="cat_' . $item->id() . '"/></td>
                            <td><label for="cat_' . $item->id() . '">' . $item->get('name') . '</label></td>
                        </tr>';
                }
                $table = '';
                $inputs = '';
                foreach ($sections as $section) {
                    $inputs .= '<button type="submit" name="section" value="' . $section['id'] . '"/>' . $section['label'] . '</button>';
                    $table .= '
                        <tr>
                            <td colspan="2"><h2>' . $section['label'] . ' (' . $section['number'] . ')</h2></td>
                        </tr>
                        ' . $section['items'];
                }
                $this->content = '
                    <form action="' . url('section/categories', true) . '" method="post">
                        ' . $inputs . '
                        <table class="table_admin">' . $table . '</table>
                    </form>';
                $this->head = '
                    <script>
                        $(document).ready(function() {
                        var $chkboxes = $(\'input:checkbox\');
                        var lastChecked = null;
                        $chkboxes.click(function(e) {
                            if (!lastChecked) {
                                lastChecked = this;
                                return;
                            }
                            if (e.shiftKey) {
                                var start = $chkboxes.index(this);
                                var end = $chkboxes.index(lastChecked);
                                $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop(\'checked\', lastChecked.checked);
                            }
                            lastChecked = this;
                        });
                    });
                    </script>';
                return $this->ui->render();
                break;
        }
    }

    public function menuInsideItems()
    {
        return parent::menuInsideItems() . '
            ' . Ui::menuAdminInside($this->type . '/categories', 'list', 'categories');
    }

    public function menuInsideItemsListElements()
    {
        return ['insert_view', 'insert_view_ajax', 'insert_check', 'modify_view', 'modify_view_ajax', 'modify_view_check', 'categories'];
    }

}
