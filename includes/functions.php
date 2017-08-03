<?php	
	
	if (isset($_POST["action"]) && !empty($_POST["action"])) {
		
		$action = $_POST["action"];
		
		if ($action == "UpdateAmount") {
			if (isset($_POST["itemID"]) && $_POST["itemID"] != null && trim($_POST["itemID"]) != "" && isset($_POST["amount"]) && $_POST["amount"] != null && trim($_POST["amount"]) != ""  && isset($_POST["registryID"]) && $_POST["registryID"] != null && trim($_POST["registryID"]) != "") {
				$itemID = $_POST["itemID"];
				$amount = $_POST["amount"];
				$registryID = $_POST["registryID"];
				$currentAmount = $_POST["currentAmount"];
							
				echo updateRegistryItemAmount($registryID, $itemID, $amount, $currentAmount);
			}
		} else if ($action == "InviteUser") {
			if (isset($_POST["a"]) && $_POST["a"] != null && trim($_POST["a"]) != "" && isset($_POST["emailAddress"]) && $_POST["emailAddress"] != null && trim($_POST["emailAddress"]) && isset($_POST["b"]) && $_POST["b"] != null && trim($_POST["b"])) {
				$registryID = $_POST["a"];
				$email = $_POST["emailAddress"];
				$userID = $_POST["b"];
				
				echo inviteUser($email, $registryID, $userID);
			}
		} else if ($action == "ManageInvite") {
			if (isset($_POST["a"]) && $_POST["a"] != null && trim($_POST["a"]) != "" && isset($_POST["b"]) && $_POST["b"] != null && trim($_POST["b"]) && isset($_POST["c"]) && $_POST["c"] != null && trim($_POST["c"])) {
				$accepted = $_POST["a"];
				$registryID = $_POST["b"];
				$userID = $_POST["c"];
				
				if ($accepted == 1) {
					echo acceptInvite($userID, $registryID);
				} else if ($accepted == 0) {
					echo declineInvite($userID, $registryID);
				} else {
					echo 0;
				}
			}
		}
	}
	
	function updateInvitationEmail($registryID, $token, $userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
						
			$stmt = mysqli_prepare($link, "UPDATE InvitedUsers SET InvitedUsers.Email=(SELECT Users.Email FROM Users WHERE Users.UserID=?) WHERE InvitedUsers.RegistryID=? AND InvitedUsers.Token=?");
			mysqli_stmt_bind_param($stmt, 'sss', $userID, $registryID, $token);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_affected_rows($stmt);
			
			if ($result > 0) {
				return true;
			} else {
				return false;
			}
			return false;
		} catch (Exception $ex) {
			return false;
		}
	}
	
	function verifyInvitationToken($registryID, $token) {
		try {
			if (trim($token) == "" || $token == null) {
				return false;
			}
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT * FROM InvitedUsers WHERE RegistryID=? AND Token=?");
			mysqli_stmt_bind_param($stmt, 'ss', $registryID, $token);
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
	
	function sendInvitation($email, $token, $registryID, $registryName, $invitedBy) {
		try {
			$to = $email;
			$subject = "TheGiftExchange.net - New Invitation!";
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';

			// Additional headers
			$headers[] = 'From: TheGiftExchange Invitations <noreply@thegiftexchange.net>';
			$headers[] = 'Reply-To: noreply@thegiftexchange.net';
			$headers[] = 'X-Mailer: PHP/' . phpversion();

			$message = "Greetings $email!<br />
						<br />
						You have been invited by $invitedBy to join the registry <strong>$registryName</strong>.<br />
						<br />
						If you already have an account, <a href=\"http://www.thegiftexchange.net/invitemanagement.php?id=$registryID&token=$token\" target=\"_blank\">click here</a> to login and associate your account with the invitation.<br />
						<br />
						If you need to create an account, <a href=\"http://www.thegiftexchange.net/register.php?id=$registryID&token=$token\" target=\"_blank\">click here</a> to register an account and associate your new account with the invitation.<br />
						<br />
						Best regards,<br />
						<a href=\"http://www.thegiftexchange.net/\" target=\"_blank\">TheGiftExchange.net</a>";
						
			if (mail($to, $subject, $message, implode("\r\n", $headers))) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			return false;
		}
	}
	
	function declineInvite($userID, $registryID) {
		try {			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			//delete the user from the invited users table
			$stmt = mysqli_prepare($link, "DELETE FROM InvitedUsers WHERE InvitedUsers.RegistryID=? AND InvitedUsers.Email=(SELECT Users.Email FROM Users WHERE Users.UserID=?)");
			mysqli_stmt_bind_param($stmt, 'ss', $registryID, $userID);
			mysqli_stmt_execute($stmt);
			
			return 1;
		} catch (Exception $ex) {
			return 0;
		}
	}
	
	function acceptInvite($userID, $registryID) {
		try {			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			//insert the user into registry associations table
			$stmt = mysqli_prepare($link, "INSERT INTO RegistryAssociations (ID, UserID, RegistryID) VALUES (null, ?, ?)");
			mysqli_stmt_bind_param($stmt, 'ss', $userID, $registryID);
			mysqli_stmt_execute($stmt);
			
			//delete the user from the invited users table
			$stmt = mysqli_prepare($link, "DELETE FROM InvitedUsers WHERE InvitedUsers.RegistryID=? AND InvitedUsers.Email=(SELECT Users.Email FROM Users WHERE Users.UserID=?)");
			mysqli_stmt_bind_param($stmt, 'ss', $registryID, $userID);
			mysqli_stmt_execute($stmt);
			
			return 1;
		} catch (Exception $ex) {
			return 0;
		}
	}
	
	function getNewInvitationCount($userID) {
		try {			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT COUNT(Registries.RegistryID) FROM Registries LEFT JOIN InvitedUsers ON InvitedUsers.RegistryID = Registries.RegistryID WHERE InvitedUsers.Email = (SELECT Users.Email FROM Users WHERE Users.UserID=?)");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $count);
				
			if (mysqli_stmt_fetch($stmt)) {
				return $count;
			}
			
			return 0;
		} catch (Exception $ex) {
			return 0;
		}
	}
	
	function getAcceptInvitedRegistries($userID) {
		try {
			$inviteList = "";
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Registries.RegistryID, Registries.RegistryName FROM Registries LEFT JOIN InvitedUsers ON InvitedUsers.RegistryID = Registries.RegistryID WHERE InvitedUsers.Email = (SELECT Users.Email FROM Users WHERE Users.UserID=?)");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $registryID, $registryName);
				
			while (mysqli_stmt_fetch($stmt)) {
				$inviteList .= "<tr id=\"invite$registryID\">
									<td>$registryName</td>
									<td class=\"text-right\"><input type=\"button\" value=\"Accept\" class=\"btn btn-success\" onclick=\"manage(1, $registryID, $userID)\" /></td>
									<td class=\"text-right\"><input type=\"button\" value=\"Decline\" class=\"btn btn-danger\" onclick=\"manage(0, $registryID, $userID)\" /></td>
								</tr>\r\n";
			}
			
			return $inviteList;
		} catch (Exception $ex) {
			return "";
		}
	}
	
	function generateToken($length) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$token = "";
		for ($i = 0; $i < $length; $i++) {
			$token .= $chars[rand(0, strlen($chars) -1)];
		}
		return $token;
	}
	
	function isUserAlreadyInvited($email, $registryID) {
		try {		
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
						
			$stmt = mysqli_prepare($link, "SELECT * FROM InvitedUsers WHERE Email=? AND RegistryID=?");
			mysqli_stmt_bind_param($stmt, 'ss', $email, $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
				
			if ($result > 0) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $ex) {
			return false;
		}
	}	
	
	function inviteUser($email, $registryID, $userID) {
		try {		
			if (isUserOwner($registryID, $userID)) {
				if (!isUserAlreadyInvited($email, $registryID)) {
					$dbhost = "gastonpesa.com";
					$dbuser = "gooby200_admin";
					$dbpass = "5zN&EH=6ztg4";
					$dbname = "gooby200_giftregistry";
					
					$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
					
					$token = generateToken(25);
					
					$stmt = mysqli_prepare($link, "SELECT Email FROM Users WHERE UserID=?");
					mysqli_stmt_bind_param($stmt, 's', $userID);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					mysqli_stmt_bind_result($stmt, $invitedBy);
						
					if (mysqli_stmt_fetch($stmt)) {
						$stmt = mysqli_prepare($link, "INSERT INTO InvitedUsers (ID, RegistryID, Email, Token) VALUES (null, ?, ?, ?)");
						mysqli_stmt_bind_param($stmt, 'sss', $registryID, $email, $token);
						mysqli_stmt_execute($stmt);

						if (sendInvitation($email, $token, $registryID, getRegistryName($registryID), $invitedBy)) {
							return 1;
						} else {
							return -4;
						}
					} else {
						return -5;
					}
				} else {
					return -3;
				}
			} else {
				return -1;
			}
		} catch (Exception $ex) {
			return -2;
		}
	}
	
	function getInvitedRegistries($userID) {
		try {
			$inviteList = "";
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Registries.RegistryID, Registries.RegistryName FROM Registries LEFT JOIN InvitedUsers ON InvitedUsers.RegistryID = Registries.RegistryID WHERE InvitedUsers.Email = (SELECT Users.Email FROM Users WHERE Users.UserID=?)");
			mysqli_stmt_bind_param($stmt, 's', $userID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $registryID, $registryName);
				
			while (mysqli_stmt_fetch($stmt)) {
				$inviteList = "$inviteList <option value=\"$registryID\">$registryName</option>\r\n";
			}
			
			return $inviteList;
		} catch (Exception $ex) {
			return "";
		}
	}
	
	function getInvitedUsersList($registryID) {
		try {
			$inviteList = "";
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT `InvitedUsers`.`ID`, `InvitedUsers`.`Email`, `Users`.`FirstName`, `Users`.`LastName` FROM `InvitedUsers` LEFT JOIN `Users` ON `InvitedUsers`.`Email` = `Users`.`Email` WHERE `InvitedUsers`.`RegistryID`=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $id, $email, $firstName, $lastName);
				
			while (mysqli_stmt_fetch($stmt)) {
				if ($firstName == null || $lastName == null || $firstName == "" || $lastName == "") {
					$inviteList = "$inviteList <option value=\"$id\">$email</option>\r\n";
				} else {
					$inviteList = "$inviteList <option value=\"$id\">$lastName, $firstName ($email)</option>\r\n";
				}
			}
			
			return $inviteList;
		} catch (Exception $ex) {
			return "";
		}
	}
	
	function getUserList($registryID) {
		try {
			$userList = "";
			
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT Users.UserID, Users.FirstName, Users.LastName, Users.Email FROM Users, RegistryAssociations WHERE Users.UserID = RegistryAssociations.UserID AND RegistryAssociations.RegistryID = ?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $userID, $firstName, $lastName, $email);
				
			while (mysqli_stmt_fetch($stmt)) {
				$userList = "$userList <option value=\"$userID\">$lastName, $firstName ($email)</option>\r\n";
			}
			
			return $userList;
		} catch (Exception $ex) {
			return "";
		}
	}
	
	function deleteRegistry($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				
			//remove registry
			$stmt = mysqli_prepare($link, "DELETE FROM Registries WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			
			//remove registry items
			$stmt = mysqli_prepare($link, "DELETE FROM RegistryItem WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);

			//remove registry associations
			$stmt = mysqli_prepare($link, "DELETE FROM RegistryAssociations WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			
			//remove registry invitations
			$stmt = mysqli_prepare($link, "DELETE FROM InvitedUsers WHERE RegistryID=?");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			
			return true;
		} catch (Exception $ex) {
			return false;
		}
	}

	
	function updateRegistrySettings($userID, $registryID, $registryName, $editPermissionLevel, $viewPermissionLevel) {
		try {
			if (trim($registryName) == "") {
				return false;
			} else {
				if (isUserOwner($registryID, $userID)) {
					$dbhost = "gastonpesa.com";
					$dbuser = "gooby200_admin";
					$dbpass = "5zN&EH=6ztg4";
					$dbname = "gooby200_giftregistry";
					
					$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				
					$registryName = htmlentities($registryName);
				
					$stmt = mysqli_prepare($link, "UPDATE Registries SET RegistryName=?, EditOk=?, Private=? WHERE RegistryID=? AND UserID=?");
					mysqli_stmt_bind_param($stmt, 'sssss', $registryName, $editPermissionLevel, $viewPermissionLevel, $registryID, $userID);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					$result = mysqli_stmt_affected_rows($stmt);
					
					if ($result == 1) {
						return true;
					} else {
						return false;
					}
				}
			}
		} catch (Exception $ex) {
			return false;
		}
	}
	
	function isUserOwner($registryID, $userID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
						
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			$stmt = mysqli_prepare($link, "SELECT * FROM Registries WHERE RegistryID=? AND UserID=?");
			mysqli_stmt_bind_param($stmt, 'ss', $registryID, $userID);
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
	
	function createRegistryItem($registryID, $userID, $productName, $productLocation, $productPrice, $notes, $productImage, $askingAmount) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
						
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$registryID = htmlentities($registryID);
			$userID = htmlentities($userID);
			$productName = htmlentities($productName);
			$productLocation = htmlentities($productLocation);
			$productPrice = htmlentities($productPrice);
			$notes = htmlentities($notes);
			$productImage = htmlentities($productImage);
			$askingAmount = htmlentities($askingAmount);
			
			$stmt = mysqli_prepare($link, "INSERT INTO RegistryItem (RegistryID, UserID, ProductName, ProductLocation, ProductPrice, Notes, ProductImage, AskingAmount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			mysqli_stmt_bind_param($stmt, 'ssssssss', $registryID, $userID, $productName, $productLocation, $productPrice, $notes, $productImage, $askingAmount);
			mysqli_stmt_execute($stmt);
			
			return true;
		} catch (Exception $ex) {
			return false;
		}
	}
	
	function updateRegistryItemAmount($registryID, $itemID, $amount, $currentAmount) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$amount = htmlentities($amount);
			
			$stmt = mysqli_prepare($link, "UPDATE RegistryItem SET PurchasedAmount=? WHERE ItemID=? AND RegistryID=? AND PurchasedAmount=?");
			mysqli_stmt_bind_param($stmt, 'ssss', $amount, $itemID, $registryID, $currentAmount);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_affected_rows($stmt);
			
			if ($result > 0) {
				return 1;
			} else {
				return -1;
			}
			return -3;
		} catch (Exception $ex) {
			return -2;
		}
	}

	function getRegistryItems($registryID) {
		try {
			$dbhost = "gastonpesa.com";
			$dbuser = "gooby200_admin";
			$dbpass = "5zN&EH=6ztg4";
			$dbname = "gooby200_giftregistry";
			
			$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			
			$stmt = mysqli_prepare($link, "SELECT RegistryItem.UserID, Users.FirstName, Users.LastName, RegistryItem.ItemID, RegistryItem.ProductName, RegistryItem.ProductLocation, RegistryItem.ProductPrice, RegistryItem.Notes, RegistryItem.ProductImage, RegistryItem.AskingAmount, RegistryItem.PurchasedAmount FROM RegistryItem, Users WHERE RegistryID=? AND Hidden=0 AND Users.UserID=RegistryItem.UserID ORDER BY RegistryItem.ItemID DESC");
			mysqli_stmt_bind_param($stmt, 's', $registryID);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $userID, $firstName, $lastName, $itemID, $productName, $productLocation, $productPrice, $productNotes, $productImage, $productAskingAmount, $productPurchasedAmount);
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
						$productPurchasedAmount = "<input class=\"form-control\" id=\"totalBought$itemID\" style=\"min-width: 100px;\" oninput=\"numberChange($itemID);\" type=\"number\" pattern=\"\d*\" min=\"$productPurchasedAmount\" max=\"$productAskingAmount\" value=\"$productPurchasedAmount\" />";
						
						$updateButton = "<input type=\"button\" value=\"Save\" id=\"saveButton$itemID\" onclick=\"save($itemID, $registryID);\" style=\"display: none;\" class=\"form-control updateButton\" />";
						
						$productAskingAmount = "<strong><span id=\"asking$itemID\" style=\"color: red;\">$productAskingAmount</span></strong>";
					}
					
					if ($userID != "-1") {
						$name = "$lastName, $firstName";
					} else {
						$name = "Unregistered User";
					}
					
					if ($productLocation != "") {
						$productName = "<a href=\"$productLocation\" target=\"_blank\">$productName</a>";
					}
					
					$items = "$items <tr>
								  <td>$name</td>
								  <td>$productName</td>
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
					
					$firstName = htmlentities($firstName);
					$lastName = htmlentities($lastName);
					$birthDate = htmlentities($birthDate);
					
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
	
	function createRegistry($userID, $registryName, $editPermissionLevel, $viewPermissionLevel) {
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
				
				$registryName = htmlentities($registryName);
				
				$stmt = mysqli_prepare($link, "INSERT INTO Registries VALUES (NULL, ?, ?, ?, ?)");
				mysqli_stmt_bind_param($stmt, 'ssss', $userID, $registryName, $editPermissionLevel, $viewPermissionLevel);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				$registryID = mysqli_stmt_insert_id($stmt);
				
				$stmt = mysqli_prepare($link, "INSERT INTO RegistryAssociations VALUES (NULL, ?, ?)");
				mysqli_stmt_bind_param($stmt, 'ss', $userID, $registryID);
				mysqli_stmt_execute($stmt);
				
				return $registryID;
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
	
	function registryAddItemPermission($registryID) {
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
					return $canEdit;
				}
			} else {
				return -1;
			}
			return -1;
		} catch (Exception $ex) {
			echo $ex;
			return -1;
		}
	}
	
	function registryPrivatePermission($registryID) {
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
					return $isPrivate;
				}
			} else {
				return -1;
			}
			return -1;
		} catch (Exception $ex) {
			echo $ex;
			return -1;
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
				return "Please enter a username.";
			} else {
				if (doesUserAlreadyExist($username)) {
					return "Username already exists.";
				} else {
					if (doesEmailAlreadyExist($email)) {
						return "Email already exists.";
					} else {
						date_default_timezone_set("America/Chicago");
						$date = date("Y/m/d H:i:s");
						$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
						
						$firstname = htmlentities($firstname);
						$lastname = htmlentities($lastname);
						$birthdate = htmlentities($birthdate);
						$password = password_hash($password, PASSWORD_BCRYPT);
						
						$stmt = mysqli_prepare($link, 'INSERT INTO Users (Username, FirstName, LastName, Email, Password, CreatedDate, LastLogon, BirthDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
						mysqli_stmt_bind_param($stmt, 'ssssssss', $username, $firstname, $lastname, $email, $password, $date, $date, $birthdate);
						return mysqli_stmt_execute($stmt);
					}
				}
			}
			
		} catch (Exception $ex) {
			echo $ex;
			return "An error occured.";
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
			
			date_default_timezone_set("America/Chicago");
			$today = date("Y/m/d H:i:s");
			
			$stmt = mysqli_prepare($link, "UPDATE Users SET LastLogon=? WHERE UserID=?");
			mysqli_stmt_bind_param($stmt, 'ss', $today, $userID);
			mysqli_stmt_execute($stmt);
						
			session_start();
			
			$_SESSION["userID"] = $userID;
			
			return true;
		} catch (Exception $ex) {
			return false;
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