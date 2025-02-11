<?php

namespace Controllers;

use Model\Citas;
use Model\CitaServicio;
use Model\Servicio;

class APIController
{
    public static function index()
    {
        $servicios = Servicio::all(); // Consulta a la base de datos

        echo json_encode($servicios); // Devuelve un JSON con los servicios
    }

    public static function guardar()
    {
        // Almacena la cita y devuelve el ID
        $cita = new Citas($_POST);

        $resultado = $cita->guardar(); // Insertamos en la base de datos
        $id = $resultado['id']; // Extraemos el ID de la cita

        // Almacena la cita y el/los servicio(s)

        $idServicios = explode(',', $_POST['servicios']); // Separamos los valores del arreglo de los id de los servicios

        // Iteramos los servicios
        foreach ($idServicios as $idServicio) {
            // Creamos el arreglo para los parametros de CitaServicio
            $args = [
                'citasId' => $id,
                'servicioId' => $idServicio
            ];
            // Creamos el objeto de CitaServicio con los parametros
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar(); // Insertamos en la BD
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $cita = Citas::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
