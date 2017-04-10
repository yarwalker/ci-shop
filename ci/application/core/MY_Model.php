<?php
class MY_Model extends CI_Model {
	
	protected $_table_name = '';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = '';
	public $rules = array();
	protected $_timestamps = FALSE;

    protected $_wsdl_url = 'https://api-iz.merlion.ru/v2/mlservice.php?wsdl',
              $_wsdl_params = array('login' => "TC0029082|OVAL",
                'password' => "123456",
                'encoding' => 'UTF-8', //"Windows-1251",
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS
                ),
              $_client;

	function __construct() {
		parent::__construct();

        try {
            $this->_client = new SoapClient($this->_wsdl_url, $this->_wsdl_params);
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
	}
	
	public function array_from_post($fields){
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->post($field);
		}
		return $data;
	}

	public function count_all()
	{
		return $this->db->count_all( $this->_table_name );
	}

	public function count_all_results($where) 
    {
        $this->db->where( $where );
        $this->db->from( $this->_table_name );
        return $this->db->count_all_results();
    }

    public function truncate()
    {
        $this->db->truncate( $this->_table_name );
    }
	
	public function get($id = NULL, $single = FALSE){
		
		if ($id != NULL) {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->where($this->_primary_key, $id);
			$method = 'row';
		}
		elseif($single == TRUE) {
			$method = 'row';
		}
		else {
			$method = 'result';
		}
		
		if (!count($this->db->ar_orderby)) {
			$this->db->order_by($this->_order_by);
		}
		return $this->db->get($this->_table_name)->$method();
	}
	
	public function get_by($where, $single = FALSE){
        if(isset($where['compare']) && $where['compare'] == 'LIKE'):
            $this->db->like($where['field'], $where['match'], 'after');
        else:
		    $this->db->where($where['field'], $where['match']);
        endif;
		return $this->get(NULL, $single);
	}
	
	public function save($data, $id = NULL){

        // Set timestamps
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			$id || $data['created'] = $now;
			$data['modified'] = $now;
		}
		
		// Insert
		if ($id === NULL) {
			//!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
			$this->db->set($data);
			$this->db->insert($this->_table_name);
			$id = $this->db->insert_id();
		}
		// Update
		else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table_name);
		}
		
		return $this->db->affected_rows(); //$id;
	}
	
	public function delete($id){
		$filter = $this->_primary_filter;
		$id = $filter($id);
		
		if (!$id) {
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table_name);

        return $this->db->affected_rows();
	}
}