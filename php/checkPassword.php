<?php
	header('Content-type:application/json');
	require_once('start-session.php');
	require_once('util.php')   ;

	$user_id =  $_SESSION['user_id'];
	$dbc = mysqli_connect(host, user, password, database)
	    or die("fail to connect");

	/* RECEIVE VALUE */
	$validateValue=$_REQUEST['fieldValue'];
	$validateId=$_REQUEST ['fieldId'];

	$validateError= "The current password is not correct";
	$validateSuccess= "The current password is correct";

	$query = "SELECT password FROM user WHERE  user_id = '$user_id ' AND password = SHA('$validateValue')";
	$data = mysqli_query($dbc, $query)
	    or die('Error query');

	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = $validateId;


	if(mysqli_num_rows($data) == 0){		// validate??
		$arrayToJs[1] = false;			// RETURN FALSE
		echo json_encode($arrayToJs);			// RETURN ARRAY WITH ERROR
	}else{
        $arrayToJs[1] = true;
        echo json_encode($arrayToJs);		// RETURN ARRAY WITH success
    }

	mysqli_close($dbc);

?>
