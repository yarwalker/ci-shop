<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MX_Controller {

    public $mname, 
           $tag = 'MENU',
           $tpl = 'menu.tpl';

    function __construct()
    {
        parent::__construct();
        $this->mname = strtolower(get_class());// imya modulya
        $this->tag = strtoupper($this->mname); // TAG v shablone

        // загружаем модель для меню
        $this->load->model( $this->mname . '/' . $this->mname . '_model' ); 
    }

    public function index()
    {
        $model = $this->mname . '_model';

        $lmenu = $this->$model->getLeftMenu();

        // обработка массива меню и формирование каталога
        if( !empty($lmenu)):
            $menu_str = '<ul id="left_menu">';

            $lev = 1;
            foreach($lmenu as $arItem):
                if( $lev == $arItem['depth_level'] ):
                    $menu_str .= '<li><span><a href="/catalog/' . $arItem['id'] . '" class="root-item"  data-id="' . $arItem['id'] . '">' . $arItem["name"] . '</a></span>';
                elseif( $lev < $arItem['depth_level'] ):
                    $menu_str .= '<ul><li><span><a href="/catalog/' . $arItem['id'] . '" class="root-item"  data-id="' . $arItem['id'] . '">' . $arItem["name"] . '</a></span>';
                else: // $lev > $arItem['DEPTH_LEVEL']
                    while( $lev > $arItem['depth_level'] ):
                        $menu_str .= '</li></ul>';
                        $lev--;
                    endwhile;
                    $menu_str .= '<li><span><a href="/catalog/' . $arItem['id'] . '" class="root-item"  data-id="' . $arItem['id'] . '">' . $arItem["name"] . '</a></span>';
                endif;

                $lev = $arItem['depth_level'];
        
            endforeach;
            $menu_str .= '</li></ul> ';

        endif;

        $this->tp->assign('left_menu', $menu_str);
        //$this->tp->parse('left_menu', $this->mname . '/left_menu.tpl');

        
    }

    public function makeUserMenu()
    {
        $model = $this->mname . '_model'; 
        $this->$model->getUserMenu(); 
    }

    

}