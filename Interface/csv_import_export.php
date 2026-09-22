<?php
class CSV_import_export
{
  	var $CSVFile; //Fichero CSV
	var $CSVData; //Variable donde se va a ir almacenando la información
	var $CSVError; //Variable donde vamos a ir almacenando los errores
	var $FieldNames; //Contiene los nombres de los campos
	var $Fields; //Array con todos los campos
	var $FieldTypes; //Array con todos los tipos de los campos
	var $fetchCursor; //Contador interno con la posición actual recorriendo el fichero
	var $Filter;
	function CSV_import_export()
	{
	//Constroctor de la clase
		$this->CSVError = array();
		$this->CSVData = "";
		$this->Filter = array();
	}
	function setFile($file)
	{
	//Fija el fichero CSV que se va a utilizar
		$this->CSVFile = $file;

		if(file_exists($this->CSVFile) && ($this->CSVFile != "none") && !empty($this->CSVFile))
		{
			$this->setData(join("",file($this->CSVFile)));
		}
		else
		{
			$this->CSVError[] = "Fichero " . $file . " no encontrado o está vacio.";
			return FALSE;
		}
	}
	function setData($string)
	{
	//Inicializa los datos
		$this->CSVData = $string;
		$this->Fields = array();
		$this->FieldTypes = array();
		$this->FieldNames = array();
	}
	function CSVFetchRow()
	{
	//Devuelve toda la fila como un array y pone en marcha el contador interno.
		if($this->fetchCursor <= $this->CSVNumRows())
		{
			$r = $this->Fields[$this->fetchCursor];
			$this->fetchCursor++;
			return $r;
		}
		else
		{
			$this->CSVError[] = "No hay más filas.";
			return FALSE;
		}
	}
	function CSVFetchArray($resultTyp = "BOTH")
	{
	//NI IDEA QUE HACE Y SI NOS INTERESA
		/*
			liefert die gesamte Zeile als ein assoziatives und/oder numerisches Array
			$resultTyp bestimmt ob es ein assoziatives (ASSOC), numerisches (NUM) oder beides (BOTH)
			zurückgelifert wird
			- ähnlich mysql_fetch_array
		*/
		if($this->fetchCursor <= $this->CSVNumRows())
		{
			if( ($resultTyp == "NUM") || ($resultTyp == "BOTH") )
			{
				$r = $this->CSVFetchRow();
				if($resultTyp == "NUM") return $r;
				$this->fetchCursor--; //Weil diese var von der Funktion CSVFetchRow inkrementiert und im nächsten Fall "ASSOC" dann scheisse liefert
			}
			if( ($resultTyp == "ASSOC") || ($resultTyp == "BOTH") )
			{
				if(is_array($this->Fields[$this->fetchCursor]))
				{
					reset($this->Fields[$this->fetchCursor]);
					while(list($field_id, $field) = each($this->Fields[$this->fetchCursor]))
					{
						$r[$this->FieldNames[$field_id]] = $field;
					}
				}
			}
			$this->fetchCursor++;
			return $r;
		}
		else
		{
			$this->CSVError[] = "Es existieren keine weiteren Datensätze";
			return FALSE;
		}
	}


	
	function CSVFetchFieldNames()
	{
		//Devuelve un array con todos los nombres de los campos
		return $this->FieldNames;
	}
	function CSVFieldName($field_id)
	{
		//Devuelve un nombre de un campo en concreto
		return $this->FieldNames[$field_id];
	}
	function CSVNumRows()
	{
		//Devuelve el número de filas del CSV
		return count($this->Fields);
	}
	function CSVNumFields()
	{
		//Devuelve el número de campos del CSV
		return count($this->FieldNames);
	}
	function setCursor($pos)
	{
		//Fija el puntero interno a la posición indicada.
		$this->fetchCursor = $pos;
	}
	function getCursor()
	{
		//Devuelve la posición actual del puntero interno
		return $this->fetchCursor;
	}
	function resetCursor()
	{
		//Devuelve la posición del cursor al principio
		$this->setCursor(0);
	}
	function getFieldID($search_field)
	{
		//Devuelve el id de un campo en el array de campos. Si no existe retorna falso
		if(!is_array($this->FieldNames)) return FALSE;
		foreach($this->FieldNames AS $field_id => $field_name)
		{
			//echo $search_field . " " . $field_name . " hallo<br>";
			if(trim($search_field) == trim($field_name))
			{
				return $field_id;
			}
		}
		return FALSE;
	}
	function echoCSVError()
	{
		//Muestra todos los errores
		foreach($this->CSVError AS $pos => $error_str)
		{
			echo "- " . ($pos+1) . ". " . $error_str . "<br>";
		}
	}
	function isOK($error_output = TRUE)
	{
		//Devuelve verdadero o falso si todo está bien en el CSV
		if($error_output) $this->echoCSVError();
		return ((count($this->CSVError) > 0) ? FALSE : TRUE);
	}
	function convertFieldType()
	{
		//Convierte el tipo del campo
		if(!count($this->FieldTypes)) return FALSE;
		$this->_prepareFieldTypes();

		$valid_holder = array("m", "h", "D", "M", "Y"); //erlaubte Platzhalter in der Datum-Definition

		foreach($this->Fields AS $line_id => $line)
		{
			foreach($line AS $field_id => $value)
			{
				if(!isset($this->FieldTypes[$field_id]) || empty($this->FieldTypes[$field_id]) || empty($value)) continue;



				$convert_type = $this->FieldTypes[$field_id];
				$convert_arg  = substr($convert_type, strpos($convert_type, "(")+1, strlen($convert_type) - (strpos($convert_type, "(") + 2));

				if(strpos($convert_arg, ",")) //mehrere Argumente
				{
					$convert_arg = explode(",", $convert_arg);

					reset($convert_arg);

					while(list($key2, $value2) = each($convert_arg))
					{
						$value2 = trim($value2);
						if(substr($value2, 0, 1) == "'") $value2 = str_replace("'", "", $value2);

						$convert_arg[$key2] = $value2;
					}
				}

				$convert_value = "";

				switch(trim(strtolower(substr($convert_type, 0, strpos($convert_type, "(")))))
				{
					case "decimal": case "double":
						$convert_value = substr($value, 0, strlen($value) - $convert_arg) . "." . substr($value, $convert_arg * -1) ;
						$convert_value = doubleval($convert_value);
						break;

					case "number": case "int": case "integer":
						$convert_value = str_replace(",", ".", $value);
						$convert_value = doubleval($convert_value);
						break;

					case "date":
						$holder_define = $convert_arg[0]; //hier wurden die Platzhalter definiert
						if(isset($holder_values)) unset($holder_values);


						for($cc = 0; $cc < strlen($value); $cc++)
						{
							$sub_value = substr($value, $cc, 1);
							$holder = substr($holder_define, $cc, 1);

							if(in_array($holder, $valid_holder))
							{
								$holder_values[$holder] .= $sub_value;
							}
						}

						$holder_define = $convert_arg[1];

						for($cc = 0; $cc < strlen($holder_define); $cc++)
						{
							$holder = substr($holder_define, $cc, 1);

							if(in_array($holder, $valid_holder))
							{
								$convert_value .= substr($holder_values[$holder], 0, 1);
								$holder_values[$holder] = substr($holder_values[$holder], 1);
							}
							else
							{
								$convert_value .= $holder;
							}
						}

						break;
				}

				if($convert_value !== $value)  $this->Fields[$line_id][$field_id] = $convert_value;
			}
		}
	}
	function applyFilter()
	{
		/*
			Wendet die gesetzten Filter an.
			Wird von der jeweiligen parseCSV Funktion aufgerufen.
		*/

		if(!count($this->Filter)) return FALSE;

		$results = array();
		$cc = 0;

		foreach($this->Filter AS $match_field => $match_values)
		{
			$match_field = substr($match_field, 0, strpos($match_field, "||"));

			//Bei Trennzeichen-CSV-Dateien steht im key anstatt der id der name des Feldes. Hier wird die id des feldes ermittelt
			if(!is_numeric($match_field) && (($fieldID = $this->getFieldID($match_field)) !== FALSE) ) $match_field = $fieldID;


			$operator = $match_values["operator"];
			$match_value = $match_values["match_value"];

			$results[$cc] = $this->Fields;

			foreach($this->Fields AS $line => $fields)
			{
				$filterResult = FALSE;

				switch($operator)
				{
					case "==": case "=":
						if($fields[$match_field] == $match_value) $filterResult = TRUE;

						break;

					case "!=" : case "!":
						if($fields[$match_field] != $match_value) $filterResult = TRUE;

						break;

					case "<": case "lt":
						if($fields[$match_field] < $match_value) $filterResult = TRUE;

						break;

					case ">": case "gt":
						if($fields[$match_field] > $match_value) $filterResult = TRUE;

						break;
				}


				if(!$filterResult)
				{
					/*
						Der definierte Filter trifft für diesen Datensatz zu,
						deswegen löschen wir einfach mal diese Zeile aus dem
						ErgebnisPuffer
					*/

					unset($results[$cc][$line]);
				}

			}

			$cc++;
		}


		//Die einzelnen Ergebnisse jedes Filters werden jetzt zusammengefasst
		$this->Fields = array();
		foreach($results AS $fields) $this->Fields = $this->array_merge_better($this->Fields, $fields);

		//Lückenlose (d.h. durchgehend nummeriert) Arrayzusammensetzung
		$r = "";
		foreach($this->Fields AS $fields) $r[] = $fields;
		$this->Fields = $r;


		/*
		//Debugging
		echo "<pre><b>Filter:</b>";
		print_r($this->Filter);
		echo "<b>Separierte Ergebnisse durch die jeweiligen Filter</b>";
		print_r($results);
		echo "<b>Feldernamen:</b>";
		print_r($this->FieldNames);
		echo "\n\n\n<b>Felder:</b>";
		print_r($this->Fields);
		echo "</pre>";
		*/




		return TRUE;

	}
	function dumpResult()
	{
		//Muestra por pantalla el contenido del CSV
		$prevFetchCursor = $this->getCursor();
		$this->resetCursor();

		$fields = $this->CSVFetchFieldNames();
		echo "<table border='1'><tr>";

		foreach($fields AS $feld)
		{
			echo "<th>" . $feld . "</th>";
		}

		echo "</tr>";

		while($row = $this->CSVFetchArray("ASSOC"))
		{
			echo "<tr>";

			foreach($row AS $feld)
			{
				echo "<td>&nbsp;" . $feld . "</td>";
			}

			echo "</tr>";
		}

		echo "</table>";

		$this->setCursor($prevFetchCursor);
	}
}
class CSVImport extends CSV_import_export
{
	var $FieldDelim;
	function CSVImport()
	{
		parent::CSV_import_export();
		$this->FieldDelim = ",";
	}
	function setDelim($delimiter)
	{
		//Fijar el carácter delimitador de campos
		$this->FieldDelim = $delimiter;
	}
	function parseCSV()
	{
		//Parsea todo el CSV
		if($this->CSVData)
		{
			$akt_line  = 0;
			$akt_field = 0;
			$akt_field_value = "";
			$last_char = "";
			$quote = 0;
			$field_input = 0;
			$head_complete = 0;

			$end_cc = strlen($this->CSVData);

			for($cc = 0; $cc < $end_cc; $cc++)
			{
				$akt_char = substr($this->CSVData,$cc,1);

				if(($akt_char == "\"") && ($last_char != "\\")) //Abschliessung des eingeschlossenen Feldes. beschreibung siehe unten
				{
					$quote = !$quote;
					$akt_char = "";
				}

				if(!$quote)
				{
					if($akt_char == $this->FieldDelim) //Trennzeichen
					{
						$field_input = !$field_input;
						$akt_char = "";
						$akt_field++;
						$akt_field_value = "";
					}
					elseif(($akt_char == "\\") && $field_input) //Escape-Zeichen
					{
						$field_input++;
						$quote++;
					}
					elseif($akt_char == "\"") //Anführungszeichen kennzeichenen ein eingeschlossenes Feld, d.h. dieses Feld kann das Trennzeichen als Text enthalten und mehrzeilig sein.
					{
						$quote--;

						if($field_input)
							$field_input--;
						else
							$field_input++;
					}
					elseif($akt_char == "\n") //Neuer Datensatz
					{

						if($head_complete && (($akt_field+1) > $this->CSVNumFields()))
						{
							$this->CSVError[] = "Error en la línea " . ($akt_line + 2) . "</b>";
						}

						$akt_line++;
						$akt_field = 0;
						if(!$head_complete) $akt_line = 0;
						$head_complete = 1;
						$akt_char = "";
						$akt_field_value = "";
					}
				}


				$last_char = $akt_char;
				if($akt_char == "\\") $akt_char = "";
				$akt_field_value .= $akt_char;



				if($head_complete)
				{
					$this->Fields[$akt_line][$akt_field] = trim($akt_field_value); //Felder befüllung
				}
				else
				{
					$this->FieldNames[$akt_field] = trim($akt_field_value); //Feldernamen befüllung
				}

			}


			if(!$akt_field) //Leeren Abschluss-Datensatz entfernen
			{
				unset($this->Fields[$akt_line]);
			}

			parent::convertFieldType();
			parent::applyFilter();
			$this->fetchCursor = 0;

			/*
			//Debugging
			echo "<pre><b>Feldernamen:</b>";
			print_r($this->FieldNames);
			echo "\n\n\n<b>Felder:</b>";
			print_r($this->Fields);
			echo "</pre>";
			*/


		}
		else
		{
			$this->CSVError[] = "Los Datos del CSV no se han cargado.";
			return FALSE;
		}
	}
}
class CSVExport extends CSV_import_export
{
	function createcsv($sql,$db,$fichero)
	{
		$resultEXPORT = mysql_query($sql,$db);		
		if (($resultEXPORT) && (mysqli_num_rows($resultEXPORT)>0)){}
		else $this->CSVError[] = "Errores en los datos o cadena vacia";
		if (($resultEXPORT) && (mysqli_num_rows($resultEXPORT)>0))
		{
			$string = "";			
			$num = mysql_num_fields($resultEXPORT);
			for ($i=0;$i<$num;$i++) $string.= mysql_field_name($resultEXPORT, $i).$this->FieldDelim;
			$string.=chr(13);
			while ($listadoEXPORT = mysql_fetch_row($resultEXPORT))
			{
				for ($i=0;($i<count($listadoEXPORT));$i++) $string.= $listadoEXPORT[$i].$this->FieldDelim;
				$string.=chr(13);
			}
		}
		if ($fichero)
		{
			$fp = fopen($fichero,'w');
			fwrite($fp,$string);
			fclose($fp);
		}
	}
	function setDelim($delimiter)
	{
		//Fijar el carácter delimitador de campos
		$this->FieldDelim = $delimiter;
	}
}
?>