<?php

session_start();

require '../config/database.php';

$id = $conn->real_escape_string($_POST['id']);

$sql = "DELETE FROM pelicula WHERE id = $id";

try{
    if($conn->query($sql)){
        $dir = "posters";
        $info_img = pathinfo($_FILES['poster']['name']);
        $info_img['extension'];
        $poster = $dir . '/' . $id . '.jpg';

        if(file_exists($poster)){
            unlink($poster);
        }
        $_SESSION['color'] = "success";
        $_SESSION['msg'] = "Registro eliminado";
    }else{
        $_SESSION['color'] = "danger";
        $_SESSION['msg'] = "Error al eliminar el registro";
    }
}catch(Exception $e){
    echo "Error de conexión: " . $e->getMessage();
}

header('Location: index.php');

?>