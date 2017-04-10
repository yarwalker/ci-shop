<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * form bootstrap Control group
 *
 * @access	public
 */
if ( ! function_exists('form_control_group'))
{
	function form_control_group($inputs = array(array('data' => '', 'value' => '', 'extra' => '')), $label, $hint = '')
	{
	
		$id = isset($inputs[0]['data']['id']) ? $inputs[0]['data']['id'] : 'input_' . (isset($inputs[0]['data']['name']) ? $inputs[0]['data']['name'] : '0');
		
		$output = '<div class="control-group">'.
			form_label($label, $id, array('class'=>'control-label')).
            '<div class="controls">';
		foreach ($inputs as $input)
		{
			$output .= form_input($input['data'], $input['value'] );
		}
		
		if(!empty($hint))
			$output .= '<p class="help-block">'.$hint.'</p>';
		
        $output .= '</div>
          </div>';
		  
		return $output;
	}
}

/**
 * Textarea group
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_textarea_group'))
{
	function form_textarea_group($data = '', $value = '', $label = '', $extra = '')
	{
		$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '40', 'rows' => '10');

		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val  = $value;
			$name = $data;
			$id   = 'textarea_'.$data;
		}
		else
		{
			$val  = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
			$id	  = isset($data['id']) ? $data['id'] : 'textarea_' . (isset($data['name']) ? $data['name'] : '0');
			$name = $data['name'];
		}
		
		$name = (is_array($data)) ? $data['name'] : $data;
		
		return '<div class="control-group">'.
				  form_label($label, $id, array('class'=>'control-label')).
				  '<div class="controls">
				  <textarea '._parse_form_attributes($data, $defaults).$extra.'>'.form_prep($val, $name).'</textarea>
				  </div>
				</div>';
	}
}


/**
 * form errors fetch
 *
 * @access	public
 */
if ( ! function_exists('form_errors_fetch'))
{
	function form_errors_fetch($data)
	{
		if (! is_array($data))
		{
			remove_result_from_session();
			echo  "<div class=\"alert alert-error\">" . $data . '</div>';
			return;
			
		} elseif ( ! empty ($data) )
		{	
			remove_result_from_session();
		}
				
		foreach ($data as $error_type => $err_list)
		{
			if (! empty($err_list))
			{
				echo "<div class=\"alert alert-error\"><h4>" .  lang("ci_error." . $error_type) . '</h4>';
			
				if (! is_array($err_list))
				{
					echo '<br/>' . $err_list;
				} else
				{	
					foreach ($err_list as $error)
					{
						echo '<br/>' . $error;
					}
				}
				echo "</div>";					
			}
		}
	}
}


// ------------------------------------------------------------------------
/* End of file form_helper.php */
/* Location: ./system/helpers/form_helper.php */
