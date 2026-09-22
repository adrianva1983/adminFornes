<?
/***VARIABLES POR GET ***/
$numero = count($_GET);
$tags = array_keys($_GET);
// obtiene los nombres de las varibles
$valores = array_values($_GET);
// obtiene los valores de las varibles
// crea las variables y les asigna el valor
//echo "GET:<hr>";
for($i=0;$i<$numero;$i++)
{
 $$tags[$i]=$valores[$i];
 //echo "<li>$$tags[$i]<li>$valores[$i]</li></li>";
}
/***VARIABLES POR POST ***/
$numero2 = count($_POST);
$tags2 = array_keys($_POST);
// obtiene los nombres de las varibles
$valores2 = array_values($_POST);
// obtiene los valores de las varibles
// crea las variables y les asigna el valor
//echo "POST:<hr>";
for($i=0;$i<$numero2;$i++)
{ 
 $$tags2[$i]=$valores2[$i];
 //echo "<li>$$tags[$i]<li>$valores[$i]</li></li>";
}
/*ahora solo hay que llamar las variables por su nombre
ej: http://.../estearchivo.php?usuario=1&password=2
para verlas solo pones la variable por su nombre
echo "nombre de usuario: ".$usuario."<br>password: ".$password;
en vez de usar $_GET['usuario'] y $_GET['password']
*/
?>