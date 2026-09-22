<?php
//COMPROBAMOS EL NIVEL DE ACCESO
require($_SERVER['DOCUMENT_ROOT']."/administra/Permisos/aut_verifica.inc.php");
$nivel_acceso=2; // Nivel de acceso para esta página.
// NIVEL MÍNIMO ADMINISTRADOR
if ($nivel_acceso <= $_SESSION['usuario_nivel'])
{
	Print "No tiene permisos para acceder a este &aacute;rea";
	exit;
}
if ($_SERVER['HTTP_REFERER'] == ""){
	die ("Error cod.:1 - Acceso incorrecto!");
	exit;
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/conexion.php");

//Cargamos el idioma
require($_SERVER['DOCUMENT_ROOT']."/administra/Usuarios/idiomas/importar_csv2-".$_SESSION['idioma'].".conf");
//Cargamos el sistema de importación
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/csv_import_export.php");
if ($submitImport=="")
{
	print "<p>".$lang["fichero"]."</p>";
	$csv_import = new CSVImport;
	$csv_import->setFile($CSV);
	$csv_import->setDelim(";");
	$csv_import->parseCSV();
	if($csv_import->isOK())
	{
		print $lang["numFilas"].": <strong>" . $csv_import->CSVNumRows() . "</strong><br/>";
		print $lang["numCampos"].": <strong>" . $csv_import->CSVNumFields() . "</strong><br/>";
		$NombresCampos = $csv_import->CSVFetchFieldNames();
		print "<form action=\"/administra/Interface/herramienta.php?modulo=Usuarios&herramienta=importar_csv2\" enctype=\"multipart/form-data\" method=\"POST\">";
		print "<ul>";		
		$columnas = array ('Nombre','Apellidos','Direccion','Ciudad','Municipio','CP','Provincia','Pais','Idioma','Email','Telefono','Movil','nombreEmpresa','CIF','Nick');
		for ($i=0;($i<($csv_import->CSVNumFields()));$i++)
		{
			print "<li>";
			print $csv_import->CSVFieldName($i)."<br/>";
			print "<input type=\"text\" style=\"display:inline;\"  name=\"Campo_".$i."_texto\" value=\"\">";
			print "<select style=\"display:inline;\" name=\"Campo_".$i."\">";
			if (in_array($csv_import->CSVFieldName($i), $columnas)) print "<option value=\"\"></option>";
			else print "<option value=\"\" SELECTED></option>";
			if ($csv_import->CSVFieldName($i)=="Nombre") print "<option value=\"Nombre\" SELECTED>".$lang["nombre"]."</option>";
			else print "<option value=\"Nombre\">".$lang["nombre"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Apellidos") print "<option value=\"Apellidos\" SELECTED>".$lang["apellidos"]."</option>";
			else print "<option value=\"Apellidos\">".$lang["apellidos"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Direccion") print "<option value=\"Direccion\" SELECTED>".$lang["direccion"]."</option>";
			else print "<option value=\"Direccion\">".$lang["direccion"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Ciudad") print "<option value=\"Ciudad\" SELECTED>".$lang["ciudad"]."</option>";
			else print "<option value=\"Ciudad\">".$lang["ciudad"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Municipio") print "<option value=\"Municipio\" SELECTED>".$lang["municipio"]."</option>";
			else print "<option value=\"Ciudad\">".$lang["ciudad"]."</option>";
			if ($csv_import->CSVFieldName($i)=="CP") print "<option value=\"CP\" SELECTED>".$lang["CP"]."</option>";
			else print "<option value=\"CP\">".$lang["CP"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Provincia") print "<option value=\"Provincia\" SELECTED>".$lang["provincia"]."</option>";
			else print "<option value=\"Provincia\">".$lang["provincia"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Pais") print "<option value=\"Pais\" SELECTED>".$lang["pais"]."</option>";
			else print "<option value=\"Pais\">".$lang["pais"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Idioma") print "<option value=\"Idioma\" SELECTED>".$lang["idioma"]."</option>";
			else print "<option value=\"Idioma\">".$lang["idioma"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Email") print "<option value=\"Email\" SELECTED>".$lang["email"]."</option>";
			else print "<option value=\"Email\">".$lang["email"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Telefono") print "<option value=\"Telefono\" SELECTED>".$lang["telefono"]."</option>";
			else print "<option value=\"Telefono\">".$lang["telefono"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Movil") print "<option value=\"Movil\" SELECTED>".$lang["movil"]."</option>";
			else print "<option value=\"Movil\">".$lang["movil"]."</option>";
			if ($csv_import->CSVFieldName($i)=="nombreEmpresa") print "<option value=\"nombreEmpresa\" SELECTED>".$lang["nombreEmpresa"]."</option>";
			else print "<option value=\"nombreEmpresa\">".$lang["nombreEmpresa"]."</option>";
			if ($csv_import->CSVFieldName($i)=="CIF") print "<option value=\"CIF\" SELECTED>".$lang["CIF"]."</option>";
			else print "<option value=\"CIF\">".$lang["CIF"]."</option>";
			if ($csv_import->CSVFieldName($i)=="Nick") print "<option value=\"Nick\" SELECTED>".$lang["nick"]."</option>";
			else print "<option value=\"Nick\">".$lang["nick"]."</option>";
			print "</select>";
			print "</li>";
		}
		print "<li><label for=\"CSV\">".$lang["CSV"].": </label><input id=\"CSV\" name=\"CSV\" type=\"file\"></li>";
		print "</ul>";
		print "<hr/><p>".$lang["otrosCampos"]."<p>";
		print "<ul><li>".$lang["enGrupo"].":";
		$requete = "SELECT * FROM `Grupos` ORDER BY `Orden`";		
		
		print "<select name=\"Grupo\">";
		print "<option value=\"\" SELECTED></option>";
		while($listado = mysqli_fetch_object($result))
		{
			print "<option value=\"".$listado->Id."\">".$listado->Nombre."</option>";
		}
		print "</select></li>";
		print "<li>".$lang["activado"].": <input type=\"radio\" style=\"display:inline;\" value=\"si\" name=\"Activado\"/>".$lang["si"]."<input style=\"display:inline;\" type=\"radio\" value=\"no\" name=\"Activado\"/>".$lang["no"]."</li>";
		print "<li>".$lang["boletin"].": <input style=\"display:inline;\" type=\"radio\" value=\"si\" name=\"Boletin\"/>".$lang["si"]."<input style=\"display:inline;\" type=\"radio\" value=\"no\" name=\"Boletin\"/>".$lang["no"]."</li>";
		print "<li>".$lang["sms"].": <input style=\"display:inline;\" type=\"radio\" value=\"si\" name=\"SMS\"/>".$lang["si"]."<input style=\"display:inline;\" type=\"radio\" value=\"no\" name=\"SMS\"/>".$lang["no"]."</li>";
		print "<li>".$lang["ExclusivoMailing"].": <input style=\"display:inline;\" type=\"radio\" value=\"si\" name=\"ExclusivoMailing\"/>".$lang["si"]."<input style=\"display:inline;\" type=\"radio\" value=\"no\" name=\"ExclusivoMailing\"/>".$lang["no"]."</li>";
		$requete = "SELECT * FROM `NivelesPermisos` WHERE `Idioma`='".$_SESSION['idioma']."' ORDER BY `Nivel`";
		
		print "<li>".$lang["nivelAcceso"]."<select name=\"NivelAcceso\"><option value=\"\" SELECTED></option>";
		while($listado = mysqli_fetch_object($result))
		{
			if ($listado->Nivel>$_SESSION['usuario_nivel'])
			{
				print "<option value=\"".$listado->Nivel."\">".$listado->Nombre."</option>";		    
			}
		}
		print "</select></li>";
		print "</ul>";
		print "<input class=\"boton\" type=\"submit\" name=\"submitImport\" id=\"submitImport\" value=\"".$lang["anadirContactos"]."\">";
		print "</form>";		
		$csv_import->dumpResult();
	}
}
else
{
	$numCreados = 0;
	$numAsignadosGrupo = 0;
	$csv_import = new CSVImport;
	$csv_import->setFile($CSV);
	$csv_import->setDelim(";");
	$csv_import->parseCSV();
	if($csv_import->isOK())
	{		
		//Miramos si hay algún campo clave seleccionado para evitar duplicados por ese campo
		$claveEmail = "";$claveCIF = "";$hayClave=false;$condicionclaveEmail=false;$condicionclaveCIF=false;
		for ($i=0;($i<($csv_import->CSVNumFields()));$i++)	
		{
			if ($_POST["Campo_".$i]== "Email") 
			{				
				$claveEmail = $i;			
				$condicionclaveEmail = true;
				$hayClave=true;
			}			
			if ($_POST["Campo_".$i]== "CIF")
			{
				$claveCIF = $i;
				$condicionclaveCIF = true;
				$hayClave=true;
			}
		}
		$csv_import->resetCursor(); //Nos colocamos al principio		
		while($row = $csv_import->CSVFetchArray("ASSOC")) //Recorremos el CSV
		{			
			if ($hayClave)
			{ //Compruebo si existe el dato en nuestra base de datos				
				$requete = "SELECT * FROM `Usuarios` WHERE ";
				if ($condicionclaveEmail)
				{					
					$campo = trim($csv_import->FieldNames[$claveEmail]); //TRIM() Quita espacios en blanco
					$requete .="`Email`='".$row[$campo]."'";
				}
				if ($condicionclaveCIF)
				{
					$campo = trim($csv_import->FieldNames[$claveCIF]); //TRIM() Quita espacios en blanco
					$requete .=" OR `CIF`='".$row[$campo]."'";
				}
				else
				{
					$campo = trim($csv_import->FieldNames[$claveCIF]); //TRIM() Quita espacios en blanco
					if ($claveCIF!="") $requete .="`CIF`='".$row[$campo]."'";	
				}				
								
				if ($result = mysqli_query($db, $requete)) 
				{					
					$listado = mysqli_fetch_object($result);
					//Actualizamos el contacto metiéndolo en el grupo si es que no estaba ya y siempre que se haya seleccionado un grupo
					if ($Grupo!="")
					{
						$requete = "SELECT * FROM `PertenenciaGrupos` WHERE `IdGrupo`='".$Grupo."' AND `IdUsuario`='".$listado->Id."'"; 
						
						if ($result = mysqli_query($db, $requete)) 
						{
							//Estaba en el grupo, por lo que no hago nada
						}
						else
						{
							$requete = "INSERT INTO `PertenenciaGrupos` (`IdGrupo`, `IdUsuario`) VALUES ('".$Grupo."', '".$listado->Id."');";
							mysqli_query($db,$requete);				
							//Agregado el usuario al grupo
							$numAsignadosGrupo++;
						}
					}
				}
				else
				{
					//No existe el usuario por lo que hay que crearlo
					$requete = "INSERT INTO `Usuarios` (";
					$primero = true;
					for ($j=0;($j<($csv_import->CSVNumFields()));$j++)
					{						
						$campo = $csv_import->FieldNames[$j];
						if  ((($_POST["Campo_".$j])!="")&&($row[$campo]!=""))
						{
							if ($primero)
							{
								$requete .= "`".$_POST["Campo_".$j]."`";
								$primero = false;
							}
							else $requete .= ",`".$_POST["Campo_".$j]."`";
						}
					}
					if ($Activado!="") $requete .=",`Activado`";
					if ($Boletin!="") $requete .=",`AltaBoletin`";
					if ($SMS!="") $requete .=",`AltaSMS`";
					if ($ExclusivoMailing!="") $requete .=",`ExclusivoMailing`";				
					if ($NivelAcceso!="") $requete .=",`NivelAcceso`";
					//Asigno los valores
					$requete .= ") VALUES (";
					$primero = true;
					for ($j=0;($j<($csv_import->CSVNumFields()));$j++)
					{						
						$campo = $csv_import->FieldNames[$j];
						if  ((($_POST["Campo_".$j])!="")&&($row[$campo]!=""))
						{							
							if ($primero) 
							{
								$requete .= "'".$row[$campo]."'";
								$primero = false;
							}
							else $requete .= ",'".$row[$campo]."'";
						}
					}
					if ($Activado!="") $requete .=",'".$Activado."'";
					if ($Boletin!="") $requete .=",'".$Boletin."'";
					if ($SMS!="") $requete .=",'".$SMS."'";
					if ($ExclusivoMailing!="") $requete .=",'".$ExclusivoMailing."'";
					if ($NivelAcceso!="") $requete .=",'".$NivelAcceso."'";
					$requete .= ");";
					mysqli_query($db,$requete);				
					$idUsuario = mysqli_insert_id($db);
					$numCreados++;
					if ($Grupo!="") //Lo asigno al grupo seleccionado
					{
						$requete = "INSERT INTO `PertenenciaGrupos` (`IdGrupo`, `IdUsuario`) VALUES ('".$Grupo."', '".$idUsuario."');";
						mysqli_query($db,$requete);	
						$numAsignadosGrupo++;						
					}
				}
			}
			else
			{
				//No hay que comprobar si existe así que lo metemos directamente
				$requete = "INSERT INTO `Usuarios` (";
				$primero = true;
				for ($j=0;($j<($csv_import->CSVNumFields()));$j++)
				{					
					$campo = $csv_import->FieldNames[$j];
					if  ((($_POST["Campo_".$j])!="")&&($row[$campo]!=""))
					{
						if ($primero) 
						{
							$requete .= "`".$_POST["Campo_".$j]."`";
							$primero = false;
						}
						else $requete .= ",`".$_POST["Campo_".$j]."`";
					}							
				}
				if ($Activado!="") $requete .=",`Activado`";
				if ($Boletin!="") $requete .=",`AltaBoletin`";
				if ($SMS!="") $requete .=",`AltaSMS`";
				if ($ExclusivoMailing!="") $requete .=",`ExclusivoMailing`";
				if ($NivelAcceso!="") $requete .=",`NivelAcceso`";
				//Asigno los valores
				$requete .= ") VALUES (";
				$primero = true;
				for ($j=0;($j<($csv_import->CSVNumFields()));$j++)
				{					
					$campo = $csv_import->FieldNames[$j];
					if  ((($_POST["Campo_".$j])!="")&&($row[$campo]!=""))
					{						
						if ($primero)
						{
							$requete .= "'".$row[$campo]."'";
							$primero = false;
						}
						else $requete .= ",'".$row[$campo]."'";
					}
				}
				if ($Activado!="") $requete .=",'".$Activado."'";
				if ($Boletin!="") $requete .=",'".$Boletin."'";
				if ($SMS!="") $requete .=",'".$SMS."'";
				if ($ExclusivoMailing!="") $requete .=",'".$ExclusivoMailing."'";
				if ($NivelAcceso!="") $requete .=",'".$NivelAcceso."'";
				$requete .= ");";
				mysqli_query($db,$requete);
				$numCreados;
				$idUsuario = mysqli_insert_id($db);
				if ($Grupo!="") //Lo asigno al grupo seleccionado
				{
					$requete = "INSERT INTO `PertenenciaGrupos` (`IdGrupo`, `IdUsuario`) VALUES ('".$Grupo."', '".$idUsuario."');";
					mysqli_query($db,$requete);
					$numAsignadosGrupo++;					
				}
			}
		}
	}
	print "<p class=\"mensajeOK\">".$lang["numImportados"].": <strong>".$numCreados."</strong><br/>";
	print $lang["numAsigGrupo"].": <strong>".$numAsignadosGrupo."</strong></p>";	
}
require($_SERVER['DOCUMENT_ROOT']."/administra/Interface/cierre.php");
?>