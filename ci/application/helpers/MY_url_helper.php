<?php
/*
 * URL helpers, this file extends
 * the current URL helper that
 * CodeIgniter includes.
*/

/**
 * base_url
 *
 * This function OVERRIDES the current
 * CodeIgniter base_url function to support
 * CDN'ized content.
 */
function base_url($uri = '')
{
   $CI =& get_instance();

   $cdn = $CI->config->item('cdn_url');
   if (!empty($cdn))
      return $cdn . $uri;

   return $CI->config->base_url($uri);
}

/**
 * lang_root
 *
 * This function OVERRIDES the current
 * CodeIgniter base_url function to support
 * CDN'ized content.
 */
function lang_root_url($uri)
{
   $CI =& get_instance();

   $ls = $CI->config->item('language_section');
   
   if (!empty($ls))
      return site_url(array($ls,$uri));

   return $CI->config->base_url($uri);
}

/*
 * is_active
 * Allows a string input that is
 * delimited with "/". If the current
 * params contain what is currently
 * being viewed, it will return true
 *
 * This function is order sensitive.
 * If the page is /view/lab/1 and you put
 * lab/view, this will return false. 
 *
 * @author sjlu
 */
function is_active($input_params = "")
{
   // uri_string is a CodeIgniter function
   $uri_string = uri_string();

   // direct matching, faster than looping.
   if ($uri_string == $input_params)
      return true;
      
   $uri_params = preg_split("/\//", $uri_string);
   $input_params = preg_split("/\//", $input_params);

   $prev_key = -1;
   foreach ($input_params as $param)
   {
      $curr_key = array_search($param, $uri_params);

      // if it doesn't exist, return null
      if ($curr_key === FALSE)
         return false;

      // this makes us order sensitive
      if ($curr_key < $prev_key)
         return false;

      $prev_key = $curr_key;
   }

   return true;
}

/*
 * get_controller()
 * get_function()
 * get_parameters()
 *
 * These functions help split out
 * the three different params in the
 * URL.
 * 
 * The URL is split in such a way where
 * controller/function/parameters[/]...
 */
function get_controller()
{
   $uri_string = uri_string();
   
   if (empty($uri_string))
      return $route['default_controller'];

   return preg_split("/\//", $uri_string, 1);
}

function get_function()
{
   $uri_string = uri_string();
   
   if (empty($uri_string))
      return $route['default_controller'];
  
   $uri_array = preg_split("/\//", $uri_string, 2);

   if (empty($uri_array[1]))
      return 'index';

   return $uri_array[1];
}

if ( ! function_exists('force_ssl'))
{
    function force_ssl()
    {
		$CI =& get_instance();
        if ( ! $CI->input->server('HTTPS'))
        {
			$CI->config->config['base_url'] = $CI->config->config['base_url_absolute_ssl'];
			redirect($CI->uri->uri_string());
        }
    }
}
 
if ( ! function_exists('remove_ssl'))
{
	function remove_ssl()
	{
		$CI =& get_instance();
        if ($CI->input->server('HTTPS'))
		{
			$CI =& get_instance();
			$CI->config->config['base_url'] = $CI->config->config['base_url_absolute'];
			redirect($CI->uri->uri_string());
        }
	}
}


	/**
	 * Генерация ссылок в зависимости от статуса.
	 */
if ( ! function_exists('get_state_link'))
{
	function get_state_link($state , $id, $controller, $prefix)
	{
		
		$CI =& get_instance();
	
		if ($state != 'ACTIVATED')
		{
			return ( $prefix == 'group' ? anchor(lang_root_url($controller . '/' . $prefix . '_activate').'/'.$id,lang('activate'))  . '<br/>' : '' ). 
				   anchor(lang_root_url($controller . '/'. $prefix . '_block').'/'.$id,lang('block')). '<br/>' . 
				   (($id == $CI->session->userdata( 'ba_name' ) || $CI->session->userdata( 'ba_role' ) == 'admin') ? anchor(lang_root_url($controller . '/' . $prefix .'_edit') . '/' . $id, lang('edit')) : '');
		} else
		{
			return anchor(lang_root_url($controller . '/'. $prefix.'_block').'/'.$id,lang('block')). '<br/>' .
			     (($id == $CI->session->userdata( 'ba_name' ) || $CI->session->userdata( 'ba_role' ) == 'admin') ? anchor(lang_root_url($controller . '/' . $prefix . '_edit') . '/' . $id, lang('edit')) : '');
		}
	}
}





	/**
	 * Вывод набора ссылок в зависимости от состояния
	 */
if ( ! function_exists('admin_list_anchors'))
{
	function admin_list_anchors( $status, $row_id, $actions = array() )
	{
		$CI =& get_instance();
		
		$links = '';
		
		for ($i = 0, $c = count($actions); $i < $c; $i++)
		{
			if ($actions[$i]['state'] == $status) continue;
			if ($i > 0 && $actions[$i-1]['state'] != $status) $links .= "<br/>";
			
			$links .= $actions[$i]['anchor'];
			
		}
		
		return $links;
		
	}
}





	/**
	 * Генерация ссылок в зависимости от статуса.
	 */
if ( ! function_exists('admin_list_actions_links'))
{
	function admin_list_actions_links( $status, $row_id, $actions = array(), $cols = 1 )
	{
		$CI =& get_instance();
		
		$links = '';

		$i = 1;
		foreach ($actions as $action)
		{
			if ($action['state'] == $status) continue;
						
			$action['row_id']		 = (isset($action['row_id'])	  ? $action['row_id'] : $row_id);
			$action['before']		 = (isset($action['before'])	  ? $action['before'] : '');
			$action['controller']	 = (isset($action['controller'])  ? $action['controller'] : '');
			$action['method']		 = (isset($action['method'])	  ? $action['method'] : '');
			$action['label']		 = (isset($action['label']) 	  ? lang('ci_base.' . $action['label']) : '');
			$action['title']		 = (isset($action['title']) 	  ? lang('ci_base.' . $action['title']) : '');
			$action['extra']		 = (isset($action['extra']) 	  ? $action['extra'] : '');
			$action['after']		 = (isset($action['after'])	 	  ? $action['after'] : '');
			$action['icon']			 = (isset($action['icon'])	  	  ? '<i class="' . $action['icon'] . '"></i>' : '');
			$action['get']			 = (isset($action['get'])	  	  ? '?'.$action['get'] : '/');
			
			if ($action['title'] != '')
			{
				$action['extra']['title'] = $action['title'];
			} 
			
			$links .= $action['before'] . anchor(
					lang_root_url(
						$action['controller'] . '/' . 
						$action['method'] . 
						$action['get'] . 
						urlencode($action['row_id'] ) . 
						( $action['get'] == "/" ? get_string() : '' ) 
					) , 
					$action['icon'] . $action['label'] , 
					$action['extra']  ) . $action['after'];
			
			if ($i % $cols == 0 ) $links .= "<br/>";
			$i++;
		}
		
		return $links;
		
	}
}


/**
 * Функция для генерации строки GET из GET запроса
 */
function get_string()
{
	$CI     		=& get_instance();
	$get_array		=  $CI->input->get();
	
	$get = '';
	if ( ! empty($get_array) )
	{
		$get = '?';
		foreach ($get_array as $key => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $_key => $_value)
				{
					$get .= $key.'['. $_key . ']=' . $_value . '&';
				}
			} else
			{
				$get .= $key . '=' . $value . '&';
			}
		}
	}
	
	return $get;
}