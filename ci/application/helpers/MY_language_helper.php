<?php

if ( ! function_exists('current_lang'))
{
	function current_lang ()
	{
		$CI =& get_instance();
		return $CI->config->item('language');
	}
	
}

	function language_links($delimiter)
	{
		$CI     		=& get_instance();
		$l_array 		=  $CI->config->item('languages');
		$get_array		=  $CI->input->get();
		$result  		=  '';
		
		$get = get_string();
		
		$url = explode("/", uri_string());
		if (isset($l_array[$url[0]]) && $l_array[$url[0]] == current_lang())
			unset($url[0]);
		
		$url = implode("/", $url );
		
		$i = 0;
		foreach($l_array as $lang => $value)
		{
			$result .= "<" . $delimiter . ">";
			if ($value == current_lang())
			{
				$result .= "<span>" . lang('ci_base.' . $lang) . "</span>";
			} else
			{
				$result .= "<a href=\"" . base_url($lang . "/" . $url) . $get ."\">" . lang('ci_base.' . $lang) . "</a>";
			}
			$result .= 	"</" . $delimiter . ">";
			$i++;
		}
		return $result;
	}

    /*function language_forum_links($delimiter)
    {
        $CI     		=& get_instance();
        $l_array 		=  $CI->config->item('languages');
        $get_array		=  $CI->input->get();
        $result  		=  '';

        $get = get_string();

        $url = explode("/", $_SERVER['REQUEST_URI']);
        if (isset($l_array[$url[0]]) && $l_array[$url[0]] == current_lang())
            unset($url[0]);

        $url = implode("/", $url );

        $i = 0;
        foreach($l_array as $lang => $value)
        {
            $result .= "<" . $delimiter . ">";
            if ($value == current_lang())
            {
                $result .= "<span>" . lang('ci_base.' . $lang) . "</span>";
            } else
            {
                $result .= "<a href=\"" . base_url($lang . "/" . $url) . $get ."\" data-lang=\"" . (($lang == 'ru') ? 'russian-utf8' : 'english-utf8') . "\">" . lang('ci_base.' . $lang) . "</a>";
            }
            $result .= 	"</" . $delimiter . ">";
            $i++;
        }
       // return $result;
        return $_SERVER['REQUEST_URI'];
    }*/
	
	function language_code ()
	{
		$CI     		=& get_instance();
		$l_array 		=  $CI->config->item('languages');
		
		$url = explode("/", uri_string());
		if (isset($l_array[$url[0]]))
		{
			return $url[0];
		}
		else
		{
			return array_search(current_lang(),$l_array);
		}
	}
	
	function no_lang_segment($segment)
	{
		$CI				=& get_instance();
		$l_array 		=  $CI->config->item('languages');
		$l_segment		=  $CI->uri->segment(1);
		
		if (isset( $l_array[$l_segment]))
		{
			return $CI->uri->segment($segment + 1);
		} else 
		{
			return $CI->uri->segment($segment);
		}
	
	}

	function no_lang_rsegment($segment)
	{
		$CI				=& get_instance();
		$l_array 		=  $CI->config->item('languages');
		$l_segment		=  $CI->uri->rsegment(1);
		
		if (isset( $l_array[$l_segment]))
		{
			return $CI->uri->rsegment($segment + 1);
		} else 
		{
			return $CI->uri->rsegment($segment);
		}
	
	}

?>