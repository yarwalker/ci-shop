<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shabashov
 * Date: 28.10.13
 * Time: 9:08
 * To change this template use File | Settings | File Templates.
 */

class Top_menu_model extends CI_MODEL {

    public $obarray, $list, $item, $prev_item;
    private $_table = 'ct_menu';
    //private $_item, $_prev_item;

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    function getTreeview($type = '')
    {
        if($type <> '') $this->db->where('type', $type);
        //$this->db->where('active', 1);
        $this->db->order_by("left", "asc");
        $query = $this->db->get('ct_menu');
        return $query->result_array();
    }

    function getTreeMenu($type = '')
    {
        if($type <> '') $this->db->where('type', $type);
        $this->db->where('active', 1);
        $this->db->order_by("left", "asc");
        $query = $this->db->get('ct_menu');
        return $query->result_array();
    }

    function getItemInfo($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);

        if($query->num_rows()):
            $this->item = $query->row();
            return true;
        endif;

        return false;
    }

    function getPrevItemInfo($id, $type)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table);

        $cur_item  = $query->row();

        $this->db->where('type', $type);
        $this->db->where('left', ($cur_item->left-1));
        $this->db->or_where('right', ($cur_item->left-1));
        $query = $this->db->get($this->_table);

        if($query->num_rows()):
            $this->prev_item = $query->row();
            return true;
        endif;

        return false;
    }

    function addItem($ru_name, $en_name, $parent, $url, $active, $type, $prev_id)
    {
        if($parent == $prev_id): // вставка дочерней в пустую ветку или в начало родительской
            // получим данные по предыдущему пункту меню
            if( $this->getItemInfo($prev_id) ):
                $this->db->query("UPDATE `" . $this->_table . "` SET `left` = `left` + 2 WHERE `left` > ".$this->item->left." AND `type` = '".$type."'");
                $this->db->query("UPDATE `" . $this->_table . "` SET `right` = `right` + 2 WHERE `right` > ".$this->item->left." AND `type` = '".$type."'");

                $data = array(
                    'ru_name' => $ru_name,
                    'en_name' => $en_name,
                    'parent' => $parent,
                    'url' => $url,
                    'active' => $active,
                    'type' => $type,
                    'left' => ($this->item->left + 1),
                    'right' => ($this->item->left + 2)
                );

                $this->db->insert($this->_table, $data);

                return $this->db->affected_rows();
            else:
                return -10;
            endif;
        else: // вставка когда предыдущий эл-т является потомком родительского
            // получим данные по предыдущему пункту меню
            if( $this->getItemInfo($prev_id) ):
                $this->db->query("UPDATE `" . $this->_table . "` SET `left` = `left` + 2 WHERE `left` > ".$this->item->right." AND `type` = '".$type."'");
                $this->db->query("UPDATE `" . $this->_table . "` SET `right` = `right` + 2 WHERE `right` > ".$this->item->right." AND `type` = '".$type."'");

                $data = array(
                    'ru_name' => $ru_name,
                    'en_name' => $en_name,
                    'parent' => $parent,
                    'url' => $url,
                    'active' => $active,
                    'type' => $type,
                    'left' => ($this->item->right + 1),
                    'right' => ($this->item->right + 2)
                );

                $this->db->insert($this->_table, $data);

                return $this->db->affected_rows();
            else:
                return -11;
            endif;
        endif;

    }

    function updateItem($id, $ru_name, $en_name, $parent, $url, $active, $prev_id, $type)
    {
        //$res = 'id:'.$id."\r\n".' name:'.$ru_name."\r\n".'parent:'. $parent."\r\n".'prev_id'.$prev_id."\r\n";

        // получим информацию по пункту меню
        $this->getItemInfo($id);
        if($this->getPrevItemInfo($id, $type)):
            $previous_item_id = $this->prev_item->id;
        else:
            $previous_item_id = 0;
        endif;

        //return 'pid:'.$this->item->parent.' - '.'parent:'.$parent.' | prev:'.$previous_item_id.' - '.' prev_id:'.$prev_id;

        if( $this->item->parent == $parent && $previous_item_id == $prev_id ):
            // положение пункта не изменилось, делаем простой update
            $data = array(
                'ru_name' => $ru_name,
                'en_name' => $en_name,
                //'parent' => $parent,
                'url' => $url,
                'active' => $active
            );
            $this->db->update($this->_table, $data, "id = ".$id);

            return $this->db->affected_rows();
        else:
            // создаем временную таблицу для переносимой ветки

            $this->load->dbforge();

            $fields = array(
                'id' => array( 'type' => 'INT','constraint' => 5),
                'parent' => array( 'type' => 'INT','constraint' => 5),
                'ru_name' => array('type' =>'VARCHAR',  'constraint' => '100'),
                'en_name' => array('type' =>'VARCHAR',  'constraint' => '100'),
                'url' => array('type' =>'VARCHAR', 'constraint' => '100'),
                'type' => array('type' =>'VARCHAR', 'constraint' => '100'),
                'active' => array( 'type' => 'INT','constraint' => 5),
                'left' => array( 'type' => 'INT','constraint' => 5),
                'right' => array( 'type' => 'INT','constraint' => 5),
                'depth' => array( 'type' => 'INT','constraint' => 5)
            );

            $this->dbforge->add_field($fields);
            $this->dbforge->create_table('tmp_menu');

            $rows = $this->db->query("SELECT * FROM `" . $this->_table . "`
                                       WHERE `left` BETWEEN " . $this->item->left . " AND " . $this->item->right . " AND `type` = '" . $type . "' ORDER BY `left`")->result();

            foreach($rows as $row):
                $data = array(
                    'id' => $row->id,
                    'parent' => $row->parent,
                    'ru_name' => $row->ru_name,
                    'en_name' => $row->en_name,
                    'url' => $row->url,
                    'type' => $row->type,
                    'active' => $row->active,
                    'left' => $row->left,
                    'right' => $row->right,
                    'depth' => $row->depth
                );

                $this->db->insert('ct_tmp_menu', $data);
            endforeach;

            $cur_item = $this->item;


            // удаляем переносимую ветку из дерева
            $this->delete_item($id);

            // определяем предыдущую ветку после удаления, т.к. после удаления происходит перерассчет позиций
            $this->getItemInfo($prev_id);
            $prev_item = $this->item;

            $m_width = $cur_item->right - $cur_item->left + 1;

            if($parent <> $prev_id):
                $diff = $cur_item->left - $prev_item->right - 1;

                $this->db->query('UPDATE `' . $this->_table . '` SET `left` = `left` + ' . $m_width . ' WHERE `left` > ' . $prev_item->right . " AND `type` = '" . $this->item->type . "'");
                $this->db->query('UPDATE `' . $this->_table . '` SET `right` = `right` + ' . $m_width . ' WHERE `right` > ' . $prev_item->right . " AND `type` = '" . $this->item->type . "'");
            else:
                $diff = $cur_item->left - $prev_item->left - 1;

                $this->db->query('UPDATE `' . $this->_table . '` SET `left` = `left` + ' . $m_width . ' WHERE `left` > ' . $prev_item->left . " AND `type` = '" . $this->item->type . "'");
                $this->db->query('UPDATE `' . $this->_table . '` SET `right` = `right` + ' . $m_width . ' WHERE `right` > ' . $prev_item->left . " AND `type` = '" . $this->item->type . "'");
            endif;

            // обновляем ветку меню во временной таблице
            $this->db->query('UPDATE `ct_tmp_menu` SET `left` = `left` - ' . $diff . ', `right` = `right` - ' . $diff);
            $this->db->query('UPDATE `ct_tmp_menu` SET `parent` = ' . $parent . ' WHERE `id` = ' . $id);

            // вставляем ветку из временной таблицы в основную
            $results = $this->db->get('ct_tmp_menu')->result(); //."\r\n";

            foreach($results as $row):
                $data = array(
                    'id' => $row->id,
                    'parent' => $row->parent,
                    'ru_name' => $row->ru_name,
                    'en_name' => $row->en_name,
                    'url' => $row->url,
                    'type' => $row->type,
                    'active' => $row->active,
                    'left' => $row->left,
                    'right' => $row->right,
                    'depth' => $row->depth
                );

                $this->db->insert($this->_table, $data);
            endforeach;

            $this->dbforge->drop_table('tmp_menu');

           return 1;
        endif;


    }

    function delete_item($id)
    {
        if( $this->getItemInfo($id) ):
            $mwidth = $this->item->right - $this->item->left + 1;
            $this->db->query("DELETE FROM `" . $this->_table . "` WHERE `left` BETWEEN " . $this->item->left . " AND " . $this->item->right . " AND `type` = '" . $this->item->type . "'");

            $this->db->query("UPDATE `" . $this->_table . "` SET `left` = `left` - " . $mwidth . " WHERE `left` > ".$this->item->right." AND `type` = '" . $this->item->type . "'");
            $this->db->query("UPDATE `" . $this->_table . "` SET `right` = `right` - " . $mwidth . " WHERE `right` > ".$this->item->right." AND `type` = '" . $this->item->type . "'");

            return 1; //$this->db->affected_rows();
        else:
            return -1;
        endif;


    }

    /**
     * Проверяем является ли пункт меню с id прямым потомком пункта меню с pid
     * @param $pid - родительский id
     * @param $id - id пункта меню
     * @return кол-во строк
     */
    function checkMenuItems($pid, $id)
    {
        $res = 0;

        if($pid == $id):
            $res = 1;
        else:
            $this->db->where('parent', $pid);
            //$this->db->where('id', $id);
            $query = $this->db->get($this->_table);

            if($query->num_rows() > 0):
                $results = $query->result_array();
                foreach($results as $row):
                    if($row['id'] == $id ):
                        return 1;
                    endif;
                endforeach;
            endif;
        endif;

        return $res;
    }

    function selectNodes($arr)
    {
        $str = ''; //'<option value="0" data-left="1" data-right="1">Корень</option>';
        foreach($arr as $node):
            $str .= '<option value="' . $node['id'] . '" data-left="' . $node['left'] . '" data-right="' . $node['right'] . '" >' . $node['ru_name'] . '</option>';
        endforeach;

        return $str;
    }

    function buildTree($catArray)
    {
        global $obarray, $list;

        $list = "<ul>";
        if (!is_array($catArray)) return '';
        $obarray = $catArray;

        $root = $obarray[0];

        foreach($obarray as $item){
            if($item['parent'] == $root['id']){
                $mainlist = $this->_buildElements($item, 0);
            }
        }
        $list .= "</ul>";
        return $list;
    }

    private function _buildElements($parent, $append)
    {
        global $obarray, $list;

        $list .= '<li><a href="' . $parent['url'] . '" data-pid="' . $parent['parent'] . '" data-id="' . $parent['id'] . '" data-active="' . $parent['active'] . '" data-en_name="'.$parent['en_name'].'" data-left="' . $parent['left'] . '" class="treeNode">' . $parent['ru_name'] . '</a>';

        if($this->_hasChild($parent['id'])){
            $append++;
            $list .= "<ul>";
            $child = $this->_buildArray($parent['id']);

            foreach($child as $item){
                $list .= $this->_buildElements($item, $append);
            }
            $list .= "</ul>";
        }
         $list .=  '</li>';
    }

    function buildTopMenu($catArray)
    {
        global $obarray, $list;

        $list = '<ul class="nav">';
        if (!is_array($catArray)) return '';
        $obarray = $catArray;

        $root = $obarray[0];

        foreach($obarray as $item){
            if($item['parent'] == $root['id']){
                $mainlist = $this->_buildItems($item, 0);
            }
        }
        $list .= "</ul>";
        return $list;
    }

    private function _buildItems($parent, $append)
    {
        global $obarray, $list;

        //$list .= '<li><a href="' . $parent['url'] . '" >' . $parent['name'] . '</a>';

        $pos = strpos($parent['url'], 'http://');

        if ($pos === false):
            $url = lang_root_url($parent['url']);
        else:
            $url = str_replace( '/ru', '/' . language_code(), $parent['url'] );
        endif;

        if($this->_hasChild($parent['id'])){
            $list .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="' . $url . '" >' . $parent[language_code().'_name'] . '<b class="caret"></b></a>';

            $append++;
            $list .= '<ul class="dropdown-menu">';
            $child = $this->_buildArray($parent['id']);

            foreach($child as $item){
                $list .= $this->_buildItems($item, $append);
            }
            $list .= "</ul>";
        } else {
            $list .= '<li><a href="' . $url . '" >' . $parent[language_code().'_name'] . '</a>';
        }

        $list .=  '</li>';
    }

    function buildTopMenuForum($catArray, $lang)
    {
        global $obarray, $list;

        $list = '<ul class="nav">';
        if (!is_array($catArray)) return '';
        $obarray = $catArray;

        $root = $obarray[0];

        foreach($obarray as $item){
            if($item['parent'] == $root['id']){
                $mainlist = $this->_buildItemsForum($item, 0, $lang);
            }
        }
        $list .= "</ul>";
        return $list;
    }

    private function _buildItemsForum($parent, $append, $lang)
    {
        global $obarray, $list;

        //$list .= '<li><a href="' . $parent['url'] . '" >' . $parent['name'] . '</a>';

        $pos = strpos($parent['url'], 'http://');

        if ($pos === false):
            $url = lang_root_url($parent['url']);
        else:
            $url = str_replace( '/ru', '/' . $lang, $parent['url'] );
        endif;

        if($lang == 'ru'):
            $url = str_replace('/pro', '/pro/ru', $url);
        endif;

        if($this->_hasChild($parent['id'])){
            $list .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="' . $url . '" >' . $parent[$lang.'_name'] . '<b class="caret"></b></a>';

            $append++;
            $list .= '<ul class="dropdown-menu">';
            $child = $this->_buildArray($parent['id']);

            foreach($child as $item){
                $list .= $this->_buildItemsForum($item, $append, $lang);
            }
            $list .= "</ul>";
        } else {
            $list .= '<li><a href="' . $url . '" >' . $parent[$lang.'_name'] . '</a>';
        }

        $list .=  '</li>';
    }
    
    private function _hasChild($parent)
    {
        global $obarray;
        $counter = 0;
        foreach($obarray as $item){
            if($item['parent'] == $parent){
                ++$counter;
            }
        }
        return $counter;
    }

    private function _buildArray($parent)
    {
        global $obarray;
        $bArray = array();

        foreach($obarray as $item){
            if($item['parent'] == $parent){
                array_push($bArray, $item);
            }
        }

        return $bArray;
    }

}