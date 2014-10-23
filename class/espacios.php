
<?php 

/*
* clase especios
*
* Clase encargada del manejo de los espacios fisicos 
* @author	Christian David Criollo <cdcriollo@icesi.edu.co>
* @since	2014-06-20
*/

session_start();
include_once("../../model/OracleServices.php");

class espacios
{
	private $tipo;
	private $oficina;
	private $antelacion;
	private $html;
	private $listespfis;
	
// Metodos

function setTipo($tipo)
{
  $tipo= $tipo;
}

function getTipo()
{
  return $tipo;
}

function setoficina($oficina)
{
  $oficina=$oficina; 	
}

function getOficina()
{
  return $oficina;	
}

function setAntelacion($antelacion)
{
	$antelacion= $antelacion;
}

function getAntelacion()
{
  return $antelacion;	
}

// Función que se encarga de obtener los tipos de espacio dependiendo del rol del usuario
function obtenerTipoEspacio($tipousuario)
{   
    // Tipos de espacios que puede reservar el colaborador 
   	if ($tipousuario == "colaborador" || $tipousuario== "Empleado Temporal")
	{
	    $html= '<option value="" selected="selected">Seleccione</option>
		<option value="scv">Sal&oacuten con Videoproyector</option>
		<option value="ssv">Sal&oacuten sin Videoproyector</option>
        <option value="cg">Camara de Gesell</option>
        <option value="sbu">Sal&oacuten de Bienestar Universitario</option>
        <option value="l">Laboratorio</option>
        <option value="a">Auditorios</option>
        <option value="sc">Sala de C&oacutemputo</option>
        <option value="sa">Sala de Reuniones</option>
        <option value="ug">Espacios de Uso General</option>';
		
	}
	
	// Tipos de espacios que puede reservar un estudiante de pregrado o postgrado
	else if($tipousuario == "Estudiante Pregrado" || $tipousuario == "Estudiante Postgrado")
	{
		$html= '<option value="" selected="selected">Seleccione</option>
		<option value="scv"> sal&oacuten con Videoproyector</option>
		<option value="ssv">Sal&oacuten sin Videoproyector</option>
        <option value="l">Laboratorio</option>
        <option value="ug">Espacios de Uso General</option>
		'; 
		
	}
	// Tipos de espacio que puede reservar un profesor de hora catedra
	else if($tipousuario == "Profesor Hora Catedra")
	{
		$html= '<option value="" selected="selected">Seleccione</option>
		<option value="scv">Sal&oacuten con Videoproyector</option>
		<option value="ssv">Sal&oacuten sin Videoproyector</option>
        <option value="cg">Camara de Gesell</option>
        <option value="sbu">Sal&oacuten de Bienestar Universitario</option>
        <option value="a">Auditorios</option>
        <option value="sc">Sala de C&oacutemputo</option>
        <option value="sa">Sala de Reuniones</option>
        <option value="ug">Espacios de Uso General</option>
		'; 
		
	}
	return $html;
	
   }// cierra funcion 
   
   
   //Funcion que se encarga de obtener los espacios fisicos de los tipos de espacio
   function obtenerEspacioFisico($tipoespacio)
   {
	  $espacio= new OracleServices('../../config/.config');
	  $espacio->conectar();
	  $sql="";
	 
	  switch($tipoespacio)
	  {
		  case 'sc':
		  $sql="SELECT CODIGO, DESCRIPCION FROM TBAS_RECURSOS WHERE CODIGO LIKE 'SW%' ORDER BY DESCRIPCION"; 
		  break;
		  
		  case 'sa':
		  $sql="SELECT CODIGO, DESCRIPCION FROM TBAS_ESPACIOS_FISIC WHERE TIPO= 'SA' AND CODIGO IN ('SDA', 'SVC1', 'SVC2') AND ESTADO='A' ORDER BY DESCRIPCION";
		  break;
		  
		  case 'l':
		  if($_SESSION['tipo']== 'Estudiante Pregrado' || $_SESSION['tipo']== 'Estudiante Postgrado')
		  {
		    $sql=  "SELECT CODIGO, DESCRIPCION FROM TBAS_ESPACIOS_FISIC WHERE TIPO= 'L' AND CODIGO IN ('102F', '103F') AND ESTADO='A' ORDER BY DESCRIPCION";
		  }
		  else
		  {
			$sql=  "SELECT CODIGO, DESCRIPCION FROM TBAS_ESPACIOS_FISIC WHERE TIPO IN ('L', 'BVC', 'STG') AND ESTADO='A' ORDER BY DESCRIPCION";  
		  }
		  break;
		  
		  default:
			$sql= "SELECT CODIGO, DESCRIPCION FROM TBAS_ESPACIOS_FISIC where TIPO= '". strtoupper($tipoespacio)."' AND ESTADO='A' ORDER BY DESCRIPCION";
		  break;
		  
	  }// cierra switch
	  
	  $resultado=$espacio->ejecutarConsulta($sql);
	  $fila= $espacio->siguienteFila($resultado);
	  
	  
	  if($tipoespacio== "sc")
	  {
	   echo '<option value="'.htmlentities($fila['CODIGO']).'">'.htmlentities($fila['DESCRIPCION']).'</option>'; 
	  }
	  else
	  {
		echo '<option value="'.htmlentities($fila['CODIGO']).'">'.$fila['CODIGO']." ".'-'." ".htmlentities($fila['DESCRIPCION']).'</option>';   
	  }
	  
	  
	  while (($row = oci_fetch_array($resultado, OCI_BOTH)) != false)  
	  {
		 if($tipoespacio== "sc")
		 { 
           echo '<option value="'.htmlentities($row['CODIGO']).'">'.htmlentities($row['DESCRIPCION']).'</option>';       
		 }
		 else
		 {
			echo '<option value="'.htmlentities($row['CODIGO']).'">'.$row['CODIGO']." ".'-'." ".htmlentities($row['DESCRIPCION']).'</option>';  
		 }
     }
	  
	  // Se cierra la conexion BD
	  $espacio->desconectar();    
	  	    
   }// cierra funcion
   
 }// cierra clase

?>