<?php
include_once '/var/www/SITIOS/apps/aula_virtual/variables_globales.php';
include_once '/var/www/SITIOS/apps/aula_virtual/datos/SakaiSQL.php';
include_once DEF_EJECUTA;
include_once GETSETTER;

class Sakai extends GetSetter
{
    public function __construct(){
        $this->sql = new SakaiSQL();
        $this->ejecuta  = new ejecuta_sql(SAKAI);
    }
    
    public function ObtenerAusentesEnExamenes(){
		$parametros=get_defined_vars();
		$this->sql->ObtenerAusentesEnExamenes();
		$consulta=$this->sql->query;
		$this->ejecuta->ejecuta_query($consulta, $parametros);
		$this->vector_resultado=$this->ejecuta->resultado;
	}
	
	public function ObtenerListadoEstudiantesAusentes($p_site_id,$p_id_examen){
	    $parametros=get_defined_vars();
	    $this->sql->ObtenerListadoEstudiantesAusentes();
	    $consulta=$this->sql->query;
	    $this->ejecuta->ejecuta_query($consulta, $parametros);
	    $this->vector_resultado=$this->ejecuta->resultado;
	}
	
	public function RegistrarEnvioNotificacion($p_examen_id,$p_site_id,$p_user_id_docente){
	    $parametros=get_defined_vars();
	    $this->sql->RegistrarEnvioNotificacion();
	    $consulta=$this->sql->query;
	    $this->ejecuta->ejecuta_query($consulta, $parametros);
	    return $this->ejecuta->resultado;
	}
	
	public function ConsultarSesionesEnAulaVirtual($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo){
	    $parametros=get_defined_vars();
	    $this->sql->ConsultarSesionesEnAulaVirtual();
	    $consulta=$this->sql->query;
	    $this->ejecuta->ejecuta_query($consulta, $parametros);
	    return $this->ejecuta->resultado;
	}
	
	public function ConsultarSesionesRegistradas($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo,$p_knumerut){
	    $parametros=get_defined_vars();
	    $this->sql->ConsultarSesionesRegistradas();
	    $consulta=$this->sql->query;
	    $this->ejecuta->ejecuta_query($consulta, $parametros);
	    return $this->ejecuta->resultado;
	}
	
	public function getUSER_ID(){
	    return $this->USER_ID;
	}
	public function getID_EXAMEN(){
	    return $this->ID_EXAMEN;
	}
	public function getSITE_ID(){
	    return $this->SITE_ID;
	}	
	public function getEMAIL(){
	    return $this->EMAIL;
	}
	public function getFIRST_NAME(){
	    return $this->FIRST_NAME;
	}
	public function getLAST_NAME(){
	    return $this->LAST_NAME;
	}
	public function getKNUMERUT(){
	    return $this->KNUMERUT;
	}
	public function getTITLE(){
	    return $this->TITLE;
	}
	public function getFECHA_INI(){
	    return $this->FECHA_INI;
	}
	public function getHORA_INI(){
	    return $this->HORA_INI;
	}
	
	
}
?>