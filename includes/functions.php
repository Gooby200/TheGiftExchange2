<?php	
	function getRegistryItems($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Users.FirstName, Users.LastName, RegistryItem.ItemID, RegistryItem.ProductName, RegistryItem.ProductLocation, RegistryItem.ProductPrice, RegistryItem.Notes, RegistryItem.ProductImage, RegistryItem.AskingAmount, RegistryItem.PurchasedAmount FROM RegistryItem, Users WHERE RegistryID=? AND Hidden=0 AND Users.UserID=RegistryItem.UserID ORDER BY RegistryItem.ItemID DESC");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $firstName, $lastName, $itemID, $productName, $productLocation, $productPrice, $productNotes, $productImage, $productAskingAmount, $productPurchasedAmount);
			$result = mysqli_stmt_num_rows($stmt);
			
			if ($result > 0) {
				$items = "";
				
				while (mysqli_stmt_fetch($stmt)) {
					if ($productImage != "") {
						$productImage = "<img src=\"$productImage\" class=\"table-image\" />";
					} else {
						$productImage = "";
					}
					
					$productPrice = "$" . number_format($productPrice, 2);
					
					if ($productNotes != "") {
						$productNotes = "<h3><span data-toggle=\"tooltip\" data-placement=\"top\" title=\"$productNotes\" id=\"ttNotes$itemID\" class=\"glyphicon glyphicon-envelope\" aria-hidden=\"true\"></span></h3>";
					} else {
						$productNotes = "";
					}
					
					$updateButton = "";
					
					if ($productPurchasedAmount == $productAskingAmount) {
						$productAskingAmount = "<strong><span style=\"color: green;\">$productAskingAmount</span></strong>";
						$productPurchasedAmount = "<strong><span style=\"color: green;\">$productPurchasedAmount</span></strong>";
					} else {
						$productPurchasedAmount = "<input class=\"form-control\" id=\"totalBought$itemID\" style=\"min-width: 100px;\" oninput=\"numberChange($itemID);\" type=\"number\" min=\"$productPurchasedAmount\" max=\"$productAskingAmount\" value=\"$productPurchasedAmount\" />";
						
						$updateButton = "<input type=\"button\" value=\"Save\" id=\"saveButton$itemID\" onclick=\"save($itemID);\" style=\"display: none;\" class=\"form-control updateButton\" />";
					}
					
					$items = "$items <tr>
								  <td><center>$lastName, $firstName</center></td>
								  <td><a href=\"$productLocation\" target=\"_blank\">$productName</a></td>
								  <td><center>$productPrice</center></td>
								  <td><center>$productNotes</center></td>
								  <td><center>$productImage</center></td>
								  <td><center>$productAskingAmount</center></td>
								  <td><center>$productPurchasedAmount$updateButton</center></td>
							  </tr>";
							  
				}
				
				return $items;
			} else {
				return "";
			}
			
		} catch (Exception $ex) {
			return $ex;
		}
	}

	function doesRegistryExist($registryID) {
		try {			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM Registries WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
				
			if ($result == 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			return false;
		}
	}

	function checkRegistryAssociation($userID, $registryID) {
		try {			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM RegistryAssociations WHERE UserID=? AND RegistryID=?");
			mysqli_stmt_bind_param($stmt, 'ss', $userID, $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
				
			if ($result == 1) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			return false;
		}
	}

	function getBelongRegistries($userID) {
		try {
			$registries = "";
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Registries.RegistryName, Registries.RegistryID FROM Registries, RegistryAssociations WHERE Registries.RegistryID=RegistryAssociations.RegistryID AND RegistryAssociations.UserID=?");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $registryName, $registryID);
				
			while (mysqli_stmt_fetch($stmt)) {
				$registryName = trim($registryName);
				$registries = "$registries <option value=\"$registryID\">$registryName</option>\r\n";
			}
			
			return $registries;
		} catch (Exception $ex) {
			return "";
		}
	}
	
	function changePassword($userID, $email, $currentPassword, $newPassword) {
		if (trim($email) == "" || trim($currentPassword) == "" || trim($newPassword) == "") {
			return false;
		} else {
			try {
				$correctCredentials = false;
				
				$dbhost = "gastonpesa.com";
				$dbuser = "gooby200_admin";
				$dbpass = "5zN&EH=6ztg4";
				$dbname = "gooby200_giftregistry";
				
				$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				
				$stmt = mysqli_prepare($link, "SELECT Password FROM Users WHERE UserID=? AND Email=?");
				mysqli_stmt_bind_param($stmt, 'ss', $userID, $email);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				mysqli_stmt_bind_result($stmt, $hashPass);
				
				if (mysqli_stmt_fetch($stmt)) {
					if (password_verify($currentPassword, $hashPass)) {
						$correctCredentials = true;
					}
				}
								
				if ($correctCredentials) {
					$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
					
					$stmt = mysqli_prepare($link, "UPDATE Users SET Password=? WHERE UserID=? AND Email=?");
					mysqli_stmt_bind_param($stmt, 'sss', $newPassword, $userID, $email);
					mysqli_stmt_execute($stmt);
					
					return true;
				} else {
					return false;
				}
			} catch (Exception $ex) {
				return false;
			}
		}
	}
	
	function updatePersonalInformation($userID, $firstName, $lastName, $birthDate) {
		if (trim($firstName) == "" || trim($lastName) == "" || trim($birthDate) == "") {
			return false;
		} else {
			if (strlen($birthDate) != 10) {
				return false;
			} else {
				try {
					$dbhost = "gastonpesa.com";
					$dbuser = "gooby200_admin";
					$dbpass = "5zN&EH=6ztg4";
					$dbname = "gooby200_giftregistry";
					
					$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
					
					$stmt = mysqli_prepare($link, "UPDATE Users SET FirstName=?, LastName=?, BirthDate=? WHERE UserID=?");
					mysqli_stmt_bind_param($stmt, 'ssss', $firstName, $lastName, $birthDate, $userID);
					mysqli_stmt_execute($stmt);
					
					
					return true;
				} catch (Exception $ex) {
					return false;
				}
			}
		}
	}
	
	function createRegistry($userID, $registryName, $isEditOk, $isPrivate) {
		try {
			if (trim($registryName) == "") {
				return false;
			} else {
				$dbhost = "gastonpesa.com";
				$dbuser = "gooby200_admin";
				$dbpass = "5zN&EH=6ztg4";
				$dbname = "gooby200_giftregistry";
				
				$registryID = "-1";
				
				$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				
				$stmt = mysqli_prepare($link, "INSERT INTO Registries VALUES (NULL, ?, ?, ?, ?)");
				mysqli_stmt_bind_param($stmt, 'ssss', $userID, $registryName, $isEditOk, $isPrivate);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				$registryID = mysqli_stmt_insert_id($stmt);
				
				$stmt = mysqli_prepare($link, "INSERT INTO RegistryAssociations VALUES (NULL, ?, ?)");
				mysqli_stmt_bind_param($stmt, 'ss', $userID, $registryID);
				mysqli_stmt_execute($stmt);
				
				return true;
			}
		} catch (Exception $ex) {
			return false;
		}
	}
	
	function getBirthDate($userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT BirthDate FROM Users WHERE UserID=?");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $birthDate);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				while (mysqli_stmt_fetch($stmt)) {
					return $birthDate;
				}
			} else {
				return "";
			}
		} catch (Exception $ex) {
			echo $ex;
			return "";
		}
	}
	
	function canEditRegistry($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT EditOk FROM Registries WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $canEdit);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				if (mysqli_stmt_fetch($stmt)) {
					if ($canEdit == "1") {
						return true;
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
			return false;
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function isRegistryPrivate($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Private FROM Registries WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $isPrivate);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				if (mysqli_stmt_fetch($stmt)) {
					if ($isPrivate == "1") {
						return true;
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
			return false;
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function getRegistryName($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT RegistryName FROM Registries WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $registryName);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				while (mysqli_stmt_fetch($stmt)) {
					return $registryName;
				}
			} else {
				return "";
			}
		} catch (Exception $ex) {
			echo $ex;
			return "";
		}
	}
	
	function getLastName($userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT LastName FROM Users WHERE UserID=?");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $lastName);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				while (mysqli_stmt_fetch($stmt)) {
					return $lastName;
				}
			} else {
				return "";
			}
		} catch (Exception $ex) {
			echo $ex;
			return "";
		}
	}
	
	function getFirstName($userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT FirstName FROM Users WHERE UserID=?");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $firstName);
			$result = mysqli_stmt_num_rows($stmt);
			if ($result == 1) {
				while (mysqli_stmt_fetch($stmt)) {
					return $firstName;
				}
			} else {
				return "";
			}
		} catch (Exception $ex) {
			echo $ex;
			return "";
		}
	}
	
	function updatePassword($email, $newPassword) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$newPassword = password_hash($newPassword, PASSWORD_BCRYPT);
			
			$stmt = mysqli_prepare($link, "UPDATE Users SET Password=?, PasswordResetToken=NULL WHERE Email=?");
			mysqli_stmt_bind_param($stmt, 'ss', $newPassword, $email);
						
			return mysqli_stmt_execute($stmt);
		} catch (Exception $ex) {
			echo $ex;
			return false;
		}
	}
	
	function verifyTokenInformation($email, $token) {
		try {
			if (trim($token) == "" || $token == null) {
				return false;
			}
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM Users WHERE Email=? AND PasswordResetToken=?");
			mysqli_stmt_bind_param($stmt, 'ss', $email, $token);
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
	
	function destroySession() {
		session_destroy();
		header("Location: index.php");
	}
	
	function isLoggedIn() {
		session_start();
		
		if (isset($_SESSION["userID"])) {
			if ($_SESSION["userID"] == null || $_SESSION["userID"] == -1) {
				return false;
			} else {
				return true;
			}
		} else {
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
						
			session_start();
			
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
	
	function verifyAccountByEmail($email, $password) {		
		$dbhost = "gastonpesa.com";
		$dbuser = "gooby200_admin";
		$dbpass = "5zN&EH=6ztg4";
		$dbname = "gooby200_giftregistry";
	
		$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		
		$stmt = mysqli_prepare($link, "SELECT UserID, Password FROM Users WHERE Email=?");
		mysqli_stmt_bind_param($stmt, 's', $email);
		
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