<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_Model extends MY_Model {

    private $_entries_cnt;
    protected $_table_name = 'blog_records';
    protected $_order_by = 'id';
    public $rules = array(
        'id' => array(
            'field' => 'id',
            'label' => 'ID',
            'rules' => 'trim|intval'
        ),
    );

    public function __construct() {
       parent::__construct();

      # labels
      $this->lang->load('blog');
    
      # helpers
      $this->load->helper('date');
    }

    public function index()
    {
       // $tmenu = 'top_menu';
       // $tmenu_model = $tmenu . '_model';
        $where = array(
            array('field' => 'url', 'match' => uri_string(), 'compare' => 'LIKE', 'both'),
            array('field' => 'lang', 'match' => language_code(), 'compare' => '=')
            );
        
        $page_content = $this->get_by($where, TRUE);

        $this->tp->tpl = $page_content->column_cnt . 'cols_template.tpl';

        //if( $this->bitauth->logged_in() )
       //     $a['CONTENT'] = isset($page_content->auth_body) ? stripslashes(htmlspecialchars_decode($page_content->auth_body, ENT_QUOTES)) : '';
       // else
            $a['CONTENT'] = isset($page_content->body) ? stripslashes(htmlspecialchars_decode($page_content->body, ENT_QUOTES)) : '';
                

        //var_dump_exit($a);

      
        $this->tp->assign($a);
        
        

    


    }

    public function get_entries($limit, $event = 'index')
    {
        // sql query initiate
        $ext_condition = '';

        // event - index or search
        if( $this->bitauth->logged_in () ):
          switch ($this->session->userdata('ba_role')) {
            case 'user':
                        // запрос для авторизованного пользователя
                        $sql = "SELECT * FROM (
                                SELECT * FROM `" . $this->db->dbprefix . "blog_records`
                                 WHERE `author_id` = " . $this->session->userdata('ba_throne_id') . "
                                UNION
                                SELECT * FROM `" . $this->db->dbprefix . "blog_records`
                                 WHERE `active` = 1 AND `date` < " . date('U',mktime(0, 0, 0, date('m'), date('d')+1, date('Y') )) . "
                                ) a
                              WHERE a.`lang` = '" . language_code() . "' ";
                        break;
            case 'admin':
                        // запрос для админа
                        $sql = "SELECT * FROM `" . $this->db->dbprefix . "blog_records` a WHERE a.`lang` = '" . language_code() . "' ";
                        break;
          }

            else:
                // запрос для неавторизованного посетителя
                $sql = "SELECT * FROM `" . $this->db->dbprefix . "blog_records` a
                         WHERE a.`active` = 1
                           AND a.`lang` = '" . language_code() . "'
                           AND a.`authorizedonly` = 0 AND a.`date` < " . date('U',mktime(0, 0, 0, date('m'), date('d')+1, date('Y') )) ;
            endif;

            // доп. условие для поиска
            if($event == 'search'):
                $ext_condition = ' AND (UPPER(a.`title`) LIKE "%' . mb_strtoupper($this->input->get('q',true)) . '%" OR UPPER(a.`announce`) LIKE "%' . mb_strtoupper($this->input->get('q',true)) . '%" OR UPPER(a.`body`) LIKE "%' . mb_strtoupper($this->input->get('q',true)) . '%") ';
            elseif($event == 'filter'): // доп. условие для фильтра
                $ext_condition = $this->process_filter();
            endif;

        $sql .= $ext_condition;

        //endif;

        //echo $sql;

        // get total entries
        $this->set_entries_cnt( $this->db->query($sql)->num_rows() );

        $offset = 0;
        if($this->get_entries_cnt() > 10 && $this->input->get('per_page',true) && is_numeric($this->input->get('per_page',true)) )
            $offset = $limit * $this->input->get('per_page',true) - $limit;

        return $this->db->query($sql . ' ORDER BY `date` DESC LIMIT ' . $offset . ', ' . $limit)->result();
    }

    public function get_entries_cnt()
    {
        return $this->_entries_cnt;
    }

    public function set_entries_cnt($cnt)
    {
        $this->_entries_cnt = $cnt;
    }

    public function get_by($where, $single = FALSE){
        if( isset($where) )
            foreach($where as $cond):
                if( $cond['compare'] == 'LIKE' ):
                    $this->db->like($cond['field'], $cond['match'], 'both');
                else:
                    $this->db->where($cond['field'], $cond['match']);
                endif;
            endforeach;

        return $this->get(NULL, $single);
    }

}