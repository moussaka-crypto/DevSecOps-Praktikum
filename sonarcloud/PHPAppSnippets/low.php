<?php

if( isset( $_GET[ 'Login' ] ) ) {
	// Get username
	$user = $_GET[ 'username' ];

	// Get password
	$pass = $_GET[ 'password' ];
	$pass = md5( $pass );

	// Check the database
	$query  = "SELECT * FROM `users` WHERE user = ? AND password = ?";
	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query); //or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

	mysqli_stmt_bind_param($stmt, "ss", $user, $pass);

    	mysqli_stmt_execute($stmt);

    	$result = mysqli_stmt_get_result($stmt);
	
	if( $result && mysqli_num_rows( $result ) == 1 ) {
		// Get users details
		$row    = mysqli_fetch_assoc( $result );
		$avatar = $row["avatar"];

		// Login successful
		$html .= "<p>Welcome to the password protected area {$user}</p>";
		$html .= "<img src=\"{$avatar}\" />";
	}
	else {
		// Login failed
		$html .= "<pre><br />Username and/or password incorrect.</pre>";
	}
	mysqli_stmt_close($stmt);
	//((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}



if (isset($_GET['Login'])) {
    $user = $_GET['username'];
    $pass = $_GET['password'];

    // Hash the password using bcrypt
    $passHash = password_hash($pass, PASSWORD_BCRYPT);

    $query = "SELECT * FROM `users` WHERE user = ?";
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query); //or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );
	
    mysqli_stmt_bind_param($stmt, "s", $user);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        // Get user details
        $row = mysqli_fetch_assoc($result);
        $storedHash = $row["password"];

        // Verify the password
        if (password_verify($pass, $storedHash)) {
            $avatar = $row["avatar"];
            $html .= "<p>Welcome to the password-protected area {$user}</p>";
            $html .= "<img src=\"{$avatar}\" />";
        } else {
            $html .= "<pre><br />Username and/or password incorrect.</pre>";
        }
    } else {
        $html .= "<pre><br />Username and/or password incorrect.</pre>";
    }

    mysqli_stmt_close($stmt);
    //((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

?>
