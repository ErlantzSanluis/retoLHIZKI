<?php
require_once __DIR__ . '/../model/AccesoBD.php';

function mostrarOpcionesCentros(){
    $bd = new AccesoBD();
    $centros = $bd->obtenerCentros();

    foreach($centros as $centro){
        echo '<option value="' . $centro['id_centro'] . '">' . htmlspecialchars($centro['nombre_centro']) . '</option>';
    }
}

function mostrarOpcionesCiclos(){
    $bd = new AccesoBD();
    $ciclos = $bd->obtenerCiclos();

    foreach($ciclos as $ciclo){
        echo '<option value="' . $ciclo['id_ciclo'] . '">' . htmlspecialchars($ciclo['nombre_ciclo']) . '</option>';
    }
}

function mostrarUsuarios(){
    $bd = new AccesoBD();
    $usuarios = $bd->obtenerUsuarios();

    return $usuarios;
}

//hola



?>