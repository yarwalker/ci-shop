<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar extends MX_Controller {

    public $mname, $tag;

    function __construct()
    {
        parent::__construct();

        $this->mname = strtolower(get_class());// imya modulya
        $this->tag = strtoupper($this->mname); // TAG v shablone

        $this->load->model($this->mname.'/'.$this->mname.'_model');
    }

    public function index(  )
    {
        $model = $this->mname.'_model';

        $sidebar_content = $this->$model->index();

        $sidebar = '';
        foreach( $sidebar_content as $mod ):
          if (is_file('../../ci/application/modules/' . strtolower(str_replace('_Model', '', get_class())) . '/views/widgets/' . $mod->module_name . '.php'))  { // проверяет существует ли виджет
            $sidebar .= $this->load->view('/widgets/' . $mod->module_name, NULL, TRUE); 
          }  
        endforeach;

        $a['SIDEBAR'] = $sidebar; 
      
        $this->tp->assign($a);
    }

    

}