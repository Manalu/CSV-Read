<html>
	<link rel="stylesheet" href="../css/z_table.css" />
<div class="CSSTableGenerator">
<?php

// Upload and Rename File3

if (isset($_POST['submit']))
{
	$filename = $_FILES["file"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
	$file_ext = substr($filename, strripos($filename, '.')); // get file name
	$filesize = $_FILES["file"]["size"];
	$allowed_file_types = array('.doc','.txt','.xls','.pdf','.XLS','.csv');
	
	if (in_array($file_ext,$allowed_file_types) && ($filesize < 200000))
	{
	// Rename file
		$newfilename = "temp".strtolower($file_ext);//md5($file_basename) . $file_ext;
		move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $newfilename);
	}
	else
	{
		unlink($_FILES["file"]["tmp_name"]);
	}
}

//$myarray = read1("upload/temp.csv");
$myarray = readcsv("upload/temp.csv");
$material = read_array($myarray,2,2);
$sector = read_array($myarray,2,0);

for ($i=0; $i <= 12 ; $i++) 
{ 
	$month[$i] = read_array($myarray,2,3+$i);
}

$temp .= "<table class='CSSTableGenerator'>";
for ($i=0; $i < count($month[0]); $i++) 
{
	$data[0] = $sector[$i];
	$data[1] = $material[$i];
	
	
	for ($x=0; $x < count($month); $x++) 
	{
		if($month[0][$i]!="1STHALF") /* check first row value, this avoid error */
		{
			$data[$x+2]=$month[$x][$i]; /* write data to the right (sector,material,1,2,3,4-12) */
		}
	}
	
	if($data[1]!="SUBTOTAL")
	{
	$temp .=  "<tr>";
	foreach ($data as $value)
	{
		$temp .=  "<td>$value</td>"; /* extract data in horizontal format */
		$content[$i][] = $value;
	}
	$temp .=  "</tr>";
	}
}
$temp .=  "</table>";
echo $temp;
//echo implode(",", $content[1]);
//header('Location: index.php');


function readcsv($filename)
{
	$row = 0;
	$temp = "";
	if (($handle = fopen($filename, "r")) !== FALSE) 
	{
		$temp .= "<table class='CSSTableGenerator'>";
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	    {
	    	$temp .=  "<tr>";
	        $num = count($data);
	        //echo "<p> $num fields in line $row: <br /></p>\n";
	        //$row++;
	        for ($c=0; $c < $num; $c++) 
	        {
	        	//$fullarray[$row][$c] = $data[$c];
				$fullarray[$row][$c]["col"] = $data[$c];
				$fullarray[$row][$c]["pos"] = "$row,$c";
	            $temp .=  "<td>($row,$c)" .$data[$c] ."</td>";// . "<br />\n";
	        }
			$row++;
			$temp .=  "</tr>";
	    }
	    fclose($handle);
		$temp .=  "</table>";
	}
	//echo $temp;
	//echo $fullarray[3][1]["pos"];
	return $fullarray;
}

function read_array($fullarray,$row,$col)
{
	$r = $row;
	if(!$row) $r=0;
	if(!$col) $c=0;
	
	$temp = "";
	
	$temp .= "<table class='CSSTableGenerator'>";
	foreach ($fullarray as $row) 
	{
		//$c=0;
		$c = $col;
		$temp .=  "<tr>";
		//foreach ($row as $col) 
		{
			//$content = strtoupper($fullarray[$r][$c]["col"]);
			$content= strtoupper($fullarray[$r][$c]["col"]);
			$content = str_replace(array(" ", " "), "", $content);
			$content_array[] = $content;
			
			$position = $col["pos"];
			//$temp .=  "<td>$content</td>";
			$temp .=  "<td>".$content_array[$r]."</td>";
			//$c++;
		}
		$temp .=  "</tr>";
		$r++;
	}
	$temp .=  "</table>";
	//echo $temp;
	return $content_array;
}

function searchword($fullarray,$findthis)
{
	$r=0;$c=0;
	$temp = "";
	
	$temp .= "<table class='CSSTableGenerator'>";
	foreach ($fullarray as $row) 
	{
		$temp .=  "<tr>";
		foreach ($row as $col) 
		{
			$content = strtoupper($col["col"]);
			$content = str_replace(array(" ", " "), "", $content);
			
			$position = $col["pos"];
			$temp .=  "<td>($position)$content</td>";
			if($content == "JAN")
			{
				break 2;
			}
		}
		$temp .=  "</tr>";
	}
	$temp .=  "</table>";
	//echo $temp;
	return $position;
}

?>
</div>
</html>