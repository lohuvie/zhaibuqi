<?php
    require_once('util.php');
    $dbc = mysqli_connect(host,user,password,database)
        or die ('Error connecting to mysql server');

	/* RECEIVE VALUE */
	$validateValue=$_GET['fieldValue'];
	$validateId=$_GET['fieldId'];

	$validateError= "This username is already taken";
	$validateSuccess= "This username is available";

	$query = "SELECT * FROM user WHERE email = '$validateValue'";
	$data = mysqli_query($dbc, $query)
		or die('Error querying database1');
		
	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = $validateId;


	if(mysqli_num_rows($data) == 0){		// validate??
		$arrayToJs[1] = true;			// RETURN TRUE
		echo json_encode($arrayToJs);			// RETURN ARRAY WITH success
	}else{
		for($x=0;$x<1000000;$x++){
			if($x == 990000){
				$arrayToJs[1] = false;
				echo json_encode($arrayToJs);		// RETURN ARRAY WITH ERROR
			}
		}
		
	}

	mysqli_close($dbc);
?>