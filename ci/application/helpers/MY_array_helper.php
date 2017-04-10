<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
function sort_array($array,$by,$order = "ASC")
{
	if ($order == "ASC")
	{
		usort($array, function($first, $second) use( $by ) { 
		if ($first[$by]>$second[$by]) { return 1; } 
		elseif ($first[$by]<$second[$by]) { return -1; } 
		return 0; 
		}); 
	} elseif ($order == "DESC")
	{
		usort($array, function($first, $second) use( $by ) { 
		if ($first[$by]<$second[$by]) { return 1; } 
		elseif ($first[$by]>$second[$by]) { return -1; } 
		return 0; 
		}); 
	}
		
	return($array); 
}



function filter_array($haystack,$key,$condition)
{
	
	if (!is_array($haystack))
		return;
	
	if ($condition['min']['val'] == 'ALL')
		return $haystack;
	
	$found = array();

	if (is_array($haystack))
	{
		if (array_key_exists($key, $haystack))
		{
			if (isset($condition['min'])){
				if (isset($condition['max'])){
					if ($condition['min']['oper'] == '>' and $condition['max']['oper'] == '<' ) {
						if ($haystack[$key] > $condition['min']['val'] and  $haystack[$key] < $condition['max']['val'])
							$found[] = $haystack;
					} elseif ($condition['min']['oper'] == '>=' and $condition['max']['oper'] == '<=' ) {
						if ($haystack[$key] >= $condition['min']['val'] and  $haystack[$key] <= $condition['max']['val'])
							$found[] = $haystack;
					} elseif ($condition['min']['oper'] == '>' and $condition['max']['oper'] == '<=' ) {
						if ($haystack[$key] > $condition['min']['val'] and  $haystack[$key] <= $condition['max']['val'])
							$found[] = $haystack;
					} elseif ($condition['min']['oper'] == '>=' and $condition['max']['oper'] == '<' ) {
						if ($haystack[$key] >= $condition['min']['val'] and  $haystack[$key] < $condition['max']['val'])
							$found[] = $haystack;
					}
				} else
				{
					if ($condition['min']['oper'] == '=') {
						if ($haystack[$key] == $condition['min']['val'])
							$found[] = $haystack;
					}
				}
			}
		} else {
			foreach ($haystack as $value)
			{
				$found = array_merge($found,filter_array($value,$key,$condition));
			}
		}
	}
	
	return $found;
}

*/

function filter_keys ($haystack = array(), $keys = array())
{
	$result = Array();
	
	foreach ($keys as $temp => $key)
	{
		if (array_key_exists($key, $haystack))
			$result[$key] = $haystack[$key];
	}
	
	if (empty($result))
		return $haystack;
	
	return $result; 
}

?>