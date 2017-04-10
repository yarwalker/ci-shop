<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller
{
    private $modules;
    public $tpl;

    public function __construct()
    {
        parent::__construct();
        $this->modules = array('auth','cabinet','ads','root', 'header'); // разрешенные модули
    }


    public function test()
    {
        modules::run('footer/test');
    }
}