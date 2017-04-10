<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages_Model extends MY_Model {

    private $table, $mname;

    protected $_table_name = 'ct_pages';
    protected $_order_by = 'id';

    public function __construct() {
        parent::__construct();
        //$this->table = '';
    }

    public function index()
    {
       // $tmenu = 'top_menu';
       // $tmenu_model = $tmenu . '_model';
        $where = array(
            array('field' => 'slug', 'match' => ( (uri_string() == '') ? '/' : uri_string() ), 'compare' => 'LIKE', 'both'),
            //array('field' => 'lang', 'match' => language_code(), 'compare' => '=')
            );
        
        $page_content = $this->get_by($where, TRUE);

        //$this->tp->tpl = $page_content->column_cnt . 'cols_template.tpl';
/*
        if (isset($_SESSION['logged']) && $_SESSION['logged'] > 0):
            $a['CONTENT'] = isset($page_content->auth_body) ? stripslashes(htmlspecialchars_decode($page_content->auth_body, ENT_QUOTES)) : '';
        else:
            $a['CONTENT'] = isset($page_content->body) ? stripslashes(htmlspecialchars_decode($page_content->body, ENT_QUOTES)) : '';
        endif;        */

        $a['CONTENT'] = $page_content->body;

        //var_dump_exit($a);

      
        $this->tp->assign($a);
        
        

      


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