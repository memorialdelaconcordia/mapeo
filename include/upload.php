<html>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="photo" id="photo">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html> 
<?php
//if they DID upload a file...
	echo "revisando si se hizo upload de un archivo<br>";
	if($_FILES['photo']['name']){
		//if no errors...
		echo "revisando que no hayan errores<br>";
		if(!$_FILES['photo']['error']){
			//now is the time to modify the future file name and validate the file
			echo "escribiendo nuevo nombre<br>";
			$new_file_name = strtolower($_FILES['photo']['tmp_name']); //rename file
			echo "revisando tamano<br>";
			if($_FILES['photo']['size'] > (1024000)) //can't be larger than 1 MB
			{
				$valid_file = false;
				$message = 'Oops!  Your file\'s size is to large.';
				echo $message;
			}
			
			//if the file has passed the test
			echo "revisando validez<br>";
			if($valid_file)
			{
				//move it to where we want it to be
				move_uploaded_file($_FILES['photo']['tmp_name'], '../multimedia/'.$new_file_name);
				$message = 'Congratulations!  Your file was accepted.';
				echo $message;
			}
			else{
				
			}
		}
		//if there is an error...
		else{
			//set that to be the returned message
			$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['photo']['error'];
			echo $message;
		}
	}
?>