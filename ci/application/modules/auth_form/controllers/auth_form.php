<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_form extends MX_Controller {

    public $mname, $tag, $label;

    function __construct()
    {
        parent::__construct();

        $this->mname = strtolower(get_class());// imya modulya
        $this->tag = strtoupper($this->mname); // TAG v shablone
    }

    public function index()
    {
       // $this->load->model($this->mname.'/'.$this->mname.'_model');
       // $model = $this->mname.'_model';
       // $this->$model->index($this->mname);

        //$this->tp->parse($this->mname, $this->mname.'/'.$this->mname.'.tpl');
        $this->load->view($this->mname);
    }

    

}