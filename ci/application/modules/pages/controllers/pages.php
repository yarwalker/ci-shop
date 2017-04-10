<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MX_Controller {

    public $mname, 
           $tag = 'CONTENT';

    function __construct()
    {
        parent::__construct();

       // $this->tp->tpl = '2cols_tempplate.tpl';
        $this->mname = strtolower(get_class());// imya modulya
        $this->tag = strtoupper($this->mname); // TAG v shablone

        $this->load->model($this->mname.'/'.$this->mname.'_model');

        # helper
       // $this->load-> helper ( 'errors' );

       // $this->lang->load('pages', current_lang());
    }

    public function index(  )
    {
        
        
        $model = $this->mname.'_model';

        $this->$model->index();

       // var_dump_exit($this->tp->D);
       // $this->tp->parse($this->tag, $this->mname.'/'.$this->mname.'.tpl'); 
    }

    public function mainPage()
    {
        $model = $this->mname.'_model';
        $this->load->model( $this->mname. '/' . $model );
         
        $this->$model->index();
        $this->tp->parse($this->tag, $this->mname.'/main_page.tpl');
    }

}