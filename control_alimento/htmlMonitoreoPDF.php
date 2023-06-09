<?php
require_once "m_almacen.php";
require_once "../funciones/f_funcion.php";

$anioSeleccionado = $_GET['anio'];
$mesSeleccionado = $_GET['mes'];
/*convierte el valor en enetero*/
$mesNumerico = intval($mesSeleccionado);


$mesesEnLetras = array(
    1 => "ENERO",
    2 => "FEBRERO",
    3 => "MARZO",
    4 => "ABRIL",
    5 => "MAYO",
    6 => "JUNIO",
    7 => "JULIO",
    8 => "AGOSTO",
    9 => "SETIEMBRE",
    10 => "OCTUBRE",
    11 => "NOVIEMBRE",
    12 => "DICIEMBRE",
);
$mesConvert = $mesesEnLetras[$mesNumerico];

$mostrar = new m_almacen();
$datos = $mostrar->MostrarInfraestructuraPDF($anioSeleccionado, $mesSeleccionado);
$versionMuestra = $mostrar->VersionMostrar();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="http://192.168.1.102/SISTEMA/control_alimento/images/icon/covifarma-ico.ico" type="images/png">
    <title>COVIFARMA</title>

</head>

<body>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            width: 100%;
        }

        thead th {
            border: 1px solid black;
        }

        tbody td {
            border: 1px solid black;
        }

        .cabecera-fila {
            background-color: #9dcdec;
            text-align: center;
        }

        .cabecera-fila td,
        .cabecera {
            text-align: center;
        }

        .column-1:nth-child(1),
        .column-2:nth-child(2) {
            width: 320px;

        }

        td.estado-vacio {
            background-color: #f2f2f2;

        }

        td.estado-R {
            background-color: #0a5e9c;
            color: #f2f2f2;
            text-align: center;
            height: 30px;

        }

        td.estado-NR {
            background-color: #E72b3c;
            color: #f2f2f2;
            text-align: center;
            height: 30px;
        }

        td.estado-OB {
            background-color: #F39A11;
            color: #f2f2f2;
            text-align: center;
            height: 30px;
        }

        td.estado-PO {
            background-color: #27a121;
            color: #f2f2f2;
            text-align: center;
            height: 30px;
        }

        .mover-derecha {
            padding-left: 20px;
        }

        .ancho {
            padding-left: 10px;
            border: none;
        }

        .borde {
            border-right: 2.4px solid #000;

        }

        td.cabecera-fila {
            width: 30px;
            height: 30px;
        }

        body {
            margin: 50mm 8mm 2mm 8mm;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        /* .tablaSeparada {
            page-break-inside: avoid;
            margin-top: 330px;
        } */
    </style>

    <!-- Table titulo-->
    <header>
        <table>

            <tr>
                <td rowspan="4" class="cabecera"><img src="http://192.168.1.102/SISTEMA/control_alimento/images/logo-covifarmaRecorte.png" alt=""></td>
                <td rowspan="4" style="text-align: center;">MONITOREO DE L & D DE ESTRUCTURAS FISICAS Y ACCESORIOS - MES DE <?php echo ($mesConvert . ' ' . $anioSeleccionado); ?> </td>
                <td>LBS-PHS-FR-01</th>
            </tr>
            <tr>
                <?php foreach ($versionMuestra as $version) { ?>
                    <td>Versión: <?php echo $version['VERSION'] ?> </td>
                <?php
                }
                ?>

            </tr>
            <tr>
                <td>Página:</td>
            </tr>
            <tr>
                <td>Fecha: <?php echo ($mesConvert . ' ' . $anioSeleccionado); ?> </td>
            </tr>

        </table>
    </header>

    <!-- Table calendario-->
    <table style="margin-top: 10px;">
        <tbody>
            <?php


            $grupos = array();

            foreach ($datos as $fila) {
                $nombreZona = $fila['NOMBRE_T_ZONA_AREAS'];
                $nombreInfraestructura = $fila['NOMBRE_INFRAESTRUCTURA'];
                $ndiaspos = $fila['N_DIAS_POS'];
                $estado = $fila['ESTADO'];
                $fechaTotal = $fila['FECHA_TOTAL'];

                if (!isset($grupos[$nombreZona])) {
                    $grupos[$nombreZona] = array();
                }

                $existingIndex = -1;
                foreach ($grupos[$nombreZona] as $index => $value) {
                    if ($value['nombreInfraestructura'] === $nombreInfraestructura) {
                        $existingIndex = $index;
                        break;
                    }
                }

                if ($existingIndex !== -1) {
                    $grupos[$nombreZona][$existingIndex]['estados'][$fechaTotal] = $estado;
                } else {
                    $grupos[$nombreZona][] = array(
                        'nombreInfraestructura' => $nombreInfraestructura,
                        'estados' => array($fechaTotal => $estado),
                        'ndiaspos' => $ndiaspos,
                        'fechaTotal' => $fechaTotal
                    );
                }
            }

            $numeroDiasMe = date('t', strtotime($fechaTotal));
            $columnasFechaTotales = $numeroDiasMe;



            echo '<tr>';
            echo '<td class="cabecera-fila column-1" rowspan="2">Zonas/areas</td>';
            echo '<td class="cabecera-fila column-2" rowspan="2">Infraestructura, accesorios complementarios</td>';
            echo '<td class="cabecera-fila" rowspan="2">Frecuencia</td>';
            echo '<td class="cabecera-fila" colspan="' . $columnasFechaTotales . '">Dias</td>';
            echo '<td class="cabecera-fila" rowspan="2">Responsable de ejecucion</td>';
            echo '</tr>';

            echo '<tr>';

            for ($l = 1; $l <= $columnasFechaTotales; $l++) {
                if ($l == $columnasFechaTotales) {
                    echo '<td class="cabecera-fila borde" style="witdh:100px;">' . $l . '</td>';
                } else {
                    echo '<td class="cabecera-fila" style="witdh:100px;">' . $l . '</td>';
                }
            }

            echo '</tr>';

            $contadorF = 0;
            foreach ($grupos as $nombreZona => $valores) {
                $contadorF++;
                echo '<tr>';
                echo '<td rowspan="' . count($valores) . '">' . $nombreZona . '</td>';

                foreach ($valores as $index => $valor) {
                    if ($index !== 0) {
                        echo '<tr>';
                    }

                    echo '<td class="cabecera">' . $valor['nombreInfraestructura'] . '</td>';
                    if ($valor['ndiaspos'] == 1) {
                        echo '<td class="cabecera">Diaria</td>';
                    } elseif ($valor['ndiaspos'] == 2) {
                        echo '<td class="cabecera">Interdiaria</td>';
                    } elseif ($valor['ndiaspos'] == 7) {
                        echo '<td class="cabecera">Semanal</td>';
                    } elseif ($valor['ndiaspos'] == 15) {
                        echo '<td class="cabecera">Quincenal</td>';
                    } elseif ($valor['ndiaspos'] == 30) {
                        echo '<td class="cabecera">Mensual</td>';
                    } else {
                        echo '<td class="cabecera">' . $valor['ndiaspos'] . '</td>';
                    }

                    // Crear array con columnas de acuerdo a la FECHA_TOTAL
                    $fechaTotal = $valor['fechaTotal'];
                    $numeroDiasMes = date('t', strtotime($fechaTotal));
                    $columnasFechaTotal = $numeroDiasMes;
                    $dias = date('d', strtotime($fechaTotal));
                    $diasConver = intval($dias);

                    $columnas = array();
                    for ($i = 1; $i <= $columnasFechaTotal; $i++) {

                        if ($i == $diasConver) {
                            $columnas[$i] = '';
                        } else {
                            $columnas[$i] = '';
                        }
                    }

                    // Agregar columna en blanco si el mes tiene 30 días
                    // if ($columnasFechaTotal == 30) {
                    //     $columnas[31] = '';
                    // }
                    print_r($valor['estados']);
                    // Asignar los estados a las columnas correspondientes
                    foreach ($valor['estados'] as $fecha => $estado) {
                        $dia = date('d', strtotime($fecha));
                        $diasCon = intval($dia);

                        if (isset($columnas[$diasCon])) {
                            if ($columnas[$diasCon] === '') {
                                $columnas[$diasCon] = $estado;
                            } else {
                                $columnas[$diasCon] .= '' . $estado;
                            }
                        }
                    }

                    // Imprimir los estados en las columnas correspondientes
                    foreach ($columnas as $columna) {
                        $estadoClass = $columna !== '' ? 'estado-' . $columna : 'estado-vacio';
                        echo '<td class="' . $estadoClass . '">' . $columna . '</td>';
                        //echo '<td class="cabecera">' . $columna . '</td>';
                    }
                    //Colocar este td para que rellene de responsable de ejecucion
                    echo '<td></td>';
                    if ($index !== 0) {
                        echo '</tr>';
                    }
                }

                echo '</tr>';
                if ($contadorF % 24 == 0) {
                    echo '<tr>';
                    for ($i = 0; $i < 30; $i++) {
                        echo '<td style="text-align:center;height:10.5rem;border-left:none; border:rght:none;"></td>';
                    }
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Table colores-->
    <table style="margin-top: 50px; border:none;">
        <tbody>
            <tr>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="ancho"></td>
                <td class="estado-R ancho"></td>
                <td class="mover-derecha ancho">L&D realizada</td>
                <td class="estado-NR ancho"></td>
                <td class="mover-derecha ancho">L&D pendiente</td>
                <td class="estado-OB ancho"></td>
                <td class="mover-derecha ancho">L&D observado</td>
                <td class="estado-PO ancho"></td>
                <td class="mover-derecha ancho">L&D postergado</td>
            </tr>
        </tbody>
    </table>
    <!-- Table observaciones-->
    <table style="margin-top: 50px; ">

        <tr>
            <td style="text-align: center; font-weight: 200;">N°</td>
            <td style="text-align: center; font-weight: 200;">Fecha</td>
            <td style="text-align: center; font-weight: 200;">Área/ Zona identificada</td>
            <td style="text-align: center; font-weight: 200;">Hallazgo/ Observación</td>
            <td style="text-align: center; font-weight: 200;">Acción correctiva</td>
            <td style="text-align: center; font-weight: 200;">Verificación realizada</td>
            <td style="text-align: center; font-weight: 200;">V°b°Supervisor</td>
        </tr>

        <tbody>
            <?php
            $contadorN = 0;
            $nContador = 1;

            $fechas = array_column($datos, 'FECHA_TOTAL');
            array_multisort($fechas, SORT_ASC, $datos);


            foreach ($datos as $fils) {
                $contadorN++;
                echo '<tr>';

                echo '<td class="cabecera">' . $nContador . '</td>';
                $nContador++;

                echo '<td class="cabecera">' . convFecSistema($fils['FECHA_TOTAL']) . '</td>';
                echo '<td class="cabecera">' . $fils['NOMBRE_T_ZONA_AREAS'] . '</td>';
                echo '<td class="cabecera">' . $fils['OBSERVACION'] . '</td>';
                echo '<td class="cabecera">' . $fils['ACCION_CORRECTIVA'] . '</td>';
                echo '<td class="cabecera">' . $fils['VERIFICACION_REALIZADA'] . '</td>';
                echo '<td></td>';

                echo '</tr>';
                // if ($contadorN % 15 == 0) {
                //     echo '<tr>';
                //     for ($i = 0; $i < 7; $i++) {
                //         echo '<td style="text-align:center;height:13.5rem; border-left:none; border-right:none;"></td>';
                //     }
                //     echo '</tr>';
                // }
            }
            ?>

        </tbody>
    </table>

    <!-- Table firma y fecha-->
    <table style="margin-top: 50px; border:none;">
        <tr>
            <td style="padding-left: 200px; border:none;"></td>
            <td style="border: none;"></td>
            <td style="padding-left: 400px; border:none;">Fecha:</td>
            <td style="padding-left: 400px; border:none;"></td>
            <td style="padding-left: 800px; border:none;"></td>

        </tr>
        <tr>
            <td style="padding-left: 200px; border:none;"></td>
            <td style="border-left: none; border-bottom:none; border-right: none;">Firma del jefe de Aseguramiento de la calidad</td>
            <td style="padding-left: 400px; border:none;"></td>
            <td style="padding-left: 400px;border-left: none; border-bottom:none; border-right: none;"></td>
            <td style="padding-left: 800px; border:none;"></td>

        </tr>
    </table>
</body>

</html>