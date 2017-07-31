<?php include 'includes/functions.php' ?>
<?php

	
	$registryID = $_GET["id"];
	$tableRows = "";
	$userNavigation = "	<div class=\"col-lg-offset-2\">
										<ul>
											<li><a id=\"lnkHome\" href=\"home.php\">Home</a></li>
											<li><a id=\"lnkView\" href=\"view.php\">View</a></li>
											<li><a id=\"lnkCreate\" href=\"create.php\">Create</a></li>
											<li><a id=\"lnkAccount\" href=\"account.php\">Account</a></li>
											<li><a id=\"lnkAboutMe\" href=\"about.php\">About Me</a></li>
											<li><a id=\"lnkLogout\" href=\"logout.php\">Logout</a></li>
										</ul>
									</div>";
	
	$registryName = "";
	$editPermissions = "";
		
	if (trim($registryID) != "" && $registryID != null) {
		
		if (doesRegistryExist($registryID) == false) {
			header("Location: view.php");
		}
		
		$canView = false;
		$canAddItems = false;
		$registryName = getRegistryName($registryID);
		$privateLevel = registryPrivatePermission($registryID);
		$addItemPermissionLevel = registryAddItemPermission($registryID);
		$isLoggedIn = isLoggedIn();
		$userInRegistry = false;
		
		
		
		//if the user isn't logged in, destroy any session data and give guest navigation
		if ($isLoggedIn == false) {
			session_destroy();
			
			$userNavigation = "<div class=\"col-lg-offset-7\">
										<ul>
											<li><a id=\"lnkRegister\" href=\"register.php\">Register</a></li>
											<li><a id=\"lnkLogin\" href=\"index.php\">Login</a></li>
										</ul>
									</div>";
			
			//give the guest a temporary user id of -1 so that they can post to registry
			$userID = -1;
		} else {
			//set the user id if the user is logged in
			$userID = $_SESSION["userID"];
		}
		
		$isOwner = isUserOwner($registryID, $userID);
		$belongInRegistry = checkRegistryAssociation($userID, $registryID);
		//check to see if the user is assocaited in the registry
		if ($isLoggedIn && $belongInRegistry) {
			$userInRegistry = true;
		}
		
		//the owner of the registry will always be able to view registry, add items, and have all options available
		if ($isLoggedIn && $isOwner) {
			$canView = true;
			$canAddItems = true;
			
			$editPermissions = "$editPermissions<input type=\"button\" onclick=\"redirectPage($registryID);\" name=\"btnRegistrySettings\" class=\"btn btn-success\" style=\"float: right;\" value=\"Registry Settings\" />";
		}
		
		//check to see if the user can view the registry based on registry permissions
		if ($privateLevel == 0 || ($privateLevel == 1 && $isLoggedIn) || ($privateLevel == 2 && $isLoggedIn && $userInRegistry)) {
			$canView = true;
		}
		
		//by this point, the user is viewing the registry, check to see if the user can add items to registry
		if ($addItemPermissionLevel == 0 || ($addItemPermissionLevel == 1 && $isLoggedIn) || ($addItemPermissionLevel == 2 && $isLoggedIn && $userInRegistry)) {
			$canAddItems = true;
		}		
				
		//allow the user to add items by displaying them the add button
		if ($canAddItems) {
			$editPermissions = "<input type=\"button\" name=\"btnAddEntry\" class=\"btn btn-success\" style=\"float: right;\" data-toggle=\"modal\" data-target=\"#myModal\" value=\"Add Entry\" />$editPermissions";
		}
		
		//check to see if the user is actually trying to add an item
		if ($canAddItems) {
			if (isset($_POST["btnAddEntry"])) {
				createRegistryItem($registryID, $userID, $_POST["txtProductName"], $_POST["txtProductLink"], $_POST["txtProductPrice"], $_POST["txtNotes"], $_POST["txtProductImageURL"], $_POST["txtNeed"]);
			}
		}
		
		//handle showing registry by permissions
		if ($canView) {
			
			//show view users button to people who belong in the registry
			if ($belongInRegistry) {
				$editPermissions = "$editPermissions<input type=\"button\" name=\"btnViewUsers\" class=\"btn btn-success\" style=\"float: right;\" data-toggle=\"modal\" data-target=\"#mdlUsers\" value=\"View Users\" />";
			}
			
			//show registry
			$tableRows = getRegistryItems($registryID);
		} else {
			header("Location: view.php");
		}
		
	} else {
		header("Location: view.php");
	}
	
	
?>
<html>
	<head>
		<title>Registry</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
		<link rel="stylesheet" type="text/css" href="main.css" />
		
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip(); 
			});
			
			function redirectPage(registryID) {
				window.location.replace("registrysettings.php?id=" + registryID);
			}
			
			function numberChange(itemID) {
				if ($("#totalBought" + itemID).val() != $("#totalBought" + itemID).prop("min")) {
					//show the update button
					$("#saveButton" + itemID).prop("style", "");
				} else {
					//hide the update button
					$("#saveButton" + itemID).prop("style", "display: none;");
				}
			}
			
			function save(itemID, registryID) {
				$.ajax({
					type: "POST",
					url: "includes/functions.php",
					data: {
						action: "UpdateAmount",
						itemID: itemID,
						amount: $("#totalBought" + itemID).val(),
						registryID: registryID,
						currentAmount: $("#totalBought" + itemID).prop("min")
					},
					success: function(data) {
						if (data == 1) {
							$("#saveButton" + itemID).prop("style", "display: none;");
							$("#totalBought" + itemID).prop("min", $("#totalBought" + itemID).val());
							
							if ($("#totalBought" + itemID).val() == $("#totalBought" + itemID).prop("max")) {
								$("#asking" + itemID).replaceWith("<strong><span style=\"color: green;\">" + $("#totalBought" + itemID).val() + "</span></strong>");
								$("#totalBought" + itemID).replaceWith("<strong><span style=\"color: green;\">" + $("#totalBought" + itemID).val() + "</span></strong>");
							}
						} else if (data == -1) {
							alert("This item has already been updated. Please refresh the page.");
						} else if (data == -2) {
							alert("An error occured.");
						} else {
							alert("An unknown error occured.");
						}
					}
				});
			}
		</script>
		<style>
			.btn {
				margin-left: 5px;
			}
		</style>
	</head>
	<body>
		<div id="navigationbar" class="clearfix">
			<?php echo $userNavigation; ?>
		</div>
		<div class="col-lg-9 window-wrapper">
			<div class="modal-header">
				<p>
					<strong>Registry: </strong><?php echo $registryName; ?>
					<?php echo $editPermissions; ?>
				</p>
			</div>
			
			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog" data-backdrop="static">
				<div class="modal-dialog">

				<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" onclick="clearModal();">&times;</button>
							<h4 class="modal-title"><strong>Add Items To: </strong><?php echo $registryName; ?></h4>
						</div>
						<form method="post" action="registry.php?id=<?php echo $registryID; ?>">
							<div class="modal-body">
								<input type="text"  name="txtProductName" runat="server" class="form-control form-text" placeholder="Product Name" autocomplete="off" required />
								<input type="url" name="txtProductLink" runat="server" class="form-control form-text" placeholder="Product Link" autocomplete="off" />
								<input type="number" name="txtProductPrice" runat="server" class="form-control form-text" placeholder="Product Price" min="0" step="any" autocomplete="off" required />
								<textarea name="txtNotes" runat="server" placeholder="Notes" rows="2" cols="20" class="form-control form-text modal-textarea"></textarea>
								<input type="url" name="txtProductImageURL" runat="server" class="form-control form-text" placeholder="Product Image URL" autocomplete="off" />
								<input type="number" name="txtNeed" runat="server" class="form-control form-text" placeholder="Needed Amount" min="1" step="any" autocomplete="off" required />
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearModal();">Close</button>
								<input type="submit" name="btnAddEntry" value="Add" class="btn btn-success" />
							</div>
						</form>
					</div>

				</div>
			</div>
			
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th>Name</th>
						<th>Product</th>
						<th><center>Price</center></th>
						<th><center>Notes</center></th>
						<th><center>Image</center></th>
						<th><center>Need</center></th>
						<th><center>Bought</center></th>
					</tr>
					<?php echo $tableRows; ?>
				</table>
			</div>
		</div>
	</body>
	<footer>
		<div class="col-lg-9 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>