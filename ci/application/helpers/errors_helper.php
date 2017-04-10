<?php

	/**
	 * throne_response_parser
	 * Парсер ошибок трона. 
	 * При возникновении проблем сохраняет в сессию
	 * код ошибки, её текст. И если код ошибки не распознан - отправляет
	 * администрации письмо с описанием.
	 * @param $response
	 * @param $method
	 */
	function throne_response_parser($response = Array(), $method = '')
	{
		if ( ! empty($response['response'] ) && empty($response['error_code']) && $response['sent'] != FALSE )
		{
			if (! is_array($response['response']))
				return Array($response['response']);
				
			return $response['response'];
		}
		
		if ( empty($response['response'] ) && $response['sent'] != FALSE && !isset( $response['error_code']) )
		{
			return Array();
		}
		
		if ( ! empty( $response['message'] ) && ($response['error_code'] == 400 || $response['error_code'] == 1  || $response['error_code'] == 500))
		{
			send_email_with_error($response['message'], $response['error_code'], $method );
			add_message_string(throne_error_by_code(1));
			
		} else
		{
			add_message_string( throne_error_by_code($response['error_code']) );
		}
		
		return throne_error_by_code($response['error_code']);
	}


	/**
	 * error_by_name()
	 * Пулучить ошибку по имени
	 * Выдаёт название ошибки по её названию на английском.
	 * @param $errorname
	 */
	function error_by_name($errorname)
	{
		return lang('ci_errors.' . $errorname);
	}
	
	/**
	 * error_by_code()
	 * Получить ошибку по её коду.
	 * @param $errorcode
	 */
	function error_by_code($errorcode)
	{
		return lang('ci_error.' . $errorcode);
	}
	
	/**
	 * throne_error_by_code()
	 * Получить ошибку трона по её коду.
	 * @param $errorcode
	 */
	function throne_error_by_code($errorcode)
	{
		return error_by_code( 'throne_' . $errorcode );
	}
	
	/**
	 * send_email_with_error()
	 * Отправка e-mail с ошибкой
	 * @param $message
	 * @param $method
	 */
	function send_email_with_error($message, $errorcode, $method)
	{
		$CI =& get_instance();

		$CI->lang	->	load('emails');
		
		$message = 
			lang('emails.hello_admin') . "\n\r" .
			lang('emails.error_date') . " " . date('Y-m-d H:i:s')  . "\n\r" .
			lang('emails.error_method') . " " . $method . "\n\r" .
			lang('emails.error_code') . " " . $errorcode . "\n\r";
			lang('emails.error_message') . "\n\r" . $message . "\n\r";
		
		log_message ("ERROR", $message );
			
		$from = $CI->session->userdata( 'ba_username' ) ? $CI->session->userdata( 'ba_username' ) : $CI->config->item('infoemail');
			
		$CI->load->library	( 'email' );
		$CI->email->from	( $from );
		$CI->email->to		( $CI->config->item('supportemail') );
		$CI->email->subject	( 'Throne website ERRORS' );
		$CI->email->message	( $message );
			
		if ( $CI->email->send() )
		{
			return TRUE;
		} else
		{
			log_message( "debug", $CI->email->print_debugger() );
		}
		return FALSE;
	}
	
	/**
	 * add_message_string()
	 * Добавляем строку ошибки в массив в сессии
	 * @param $string
	 */
	function add_message_string($string)
	{
		$CI 	  =& get_instance();
		$errors   = $CI->session->userdata('error_array') ?   $CI->session->userdata('error_array') : Array();
		$errors[] = $string;
		
		$CI->session->set_userdata('error_array', $errors);
	}
	
	function get_messages_array()
	{
		$CI 	  =& get_instance();
		if ( $CI->session->userdata('error_array') && $CI->session->userdata('error_array') != "" )
		{
			return $CI->session->userdata('error_array');
		}
		return FALSE;
	}
	
	/**
	 * remove_error_string()
	 * Удаляем строки ошибок из массива в сессии
	 */
	function remove_result_from_session()
	{
		$CI 	  =& get_instance();
		$CI->session->set_userdata('error_array', '');
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $result
	 */
	function show_result_in_modal_window($messages = Array())
	{
		$CI 	  =&  get_instance();
		remove_result_from_session();
		
		$result = '';
		
		if (is_array($messages))
		{
			foreach ($messages as $message)
			{
				$result .= $message . "<br/>\n\r";
			}
		} else {
			$result = $messages;
		}
		
		$data['result'] = $result;
		return $CI->load->view('modal/result', $data, TRUE);
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	function show_result_on_result_page()
	{
		redirect (lang_root_url('resultpage'));
	}
	
	
?>