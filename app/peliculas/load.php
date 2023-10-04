<?php
require '../config/database.php';

$columns = ['id', 'nombre', 'descripcion', 'id_genero'];
$table = "pelicula";

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']):null;

#$campo = $conn->real_escape_string($_POST['campo']) ?? null;

$sql = "SELECT " . implode(", ", $columns) . "FROM $table";

$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

$html = '';

/*if($num_rows > 0){
    while()
}*/

?>