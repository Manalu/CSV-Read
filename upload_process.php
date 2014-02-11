<html>
	<link rel="stylesheet" href="../css/z_table.css" />
<div class="CSSTableGenerator">
<?php

// Upload and Rename File

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
//echo implode("|", $myarray);


//header('Location: index.php');

function filename($myarray,$text2find,$title,$x1,$x2)
{
	$find_position = strpos($myarray[0][0], $text2find);
	$filedate = substr($myarray[0][0], $find_position+$x1,10);
	$filedate_to = substr($myarray[0][0], $find_position+$x2,10);
	$filedate =date(str_replace('.','-',$filedate));
	$filename = "$title".$filedate;
	
	$filename_array[0]=$filedate;
	$filename_array[1]=$filedate_to;
	$filename_array[2]=$filename;
	return $filename_array;
}


function read1($filename)
{
	$row = 0;
	$counter = 0;
	
	$file_handle = fopen($filename, "r");
	while (!feof($file_handle)) 
	{
		$counter = 0;
	   	$data[$row] = fgetcsv($file_handle);
	   
		$splitcontents = explode(",", $data[$row]);
		echo "<br>";
		foreach($splitcontents as $mydata)
		{
			echo " | " .$mydata. " | ";
			$fullarray[$row][$counter]=$mydata;
			$counter++;
		}
	   $row++;
	   echo "<br>";
	}
	fclose($file_handle);
	return $fullarray;
}

function readcsv2($filename)
{
	$file = fopen($filename,"r");
	while(! feof($file))
  {
  print_r(fgetcsv($file));
  }

fclose($file);
}

function readcsv($filename)
{
	$row = 1;
	if (($handle = fopen($filename, "r")) !== FALSE) 
	{
		echo "<table class='CSSTableGenerator'>";
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	    {
	    	echo "<tr>";
	        $num = count($data);
	        //echo "<p> $num fields in line $row: <br /></p>\n";
	        $row++;
	        for ($c=0; $c < $num; $c++) 
	        {
	            echo "<td>($row,$c)" .$data[$c] ."</td>";// . "<br />\n";
	        }
			echo "</tr>";
	    }
	    fclose($handle);
		echo "</table>";
	}
}

function display($fullarray,$param,$arrayselected)
{
	$count = 0;$x=0;$y=0;
	foreach ($fullarray as $type) 
	{
		$y=0;
	    $count= 10;//count($type);
	    
		foreach ($type as $dd)
		{
			$data = trim($fullarray[$x][$y]);
			$strlen = strlen($data);
			
			if(substr($data,-5)==$param)
			{
				$temp1 = $temp1 + str_replace(',', '', $fullarray[$x][6]);
				$temp2 = $temp2 + str_replace(',', '', $fullarray[$x][7]);
				$temp3 = $temp3 + str_replace(',', '', $fullarray[$x][8]);
				$temp4 = $temp4 + str_replace(',', '', $fullarray[$x][9]);
			}
			$y++;
		}
		$x++;
	}
	$temp[0]=$temp1;
	$temp[1]=$temp2;
	$temp[2]=$temp3;
	$temp[3]=$temp4;
	return $temp[$arrayselected];
}
?>
</div>
</html>