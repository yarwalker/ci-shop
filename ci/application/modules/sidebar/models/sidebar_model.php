<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar_Model extends MY_Model {

    private $table, $mname;

    protected $_table_name = 'ct_sidebar';
    protected $_order_by = 'order';

    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
      $langs = array('ru', 'en');

      $where = array(
            array('field' => 'url', 'match' => ( (!uri_string() || in_array(uri_string(), $langs)) ? '/' : uri_string() ), 'compare' => 'LIKE', 'both'),
            //array('field' => 'lang', 'match' => language_code(), 'compare' => '=')
            );
        
      $sidebar_content = $this->get_by($where, FALSE);

      return $sidebar_content; 
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