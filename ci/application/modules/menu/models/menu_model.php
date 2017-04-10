<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_Model extends MY_Model {

    private $table, $mname;

    protected $_table_name = 'ct_menu';
    protected $_order_by = 'm_id';
    protected $_timestamps = FALSE;

    public $obarray, $list, $item, $prev_item;
    //private $_table = 'ct_menu';

    public function __construct() {
        parent::__construct();
        //$this->table = '';
    }

    public function save($data, $id = NULL){

        // Set timestamps
        if ($this->_timestamps == TRUE) {
            $now = date('Y-m-d H:i:s');
            //$id || $data['created'] = $now;
            $data['date'] = $now;
        }
        
        // Insert
        if ($id === NULL) {
            //!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        
        return $this->db->affected_rows(); //$id;
    }

    public function getLeftMenu()
    {
        
        // проверка меню в базе
        $date = new DateTime(date(DATE_ISO8601,strtotime("-1 hours")), new DateTimeZone('Europe/Moscow'));
        $where = array('date >' => $date->format('Y-m-d H:i:s')); 

        if( $this->count_all_results($where) == 0 ):

            $this->truncate();
            /*
            $wsdl_url = 'https://api-iz.merlion.ru/v2/mlservice.php?wsdl'; //'https://api.merlion.com/re/mlservice2?wsdl'; //"https://api-iz.merlion.ru/v2/mlservice.php?wsdl";
            $params = array('login' => "TC0029082|OVAL",
                            'password' => "123456",
                            'encoding' => 'UTF-8', //"Windows-1251",
                            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
            );
            */
            try {

                //$client = new SoapClient($this->_wsdl_url, $this->_wsdl_params);
                
                $cat = $this->_client->getCatalog();
                $data = $cat->item;

                foreach( $data as $key => $row )
                    $ids[$key] = $row->ID;

                array_multisort($ids, SORT_ASC, $data); 

                $menu = array();
                $now = new DateTime;
                $level = $prev_id = 0;


                foreach( $data as $i => $row ):
                    if( $row->ID_PARENT == 'Order' ):
                        $level = 1;
                    elseif( $row->ID_PARENT == $prev_id ):
                        $level++;
                    else:
                        $needle = $row->ID_PARENT;

                        $arr = array_filter( $menu, function( $innerArray ) use ($needle) {
                            return $needle == $innerArray['id'];
                        });
                        //var_dump_print($arr);
                        $keys = array_keys($arr);
                        $level = $arr[$keys[0]]['depth_level'] + 1;
                    endif;

                    $menu[] = array(
                        'name' => $row->Description, // TEXT
                        "depth_level" => $level,
                        "id_parent" => $row->ID_PARENT,
                        "id" => $row->ID,
                        'type' => 'left',
                        'date' => $now->format('Y-m-d H:i:s'),
                        'm_id' => $i
                    );

                    $prev_id = $row->ID;

                endforeach;  

                // закэшируем меню в базе
                $this->db->insert_batch($this->_table_name, $menu);

                return $menu;
            }
            catch (SoapFault $E) {
                echo $E->faultstring;
            }
        else:
            //var_dump_exit($this->get());


            $arr = $this->get();
            foreach( $arr as $row ):
                $menu[] = array(
                    'name' => $row->name, // TEXT
                    "depth_level" => $row->depth_level,
                    "id_parent" => $row->id_parent,
                    "id" => $row->id,
                    'type' => $row->type,
                    'date' => $row->date
                );
            endforeach;

            return $menu;
        endif;

    }



    public function index( $type )
    {
        
        // верхнее меню
      //  $this->common->load_model($this->mname, $tmenu);
        //$this->load->model($this->mname.'/'.'top_menu_model');
        //$this->load->model('application/models/tree');
        //$top_menu = $this->tree->getTreeMenu(($this->session->userdata( 'ba_role' )) ? $this->session->userdata( 'ba_role' ) : 'all');

        // в зависимости от авторизации и юзера нужно определять тип меню
        $top_menu = $this->getTreeMenu($type);
        
        //echo $this->top_menu_model->buildTopMenu($top_menu);
        //return $this->top_menu_model->buildTopMenu($top_menu);
        //return $list;



        // заполняем необходимые метки
       
        //return $this->buildTopMenu($top_menu);
        $a['menu'] = $this->buildTopMenu($top_menu);
        $this->tp->assign($a);


    }

    public function getUserMenu()
    {
        $user_menu_str = '';

        if (isset($_SESSION['logged']) && $_SESSION['logged'] > 0):
            $user_menu_str .= '
                <ul class="nav pull-right">
                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i>' . ( $this->session->userdata('ba_name') ? trim(str_replace($this->session->userdata('ba_username'), '', $this->session->userdata('ba_name'))) : lang('ci_base.user') ) . '<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
            if ( ! isset($_SESSION['ba_role']) || $_SESSION['ba_role'] != 'admin' ):
                    $user_menu_str .= '<li><a target="_blank" href="' . lang_root_url('user/profile') . '">' . lang('ci_base.edit_my_profile') . '</a></li>' .
                                      '<li><a target="_blank" href="' . lang_root_url('user/invite') . '">' . lang('ci_base.invite') . '</a></li>' .
                                      '<li><a href="' . lang_root_url('projects') . '">' . lang('ci_base.my_company_projects') . '</a></li>' .
                                      '<li><a href="' . lang_root_url('company') . '">' . lang('ci_base.my_company_info') . '</a></li>' .
                                      '<li><a href="' . lang_root_url('user') . '">' . lang('ci_base.my_company_users') . '</a></li>';
            endif; 
            
            $user_menu_str .= '<li><a href="' . lang_root_url('auth/logout') . '"><i class="icon-external-link"></i>' . lang('ci_base.exit') . '</a></li>
                            </ul>
                        </li>
                    </ul>';
        endif; 

        $a['user_menu'] = $user_menu_str;
        $this->tp->assign($a);
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
        $query = $this->db->get($this->_table_name);

        if($query->num_rows()):
            $this->item = $query->row();
            return true;
        endif;

        return false;
    }

    function getPrevItemInfo($id, $type)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->_table_name);

        $cur_item  = $query->row();

        $this->db->where('type', $type);
        $this->db->where('left', ($cur_item->left-1));
        $this->db->or_where('right', ($cur_item->left-1));
        $query = $this->db->get($this->_table_name);

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
                $this->db->query("UPDATE `" . $this->_table_name . "` SET `left` = `left` + 2 WHERE `left` > ".$this->item->left." AND `type` = '".$type."'");
                $this->db->query("UPDATE `" . $this->_table_name . "` SET `right` = `right` + 2 WHERE `right` > ".$this->item->left." AND `type` = '".$type."'");

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

                $this->db->insert($this->_table_name, $data);

                return $this->db->affected_rows();
            else:
                return -10;
            endif;
        else: // вставка когда предыдущий эл-т является потомком родительского
            // получим данные по предыдущему пункту меню
            if( $this->getItemInfo($prev_id) ):
                $this->db->query("UPDATE `" . $this->_table_name . "` SET `left` = `left` + 2 WHERE `left` > ".$this->item->right." AND `type` = '".$type."'");
                $this->db->query("UPDATE `" . $this->_table_name . "` SET `right` = `right` + 2 WHERE `right` > ".$this->item->right." AND `type` = '".$type."'");

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

                $this->db->insert($this->_table_name, $data);

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
            $this->db->update($this->_table_name, $data, "id = ".$id);

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

            $rows = $this->db->query("SELECT * FROM `" . $this->_table_name . "`
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

                $this->db->query('UPDATE `' . $this->_table_name . '` SET `left` = `left` + ' . $m_width . ' WHERE `left` > ' . $prev_item->right . " AND `type` = '" . $this->item->type . "'");
                $this->db->query('UPDATE `' . $this->_table_name . '` SET `right` = `right` + ' . $m_width . ' WHERE `right` > ' . $prev_item->right . " AND `type` = '" . $this->item->type . "'");
            else:
                $diff = $cur_item->left - $prev_item->left - 1;

                $this->db->query('UPDATE `' . $this->_table_name . '` SET `left` = `left` + ' . $m_width . ' WHERE `left` > ' . $prev_item->left . " AND `type` = '" . $this->item->type . "'");
                $this->db->query('UPDATE `' . $this->_table_name . '` SET `right` = `right` + ' . $m_width . ' WHERE `right` > ' . $prev_item->left . " AND `type` = '" . $this->item->type . "'");
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

                $this->db->insert($this->_table_name, $data);
            endforeach;

            $this->dbforge->drop_table('tmp_menu');

           return 1;
        endif;


    }

    function delete_item($id)
    {
        if( $this->getItemInfo($id) ):
            $mwidth = $this->item->right - $this->item->left + 1;
            $this->db->query("DELETE FROM `" . $this->_table_name . "` WHERE `left` BETWEEN " . $this->item->left . " AND " . $this->item->right . " AND `type` = '" . $this->item->type . "'");

            $this->db->query("UPDATE `" . $this->_table_name . "` SET `left` = `left` - " . $mwidth . " WHERE `left` > ".$this->item->right." AND `type` = '" . $this->item->type . "'");
            $this->db->query("UPDATE `" . $this->_table_name . "` SET `right` = `right` - " . $mwidth . " WHERE `right` > ".$this->item->right." AND `type` = '" . $this->item->type . "'");

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
            $query = $this->db->get($this->_table_name);

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