<?php 
function get_safe_value($str){
	global $conn;
	$str=mysqli_real_escape_string($conn,$str);
	return $str;

}
?>