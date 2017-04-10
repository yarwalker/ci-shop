<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {

    public $mname, 
           $tag = 'CONTENT',
           $_cookie_elem_prefix = 'ba_';

    function __construct()
    {
        parent::__construct();

        # helper
        $this->load->helper( 'errors' );
    }

    public function index(  )
    {
        //$this->data['show_links'] = FALSE;
        //$this->data['login_errors'] = Array();
        $this->lang->load('main');
        
        if($this->logged_in()) 
        {
          if ( $this->session->userdata( 'ba_role' ) == 'admin' ) 
          {
            $_SESSION['gobacklink'] = anchor( lang_root_url('administration/companies'), lang('ci_main.back_to_admin_panel') );
          } 
          else 
          {
            $_SESSION['gobacklink'] = anchor( lang_root_url('company'), lang('ci_main.back_to_user_panel') );
          }
        }

        if( $this->input->post('username') )
        {
          $this->form_validation->set_rules('username', 'Username', 'trim|required');
          $this->form_validation->set_rules('password', 'Password', 'required');
      
          if( $this->form_validation->run() == TRUE )
          {
            //session_start();
            

            # Login
            if( $this->login($this->input->post('username'), $this->input->post('password'),FALSE,FALSE) )
            {
              $_SESSION['ba_username'] = $this->session->userdata('ba_username');
              $_SESSION['ba_password'] = $this->session->userdata('ba_password');
              $_SESSION['ba_role'] = $this->session->userdata( 'ba_role' );
              $_SESSION['ba_name'] = trim(str_replace($this->session->userdata('ba_username'), '', $this->session->userdata('ba_name')));
              $_SESSION['logged'] = (int)$this->session->userdata( 'ba_logged' );
              $_SESSION['logged_in'] = TRUE;

              $this->data['logged'] = (int)$this->session->userdata( 'ba_logged' );
              
                    //$this->session->set_userdata('ba_name', trim(str_replace($this->session->userdata('ba_username'), '', $this->session->userdata('ba_name'))) );

                    //echo trim(str_replace($this->session->userdata('ba_username'), '', $this->session->userdata('ba_name')));

                   /* if($this->input->post('referer_url'))
                        redirect('/' . $this->input->post('referer_url'));
                    else
              redirect(lang_root_url('/')); */
            }
            else
            {
              $this->data['login_errors'] = $this->bitauth->get_error(); 
              $_SESSION['login_errors'] = $this->bitauth->get_error();
            }
          }
          else
          {
            $this->data['login_errors'] = validation_errors();
            $_SESSION['login_errors'] = validation_errors();
          }

          if( $this->input->post('referer_url') ):
               // echo $this->input->post('referer_url') . '<br/>';
               // unset($this->data['login_errors']);
            remove_result_from_session(); //$this->session->unset_userdata('error_array');
            redirect(base_url($this->input->post('referer_url')));
          else:
            unset($_SESSION['login_errors']);
            redirect(lang_root_url('/'));
          endif;
        }
        
        if ( get_messages_array() )
        {
            //var_dump_exit($this->session);

          $this->data['modal_messages'] = show_result_in_modal_window(
            get_messages_array()
          );
        }

        //redirect( $this->input->post('referer_url') );
    }

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
        //var_dump_exit($xmlloginpass);
        //$this->log_attempt($xmlloginpass['username'], TRUE);

        return TRUE;
      }
    /*  else
      {
        $this->log_attempt(FALSE, FALSE);
      } */

      $this->set_error(sprintf($this->lang->line('bitauth_login_failed'), $this->lang->line('bitauth_username')));
      return FALSE;
    }

    public function check_throne_logins($username, $password, $asadmin = FALSE)
    {
  
      # XML RPC setup
      $this->xmlrpc->initialize(array('curl' => TRUE));
      $this->xmlrpc->set_debug(FALSE);
      $this->xmlrpc->server( $this->config->item('server'), $this->config->item('port')); 
  
      $data = array(
        array( $username, 'string' ),
        array( $password, 'string' )
      );
  
      $userdata = Array();
  
      if ($asadmin === TRUE)
      {
        $response = $this->xmlrpc->send_xml_rpc('AdmGetAdminAttributes',$data);
      
        $response = throne_response_parser($response, 'AdmGetAdminAttributes');
      
        if ( is_array ($response) && isset($response['Name']))
        {
          $userdata['name']   = $response['Name'];
          $userdata['username'] = $username;
          $userdata['password'] = $password;
          $userdata['groups']   = Array(1);
          $userdata['role']     = 'admin';
        }
      
      } 
      else
      {
        $response = $this->xmlrpc->send_xml_rpc('GetUserInfo',$data);
      
        $response = throne_response_parser($response, 'GetUserInfo');
      
        if ( is_array ($response) && isset($response['ID']))
        {
          $userdata['name']     = $response['UserAttributes']['Name'] . ' ' . $response['UserAttributes']['Surname'];
          $userdata['username']   = $username;
          $userdata['password']   = $password;
          $userdata['groups']     = Array(2);
          $userdata['Position']   = $response['UserAttributes']['Position'];
          $userdata['Language']   = $response['UserAttributes']['Language'];
          $userdata['throne_id']  = $response['ID'];
          $userdata['group_id']   = $response['GroupID'];
        //
        //////////// TO REMOVE 
          $userdata['role']     = 'user';
          $userdata['group']    = $response['GroupID'];
        //$userdata['active']   = $response['State'];
        }
      }
    
      if( !empty($userdata) )
      {
        return $userdata; 
      }
    
      return FALSE;
    
  }

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

  public function set_error($str, $update_session = TRUE)
  {
    $this->_error = $str;

    if($update_session == TRUE)
    {
      $this->session->set_flashdata('bitauth_error', $this->_error);
    }
  }

  public function log_attempt($user_id, $success = FALSE)
  {
    if($this->_log_logins == TRUE)
    {

      $data = array(
        'ip_address' => ip2long($_SERVER['REMOTE_ADDR']),
        'user_id' => (empty($user_id) ? 0 : $user_id),
        'success' => (int)$success,
        'time' => $this->timestamp()
      );

      return $this->db->insert($this->_table['logins'], $data);
    }

    return TRUE;
  }

  public function logged_in()
  {
    return (bool)$this->session->userdata($this->_cookie_elem_prefix.'username');
  }

  public function logout()
    {
        session_start();
        
        // удаляем данные из сессий для SMF
//var_dump_exit($_SESSION);
        unset($_SESSION['ba_username']);
        unset($_SESSION['ba_password']);
        unset($_SESSION['ba_role']);
        unset($_SESSION['ba_name']);

        unset($_SESSION['logged']);

        if(isset($_SESSION['ba_member_id'])):
            $this->db->query('DELETE FROM `smf_log_online` WHERE `id_member` = ' . $_SESSION['ba_member_id']);
            unset($_SESSION['ba_member_id']);
        endif;

        $this->session->sess_destroy();
        if(isset($_GET['refer_url']) && $_GET['refer_url'] <> ''):
            redirect(lang_root_url('/').'/'.$_GET['refer_url']);
            //echo $_GET['refer_url'];
        else:
            redirect(lang_root_url('/'));
           // redirect($_SERVER['HTTP_REFERER']);
           //echo 'bad';
        endif;

        return;
    }

}