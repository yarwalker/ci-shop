<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Footer_Model extends MY_Model {

    private $table, $mname;

    protected $_table_name = 'ct_footer_links';
    protected $_order_by = 'id';

    public function __construct() {
        parent::__construct();
        //$this->table = '';
    }

    public function index($mname)
    {
        $this->mname = $mname;

        // получим ссылки для футера
        $this->_order_by = 'column ASC, row ASC';
        $links = $this->footer_model->get();

        $a['columns'] = array();
        $col = 1;
        $arr = array();
        $url = language_code() . '_url';
        $name = language_code() . '_name';
        foreach($links as $link):
            //$a['footer_links'] .= '<a href="' . $link->en_url . '">' . $link->en_name . '</a><br/>';
            if( $col == $link->column ):
                $arr[] = array('url' => $link->$url, 'name' => $link->$name);
            else:
                $a['columns'][] = array('links' => $arr);
                $arr = array();
                $arr[] = array('url' => $link->$url, 'name' => $link->$name);
                $col = $link->column;
            endif;
        endforeach;

        // добавим последний массив
        $a['columns'][] = array('links' => $arr);



        //$a['footer_links'] = $this->footer_model->get();
        $this->tp->assign($a);
        $this->tp->parse('footer_links', $this->mname . '/footer_links.tpl');

        //var_dump_exit($a);

    }

}