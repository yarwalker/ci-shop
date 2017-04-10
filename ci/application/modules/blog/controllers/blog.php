<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MX_Controller {

    public $mname, 
           $tag = 'CONTENT',
           $per_page = 10;;

    function __construct()
    {
        parent::__construct();

        $this->tp->tpl = '2cols_tempplate.tpl';
        $this->mname = strtolower(get_class());// imya modulya
        //$this->tag = strtoupper($this->mname); // TAG v shablone

        $this->load->model($this->mname.'/'.$this->mname.'_model');

        # helper
      //  $this->load-> helper ( 'errors' );

     //   $this->lang->load('pages', current_lang());
    }

    public function index()
    {
        $model = $this->mname . '_model';

        //$this->$model->index();

        if( $this->input->get('q',true) ):
            $entries = $this->$model->get_entries($this->per_page, 'search');
        elseif( $this->input->get('filter',true) ):
            $entries = $this->$model->get_entries($this->per_page, 'filter');
        else:
            $entries = $this->$model->get_entries($this->per_page);
        endif;
       
        $this->m_pagination("blog/index", $this->blog_model->get_entries_cnt());

        foreach ($entries as $id => &$entry)
        {
          //$tagIDs =  $this->blog_model->_get_entry_tags($entry->id);
          //$entryTags = $this->blog_model->get_post_tags($entry->id);
          $entryTags = modules::run('tags_bind/get_entry_tags_label', $entry->id, 'blog');
          $tags_array = Array();

          if(count($entryTags)):
                foreach($entryTags as $tag):
                    if (strlen($tag->name) > 0)
                        $tags_array[] = anchor(lang_root_url('blog/index?filter[tags]=' . $tag->tag_id ), $tag->name );
                endforeach;
            endif;

          $entry->tags = implode(", ",$tags_array);
          $entry->body = htmlspecialchars_decode($this->_limit($entry->body));
          //$entry->comments = modules::run('blog_comments', $entry->id);
        }


    }

    public function m_pagination($pagerpath, $totalrows)
    {
        # libraries
        $this->load->library('pagination');

        $config['base_url']   = lang_root_url( $pagerpath ); // путь к страницам в пейджере
        $config['total_rows'] = $totalrows;
        $config['per_page']   = 10;
        $config['num_links']  = 5;
        $config['page_query_string'] = true;

        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
    }

    

}