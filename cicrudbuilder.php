<?php

$mysqli = new mysqli("localhost","root","vertrigo","finance_mngr");
// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

// Make sure source folder have sufficient permission to read files
$src = "S:/VertrigoServ/www/api_builder/SAMPLE";  
$dst = "S:/VertrigoServ/www/api_builder/";

$result = mysqli_query($mysqli,"show tables"); // run the query and assign the result to $result
		echo '<pre>';
		$valid='';
		$params='';
		$tble='';
		$tbody='';
		$addform='';
		$editform='';
while($row = mysqli_fetch_array($result))  
    {   
		echo '================'.$row[0].'================<br>';
		$rs2 = mysqli_query($mysqli,"SHOW COLUMNS FROM ".$row[0]);
        while($rw2 = mysqli_fetch_array($rs2))
        {   
/////////////////////
     
		if($rw2[0]=='id' || $rw2[0]=='status' || $rw2[0]=='created_on' || $rw2[0]=='updated_on'){
			continue;
		}
		// $mydemo="select data_type,CHARACTER_MAXIMUM_LENGTH,NUMERIC_PRECISION from information_schema.columns where table_name = '".$row[0]."'  
		$mydemo="select data_type from information_schema.columns where table_name = '".$row[0]."' 
		and COLUMN_NAME='".$rw2[0]."'";
		$mydemo = mysqli_query($mysqli,$mydemo);
		$mydemo = mysqli_fetch_array($mydemo);
			if($mydemo[0]=='int'){
				$mycount="select NUMERIC_PRECISION from information_schema.columns where table_name = '".$row[0]."' 
							and COLUMN_NAME='".$rw2[0]."'";
				$mycount = mysqli_query($mysqli,$mycount);
				$mycount = mysqli_fetch_array($mycount);
				$mylngth= $mycount[0];
				$fcount="|max_length[".$mylngth."]";
			}elseif($mydemo[0]=='varchar'){
				$mychar="select CHARACTER_MAXIMUM_LENGTH from information_schema.columns where table_name = '".$row[0]."' and COLUMN_NAME='".$rw2[0]."'";
				$mychar = mysqli_query($mysqli,$mychar);
				$mychar = mysqli_fetch_array($mychar);
				$mylngth= $mychar[0];
				$fcount="|max_length[".$mylngth."]";	
			}else{
				$fcount="";
			}
/////////////////////
			$valid.="\$this->form_validation->set_rules('".$rw2[0]."','".ucfirst($rw2[0])."','required".$fcount."')\n";
			$params.="'".$rw2[0]."' => \$this->input->post('".$rw2[0]."'),\n";
			$tble.="<th>".Ucfirst($rw2[0])."</th>\n\t\t";
			$tbody.="<td><?php echo \$c['".$rw2[0]."']; ?></td>\n\t\t";
			$addform.="<div>
		<span class=\"text-danger\">*</span>".$rw2[0]." : 
		<input type=\"text\" name='".$rw2[0]."' value=\"<?php echo \$this->input->post('".$rw2[0]."'); ?>\" />
		<span class=\"text-danger\"><?php echo form_error('".$rw2[0]."');?></span>
	</div>";
		$editform.="<div>
		<span class=\"text-danger\">*</span>".$rw2[0]." : 
		<input type=\"text\" name='".$rw2[0]."' value=\"<?php echo (\$this->input->post('status') ? \$this->input->post('status') : $".$row[0]."['".$rw2[0]."']); ?>\" />
		<span class=\"text-danger\"><?php echo form_error('".$rw2[0]."');?></span>
	</div>";
        }
		// echo $valid;
		// echo $params;
		// echo $tble;
		// echo $tbody;
		$name=$row[0];
		copy_folder($src, $dst,$name,$valid,$params,$tble,$tbody,$addform,$editform);
		echo '================'.$row[0].' Ends Here================<br><br><br>';
    }
function copy_folder($src, $dst,$name,$valid,$params,$tble,$tbody,$addform,$editform) { 
   
    // open the source directory
    $dir = opendir($src); 
    // Make the destination directory if not exist
	$dst=$dst.$name;
    @mkdir($dst); 
    // Loop through the files in source directory
    foreach (scandir($src) as $file) { 
   
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) 
            { 
   
                // Recursively calling custom copy function
                // for sub directory 
                copy_folder($src . '/' . $file, $dst . '/',$name,$valid,$params,$tble,$tbody,$addform,$editform); 
   
            } 
             else { 
				if($file=='add.php' || $file=='edit.php' || $file=='index.php'){
					copy($src . '/' . $file, $dst . '/' . $file);
					/// add my code 
					$path=$dst . '/' . $file;
					$str = file_get_contents($path);
					$str = str_replace('%%tblname%%', $name, $str);
					$str = str_replace('%%thth%%', $tble, $str);
					$str = str_replace('%%tdtd%%', $tbody, $str);
					$str = str_replace('%%addform%%', $addform, $str);
					$str = str_replace('%%editform%%', $editform, $str);
					file_put_contents($path, $str);
					/// add my code ends
				}else{
					$word = "model";					 
					// Test if string contains the word 
					if(strpos($file, $word) !== false){
						$Uname=ucfirst($name);
						copy($src . '/' . $file, $dst . '/' . $Uname.'_model.php');
						/// add my code 
						$path=$dst . '/' . $Uname.'_model.php';
						$str = file_get_contents($path);
						$str = str_replace('%%tblname%%_model', $Uname.'_model', $str);
						$str = str_replace('%%tblname%%', $name, $str);
						file_put_contents($path, $str);
						/// add my code ends
					} else{
						$Uname=ucfirst($name);
						copy($src . '/' . $file, $dst . '/' . $Uname.'.php');
						/// add my code 
						$path=$dst . '/' . $Uname.'.php';
						$str = file_get_contents($path);
						$str = str_replace('class %%tblname%%', 'class '.$Uname, $str);
						$str = str_replace('%%tblname%%_model', $Uname.'_model', $str);
						$str = str_replace('%%tblname%%', $name, $str);
						$str = str_replace('%%validation%%', $valid, $str);
						$str = str_replace('%%params%%', $params, $str);
						file_put_contents($path, $str);
						/// add my code ends
					}
					 
				} 
			} 
        } 
    } 
   
    closedir($dir);
} 

  
?>
  

