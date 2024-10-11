<?php
/*session_start();
ini_set ('error_reporting', E_ALL);
ini_set ('display_errors', 1);
// Configuración inicial del archivo
include_once '../variables_globales.php';
include_once ("../negocios/General.php");
include_once ("../negocios/Sakai.php");
include_once FUNCIONES;
include_once SEGURIDAD; 

$seguridad = new seguridad();  

$p_anhoproc	= !empty($_GET['v1'])?$seguridad->decode($_GET['v1']):null;
$p_semestre	= !empty($_GET['v2'])?$seguridad->decode($_GET['v2']):null;
$p_kcodasig	= !empty($_GET['v3'])?$seguridad->decode($_GET['v3']):null;
$p_paralelo	= !empty($_GET['v4'])?$seguridad->decode($_GET['v4']):null;  
$p_curso_aula = $seguridad->decode($_GET['v5']);
$p_sesionaula = $seguridad->decode($_GET['v6']);
$p_knumerut = $seguridad->decode($_GET['v7']);
$p_nombre_sesion_aula = $seguridad->decode($_GET['v8']);

if(!empty($_GET['trust'])){
    
    $p_curso_aula = $_GET['v5'];
    $p_sesionaula = $_GET['v6'];
    $p_knumerut = $_GET['v7'];
    $p_nombre_sesion_aula = $_GET['v8'];
    
    $guiones = substr_count($p_curso_aula, '-');
    $x_parametros = explode("-",$p_curso_aula);
    $p_kcodasig= $x_parametros[0];
    $p_paralelo= $x_parametros[1];
    $p_anhoproc= $x_parametros[$guiones-1];
    $p_semestre= $x_parametros[$guiones];
    
    $admin_virtualizacion = true;
}else{
    $admin_virtualizacion = false;
}

if(is_numeric($p_kcodasig)){
    die('No es posible revisar este curso');
}
$variables_no_recibidas = false;
if(empty($p_anhoproc) || empty($p_semestre) || empty($p_kcodasig) || empty($p_paralelo) || empty($_GET['v5']) || empty($_GET['v6']) || empty($_GET['v7']) || empty($_GET['v8'])){
   die('No se han podido obtener los datos de la sesión que quiere validar, es posible que esto se deba a su navegador. Actualice la versión de su navegador o pruebe utilizando otro. Si el problema persiste contactarse con asistencia@unap.cl.');
}
$kcode = $seguridad->encode('$$||'.$p_anhoproc.'||'.$p_semestre.'||'.$p_kcodasig.'||'.$p_paralelo.'||'.$p_knumerut);

$aulavirtual = new Sakai();
$orades2 = new General();

////FUNCIONES/////////////

/*function evaluateDayBlock($date){ //Devuelve verdadero cuando la fecha debe ser bloqueada y no se le debe permitir asignar sesion
    $evaluateDate = strtotime(str_replace('/', '-',$date));
    $evaluateDateDiff = time() - $evaluateDate; //diferencia positiva mientras mas lejos este la fecha del presente
    $daysDiff = round($evaluateDateDiff/ (60 * 60 * 24)); //calcular dias
    return $daysDiff > 7 ? true : false;
}*/

/*function evaluate24Hours($date){ //Devuelve verdadero cuando supera las 24 horas
    $evaluateDate = strtotime(str_replace('/', '-',$date));
    $evaluateDateDiff = time() - $evaluateDate; //diferencia positiva mientras mas lejos este la fecha del presente
    $daysDiff = round($evaluateDateDiff/ (60 * 60)); //calcular horas
    return $daysDiff > 23 ? true : false;
}*/

function date_compare($a, $b){
    $t1 = strtotime(str_replace('/', '-',$a['DATE']));
    $t2 = strtotime(str_replace('/', '-',$b['DATE']));
    return $t1 - $t2;
}

//////////////////////////

/*$tipo_carrera = $orades2->ObtenerTipoCarrera($p_anhoproc, $p_semestre, $p_kcodasig, $p_paralelo);

$sesiones_no_asignadas = $aulavirtual->AsignarSesionesNoAsignadas($p_curso_aula);
if(!empty($sesiones_no_asignadas)){ //ESTA SECCION DETERMINA EL DOCENTE DUEÑO DE LA SESION Y SE LA ASIGNA
    foreach ($sesiones_no_asignadas as $sesion_no_asignada){
        $aulavirtual->InsertarSesionesNoAsignadas($sesion_no_asignada['SITEID'], $sesion_no_asignada['EID'], $sesion_no_asignada['PAGEID'], $sesion_no_asignada['FECHA']);
    }
}

$dias_clase_docente = $orades2->ObtenerDiasClaseDocente($p_anhoproc, $p_semestre, $p_kcodasig, $p_paralelo, $p_knumerut);

$sesiones_registradas = $aulavirtual->ConsultarSesionesRegistradas($p_curso_aula, $p_knumerut);

if(!empty($sesiones_registradas)){ //ESTA SECCION LIMPIA LAS SESIONES SEGUN CORRESPONDA
    //foreach($sesiones_registradas as $key => $sesion){
    //  if($sesion['CUMPLE']=='0' && $sesion['PAGEID']!='0' && $sesion['PAGEID']!=1){
    //      $resultado = $aulavirtual->QuitaPageidFecha($sesion['PAGEID'], $p_curso_aula, $p_knumerut, $sesion['DATE']);
    //      $sesiones_registradas[$key]['PAGEID'] = null;
    //  }
    //}
    foreach($sesiones_registradas as $k => $sesion){ //RECORRO LAS SESIONES YA REGISTRADAS POR CADA DIA DE LA ASIGNATURA
        $invalido = true;
        foreach($dias_clase_docente as $key => $clase){ //RECORRO TODOS LOS DIAS DE LA ASIGNATURA
            if($sesion['DATE']==$clase['FECHA']){ //SI ENCUENTRO UNA QUE COINCIDA TOMO LOS DATOS DE ESTE DIA QUE VIENE DE LA QUERY DE ORACLE SI EL DIA ES HABIL O NO, Y EL NOMBRE DEL DIA
                $sesiones_registradas[$k]['DIA_HABIL'] = $clase['ESTADO'];
                $sesiones_registradas[$k]['ESTADO_DESC'] = $clase['ESTADO_DESC'];
                $sesiones_registradas[$k]['DIA'] = $clase['DIA'];
                unset($dias_clase_docente[$key]);
                $invalido = false;
                break;
            }
        }
        if($invalido){            
            $resultado = $aulavirtual->EliminarFecha($p_curso_aula, $p_knumerut, $sesion['DATE']); //SI LA FECHA NO EXISTE LA ELIMINO
            unset($sesiones_registradas[$k]);
        }
    }
}

if(count($dias_clase_docente)>0){ //ESTA SECCION REGISTRA LAS POSIBLES FECHAS DE CLASE DEL DOCENTE
    foreach($dias_clase_docente as $key => $clase){
        $insertado = $aulavirtual->IngresarFechaNoRegistrada($p_curso_aula, $p_knumerut, $clase['FECHA']);
        if($insertado>0){
            $fecha_clase    =  array(   'PAGEID'    =>  null,
                'TITLE'     =>  null,
                'DATE'      =>  $clase['FECHA'],
                'DIA_HABIL' =>  $clase['ESTADO'],
                'ESTADO_DESC'=> $clase['ESTADO_DESC'],
                'DIA'       =>  $clase['DIA']
            );
            $sesiones_registradas[] = $fecha_clase;
        }
    }
}

if($sesiones_registradas){//ESTA SECCION ES LA QUE UNE LA SESION NO ASIGNADA CON LA FECHA DE CLASE
    usort($sesiones_registradas, 'date_compare');
    $sesiones_registradas = array_values($sesiones_registradas);
    $sesiones_no_registradas = $aulavirtual->ConsultarSesionesNoRegistradas($p_curso_aula, $p_knumerut);
        
    if(!empty($sesiones_no_registradas)){
        foreach($sesiones_registradas as $key => $sesion){                       
            if($sesion['DIA_HABIL']!='0' && $sesion['PAGEID']==null && !evaluateDayBlock($sesion['DATE'])){                
                $sesion_no_registrada = array_shift($sesiones_no_registradas);
                if(count($sesion_no_registrada)>0){                    
                    $sesiones_registradas[$key]['PAGEID'] = $sesion_no_registrada['PAGEID'];
                    $sesiones_registradas[$key]['TITLE'] = $sesion_no_registrada['TITLE'];
                    $resultado = $aulavirtual->AsignarPageidFecha($sesion_no_registrada['PAGEID'], $p_curso_aula, $p_knumerut, $sesion['DATE']);
                }else{
                    break;
                }
            }    
        }
    }
}
/*
echo '<pre>';
var_dump($sesiones_registradas);
echo '</pre>';
die();
*/
/*$sesiones_que_cumplen = $aulavirtual->ConsultarSesionesEnAulaVirtual($p_curso_aula,$p_knumerut);*/
?>
<html lang="es">
<head>	
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Validar clase online - Universidad Arturo Prat</title>	
	<link rel="icon" href="C:\xampp\htdocs\index_virtualizacion\presentacion\img\favicon.ico" type="image/x-icon">	
	<link rel="stylesheet" href="https://campus.unap.cl/generalidades/lib/bootstrap-4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://campus.unap.cl/generalidades/lib/fontawesome-5.12.0/css/all.min.css">
	<link rel="stylesheet" href="https://campus.unap.cl/generalidades/lib/toastr-2.1.3/css/toastr-2.1.3.min.css">
	<link rel="stylesheet" href="index/plugins/toastr/toastr.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<style>
		.calendar__date.selected {
        	background-color: #606060; 
        	color: #fff;
        	cursor: pointer; 
        }
		.calendar__date.disabled {
            background-color: #ccc; 
            color: #666;
            cursor: not-allowed;
        }	
		.calendar__date.today {
			background-color: #fff; 
			color: #000;
		}


        header{background: #036dab;}
        #div_general ul li{margin: 4px 0;}
        #div_general td{padding: 5px 10px;}
        h4,h5{font-weight: bold;}
        .color-primario{color:#15597e;}
        #guardar-sesiones{
        	height: 31px;
        	width: 123px;
        }
		
		#customers {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#customers td, #customers th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#customers th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			color: white;
		}
		#label1{
			color: rgb(247, 5, 5);
		}
		.check-indicador{
               height: 12px;
            }

		.pb-1 , .py-1 {
			padding-bottom: .01rem !important;
		}	

		.row {
			display: flex;
			flex-wrap: wrap;
			margin-right: 15px;
			margin-left: -15px;
			margin-bottom: -5px;
		}
		.card {
			position:relative;
			display:flex;
			flex-direction: column;
			min-width: 0;
			word-wrap: break-Word;
			background-color: #fff;
			background-clip: border-box;
			border: 1px solid rgba(0, 0, 0, .125);
			border-radius: .25rem;
		}
		.calendar__today{
			background-color: #036dab;
		}
    </style>
</head>

<body style="background-color: white;" class=""> 
		<header id="header-criterios" data-user="c45938f1943cf589" data-site="a12c45f7972988f0ffc70ed77137590fec540d068c6f0dba9c93adfd67e8542c61f1ecb394d98947">
    	<div class="container py-1 mb-2">
    		<div class="row">
    			<div class="col-6">
				<img src="presentacion/img/sello_unaponline_blanco.png" width= 260px height=60px style="margin-left: -56px;">
    			</div>
    			<div class="col-6 d-flex align-items-center justify-content-end">
    				<button class="btn btn-sm btn-outline-light" onclick="javascript:window.history.back()">Volver</button>
    			</div>
    		</div>
    	</div>
	</header>	
	<div class="tab-content">
		<div id="div_general" class="container">
			<div class="row align-items-center">
				<div class="col-12">
					<h3 class="pt-2 pb-0">Seguimiento diario de clases online</h3>
					<h4 style="color:#0070aa;">[ABC15] CURSO DE PRUEBA Paralelo A 2020/1 </h4>
				</div>
			</div>
		</div>
	</div>
	<!--Indicadores-->
	<div id="div_general2" class="container">
		<div class="row align-items-center">
			<div class="col-12">
				<h4 class="pt-2 pb-0">Indicadores</h4>
				<!--<img src="presentacion/img/indicadores.png">-->
				<div class="row align-items-center">
						<!--<div class="card" style="width: 160px; height: 65px; left:20px" title2=" Las veces que el docente ha ingresado a la plataforma Aula Virtual">
							<div class="card-body">
								<p style="font-size: 22px; width: 58px; float: left; margin-top: -15px;"><strong>10</strong></p><img src="img/graficoAzul.png" alt="10" style="width: 45px; float: right; margin-top: -20px;">
							</div>
							<p class="card-text" style="font-size: 9px; text-align:center; position: relative; top: -10px;"><strong>Ingresos durante 7 días</strong></p>
						</div>
					</div>
					<div class="col">
						<div class="card" style="width: 160px; height: 65px; left:20px" title2=" La cantidad de respuestas que el docente a enviado a los estudiantes">
							<div class="card-body">
								<p style="font-size: 22px; width: 58px; float: left; margin-top: -15px;"><strong>65</strong></p><img src="img/graficoMorado.png" alt="10" style="width: 45px; float: right; margin-top: -20px;">
							</div>
							<p class="card-text" style="font-size: 9px; text-align: center; padding: 5px 10px;position: relative; top: -10px;"><strong>Respuesta a estudiantes a través del aula virtual</strong></p>
						</div>
					</div>
					<div class="col">
						<div class="card" style="width: 160px; height: 65px; left:20px"  title2=" Las veces que el docente ha interactuado con el estudiante en la plataforma Aula Virtual">
							<div class="card-body">
								<p style="font-size: 22px; width: 58px; float: left; margin-top: -15px;"><strong>34</strong></p><img src="img/graficoNaranjo.png" alt="10" style="width: 45px; float: right; margin-top: -20px;">
							</div>
							<p class="card-text" style="font-size: 9px; text-align:center; padding: 5px 10px;position: relative; top: -10px;"><strong>Retroalimentacion a estudiantes en actividades</strong></p>
						</div>
					</div>
					<div class="col">
						<div class="card" style="width: 160px; height: 65px; left:20px" title2=" Muestra el porcentaje en relacion de las pruebas publicadas y/o corregidas en el Aula Virtual">
							<div class="card-body">
								<p style="font-size: 22px; width: 58px; float: left; margin-top: -15px;"><strong>33%</strong></p><img src="img/graficoVerde.png" alt="10" style="width: 45px; float: right; margin-top: -20px;">
							</div>
							<p class="card-text"  style="font-size: 9px; text-align:center; position: relative; top: -10px;"><strong>Porcentaje exámenes corregidos</strong></p>
						</div>
					</div>-->
					<div class="col-md-3 mb-4">
						<div class="card" style="width: 180px; height: 80px; margin-top: -10px;" title2=" Las veces que el docente ha ingresado a la plataforma Aula Virtual">
							<div class="card-body d-flex align-items-center justify-content-center position-relative" style="height: 80px;">
								<strong style="position: absolute; left: 10px; font-size: 24px;">10</strong>
								<img src="img/graficoAzul.png" class="img-fluid" alt="10" style="max-height: 80px; width: 70px; position: absolute; right: 10px;">
							</div>
						</div>
						<p class="card-text" style="font-size: 14px; text-align: center; margin-top: 12px; width: 180px;">Ingresos durante 7 días</p>
					</div>
					<div class="col-md-3 mb-4">
						<div class="card" style="width: 180px; height: 80px; margin-top: 11px;" title2=" La cantidad de respuestas que el docente a enviado a los estudiantes">
							<div class="card-body d-flex align-items-center justify-content-center position-relative" style="height: 80px;">
								<strong style="position: absolute; left: 10px; font-size: 24px;">10</strong>
								<img src="img/graficoMorado.png" class="img-fluid" alt="10" style="max-height: 80px; width: 70px; position: absolute; right: 10px;">
							</div>
						</div>
						<p class="card-text" style="font-size: 14px; text-align: center; margin-top: 12px; width: 180px;">Respuesta a estudiantes a través del aula virtual</p>
					</div>
					<div class="col-md-3 mb-4">
						<div class="card" style="width: 180px; height: 80px; margin-top: 11px;" title2=" Las veces que el docente ha interactuado con el estudiante en la plataforma Aula Virtual">
							<div class="card-body d-flex align-items-center justify-content-center position-relative" style="height: 80px;">
								<strong style="position: absolute; left: 10px; font-size: 24px;">10</strong>
								<img src="img/graficoNaranjo.png" class="img-fluid" alt="10" style="max-height: 80px; width: 70px; position: absolute; right: 10px;">
							</div>
						</div>
						<p class="card-text" style="font-size: 14px; text-align: center; margin-top: 12px; width: 180px;">Retroalimentacion a estudiantes en actividades</p>
					</div>
					<div class="col-md-3 mb-4">
						<div class="card" style="width: 180px; height: 80px; margin-top: 11px;" title2=" Muestra el porcentaje en relacion de las pruebas publicadas y/o corregidas en el Aula Virtual">
							<div class="card-body d-flex align-items-center justify-content-center position-relative" style="height: 80px;">
								<strong style="position: absolute; left: 10px; font-size: 24px;">10</strong>
								<img src="img/graficoVerde.png" class="img-fluid" alt="10" style="max-height: 80px; width: 70px; position: absolute; right: 10px;">
							</div>
						</div>
						<p class="card-text" style="font-size: 14px; text-align: center; margin-top: 12px; width: 180px;">Porcentaje exámenes corregidos</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br><br><br>
	<!--Calendarios-->
	<div class="tab-content">
		<div class="tab-pane active" id="criterios" role="tabpanel" aria-labelledby="criterios-tab">
			<div id="div_general" class="container">
					<div class="row">
						<div class="col-12 col-md-6">
							<h4><div class="mb-3">Calendario</div></h4>
							<!--Aquí va el calendario--><br>
							<div class="calendar">
								<div class="calendar__info">
									<div class="calendar__prev" id="prev-month">&#9664;</div>
									<div class="calendar__month" id="month"></div>
									<div class="calendar__year" id="year"></div>
									<div class="calendar__next" id="next-month">&#9654;</div>
								</div>
								<div class="calendar__week">
									<div class="calendar_day calendar_item">Lun</div>
									<div class="calendar_day calendar_item">Mar</div>
									<div class="calendar_day calendar_item">Miér</div>
									<div class="calendar_day calendar_item">Jue</div>
									<div class="calendar_day calendar_item">Vie</div>
									<div class="calendar_day calendar_item">Sáb</div>
									<div class="calendar_day calendar_item">Dom</div>
								</div>
								<div class="calendar__dates" id="dates">
								<!--<div class="calendar__date" data-date="2023-10-06">1</div>
   								<div class="calendar__date" data-date="2023-10-07">2</div>-->
								</div>
							</div>									
						</div>
						<div class="col-12 col-md-6">
						<h4 id="selected-date">Evaluando el día: <?php echo date("d/m/Y"); ?></h4>
							<!--Aquí va el calendario-->
						<form id="myForm" action="reporte_online.php" method="POST" target="_blank">
							<table id="customers">
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Guiar y supervisar el trabajo del estudiante" id="flexCheckDefault" required title="Campo obligatorio">
											<label class="form-check-label" for="flexCheckDefault" required title="Campo obligatorio">
												Guiar y supervisar el trabajo del estudiante
											</label>
											<label id="label1">*</label>	
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]"value="Facilitar y promover la reflexión del estudiante" id="flexCheckDefault1" required title="Campo obligatorio">
											<label class="form-check-label" for="flexCheckDefault1" required title="Campo obligatorio">
												Facilitar y promover la reflexión del estudiante
											</label>
											<label id="label1">*</label>	
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Retroalimentar al estudiante en sus consultas" id="flexCheckDefault2" required title="Campo obligatorio">
											<label class="form-check-label" for="flexCheckDefault2" required title="Campo obligatorio">
												Retroalimentar al estudiante en sus consultas
											</label>
											<label id="label1">*</label>	
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Retroalimentar al estudiantes sus actividades" id="flexCheckDefault3">
											<label class="form-check-label" for="flexCheckDefault3">
												Retroalimentar al estudiantes sus actividades
											</label>
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Atender consultas" id="flexCheckDefault4">
											<label class="form-check-label" for="flexCheckDefault4">
												Atender consultas
											</label>
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Realizar videoconferencia/Atención virtual/Grabar video" id="flexCheckDefault5">
											<label class="form-check-label" for="flexCheckDefault5">
												Realizar videoconferencia/Atención virtual/Grabar video
											</label>
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Realizar seguimiento diario de los estudiante" id="flexCheckDefault6">
											<label class="form-check-label" for="flexCheckDefault6">
												Realizar seguimiento diario de los estudiante
											</label>
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Corrección de exámenes" id="flexCheckDefault7">
											<label class="form-check-label" for="flexCheckDefault7">
												Corrección de exámenes
											</label>
										</div>
									</td>
								  </tr>
								  <tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="checkbox1[]" value="Registro de notas" id="flexCheckDefault8">
											<label class="form-check-label" for="flexCheckDefault8">
												Registro de notas
											</label>
										</div>
									</td>
								  </tr>
							</table>	
							<div style="display: inline-block;">
							<p style="color: #f70505;">* Este símbolo indica que son campos obligatorios</p>
							<button type="submit" class="btn btn-danger btn-sm">Enviar reporte PDF</button>			
							<input type="hidden" id="saved-data" name="saved_data" value="">	
							<input type="hidden" id="selected-date-input" name="selected_date">	
							</form>
							<!--<a href="http://localhost/index/reporte_online.php" target="_blank" class="btn btn-danger btn-sm">Ver reporte en PDF</a>-->
							<!--<a href="https://campus.unap.cl/aula_virtual/presentacion/reporte_virtualizacion_preview.php?kcode=<?=$kcode?>" target="_blank" class="btn btn-danger btn-sm">Ver reporte en PDF</a>-->
            				<button type= "button" id="guardar-sesiones" class="btn btn-primary btn-sm" data-var0="<?/=$seguridad->encode('reasignar_sesion')/?>" onclick="validarCampos()">Guardar cambios</button>
						</div>
					</div>		
			</div>		
		</div>
	</div>
	<br><br>
	<script src="js\script1.js"></script>
    <script src="https://campus.unap.cl/generalidades/lib/jquery-3.3.1/jquery-3.3.1.min.js"></script>
	<script src="https://campus.unap.cl/generalidades/lib/bootstrap-4.5.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://campus.unap.cl/generalidades/lib/toastr-2.1.3/js/toastr-2.1.3.min.js"></script>
	<script src="/index/plugins/toastr/toastr.min.js"></script>
	<script>
	$(document).ready(function() {
    toastr.options = {
        "preventDuplicates": true
    };
    let selectedDate = null;
    let checkboxStates = {};  // Objeto para almacenar los estados de los checkboxes por día
	let savedDates = [];  // Array para almacenar todas las fechas guardadas

    function validarCampos() {
        const campoGuiar = document.getElementById('flexCheckDefault');
        const campoGuiar1 = document.getElementById('flexCheckDefault1');
        const campoGuiar2 = document.getElementById('flexCheckDefault2');
        if (!(campoGuiar.checked && campoGuiar1.checked && campoGuiar2.checked)) {
            toastr.error("Tiene que seleccionar los tres primeros campos obligatorios", "ERROR");
            return false; // Retorna false si no se validan los campos
        }
        return true; // Retorna true si todos los campos son válidos
    }
    function limpiarCheckboxes() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
    function restaurarCheckboxes(dateKey) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        if (checkboxStates[dateKey]) {
            checkboxes.forEach(function(checkbox, index) {
                checkbox.checked = checkboxStates[dateKey][index] || false;  // Restaura el estado si existe
            });
        } else {
            limpiarCheckboxes();  // Si no hay estado guardado, limpiar checkboxes
        }
    }
    function markToday() {
        const today = new Date();
        const currentDay = today.getDate();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();

        const calendarDates = document.querySelectorAll('.calendar__date');
        calendarDates.forEach(date => {
            date.classList.remove('calendar__today');
            if (parseInt(date.textContent) === currentDay &&
                monthNumber === currentMonth &&
                currentYear === currentYear) {
                date.classList.add('calendar__today');
            }
        });
    }
    function updateCalendar() {
        writeMonth(monthNumber);
        markToday();
        markSavedDates();
    }
    // Llamar a updateCalendar cuando se cambie de mes
    document.querySelector('.calendar__prev').addEventListener('click', function() {
        updateCalendar();
    });

    document.querySelector('.calendar__next').addEventListener('click', function() {
        updateCalendar();
    });

    // Asegurarse de que el día actual esté marcado al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        updateCalendar();
    });
    function marcarDiaSeleccionado(date) {
        $('.calendar__date').each(function() {
            $(this).removeClass('selected-date');
            if ($(this).text() === date) {
                $(this).addClass('selected-date');
            }
        });
    }
	function markSavedDates() {
    $('.calendar__date').each(function() {
        const day = $(this).text();
        const dateKey = `${currentYear}-${monthNumber + 1}-${day}`;
        if (checkboxStates[dateKey]) {
            $(this).addClass('saved-date');
            $(this).css('background-color', 'green');
            $(this).css('color', 'white');
            
            // Actualizar el campo oculto con los datos guardados
            $('#saved-data').val(JSON.stringify(checkboxStates[dateKey]));
        } else {
            $(this).removeClass('saved-date');
            $(this).css('background-color', '');
            $(this).css('color', '');
        }
    });
}

	function updateSavedSessionsField() {
        const savedSessions = Object.keys(checkboxStates).map(dateKey => {
            return {
                date: dateKey,
                checkboxes: checkboxStates[dateKey]
            };
        });
        $('#saved-data').val(JSON.stringify(savedSessions));
    }
	$("#guardar-sesiones").on('click', function() {
    if (validarCampos()) {
        if (selectedDate) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const day = parseInt(selectedDate);
            const monthIndex = monthNumber;
            const year = currentYear;
            if (isNaN(day) || isNaN(monthIndex) || isNaN(year)) {
                toastr.error("Error al obtener la fecha seleccionada", "ERROR");
                return;
            }
            const dateKey = `${year}-${monthIndex + 1}-${day.toString().padStart(2, '0')}`;
            
            // Guarda solo los valores de los checkboxes seleccionados
            const selectedCheckboxes = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            const months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            const formattedDate = `${day.toString().padStart(2, '0')}/${months[monthIndex]}/${year}`;

            // Agregar o actualizar la fecha en savedDates
            const existingIndex = savedDates.findIndex(date => date.date === formattedDate);
            if (existingIndex !== -1) {
                savedDates[existingIndex] = { date: formattedDate, checkboxes: selectedCheckboxes };
            } else {
                savedDates.push({ date: formattedDate, checkboxes: selectedCheckboxes });
            }

            toastr.success(`Cambios guardados para el día ${formattedDate}`, "Guardado");

            $('#selected-date').text(`Evaluando el día: ${formattedDate}`);
            marcarDiaSeleccionado(selectedDate);
            markSavedDates();
            
            // Actualizar el campo oculto con todas las sesiones guardadas
            updateSavedSessionsField();
        } else {
            toastr.error("Debe seleccionar un día antes de guardar los cambios", "ERROR");
        }
    }
});

function updateSavedSessionsField() {
    $('#saved-data').val(JSON.stringify(savedDates));
}

// Modificar el evento de envío del formulario
$('#myForm').on('submit', function(e) {
    if (savedDates.length === 0) {
        e.preventDefault();
        toastr.error("No hay fechas guardadas para generar el reporte", "ERROR");
        return false;
    }
    
    $('#saved-data').val(JSON.stringify(savedDates));
    return true;
});

function markSavedDates() {
    $('.calendar__date').each(function() {
        const day = $(this).text();
        const dateKey = `${currentYear}-${monthNumber + 1}-${day.toString().padStart(2, '0')}`;
        const formattedDate = `${day.toString().padStart(2, '0')}/${(monthNumber + 1).toString().padStart(2, '0')}/${currentYear}`;
        if (savedDates.some(date => date.date === formattedDate)) {
            $(this).addClass('saved-date');
            $(this).css('background-color', 'green');
            $(this).css('color', 'white');
        } else {
            $(this).removeClass('saved-date');
            $(this).css('background-color', '');
            $(this).css('color', '');
        }
    });
}

$('.calendar__date').on('click', function() {
    const clickedDate = $(this).text();
    if (selectedDate !== clickedDate) {
        selectedDate = clickedDate;
        const dateKey = `${currentYear}-${monthNumber + 1}-${selectedDate}`;
        const formattedDate = `${selectedDate.toString().padStart(2, '0')}/${(monthNumber + 1).toString().padStart(2, '0')}/${currentYear}`;
        const savedDate = savedDates.find(date => date.date === formattedDate);
        if (savedDate) {
            // Restaurar los checkboxes guardados para esta fecha
            $('input[type="checkbox"]').each(function() {
                $(this).prop('checked', savedDate.checkboxes.includes($(this).val()));
            });
        } else {
            // Limpiar todos los checkboxes si no hay datos guardados para esta fecha
            $('input[type="checkbox"]').prop('checked', false);
        }
        $('#selected-date').text(`Evaluando el día: ${formattedDate}`);
        $('#selected-date-input').val(`${currentYear}-${monthNumber + 1}-${selectedDate}`);
        marcarDiaSeleccionado(selectedDate);
    }
});
});
</script>
	<script>
	// Selecciona el contenedor de fechas
	let today = document.querySelector('.calendar__today');
	const datesContainer = document.getElementById('dates');
	let selectedDate = null;
	const clickedDate = new Date();
	// Agrega un evento al contenedor de fechas para detectar clics en los días
	datesContainer.addEventListener('click', function(event) {
		const clickedDate = event.target;
		if (clickedDate.classList.contains('calendar__date') && !clickedDate.classList.contains('disabled')) {
			if (selectedDate != null) {
				selectedDate.classList.remove('selected');
			}
			selectedDate = clickedDate;
			selectedDate.classList.add('selected' );
			const day = selectedDate.textContent;
			const monthName = monthNames[monthNumber];
			const year = currentYear;
			
			// Actualizara el contenido de evaluando dia
			document.getElementById('selected-date').textContent = `Evaluando el día: ${day}/${monthNumber + 1}/${year}`;
			unmarkToday();
			markNewDayAsToday(selectedDate);
		}
	});
	document.addEventListener('DOMContentLoaded', function () {
		writeMonth(monthNumber);
	});
</script>
<script>
// Para que al momento de cargar la página se eliminen los checkboxes
window.addEventListener('', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
});
</script>
<script>
/*toastr.options = {
	//primeras opciones
  "closeButton": false,//aparecera una X para cerrar
  "debug": false,
  "newestOnTop": false, //notificaciones mas nuevas van en la parte superior
  "progressBar": false,// barra de progreso hasta que se oculta la  notificacion
  "preventDuplicates": true, //para prevenir mensajes duplicador
  "onclick": null,

  "positionClass": "toast-top-right", 
  /*posicion de la notificacion
  toast-bottom-left, toast-bottom-right,
  toast-top-full-width, toast-top-center 

  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}*/
</script>
</body>
</html>