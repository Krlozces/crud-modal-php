<?php

$conn = new mysqli("localhost", "root", "", "cinema");
mysqli_set_charset($conn, "utf8mb4");
if($conn->connect_error){
    die("Error de conexión ". $conn->connect_error);
}

?>