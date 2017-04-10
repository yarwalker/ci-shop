<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags_bind_model extends MY_Model {
    protected $_table_name = 'tags_bind';
    protected $_tags_table_name = 'tags';
    protected $_primary_key = 'tag_id';
    protected $_secondary_key = 'record_type';
    protected $_third_key = 'record_id';
    protected $_primary_filter = 'intval';
    protected $_secondary_filter = 'strval';
    protected $_order_by = 'tag_id';
    public $rules = array(
        'tag_id' => array(
            'field' => 'tag_id',
            'label' => 'Tag ID',
            'rules' => 'trim|intval'
        ),
        'record_id' => array(
            'field' => 'record_id',
            'label' => 'ID записи',
            'rules' => 'trim|intval'
        ),
        'record_type' => array(
            'field' => 'record_type',
            'label' => 'Тип записи',
            'rules' => 'trim|required'
        ),

    );

    public function get_new()
    {
        $tag_link = new stdClass();
        $tag_link->tag_id = 0;
        $tag_link->record_id = 0;
        $tag_link->record_type = '';
        return $tag_link;
    }

    public function delete( $params = array() ) 
    {
        if( !empty($params) ):
            if( isset($params['tag_id']) )
            {
                $filter = $this->_primary_filter;
                $params['tag_id'] = $filter($params['tag_id']);
                $this->db->where($this->_primary_key, $params['tag_id']);
            }

            if( isset($params['record_id']) )
            {
                $filter = $this->_primary_filter;
                $params['record_id'] = $filter($params['record_id']);
                $this->db->where($this->_third_key, $params['record_id']);
            }

            if( isset($params['type']) )
            {
                $filter = $this->_secondary_filter;
                $params['type'] = $filter($params['type']);
                $this->db->where($this->_secondary_key, $params['type']);
            }

            $this->db->delete($this->_table_name);

            return $this->db->affected_rows();
        endif;

        return 0;
        
    }

    public function delete_by_rec_id( $params = array() )
    {
        $this->delete( $params['record_id'], $params['type'] );
    }

    public function save($id)
    {
        parent::save($id);
    }

    public function get_entry_tags_label( $entryID, $type )
    {
        $filter = $this->_primary_filter;
        $entryID = $filter($entryID);

        $filter = $this->_secondary_filter;
        $type = $filter($type);

        $arr = array('record_id' => $entryID, 'record_type' => $type);

        $this->db->select($this->_table_name . '.*, ' . $this->_tags_table_name . '.name' );
        $this->db->from($this->_table_name);
        $this->db->join($this->_tags_table_name, $this->_table_name . '.tag_id = ' . $this->_tags_table_name . '.id');
        $this->db->where($arr);

        return $this->db->get()->result();
    }

    public function get_by( $entryID, $type )
    {

        //echo $entryID .' - ' . $type;

        $this->db->where('record_id' , $entryID);
        $this->db->where('record_type', $type);

        return $this->get();
    }

    public function save_tags_link( $entryId, $tags = NULL, $type = NULL )
    {
        if ( $tags != NULL && $type != NULL):
            $this->delete( array('record_id' => $entryId, 'type' => $type) );
            foreach ( $tags as $tagid ):
                $i_data = Array(
                    'tag_id'    => $tagid,
                    'record_id' => $entryId,
                    'record_type' => $type,
                );
                //$this->db->insert($this->_link_table_name, $i_data);
                $this->tags_bind_model->save($i_data);
            endforeach;
        endif;
    }


}