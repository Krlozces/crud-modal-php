<?php

session_start();

require '../config/database.php';

$sqlPeliculas = "SELECT p.id, p.nombre, p.descripcion, g.nombre AS genero FROM pelicula AS p INNER JOIN genero AS g ON p.id_genero = g.id";
$peliculas = $conn->query($sqlPeliculas);

$dir = "posters/";

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CRUD Modal</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="conatiner py-3 mx-4">
            <h2 class="text-center">Peliculas</h2>
            <hr>
            <?php if(isset($_SESSION['msg']) && isset($_SESSION['color'])){?>
                <div class="alert alert-<?= $_SESSION['color']; ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['msg']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php 
            unset($_SESSION['msg']);
            unset($_SESSION['color']);
            } ?>
            <div class="row justify-content-end">
                <div class="col-auto">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoModal"><i class="fa-solid fa-circle-plus"></i> Nuevo registro</a>
                </div>
                <form action="" method="post">
                    <label for="campo" class="form-label">Buscar:</label>
                    <input type="text" name="campo" id="campo" class="form-control form-control-sm">
                </form>
            </div>
            <table class="table table-sm table-striped table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Género</th>
                        <th>Poster</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row_pelicula = $peliculas->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row_pelicula['id']; ?></td>
                            <td><?= $row_pelicula['nombre']; ?></td>
                            <td><?= $row_pelicula['descripcion']; ?></td>
                            <td><?= $row_pelicula['genero']; ?></td>
                            <td><img src="<?= $dir . $row_pelicula['id'] . '.jpg?n='.time(); ?>" width="100"></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editaModal" data-bs-id="<?= $row_pelicula['id'];?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>

                                <a href="#" class="btn btn-sm btn-danger"  data-bs-toggle="modal" data-bs-target="#eliminaModal" data-bs-id="<?= $row_pelicula['id'];?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <?php
        $sqlGenero = "SELECT id, nombre FROM genero";
        $generos = $conn->query($sqlGenero);
        ?>

        <?php include 'nuevoModal.php';?>
        <?php $generos->data_seek(0); ?>
        <?php include 'editaModal.php';?>
        <?php include 'eliminaModal.php';?>

        <script async defer>
            let nuevoModal = document.getElementById('nuevoModal');
            let editaModal = document.getElementById('editaModal');
            let eliminaModal = document.getElementById('eliminaModal');

            nuevoModal.addEventListener('.shown.bs.modal', event =>{
                nuevoModal.querySelector('.modal-body #nombre').focus()
            })

            nuevoModal.addEventListener('hide.bs.modal', event =>{
                nuevoModal.querySelector('.modal-body #nombre').value = ""
                nuevoModal.querySelector('.modal-body #descripcion').value = ""
                nuevoModal.querySelector('.modal-body #genero').value = ""
                nuevoModal.querySelector('.modal-body #poster').value = ""
            })

            editaModal.addEventListener('hide.bs.modal', event =>{
                editaModal.querySelector('.modal-body #nombre').value = ""
                editaModal.querySelector('.modal-body #descripcion').value = ""
                editaModal.querySelector('.modal-body #genero').value = ""
                editaModal.querySelector('.modal-body #img_poster').value = ""
                editaModal.querySelector('.modal-body #poster').value = ""
            })

            editaModal.addEventListener('shown.bs.modal', event => {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')

                let inputId = editaModal.querySelector('.modal-body #id')
                let inputNombre = editaModal.querySelector('.modal-body #nombre')
                let inputDescripcion = editaModal.querySelector('.modal-body #descripcion')
                let inputGenero = editaModal.querySelector('.modal-body #genero')
                let poster = editaModal.querySelector('.modal-body #img_poster')
                
                let url = "getPelicula.php"
                let formData = new FormData()
                formData.append('id', id)

                fetch(url, {
                    method:"POST",
                    body: formData
                }).then(response => response.json()).then(data=>{
                    console.log("Datos recibidos:", data);
                    console.log(data);
                    inputId.value = data.id
                    inputNombre.value = data.nombre
                    inputDescripcion.value = data.descripcion
                    inputGenero.value = data.id_genero
                    poster.src = '<?= $dir ?>' + data.id + '.jpg'
                }).catch(err => console.log(err))
            });

            eliminaModal.addEventListener('shown.bs.modal', event =>{
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                eliminaModal.querySelector('.modal-footer #id').value = id
            })
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" async defer></script>
    </body>
    
</html>