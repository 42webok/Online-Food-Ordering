<?php
// to print array without stoping execution of the script
function pr($arr){
	echo '<pre>';
	print_r($arr);
}

// to print array with stoping execution of the script
function prx($arr){
	echo '<pre>';
	print_r($arr);
	die();
}

//  to protect the values from sql attacks
function get_safe_value($str){
	global $con;
	$str=mysqli_real_escape_string($con,$str);
	return $str;

}

// to open any other link 
function redirect($link){
	?>
<script>
window.location.href = '<?php echo $link?>';
</script>
<?php
	die();
}
?>