<?php
/**
 * Created by PhpStorm.
 * User: Abid
 * Date: 01-Oct-18
 * Time: 5:25 PM
 */


if(! function_exists( 'var_dump_die')){
	function var_dump_die($var){

		echo "<pre>";
		var_dump( $var);
		echo "</pre>";
		die();


	}
}