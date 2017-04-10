<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Bitauth extends Bitauth
{
	
	// IF YOU CHANGE THE STRUCTURE OF THE `users` TABLE, THAT CHANGE MUST BE REFLECTED HERE
	private $_data_fields = array(
		'username','password','password_last_set','password_never_expires','remember_me', 'activation_code',
		'active','forgot_code','forgot_generated','enabled','last_login','last_login_ip'
	);

	public function __construct()
	{
		$this->_assign_libraries();

		$this->_table						= $this->config->item('table', 'bitauth');
		$this->_default_group_id			= $this->config->item('default_group_id', 'bitauth');
		$this->_remember_token_name			= $this->config->item('remember_token_name', 'bitauth');
		$this->_remember_token_expires		= $this->config->item('remember_token_expires', 'bitauth');
		$this->_remember_token_updates		= $this->config->item('remember_token_updates', 'bitauth');
		$this->_require_user_activation		= $this->config->item('require_user_activation', 'bitauth');
		$this->_pwd_max_age					= $this->config->item('pwd_max_age', 'bitauth');
		$this->_pwd_age_notification		= $this->config->item('pwd_age_notification', 'bitauth');
		$this->_pwd_min_length				= $this->config->item('pwd_min_length', 'bitauth');
		$this->_pwd_max_length				= $this->config->item('pwd_max_length', 'bitauth');
		$this->_pwd_complexity				= $this->config->item('pwd_complexity', 'bitauth');
		$this->_pwd_complexity_chars		= $this->config->item('pwd_complexity_chars', 'bitauth');
		$this->_forgot_valid_for			= $this->config->item('forgot_valid_for', 'bitauth');
		$this->_log_logins					= $this->config->item('log_logins', 'bitauth');
		$this->_invalid_logins				= $this->config->item('invalid_logins', 'bitauth');
		$this->_mins_login_attempts			= $this->config->item('mins_login_attempts', 'bitauth');
		$this->_mins_locked_out				= $this->config->item('mins_locked_out', 'bitauth');
		$this->_date_format					= $this->config->item('date_format', 'bitauth');

		$this->_all_roles					= $this->config->item('roles', 'bitauth');

		// Grab the first role on the list as the administrator role
		$slugs = array_keys($this->_all_roles);
		$this->_admin_role = $slugs[0];

		// Specify any extra login fields
		$this->_login_fields = array();

		// If we're logged in, grab session values. If not, check for a "remember me" cookie
		if($this->logged_in())
		{
			$this->get_session_values();
		}
		else if($this->input->cookie($this->config->item('cookie_prefix').$this->_remember_token_name))
		{
			$this->login_from_token();
		}

		$this->set_error($this->session->flashdata('bitauth_error'), FALSE);
		unset($slugs);
	}
	
	/**
	 * Bitauth::login()
	 *
	 * Process a login, either from username/password (+ extra fields) or a "remember me" cookie
	 */
	public function login($username, $password, $remember = FALSE, $asadmin = FALSE, $token = NULL)
	{
		
		if(empty($username))
		{
			$this->set_error($this->lang->line('bitauth_username_required'));
			return FALSE;
		}

		$xmlloginpass = $this->check_throne_logins( $username, $password, $asadmin );

		if($xmlloginpass !== FALSE)
		{
			$xmlloginpass['username'] = $username;
			$xmlloginpass['logged']   = 1;			
			
			$this->set_session_values($xmlloginpass);
			
			$this->log_attempt($xmlloginpass['username'], TRUE);
				return TRUE;
		}
		else
		{
			$this->log_attempt(FALSE, FALSE);
		}

		$this->set_error(sprintf($this->lang->line('bitauth_login_failed'), $this->lang->line('bitauth_username')));
		return FALSE;
	} /**/
	
	
	/**
	 * Bitauth::login()
	 *
	 * Process a login, either from username/password (+ extra fields) or a "remember me" cookie
	 * /
	public function login($username, $password, $remember = FALSE, $asadmin = FALSE, $token = NULL)
	{
		if(empty($username))
		{
			$this->set_error($this->lang->line('bitauth_username_required'));
			return FALSE;
		}
		
		if($this->locked_out())
		{
			$this->set_error(sprintf($this->lang->line('bitauth_user_locked_out'), $this->_mins_locked_out));
			return FALSE;
		}

		$user = $this->get_user_by_username($username);

		if($user !== FALSE)
		{
			if($this->CheckPassword($password, $user->password) || ($password === NULL && $user->remember_me == $token))
			{
				if( ! empty($this->_login_fields) && ! $this->check_login_fields($user, $extra))
				{
					$this->log_attempt($user->user_id, FALSE);
					return FALSE;
				}

				// Inactive
				if( ! $user->active)
				{
					$this->log_attempt($user->user_id, FALSE);
					$this->set_error($this->lang->line('bitauth_user_inactive'));
					return FALSE;
				}

				// Expired password
				if($this->password_is_expired($user))
				{
					$this->log_attempt($user->user_id, FALSE);
					$this->set_error($this->lang->line('bitauth_pwd_expired'));
					return FALSE;
				}

				$this->set_session_values($user);

				if($remember != FALSE)
				{
					$this->update_remember_token($user->username, $user->user_id);
				}

				$data = array(
					'last_login' => $this->timestamp(),
					'last_login_ip' => ip2long($_SERVER['REMOTE_ADDR'])
				);

				// If user logged in, they must have remembered their password.
				if( ! empty($user->forgot_code))
				{
					$data['forgot_code'] = '';
				}

				// Update last login timestamp and IP
				$this->update_user($user->user_id, $data);

				$this->log_attempt($user->user_id, TRUE);
				return TRUE;
			}

			$this->log_attempt($user->user_id, FALSE);
			
		} elseif ( $thronedata = $this->check_throne_logins($username, $password, $asadmin) )
		{
			$this->add_user($thronedata, FALSE);
			$this->login($username, $password, $remember , $asadmin, $token);
		
		}else
		{
			$this->log_attempt(FALSE, FALSE);
		}

		$this->set_error(sprintf($this->lang->line('bitauth_login_failed'), $this->lang->line('bitauth_username')));
		return FALSE;
	}

	
	/***/
		
	
	public function check_throne_logins($username, $password, $asadmin = FALSE)
	{
	
		# XML RPC setup
		$this->xmlrpc -> initialize(array('curl' => TRUE));
		$this->xmlrpc -> set_debug(FALSE);
		$this->xmlrpc -> server( $this->config->item('server'), $this->config->item('port'));	
	
		$data = array(
			array(
				$username,
				'string'
				),
			array(
				$password,
				'string'
			)
		);
	
		$userdata = Array();
	
		if ($asadmin === TRUE)
		{
			$response = $this->xmlrpc->send_xml_rpc('AdmGetAdminAttributes',$data);
			
			$response = throne_response_parser($response, 'AdmGetAdminAttributes');
			
			if ( is_array ($response) && isset($response['Name']))
			{
				$userdata['name']	  = $response['Name'];
				$userdata['username'] = $username;
				$userdata['password'] = $password;
				$userdata['groups']   = Array(1);
				$userdata['role'] 	  = 'admin';
			}
			
		} else
		{
			$response = $this->xmlrpc->send_xml_rpc('GetUserInfo',$data);
			
			$response = throne_response_parser($response, 'GetUserInfo');
			
			if ( is_array ($response) && isset($response['ID']))
			{
				$userdata['name']	    = $response['UserAttributes']['Name'] . ' ' . $response['UserAttributes']['Surname'];
				$userdata['username']   = $username;
				$userdata['password']   = $password;
				$userdata['groups']     = Array(2);
				$userdata['Position']   = $response['UserAttributes']['Position'];
				$userdata['Language']   = $response['UserAttributes']['Language'];
				$userdata['throne_id']  = $response['ID'];
				$userdata['group_id']   = $response['GroupID'];
				//
				//////////// TO REMOVE 
				$userdata['role'] 		= 'user';
				$userdata['group'] 		= $response['GroupID'];
				//$userdata['active']   = $response['State'];
			}
		}
		
		if( !empty($userdata) )
		{
			return $userdata;	
		}
		
		return FALSE;
		
	}
	
	
	/**
	 * Bitauth::set_session_values()
	 *
	 * Set values to be saved in the session (should be coming from get_user_by_x)
	 */
	public function set_session_values($values)
	{
		$session_data = array();
		foreach($values as $_key => $_value)
		{
			/*if($_key !== 'password')
			{*/
				$this->$_key = $_value;

				if($_key == 'roles')
				{
					$_value = $this->encrypt->encode($_value);
				}

				$session_data[$this->_cookie_elem_prefix.$_key] = $_value;
			/*}*/
		}

		$this->session->set_userdata($session_data);
	}
	
	
	/**
	 * Bitauth::hash_password()
	 *
	 * Just encryption, instead of standart phpass hash method :
	 * return $this->phpass->HashPassword($str);
	 */
	public function hash_password($str)
	{
		return $this->encrypt->encode($str);
	}
	
	
	/**
	 * Bitauth::unhash_password()
	 *
	 * Decription
	 */
	public function unhash_password($str)
	{
		return $this->encrypt->decode($str);
	}
	
	
	public function CheckPassword($password, $stored_hash)
	{
		return $this->unhash_password($stored_hash) == $password;
	}
	
	
	/**
	 * Bitauth::_assign_libraries()
	 *
	 * Grab everything from the CI superobject that we need
	 */
	 public function _assign_libraries()
	 {
 		if($CI =& get_instance())
 		{
 			$this->input	= $CI->input;
			$this->load		= $CI->load;
			$this->config	= $CI->config;
			$this->lang		= $CI->lang;

			$CI->load->library('session');
			$this->session	= $CI->session;

			$CI->load->library('encrypt');
			$this->encrypt	= $CI->encrypt;

			$CI->load->library('xmlrpc');
			$this->xmlrpc	= $CI->xmlrpc;
			
			$this->load->database();
			$this->db		= $CI->db;

			$this->lang->load('bitauth');
			$this->load->config('bitauth', TRUE);
			$this->load->config('xmlrpc');
			
			return;
		}

		log_message('error', $this->lang->line('bitauth_instance_na'));
		show_error($this->lang->line('bitauth_instance_na'));

	 }
	 
	 
	 function is_throne_admin($message = TRUE)
	 {
		if($CI =& get_instance())
		{
			if( ! $this->logged_in() || $CI->session->userdata( 'ba_role' ) != 'admin')
			{
				if ( $message )
				{	
					add_message_string(lang('ci_error.loginfirst_adm'));
					redirect(lang_root_url('administration'));
				}
			} else
			{
				return TRUE;
			}
		}
		return FALSE;
	 }
	 
	 function is_throne_user($message = TRUE)
	 {
		if($CI =& get_instance())
		{
			if( ! $this->logged_in() || $CI->session->userdata( 'ba_role' ) == 'admin')
			{
				if ( $message )
				{	
	    			add_message_string(lang('ci_error.loginfirst'));
	    			redirect(lang_root_url('/'));
				}
			} else
			{
				return TRUE;
			}
		}
		return FALSE;
	 }
	 
	 
	 
	
}