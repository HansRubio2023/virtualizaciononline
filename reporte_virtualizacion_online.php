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
$date = date('d/m/Y H:i:s');
ob_start();*/
?>
<style>
    *{
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
    	text-align: left;                	
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
<?php 
/*$styles = ob_get_clean();
ob_start();*/
/*foreach($asignaturas as $asignatura){
    /*$hojas++;
    $anhoproc = $asignatura['anhoproc'];
    $semestre = $asignatura['semestre'];
    $kcodasig = $asignatura['kcodasig'];
    $paralelo = $asignatura['paralelo'];
    $knumerut = $asignatura['knumerut'];

    $orades2->ObtenerInformacionAsignatura2($anhoproc, $semestre, $kcodasig, $paralelo, $knumerut);
    
    $nombre_profesor = '';
    $nombre_asignatura = '';
    if($orades2->getFilas()){
        $orades2->getSet();
        $nombre_profesor = $orades2->getNOMBRE();
        $nombre_asignatura =  $orades2->getNOMBASIG();
    }else{
        die('Los datos ingresados no son válidos...');
    }
    
    $orades2->ObtenerCarrerasAsigntura2($anhoproc, $semestre, $kcodasig, $paralelo, $knumerut);
    $carreras = '';
    if($orades2->getFilas()){
        $orades2->getSet();
        $carreras = 'Carrera(s): '.$orades2->getCARRERAS();
    }
    
    $orades2->ObtenerAsignaturaPadre($anhoproc, $semestre, $kcodasig, $paralelo);
    if($orades2->getFilas()){
        $orades2->getSet();
        $kcodasig = $orades2->getASIG_PADRE();
        $paralelo = $orades2->getPARALELO_PADRE();
    }
    
    $sakai = new Sakai();    
    $dias_clase_docente = $orades2->ObtenerDiasClaseDocente($anhoproc, $semestre, $kcodasig, $paralelo, $knumerut);
    $sesiones_registradas = $sakai->ConsultarSesionesRegistradas($anhoproc, $semestre, $kcodasig, $paralelo, $knumerut);
    
    if($sesiones_registradas){
        foreach($dias_clase_docente as $key => $clase){
            foreach($sesiones_registradas as $k => $sesion){
                if($sesion['DATE']==$clase['FECHA']){
                    $sesiones_registradas[$k]['DIA_HABIL'] = $clase['ESTADO'];
                    $sesiones_registradas[$k]['ESTADO_DESC'] = $clase['ESTADO_DESC'];
                    $sesiones_registradas[$k]['DIA'] = $clase['DIA'];
                    break;
                }
            }
        }
    }
    
    $filas = count($sesiones_registradas);
    $html = '';
    if($hojas==1){
        echo $styles;
    }*/
    ?>
        <div id="pdf-container">
    		<table class="text-center">
    			<tr>
    				<td>
    					<img class="img-logo" src="logo_unap.png">
    				</td>
    			</tr>
    			<tr>
    				<th style="font-size: 14px">
    					Reporte de virtualizacion de clases online
    				</th>
    			</tr>
    		</table>
    		<table>
    			<tr>
    				<td class="text-left">
    					<b>Docente:</b> <?=$nombre_profesor?> / <b>Rut:</b> <?=$knumerut?>-<?=GetDV($knumerut)?>
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
    					<?=$carreras?>
    				</td>
    			</tr>
    			<tr>
    				<td class="text-left">
    					Fecha de generación: <?=$date?>
    				</td>    				
    			</tr>
    		</table>
    		<?php 
    		if($filas){
    		?>
    		<table>
    			<tr>
    				<th class="text-left">
    					LISTADO DE DIAS CORRESPONDIENTES A LA GUIA ACADEMICA
    				</th>
    			</tr>
    		</table>	
    		<table id="tabla-asistencia-pdf">
    	    	<thead>
    	    		<tr>
        	    		<th>N°</th>
        	    		<th>Clases</th>	
        	    		<th>Guiar y supervisar el trabajo del estudiante</th>
        	    		<th>Detalle</th>
        	    		<th>Estado</th>
        	    		
        			</tr>
    	    	</thead>
    	    	<tbody>
    	    		<?php 
    	    		/*$i=0;
    	    		foreach($sesiones_registradas as $sesion){
    	    		    $i++;
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?=$i?></td>
    	    		    	<?php 
    	    		    	if($sesion['PAGEID']!='0' && $sesion['PAGEID']!='1'){
    	    		    	?>
    	    		    	<td><?=!empty($sesion['TITLE'])?$sesion['TITLE']:'-'?></td>
    	    		    	<?php
    	    		    	}
                            elseif($sesion['PAGEID']=='1'){
                            ?>
                            <td>Clase Presencial</td>
                            <?php
                            }
                            else{
    	    		    	?>
    	    		    	<td>-</td>    	    		    	
    	    		    	<?php 
    	    		    	}
    	    		    	?>
    	    		    	<td><?=$sesion['DATE']?></td>
    	    		    	<td><?=$sesion['DIA']?></td>
    	    		    	<td><?=!empty($sesion['ESTADO_DESC'])?$sesion['ESTADO_DESC']:'Día hábil'?></td>
    	    		    	<td class="text-center">
    	    		    	<?php 
    	    		    	if(!empty($sesion['PAGEID'])){
        		    		?>
        		    			<img src="img/check_t.png" class="check-indicador">
        		    		<?php 
                            }else{
                                if($sesion['DIA_HABIL']!=0 && $sesion['PAGEID']!='0'){
                                    ?>
                                    <img src="img/times_t.png" class="times-indicador">
                                    <?php 
                                }elseif($sesion['PAGEID']=='0'){
                                    ?>
                                    <img src="img/exclamacion_t.png" class="times-indicador">                                    
                                    <?php 
                                }
                            }
        		    		?>
        		    		</td>
    	    		    	<?php 
    	    		    	if($sesion['DIA_HABIL']!='0' && $sesion['PAGEID']!='0' && $sesion['PAGEID']!='1'){
    	    		    	?>    	    		    	
    	    		    	<td><?=$sesion['EXAMEN']?></td>
    	    		    	<td><?=$sesion['FORO']?></td>
    	    		    	<td><?=$sesion['TAREA']?></td>
    	    		    	<td><?=$sesion['CONTENIDO']?></td>
    	    		    	<td><?=$sesion['ARCHIVO_ADJUNTO']?></td>  	
    	    		    	<td><?=$sesion['ENLACE']?></td>
    	    		    	<td><?=$sesion['PREGUNTA_ENCUESTA']?></td>  
    	    		    	<td><?=$sesion['HERRAMIENTA_COMENTARIOS']?></td> 
    	    		    	<td><?=$sesion['CONTENIDO_ALUMNO']?></td>		 
    	    		    	<?php 
    	    		    	}elseif($sesion['DIA_HABIL']!='0' && $sesion['PAGEID']=='0'){
    	    		    	?>
    	    		    	<td colspan="10">El docente ha indicado de que no se asigne una sesión en esta fecha</td>   
    	    		    	<?php
    	    		    	}elseif($sesion['DIA_HABIL']!='0' && $sesion['PAGEID']=='1'){
    	    		    	    ?>
    	    		    		<td colspan="10">Presencial</td>   
    	    		    	<?php 
    	    		    	}else{
    	    		    	?>
    	    		    	<td colspan="10">No es posible asignar sesiones en los feriados</td>   
    	    		    	<?php 
    	    		    	}
    	    		    	?>	
    	    		    </tr>
    	    		    <?php 
    	    		}*/
    	    		?>
    	    	</tbody>
    	    </table>
    	    <br>
    	    <?php 
    		}else{
    		?>
    	    <p>No se encontraron sesiones para esta actividad curricular que cumplan con los criterios de evaluación en el Aula Virtual.</p>
    	    <?php
    		}
    	    ?>	    
        </div>
    <?php 
    if(($numero_hojas>1 && $hojas==1) || ($hojas>1 && $hojas<$numero_hojas)){
        ?>
        <div class="page_break"></div>
        <?php 
    }
/*}
$html = ob_get_clean();
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('letter', 'landscape');
$dompdf->render();
$dompdf->stream('Virtualizacion_'.time().'.pdf',array("Attachment" => false));
exit(0);*/