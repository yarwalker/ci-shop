<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_comments_Model extends MY_Model {

    private $_entries_cnt;
    protected $_table_name = 'blog_comments';
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

}    