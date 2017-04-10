<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Convert 20130114T13:34:38 to unix timestamp GMT
 */
function w3c_mktime($w3cdate)
{
	if (preg_match('/([\d]{4})([\d]{2})([\d]{2})T([\d]{2}):([\d]{2}):([\d]{2})/', $w3cdate, $ar_date ))
	{
		return mktime($ar_date[4], $ar_date[5] , $ar_date[6], $ar_date[2], $ar_date[3], $ar_date[1] );
	
	} else 
	{
		log_message("ERROR", "Date is not in the required format!");
		return $w3cdate;
	}
}


/* End of file date_helper.php */
/* Location: ./system/helpers/date_helper.php */