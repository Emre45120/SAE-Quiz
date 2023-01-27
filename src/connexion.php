<?php>

$host = 'host_name'; 
$user = 'user_name'; 
$password = 'password'; 
$dbname = 'database_name'; 

// établir la connexion
$conn = mysqli_connect($host, $user, $password, $dbname);

// vérifier la connexion
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
print "Connected successfully";

?>