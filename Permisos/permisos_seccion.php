<?php
function permisos_seccion($usuario_id,$usuario_nivel,$id_seccion)
{
	// NECESITA:
	//  - conexión a base de datos.
	//  - Id de la sección en la que comprobar si el usuario tiene permisos
	//  - Id del usuario
	//  - Nivel de permisos del usuario
	if ($usuario_nivel > 1)
	{// COMPROBACIÓN PERMISOS
		// Tratamos primer elemento. Si tenemos permiso sobre el elemento actual todo va bien	---USUARIO---
		$requete2 = "SELECT * FROM `Permisos` WHERE `IdSeccion`=".$id_seccion." AND `Nivel`<'5' AND `IdUsuarioSuscrito`='".$usuario_id."'";
		
		if ($result = mysqli_query($db, $requete))
		{
			$listado2 = mysqli_fetch_object($result2);
			$nivel = $listado2->Nivel;
		}
		// Si no tenemos permiso sobre el elemento actual recorremos todos los padres ---USUARIO---
		$listado3=$listado;
		if ((!$nivel) AND ($listado3->IdPadre))
		{
			while (($listado3->IdPadre) AND (!$nivel))
			{
				$requete3 = "SELECT * FROM `Secciones` WHERE `Id`='".$listado3->IdPadre."'";
				
      	$listado3 = mysqli_fetch_object($result3);
      	$requete2 = "SELECT * FROM `Permisos` WHERE `IdSeccion`='".$listado3->Id."' AND `Nivel`<'5' AND `IdUsuarioSuscrito`='".$usuario_id."'";
      	
      	if ($result2 = mysqli_query($db, $requete2))
      	{
       		$listado2 = mysqli_fetch_object($result2);
       		$nivel = $listado2->Nivel;
      	}
    	}
  	}
  	return $nivel;
}
else return 1;
}
?>