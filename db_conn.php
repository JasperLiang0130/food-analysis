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

?>