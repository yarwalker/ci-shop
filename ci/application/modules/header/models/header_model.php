<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Header_Model extends MY_Model {
    
    private $table, $mname;

    protected $_table_name = 'ct_menu';
    protected $_order_by = 'id';

    public function __construct() {
        parent::__construct();
        //$this->table = '';
    }
    
    public function index($mname)
    {
        $this->mname = $mname;
       // echo $mname . '<br/>';

        // верхнее меню
      //  $this->common->load_model($this->mname, 'top_menu');
        //$this->load->model($this->mname.'/'.'top_menu_model');
        //$this->load->model('application/models/tree');
        //$top_menu = $this->tree->getTreeMenu(($this->session->userdata( 'ba_role' )) ? $this->session->userdata( 'ba_role' ) : 'all');

      //  $top_menu = $this->top_menu_model->getTreeMenu('all');
        //var_dump($top_menu);
        //echo $this->top_menu_model->buildTopMenu($top_menu);
        //return $this->top_menu_model->buildTopMenu($top_menu);
        //return $list;

        // заполняем необходимые метки
        $a['modal_info'] = lang('ci_base.info');
        $a['modal_btn_close'] = lang('ci_base.close');
        $a['language_links'] = language_links('li');
        $a['promo_link'] = lang('ci_base.gotopromo');
        $a['support'] = lang('ci_base.technical_support');

      //  $a['top_menu'] = $this->top_menu_model->buildTopMenu($top_menu);
        $this->tp->assign($a);



    }
    
}