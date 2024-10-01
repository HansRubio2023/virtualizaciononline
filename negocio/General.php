<?php
include_once '/var/www/SITIOS/apps/aula_virtual/variables_globales.php';
include_once '/var/www/SITIOS/apps/aula_virtual/datos/GeneralSQL.php';
include_once DEF_EJECUTA;
include_once GETSETTER;

class General extends GetSetter{
    public function __construct(){
        $this->sql = new GeneralSQL();
        $this->ejecuta  = new ejecuta_sql(ORADES2);
    }
    
    public function ObtenerEmailEstudiante($p_listado_ruts){
        $parametros=get_defined_vars();
        $this->sql->ObtenerEmailEstudiante();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerDiasClaseGuiaAcademica($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_pactasig){
        $parametros=get_defined_vars();
        $this->sql->ObtenerDiasClaseGuiaAcademica();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerDiasClaseGuiaAcademica2($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo){
        $parametros=get_defined_vars();
        $this->sql->ObtenerDiasClaseGuiaAcademica2();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerAsignaturaPadre($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo){
        $parametros=get_defined_vars();
        $this->sql->ObtenerAsignaturaPadre();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerInformacionAsignatura($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_pactasig,$p_knumerut){
        $parametros=get_defined_vars();
        $this->sql->ObtenerInformacionAsignatura();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerInformacionAsignatura2($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_knumerut){
        $parametros=get_defined_vars();
        $this->sql->ObtenerInformacionAsignatura2();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerCarrerasAsigntura($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_pactasig,$p_knumerut){
        $parametros=get_defined_vars();
        $this->sql->ObtenerCarrerasAsigntura();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }    
    
    public function ObtenerCarrerasAsigntura2($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_knumerut){
        $parametros=get_defined_vars();
        $this->sql->ObtenerCarrerasAsigntura2();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    } 
    
    public function ObtenerAsignaturaDelDecreto($p_solicitud){
        $parametros=get_defined_vars();
        $this->sql->ObtenerAsignaturaDelDecreto();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerAsignaturaDelDecreto2($p_solicitud){
        $parametros=get_defined_vars();
        $this->sql->ObtenerAsignaturaDelDecreto2();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerIdRVConvenioDocente(){
        $parametros=get_defined_vars();
        $this->sql->ObtenerIdRVConvenioDocente();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function ObtenerIdRVConvenioAsig(){
        $parametros=get_defined_vars();
        $this->sql->ObtenerIdRVConvenioAsig();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        $this->vector_resultado=$this->ejecuta->resultado;
    }
    
    public function InsertarRVConvenioDocente($p_id_reporte,$p_id_convenio,$p_knumerut,$p_cdt){
        $parametros=get_defined_vars();
        $this->sql->InsertarRVConvenioDocente();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function InsertarRVConvenioAsig($p_id_reporte_asig,$p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_id_reporte,$p_nombasig,$p_carreras){
        $parametros=get_defined_vars();
        $this->sql->InsertarRVConvenioAsig();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function InsertarRVConvenioAsigClase($p_id_reporte_asig,$p_orden,$p_fecha_clase,$p_estado){
        $parametros=get_defined_vars();
        $this->sql->InsertarRVConvenioAsigClase();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function InsertarRVConvenioAsigSesion($p_id_reporte_asig,$p_orden,$p_sesion,$p_examen,$p_foro,$p_tareas,$p_contenido,$p_archivo_adjunto,$p_enlace,$p_pregunta,$p_comentario,$p_contenido_alumno){
        $parametros=get_defined_vars();
        $this->sql->InsertarRVConvenioAsigSesion();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerRVCapturado($p_id_convenio){
        $parametros=get_defined_vars();
        $this->sql->ObtenerRVCapturado();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }  
    public function ObtenerAsignaturasRVCapturado($p_id_reporte){
        $parametros=get_defined_vars();
        $this->sql->ObtenerAsignaturasRVCapturado();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    } 
    public function ObtenerClasesRVCapturado($p_id_reporte_asignatura){
        $parametros=get_defined_vars();
        $this->sql->ObtenerClasesRVCapturado();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    } 
    public function ObtenerSesionesRVCapturado($p_id_reporte_asignatura){
        $parametros=get_defined_vars();
        $this->sql->ObtenerSesionesRVCapturado();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }     
    
    public function ObtenerDiasClaseDocente($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_knumerut){
        $parametros=get_defined_vars();
        $this->sql->ObtenerDiasClaseDocente();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }	
    
    public function getKNUMERUT(){
        return $this->KNUMERUT;
    }
    public function getEMAIL(){
        return $this->EMAIL;
    }	
    public function getASIG_PADRE(){
        return $this->ASIG_PADRE;
    }
    public function getPARALELO_PADRE(){
        return $this->PARALELO_PADRE;
    }	
    public function getNOMBRE(){
        return $this->NOMBRE;
    }
    public function getNOMBASIG(){
        return $this->NOMBASIG;
    }
    public function getTIPO_ASIGNATURA(){
        return $this->TIPO_ASIGNATURA;
    }    
    public function getCARRERAS(){
        return $this->CARRERAS;
    }    
    public function getANHOPROC(){
        return $this->ANHOPROC;
    }
    public function getSEMESTRE(){
        return $this->SEMESTRE;
    }
    public function getKCODASIG(){
        return $this->KCODASIG;
    }
    public function getPARALELO(){
        return $this->PARALELO;
    }
    public function getPACTASIG(){
        return $this->PACTASIG;
    }
    public function getID_REPORTE(){
        return $this->ID_REPORTE;
    }
    public function getID_REPORTE_ASIG(){
        return $this->ID_REPORTE_ASIG;
    }    
}