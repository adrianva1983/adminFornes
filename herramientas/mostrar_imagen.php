<?php
//Necesita
//   - $imagen: Imagen a pintar
//   - $ancho: Ancho de la foto en pixels
//   - $alto: Alto de la foto en pixels
//   - $marcaAguaImagen: Imagen que será marca de agua
//   - $marcaAguaTexto: Texto de marca de agua
//$extension = mime_content_type($imagen);
$tmp = explode(".",$imagen);
$extension = $tmp[count($tmp)-1];
switch ($extension)
{
case "jpeg":
case "jpg":
case "JPEG":
case "JPG":
  $imagen_origen = imagecreatefromjpeg($imagen);
  break;
case "gif":
case "GIF":
  $imagen_origen = imagecreatefromgif($imagen);
  break;
case "png":
case "PNG":  
  $imagen_origen = imagecreatefrompng($imagen);
  break;
}
list($width, $height) = getimagesize($imagen);
// Evitamos que la foto se distorsione
$proporcion = $width / $height;
$proporcion2 = $ancho / $alto;
if ($ancho>=$alto)
{
   if ($proporcion<$proporcion2)
   {
     $ancho_temp = $ancho;
     $alto_temp = $ancho_temp / $proporcion;
   }
   else
   {
     $alto_temp = $alto;
     $ancho_temp = $alto_temp * $proporcion;
   }
}
else
{
   if ($proporcion>$proporcion2)
   {
     $alto_temp = $alto;
     $ancho_temp = $alto_temp * $proporcion;
   }
   else
   {
     $ancho_temp = $ancho;
     $alto_temp = $ancho_temp / $proporcion;
   }
}
$imagen_temp = imagecreatetruecolor($ancho_temp,$alto_temp);
imagecopyresampled ($imagen_temp, $imagen_origen, 0, 0, 0, 0, $ancho_temp, $alto_temp, $width, $height);
$imagen2 = imagecreatetruecolor($ancho,$alto);
if ($ancho>=$alto)
{
   if ($proporcion<$proporcion2)
   {
   $caja_x1 = 0;
   $caja_x2 = $ancho_temp;
   $caja_y1 = ($alto_temp/2) - ($alto/2);
   $caja_y2 = ($alto_temp/2) + ($alto/2);
   }
   else
   {
   $caja_x1 = ($ancho_temp/2) - ($ancho/2);
   $caja_x2 = ($ancho_temp/2) + ($ancho/2);
   $caja_y1 = 0;
   $caja_y2 = $alto_temp;
   }
}
else
{
   if ($proporcion<$proporcion2)
   {
   $caja_x1 = 0;
   $caja_x2 = $ancho_temp;
   $caja_y1 = ($alto_temp/2) - ($alto/2);
   $caja_y2 = ($alto_temp/2) + ($alto/2);
   }
   else
   {
   $caja_x1 = ($ancho_temp/2) - ($ancho/2);
   $caja_x2 = ($ancho_temp/2) + ($ancho/2);
   $caja_y1 = 0;
   $caja_y2 = $alto_temp;
   }
}
//print "<br>cajax1:".$caja_x1;
//print "<br>cajax2:".$caja_x2;
//print "<br>cajay1:".$caja_y1;
//print "<br>cajay2:".$caja_y2;
imagecopymerge ($imagen2, $imagen_temp, 0, 0, $caja_x1, $caja_y1, $caja_x2, $caja_y2, 100);
//print "<br>Extension:".$extension;
switch ($extension)
{
case "jpeg":
case "jpg":
case "JPEG":
case "JPG":
  imagejpeg($imagen2, null, 100);
  break;
case "gif":
case "GIF":
  imagegif($imagen2, null);
  break;
case "png":
case "PNG":
  imagepng($imagen2, null);
  break;
}
?>