<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * XML-RPC request handler class
 *
 * Adds cURL support for CI XML-RPC
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	XML-RPC
 * @author		Partikule (www.partikule.com)
 *
 */
class MY_Xmlrpc extends CI_Xmlrpc
{
	// Use cURL or not..
	protected $curl = FALSE;

	public $CI;

	public function __construct($config = array())
	{
		parent::__construct($config = array());

		$this->CI =& get_instance();

	}
	
	/**
	 * �������� ����� ��������, ����� ������������ � http(s)
	 */
	public function server($url, $port=80)
	{
		if (substr($url, 0, 4) != "http")
		{
			$url = "http://".$url;
		}

		$parts = parse_url($url);

		$path = ( ! isset($parts['path'])) ? '/' : $parts['path'];

		if (isset($parts['query']) && $parts['query'] != '')
		{
			$path .= '?'.$parts['query'];
		}

		$this->client = new MY_XML_RPC_Client($path, $parts['host'], $port);
		$this->client->curl =  $this->curl;
	}
	
	public function send_request()
	{
		log_message('debug', 'curl : ' . $this->curl);

		$this->message = new MY_XML_RPC_Message($this->method,$this->data);
		$this->message->debug = $this->debug;
		$this->message->curl =  $this->curl;

		if ( ! $this->result = $this->client->send($this->message))
		{
			$this->error = $this->result->errstr;
			return FALSE;
		}
		elseif( ! is_object($this->result->val))
		{
			$this->error = $this->result->errstr;
			return FALSE;
		}
		
		$this->response = $this->result->decode();

		return TRUE;
	}
	
   /**
    * ������� ��� �������� XML RPC ������ �� ������ T-Server
	* void SendUserAuthorizationCode(string groupPassword, string userLogin)
	*/
   public function send_xml_rpc($method, $data)
   {
		
		$this->method($method);
		
		$this->request($data);

		log_message('debug', "XML DATA REQUEST");
		
		if ($this->send_request()) {

		log_message('debug', "XML DATA RECIEVED");
		
			return array('sent'=>true,'response'=>$this->display_response());
		}
		else {
			return array('sent'=>false,  'response'=>$this->display_response(), 'message'=>$this->display_error(), 'error' => lang('ci_error.throne_' . $this->result->faultCode() ), 'error_code' => $this->result->faultCode());
		}
   }
}


class MY_XML_RPC_Client extends XML_RPC_Client
{
	public function __construct($path, $server, $port=80)
	{
		parent::__construct($path, $server, $port);

		$this->CI =& get_instance();
	}


	function sendPayload($msg)
	{
		if(empty($msg->payload))
		{
			# $msg = XML_RPC_Messages
			$msg->createPayload();
		}

		if ($this->curl == TRUE)
		{
			$fp = curl_init();
			
			# ������ �������� CURL;
			if ( ! $fp)
			{
				log_message($this->xmlrpcstr['http_error']);
				$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'],$this->xmlrpcstr['http_error']);
				return $r;
			}
			
			# ���������� �������� http ��� https
			if( $this->CI->config->item('protocol') ==  'https')
			{
				$protocol = 'https://';
				curl_setopt( $fp, CURLOPT_SSL_VERIFYPEER, 0 );
				curl_setopt( $fp, CURLOPT_SSL_VERIFYHOST, 0 );
			}
			else
			{
				$protocol = 'http://';
			}
		
			# ������������� �������� ��� CURL
			curl_setopt($fp, CURLOPT_URL, $protocol . $this->server . $this->path);
			curl_setopt($fp, CURLOPT_PORT, $this->port);
			curl_setopt($fp, CURLOPT_HEADER, 1);
			curl_setopt($fp, CURLOPT_HTTP_VERSION, 1.0);
			curl_setopt($fp, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($fp, CURLOPT_POST, 1);
			curl_setopt($fp, CURLOPT_TIMEOUT, 60);
			curl_setopt($fp, CURLOPT_CONNECTTIMEOUT, 60);
			curl_setopt($fp, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($fp, CURLOPT_POSTFIELDS, $msg->payload);
			curl_setopt($fp, CURLOPT_RETURNTRANSFER, TRUE);
			
			if ($this->debug === TRUE)
			{
				log_message("DEBUG", "SENDING REQUEST TO REMOTE SERVER");
				ob_start();
				print_r($msg->payload);
				$log = ob_get_contents();
				ob_end_clean();
				log_message("DEBUG", $log);
			}
			
			# ����� � �������� ����
			$resp = curl_exec($fp);
			curl_close($fp);

			# ����� �� ������ ������
			if ($resp === FALSE)
			{
				log_message('error',$this->xmlrpcstr['http_error']);
				
				$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'], $this->xmlrpcstr['http_error']);
				return $r;
			}
			
			# ������ �����
			$resp = $msg->parseResponse($resp);
		}
		else
		{
			$fp = @fsockopen($this->server, $this->port,$this->errno, $this->errstr, $this->timeout);
			if ( ! is_resource($fp))
			{
				log_message('error',$this->xmlrpcstr['http_error']);
				
				$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'],$this->xmlrpcstr['http_error']);
				return $r;
			}
			
			$r = "\r\n";
			$op  = "POST {$this->path} HTTP/1.0$r";
			$op .= "Host: {$this->server}$r";
			$op .= "Content-Type: text/xml$r";
			$op .= "User-Agent: {$this->xmlrpcName}$r";
			$op .= "Content-Length: ".strlen("<?xml version=\"1.0\"?".">\r\n".$msg->payload). "$r$r";
			$op .= "<?xml version=\"1.0\"?".">\r\n".$msg->payload;
	
			if ( ! fputs($fp, $op, strlen($op)))
			{
				log_message('error',$this->xmlrpcstr['http_error']);
				$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'], $this->xmlrpcstr['http_error']);
				return $r;
			}
			$resp = $msg->parseResponse($fp);
			fclose($fp);

		}		
		
		return $resp;
	}
}


class MY_XML_RPC_Message extends XML_RPC_Message
{

	var $valid_parents = array();

	public function __construct($method, $pars=0)
	{
		parent::__construct($method, $pars);

		$this->valid_parents['NIL'] = array('VALUE');
		
		$this->CI =& get_instance();
		
		log_message('debug', "XML DATA PARSING");
	}
	
	//-------------------------------------
	//  Create Payload to Send
	//-------------------------------------

	function createPayload()
	{
		$this->payload = "<methodCall>\r\n";
		$this->payload .= '<methodName>' . $this->method_name . "</methodName>\r\n";
		$this->payload .= "<params>\r\n";

		for ($i=0; $i<count($this->params); $i++)
		{
			// $p = XML_RPC_Values
			$p = $this->params[$i];
			$this->payload .= "<param>\r\n".$p->serialize_class()."</param>\r\n";
		}

		$this->payload .= "</params>\r\n</methodCall>\r\n";
	}
	
	function parseResponse($fp)
	{
		$data = '';
		
		if ($this->curl == FALSE)
		{
			while($datum = fread($fp, 4096))
			{
				$data .= $datum;
			}
		}
		else
		{
			$data = $fp;
		}

		//-------------------------------------
		//  DISPLAY HTTP CONTENT for DEBUGGING
		//-------------------------------------
		
		if ($this->debug === TRUE)
		{
			ob_start();
			
			echo "<pre>";
			//echo "---DATA---\n" . htmlspecialchars($data) . "\n---END DATA---\n\n";
			echo "---DATA---\n" . $data . "\n---END DATA---\n\n";
			echo "</pre>";
			
			$log = ob_get_contents();
			
			ob_end_clean();
			
			log_message("DEBUG", $log);
			
			
		}
		
		//-------------------------------------
		//  Check for data
		//-------------------------------------

		if($data == "")
		{
			log_message('error',$this->xmlrpcstr['no_data']);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['no_data'], $this->xmlrpcstr['no_data']);
			return $r;
		}
		
		
		//-------------------------------------
		//  Check for HTTP 200 Response
		//-------------------------------------
		
		if (strncmp($data, 'HTTP', 4) == 0 && ! preg_match('/^HTTP\/[0-9\.]+ 200 /', $data))
		{
			$errstr= substr($data, 0, strpos($data, "\n")-1);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['http_error'], $this->xmlrpcstr['http_error']. ' (' . $errstr . ')');
			return $r;
		}
		
		//-------------------------------------
		//  Create and Set Up XML Parser
		//-------------------------------------
	
		$parser = xml_parser_create($this->xmlrpc_defencoding);

		$this->xh[$parser]				 = array();
		$this->xh[$parser]['isf']		 = 0;
		$this->xh[$parser]['ac']		 = '';
		$this->xh[$parser]['headers'] 	 = array();
		$this->xh[$parser]['stack']		 = array();
		$this->xh[$parser]['valuestack'] = array();
		$this->xh[$parser]['isf_reason'] = 0;

		xml_set_object($parser, $this);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($parser, 'open_tag', 'closing_tag');
		xml_set_character_data_handler($parser, 'character_data');
		//xml_set_default_handler($parser, 'default_handler');


		//-------------------------------------
		//  GET HEADERS
		//-------------------------------------
		$lines = explode("\r\n", $data);
		while (($line = array_shift($lines)))
		{
			if (strlen($line) < 1)
			{
				break;
			}
			$this->xh[$parser]['headers'][] = $line;
		}
		$data = implode("\r\n", $lines);
		
		
		//-------------------------------------
		//  PARSE XML DATA
		//-------------------------------------  	
		if ( ! xml_parse($parser, $data, count($data)))
		{
			$errstr = sprintf('XML error: %s at line %d',
					xml_error_string(xml_get_error_code($parser)),
					xml_get_current_line_number($parser));
			//log_message($errstr);
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'], $this->xmlrpcstr['invalid_return']);
			xml_parser_free($parser);
			return $r;
		}
		xml_parser_free($parser);
		
		// ---------------------------------------
		//  Got Ourselves Some Badness, It Seems
		// ---------------------------------------
		
		if ($this->xh[$parser]['isf'] > 1)
		{
			if ($this->debug === TRUE)
			{
				echo "---Invalid Return---\n";
				echo $this->xh[$parser]['isf_reason'];
				echo "---Invalid Return---\n\n";
			}
				
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'],$this->xmlrpcstr['invalid_return'].' '.$this->xh[$parser]['isf_reason']);
			return $r;
		}
		elseif ( ! is_object($this->xh[$parser]['value']))
		{
			$r = new XML_RPC_Response(0, $this->xmlrpcerr['invalid_return'],$this->xmlrpcstr['invalid_return'].' '.$this->xh[$parser]['isf_reason']);
			return $r;
		}
		
		//-------------------------------------
		//  DISPLAY XML CONTENT for DEBUGGING
		//-------------------------------------  	
		
		if ($this->debug === TRUE)
		{
			
			ob_start();
			
			echo "<pre>";
			
			if (count($this->xh[$parser]['headers'] > 0))
			{
				echo "---HEADERS---\n";
				foreach ($this->xh[$parser]['headers'] as $header)
				{
					echo "$header\n";
				}
				echo "---END HEADERS---\n\n";
			}
			
			//echo "---DATA---\n" . htmlspecialchars($data) . "\n---END DATA---\n\n";
			echo "---DATA---\n" . $data . "\n---END DATA---\n\n";
			
			//echo "---PARSED---\n" ;
			//var_dump($this->xh[$parser]['value']);
			//echo "\n---END PARSED---</pre>";
			
			$log = ob_get_contents();
			ob_end_clean();
			
			log_message( "DEBUG", $log );
			
		}
		
		//-------------------------------------
		//  SEND RESPONSE
		//-------------------------------------
		
		$v = $this->xh[$parser]['value'];
			
		if ($this->xh[$parser]['isf'])
		{
			$errno_v = $v->me['struct']['faultCode'];
			$errstr_v = $v->me['struct']['faultString'];
			$errno = $errno_v->scalarval();

			if ($errno == 0)
			{
				// FAULT returned, errno needs to reflect that
				$errno = -1;
			}

			$r = new XML_RPC_Response($v, $errno, $errstr_v->scalarval());
		}
		else
		{
			$r = new XML_RPC_Response($v);
		}

		$r->headers = $this->xh[$parser]['headers'];
		return $r;
	}
	
	
	// ------------------------------------
	//  Begin Return Message Parsing section
	// ------------------------------------

	// quick explanation of components:
	//   ac - used to accumulate values
	//   isf - used to indicate a fault
	//   lv - used to indicate "looking for a value": implements
	//		the logic to allow values with no types to be strings
	//   params - used to store parameters in method calls
	//   method - used to store method name
	//	 stack - array with parent tree of the xml element,
	//			 used to validate the nesting of elements

	//-------------------------------------
	//  Start Element Handler
	//-------------------------------------

	function open_tag($the_parser, $name, $attrs)
	{
		// If invalid nesting, then return
		if ($this->xh[$the_parser]['isf'] > 1) return;

		// Evaluate and check for correct nesting of XML elements

		if (count($this->xh[$the_parser]['stack']) == 0)
		{
			if ($name != 'METHODRESPONSE' && $name != 'METHODCALL')
			{
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = 'Top level XML-RPC element is missing';
				return;
			}
		}
		else
		{
			// not top level element: see if parent is OK
			if ( ! in_array($this->xh[$the_parser]['stack'][0], $this->valid_parents[$name], TRUE))
			{
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = "XML-RPC element $name cannot be child of ".$this->xh[$the_parser]['stack'][0];
				return;
			}
		}

		switch($name)
		{
			case 'STRUCT':
			case 'ARRAY':
				// Creates array for child elements

				$cur_val = array('value' => array(),
								 'type'	 => $name);

				array_unshift($this->xh[$the_parser]['valuestack'], $cur_val);
			break;
			case 'METHODNAME':
			case 'NAME':
				$this->xh[$the_parser]['ac'] = '';
			break;
			case 'FAULT':
				$this->xh[$the_parser]['isf'] = 1;
			break;
			case 'PARAM':
				$this->xh[$the_parser]['value'] = NULL;
			break;
			case 'VALUE':
				$this->xh[$the_parser]['vt'] = 'value';
				$this->xh[$the_parser]['ac'] = '';
				$this->xh[$the_parser]['lv'] = 1;
			break;
			case 'I4':
			case 'INT':
			case 'STRING':
			case 'BOOLEAN':
			case 'DOUBLE':
			case 'DATETIME.ISO8601':
			case 'BASE64':
				if ($this->xh[$the_parser]['vt'] != 'value')
				{
					//two data elements inside a value: an error occurred!
					$this->xh[$the_parser]['isf'] = 2;
					$this->xh[$the_parser]['isf_reason'] = "'Twas a $name element following a ".$this->xh[$the_parser]['vt']." element inside a single value";
					return;
				}

				$this->xh[$the_parser]['ac'] = '';
			break;
			case 'MEMBER':
				// Set name of <member> to nothing to prevent errors later if no <name> is found
				$this->xh[$the_parser]['valuestack'][0]['name'] = '';

				// Set NULL value to check to see if value passed for this param/member
				$this->xh[$the_parser]['value'] = NULL;
			break;
			case 'DATA':
			case 'METHODCALL':
			case 'NIL':
			case 'METHODRESPONSE':
			case 'PARAMS':
				// valid elements that add little to processing
			break;
			default:
				/// An Invalid Element is Found, so we have trouble
				$this->xh[$the_parser]['isf'] = 2;
				$this->xh[$the_parser]['isf_reason'] = "Invalid XML-RPC element found: $name";
			break;
		}

		// Add current element name to stack, to allow validation of nesting
		array_unshift($this->xh[$the_parser]['stack'], $name);

		if ($name != 'VALUE') $this->xh[$the_parser]['lv'] = 0;
	}
	// END
}

/* End of file My_Xmlrpc.php */
