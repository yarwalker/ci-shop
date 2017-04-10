<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags_bind extends MX_Controller {

    public $mname, 
           $tag = 'CONTENT',
           $per_page = 10;;

    function __construct()
    {
        parent::__construct();

        //$this->tp->tpl = '2cols_tempplate.tpl';
        $this->mname = strtolower(get_class());// imya modulya
        //$this->tag = strtoupper($this->mname); // TAG v shablone

        $this->load->model($this->mname . '/' . $this->mname . '_model');

        # helper
      //  $this->load-> helper ( 'errors' );

     //   $this->lang->load('pages', current_lang());
    }

    public function get_entry_tags_label( $id, $type) 
    {
      $model = $this->mname . '_model';

      return $this->$model->get_entry_tags_label( $id, $type);
    }
}