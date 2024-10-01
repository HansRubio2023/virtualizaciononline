<?php
include_once '/var/www/SITIOS/apps/aula_virtual/variables_globales.php';
include_once '/var/www/SITIOS/apps/aula_virtual/datos/TallerSQL.php';
include_once DEF_EJECUTA;
include_once GETSETTER;

class Taller extends GetSetter{
    public function __construct($p_usercon){
        $this->sql = new TallerSQL();
        $this->ejecuta  = new ejecuta_sql($p_usercon);
    }
    
    public function ObtenerTalleresAsincronicos(){
        $parametros=get_defined_vars();
        $this->sql->ObtenerTalleresAsincronicos();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerTalleresSincronicos(){
        $parametros=get_defined_vars();
        $this->sql->ObtenerTalleresSincronicos();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerDetalleTalleresSincronicos($p_id_taller){
        $parametros=get_defined_vars();
        $this->sql->ObtenerDetalleTalleresSincronicos();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerTallerInfo($p_id_taller){
        $parametros=get_defined_vars();
        $this->sql->ObtenerTallerInfo();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerInscripcionesAsincronicas($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo){
        $parametros=get_defined_vars();
        $this->sql->ObtenerInscripcionesAsincronicas();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerSiteID($p_anhoproc,$p_semestre,$p_kcodasig,$p_paralelo){
        $parametros=get_defined_vars();
        $this->sql->ObtenerSiteID();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
    public function ObtenerEstudiantesAulaVirtual($p_site_id){
        $parametros=get_defined_vars();
        $this->sql->ObtenerEstudiantesAulaVirtual();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }    
    
    public function ObtenerParticipantesSincronicos($p_id_sincronico){
        $parametros=get_defined_vars();
        $this->sql->ObtenerParticipantesSincronicos();
        $consulta=$this->sql->query;
        $this->ejecuta->ejecuta_query($consulta, $parametros);
        return $this->ejecuta->resultado;
    }
    
}