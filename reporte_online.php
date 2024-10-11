<?php
/*session_start();
ini_set ('error_reporting', E_ALL);
ini_set ('display_errors', 1);
include_once '/var/www/SITIOS/apps/aula_virtual/variables_globales.php';
include_once '/var/www/SITIOS/apps/aula_virtual/negocios/General.php';
include_once '/var/www/SITIOS/apps/aula_virtual/negocios/Sakai.php';
include_once FUNCIONES;
include_once SEGURIDAD;
include_once POST;
include_once GET;
include_once DOMPDF;*/

/*$seguridad = new seguridad();*/
/*$orades2 = new General();
$asignaturas = array();
if(!empty($pks)){
    $pks = explode('=',base64_decode($pks));
    $pk = str_replace('||','',$pks[1]);
    if(is_numeric($pk)){
        $orades2->ObtenerAsignaturaDelDecreto2($pk);
        if($orades2->getFilas()){
            for($i=0;$i<$orades2->getFilas();$i++){
                $orades2->getSet($i);
                $asignaturas[] = array( 'anhoproc' => $orades2->getANHOPROC(),
                                        'semestre' => $orades2->getSEMESTRE(),
                                        'kcodasig' => $orades2->getKCODASIG(),
                                        'paralelo' => $orades2->getPARALELO(),
                                        'knumerut' => $orades2->getKNUMERUT()
                );
            }
        }else{
            die('El siguiente reporte solo es necesario para convenios de honorarios que tengan asociadas actividades curriculares. En este caso el convenio consultado no tiene asociadas actividades curriculares.');
        }
    }else{
        //envia_mail('Reporte de virtualizacion en GEDO UNAP portal2', array('jcaruncho@unap.cl','enzfigue@unap.cl','dvasquez@unap.cl'),'Variable PKS con errores', json_encode($_SESSION), '', 'no-reply@unap.cl', null);
        die('Solicitud no encontrada...');
    }
}

if(!empty($formulario)){
    $asignaturas[] = array( 'anhoproc' => $anhoproc,
                            'semestre' => $semestre,
                            'kcodasig' => $kcodasig,
                            'paralelo' => $paralelo,
                            'knumerut' => $knumerut
    );
}

if(!empty($kcode)){
    $kcode = $seguridad->decode($kcode);
    if(substr($kcode, 0, 2)==='$$'){
        $kcode = explode('||', $kcode);
        if(!empty($kcode[5])){
            $asignaturas[] = array( 'anhoproc' => $kcode[1],
                'semestre' => $kcode[2],
                'kcodasig' => $kcode[3],
                'paralelo' => $kcode[4],
                'knumerut' => $kcode[5]
            );
        }else{
            die('No se encuentra el usuario...');
        }
    }else{
        die('Error al enviar los datos de la actividad curricular...');
    }
}

if(empty($asignaturas)){
    //envia_mail('Reporte de virtualizacion en GEDO UNAP portal2', array('jcaruncho@unap.cl','enzfigue@unap.cl','dvasquez@unap.cl'),'Entrada registrada sin datos encontrados y sin la variable PKS', json_encode($_SESSION), '', 'no-reply@unap.cl', null);
    die('Reporte en desarrollo...');
}

use Dompdf\Dompdf;
use Dompdf\Options;

$numero_hojas = count($asignaturas);
$hojas = 0;
ob_start();*/
// Asegúrate de tener instalado Dompdf y de cargarlo correctamente.
require 'vendor/autoload.php'; // Ruta a tu archivo autoload de Composer.

use Dompdf\Dompdf;
use Dompdf\Options;


// Función para convertir imagen a base64
function imageToBase64($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}


// Configuración de Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // Habilitar imágenes remotas si las usas.
$options->set('defaultFont', 'Helvetica');



// Inicialización de Dompdf
$dompdf = new Dompdf($options);

// Definir las rutas de las imágenes
$checkImagePath = 'presentacion/img/check_t.png';
$timesImagePath = 'presentacion/img/times_t.png';
$unapImagePath = 'presentacion/img/sello_unaponline_celeste.png';



// Convertir imágenes a base64
$checkImageBase64 = imageToBase64($checkImagePath);
$timesImageBase64 = imageToBase64($timesImagePath);
$unapImagePath = imageToBase64($unapImagePath);

// Generar el contenido HTML
ob_start();
$date = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            /* Estilos CSS para el PDF */
            body {
                font-size: 10px;
                font-family: 'Helvetica';
            }
            /*#pdf-container {
                padding: 20px 40px;
            }*/
            .text-center {
                text-align: center;
            }
            .text-left {
                text-align: left;
            }
            /*.img-logo {
                max-width: 160px;
            }*/
            table {
                width: 104%;
                border-collapse: collapse;
            }
            #tabla-asistencia-pdf th {
                text-align: center;
                font-family: 'Helvetica';
            }
            #tabla-asistencia-pdf th, #tabla-asistencia-pdf td {
                padding: 4px;
                font-size: 9px;
                border: 1px solid #ddd;
            }
            #tabla-asistencia-pdf tr:nth-child(even) {
                background-color: #d6eaf8;
            }
            #tabla-asistencia-pdf thead {
                background-color: #2980b9;
                color: #fff;
            }
            tr{
                font-size: 10px;
            }
            td{
                font-size: 10px;
            }
            
        </style>
    </head>
    <body>
        <div id="pdf-container">
    		<table class="text-center" >
    			<tr>
    				<td>
                    <img src="<?php echo $unapImagePath; ?>" width="180px" height="36px" style="display: block; margin: 0 auto;">
                    <br>
                    <span style="font-size: 20px; display: block; text-align: center;"><strong>Seguimiento de Clases Online</strong></span>
    				</td>
    			</tr>
    		</table>
    		<table>
    			<tr>
    				<td class="text-left">
    				</td>
    			</tr>
    		</table>
            <br></br>
    		<table style="margin-bottom: 6px;">
    			<tr>
    				<td class="text-left"><b>Act. Curricular:</b> [<?=$kcodasig.'] '.$nombre_asignatura?></td>
    				<td class="text-left"><b>Paralelo:</b> <?=$paralelo?></td>    
    				<td class="text-left"><b>Periodo:</b> <?=$anhoproc?>-<?=$semestre?></td>				
    			</tr>
    		</table>    	
    		<table style="margin-bottom: 6px;">
    			<tr>
    				<td class="text-left">
    					Carrera: <?=$carreras?>
    				</td>
    			</tr>
    			<tr>
    				<td class="text-left">
    					Fecha de generación: <?=$date?>
    				</td>    				
    			</tr>
    		</table>
    		<?php 
    		if($filas){}
    		?>
    		<table>
    			<tr>
    				<th class="text-left">
    					<p>LISTADO DE DIAS CORRESPONDIENTES A LA GUIA ACADEMICA </p><br>
    				</th>
    			</tr>
    		</table>	
    		<table id="tabla-asistencia-pdf"  class="table table-bordered">
            </table>
            <?php
// Inicializar $saved_data fuera del bloque POST
$saved_data = isset($_POST['saved_data']) ? json_decode($_POST['saved_data'], true) : [];

// Asegurarse de que $saved_data sea siempre un array
if (!is_array($saved_data)) {
    $saved_data = [];
}

// Definir los nombres de los checkbox que se mostrarán
$checkbox_options = [
    "Guiar y supervisar el trabajo del estudiante",
    "Facilitar y promover la reflexión del estudiante",
    "Retroalimentar al estudiante en sus consultas",
    "Retroalimentar al estudiantes sus actividades",
    "Atender consultas",
    "Realizar videoconferencia/Atención virtual/Grabar video",
    "Realizar seguimiento diario de los estudiante",
    "Corrección de exámenes",
    "Registro de notas"
];

echo '<table id="tabla-asistencia-pdf" class="table table-bordered">
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>';
foreach ($checkbox_options as $option) {
    echo '<th>' . $option . '</th>';
}
echo '</tr></thead>
        <tbody>';

if (!empty($saved_data)) {
    foreach ($saved_data as $index => $entry) {
        echo '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . $entry['date'] . '</td>';
        
        foreach ($checkbox_options as $option) {
            if (in_array($option, $entry['checkboxes'])) {
                echo '<td><img src="' . $checkImageBase64 . '" alt="Marcado" style="width: 10px; height: 10px;"></td>';
            } else {
                echo '<td><img src="' . $timesImageBase64 . '" alt="No Marcado" style="width: 10px; height: 10px;"></td>';
            }
        }
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="' . (count($checkbox_options) + 2) . '">No hay datos guardados.</td></tr>';
}

echo '</tbody></table>';

// Actualizar el campo hidden con los datos guardados
echo '<input type="hidden" name="saved_data" value="' . htmlspecialchars(json_encode($saved_data)) . '">';
?>
    </body>
</html>

<?php
// Recoger el contenido HTML generado
$html = ob_get_clean();

// Cargar el contenido en Dompdf
$dompdf->loadHtml($html);

// (Opcional) Configurar el tamaño de papel y la orientación
$dompdf->setPaper('A4', 'portrait');

// Renderizar el PDF
$dompdf->render();

// Enviar el PDF al navegador para descarga o vista previa
$dompdf->stream('reporte.pdf', ['Attachment' => 0]); // Attachment = 0 para abrirlo en el navegador