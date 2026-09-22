<html>
<head>
<meta content="text/html; charset=UTF8" http-equiv="Content-Type"/>
</head>
<body>
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/administra/Scripts-Gestion/parser_html/simple_html_dom.php");

for ($j=1;$j<=1;$j++)
{	
	$url = "http://www.paginasamarillas.es/search/gimnasios/all-ma/all-pr/all-is/cuenca/all-ba/all-pu/all-nc/1?what=gimnasios&where=cuenca&nb=false&ub=false&pc=Cuenca%26jaen%26soria%26cordoba";
	if (!$html = file_get_html($url))
	{
		if (!$html = file_get_html($url))
		{
			if (!$html = file_get_html($url))
			{}
		}
	}
	$i = 0;
	foreach ($html->find("li.m-results-business") as $empresa)
	{		
		foreach ($empresa->find("h3.m-results-business--name a span") as $element_nombre)
		{		
			$empresas[$i] = $element_nombre->plaintext;
		}	
		foreach ($empresa->find("div ul li.is-maxi a") as $campo)
		{
			$tmp = explode(":",$campo->href);
			if ($tmp[0]=="mailto") 
			{
				$empresas[$i] .= ",".$tmp[1];
				print $empresas[$i]."<br/>";
			}
			else $i--;
		}
		$i++;	
	}
}
?>
</body>
</html>