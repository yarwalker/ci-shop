<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function var_dump_print($var)
{
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function var_dump_exit($var)
{
	echo "<pre>";
	print_r($var);
	echo "</pre>";
	exit();
}
