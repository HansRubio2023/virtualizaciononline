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
$date = date('d/m/Y H:i:s');
?>
<html>
    <head>
	<link rel="icon" href="/index/presentacion/img/favicon.ico" type="image/x-icon">	
        <meta charset="UTF-8">
        <style>
            {
                font-size: 10px;
                font-family: 'Helvetica';
            }
            #pdf-container{
                padding: 20px 40px; 
            }
            .text-center{
                text-align: center;
            }
            .text-left{
                text-align: left;
            }
            .img-logo{
                max-width: 160px;
            }
            table{
                width: 100%;
            }
            #tabla-asistencia-pdf th{
                text-align: center;  
				font-family: 'Helvetica';           	
            }
            #tabla-asistencia-pdf{
                width: 100%;
                border-collapse: collapse;
                page-break-inside: auto;
            }
            #tabla-asistencia-pdf th,#tabla-asistencia-pdf td{
                padding: 4px;
                font-size: 9px;
            }
            #tabla-asistencia-pdf tr:nth-child(even){
                background-color: #d6eaf8;
            }
            #tabla-asistencia-pdf thead{
                background-color: #2980b9;
                color: #fff;
            }
            .page_break { page-break-before: always; }
            .check-indicador{
               height: 12px;
            }
            .times-indicador{
               height: 10px;
            }
        </style> 
    </head>
    <body>
        <div id="pdf-container">
    		<table class="text-center">
    			<tr>
    				<td>
    					<img class="img-logo" src="/index/presentacion/img/unap_positivo.png">
    				</td>
    			</tr>
    			<tr>
    				<th style="font-size: 20px">
    					Seguimiento de Clases Online
    				</th>
    			</tr>
    		</table>
    		<table>
    			<tr>
    				<td class="text-left">
    					
    				</td>
    			</tr>
    		</table>
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
    		<table id="tabla-asistencia-pdf">
    	    	<thead>
    	    		<tr>
        	    		<th>N°</th>
        	    		<th>Clases</th>	
        	    		<th><p>Guiar y supervisar</p> <p>el trabajo del</p><p> estudiante</p></th>
        	    		<th><p>Facilitar y promover la</p> <p>reflexión del estudiante</p></th>
                        <th><p>Retroalimentar al</p> <p>estudiante en sus</p><p>consultas</p></th>
        	    		<th><p>Atender consultas</p></th>
                        <th><p>Realizar videoconferencia</p> <p>Atención virtual</p><p> Grabar video</p></th>
        	    		<th><p>Realizar seguimiento</p> <p>diario de los estudiantes</p></th>
                        <th><p>Corrección de</p> <p>éxamenes</p></th>
                        <th><p>Registro de notas</p></th>        	    		
        			</tr>
    	    	</thead>
    </body>
</html>