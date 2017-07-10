<?php	
	function registerAccount($username, $firstname, $lastname, $birthdate, $email, $password) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			if (trim($username) == "" || trim($password) == "") {
				return false;
			} else {
				if (doesUserAlreadyExist($username)) {
					return false;
				} else {
					if (doesEmailAlreadyExist($email)) {
						return false;
					} else {
						date_default_timezone_set("America/Chicago");
						$date = date("Y/m/d H:i:s");
						$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
						
						$stmt = mysqli_prepare($link, 'INSERT INTO Users (Username, FirstName, LastName, Email, Password, CreatedDate, LastLogon, BirthDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
						mysqli_stmt_bind_param($stmt, 'ssssssss', $username, $firstname, $lastname, $email, $password, $date, $date, $birthdate);
						return mysqli_stmt_execute($stmt);
					}
				}
			}
			
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function doesEmailAlreadyExist($email) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM Users WHERE Email=?");
			mysqli_stmt_bind_param($stmt, 's', $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			
			if (mysqli_stmt_num_rows($stmt) >= 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function doesUserAlreadyExist($username) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM Users WHERE Username=?");
			mysqli_stmt_bind_param($stmt, 's', $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			
			if (mysqli_stmt_num_rows($stmt) >= 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function successfulLogin($userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "UPDATE Users SET LastLogon=? WHERE UserID=?");
			mysqli_stmt_bind_param($stmt, 'ss', $today, $userID);
			mysqli_stmt_execute($stmt);
			
			$_SESSION["userID"] = $userID;
			header("Location: home.php");
		} catch (Exception $ex) {
			echo $ex;
		}
	}
		
	function verifyAccount($username, $password) {		
		$dbhost = "gastonpesa.com";
		$dbuser = "gooby200_admin";
		$dbpass = "5zN&EH=6ztg4";
		$dbname = "gooby200_giftregistry";
	
		$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		
		$stmt = mysqli_prepare($link, "SELECT UserID, Password FROM Users WHERE Username=?");
		mysqli_stmt_bind_param($stmt, 's', $username);
		
		mysqli_stmt_execute($stmt);
		
		mysqli_stmt_store_result($stmt);
		
		mysqli_stmt_bind_result($stmt, $userID, $hashPass);
		
		$result = mysqli_stmt_num_rows($stmt);
		
		if ($result == 1) {
			while (mysqli_stmt_fetch($stmt)) {
				if (password_verify($password, $hashPass)) {
					return $userID;
				} else {
					return -1;
				}
			}
		} else {
			return -1;
		}
		
		mysqli_stmt_close($stmt);
		mysqli_close($link);
	
	}
?>