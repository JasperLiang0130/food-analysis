<?php
//connect to mysql
$conn = new mysqli('localhost', 'root', 'Strawberry13579', 'food');

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
// else {
// 	printf("Connect successful!");
// }

function print_o($value){
	return print $value . "<br>";
}
function print_2D($arr_2d){
	foreach ($arr_2d as $arr) {
		foreach ($arr as $value) {
			print $value . ' ';
		}
		print '<br>';
	}
}

?>