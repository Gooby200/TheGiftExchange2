<?php include 'includes/functions.php' ?>
<?php

	
	$registryID = $_GET["id"];
	$tableRows = "";
	
	$registryName = "";
	
	if (trim($registryID) != "" && $registryID != null) {
		
		if (doesRegistryExist($registryID) == false) {
			header("Location: view.php");
		}
		
		$registryName = getRegistryName($registryID);
		$isPrivate = isRegistryPrivate($registryID);
		
		if ($isPrivate) {
			//if private, check if the user is logged in because they have to be logged in to view
			if (!isLoggedIn()) {
				destroySession();
			}
			
			//since the user is logged in by this point, get their user id
			$userID = $_SESSION["userID"];
			
			//check the association of the user against the registry and see if the user is part of it
			if (checkRegistryAssociation($userID, $registryID) == false) {
				header("Location: view.php");
			}
			
			//display the items if we haven't been kicked out yet due to permissions
			$tableRows = getRegistryItems($registryID);
			
		} else {
			//if its not private, anyone can view this registry and doesn't have to be logged in
			$tableRows = getRegistryItems($registryID);
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
			
			function numberChange(itemID) {
				if ($("#totalBought" + itemID).val() != $("#totalBought" + itemID).prop("min")) {
					//show the update button
					//show the update column
				} else {
					//hide the update button
					//check to see if the other buttons are invisible. if they are, hide update column. if not, keep it displayed
				}
			}
		</script>
	</head>
	<body>
		<div id="navigationbar" class="clearfix">
			<div class="col-lg-offset-2">
				<ul>
					<li><a id="lnkHome" href="home.php">Home</a></li>
					<li><a id="lnkView" href="view.php">View</a></li>
					<li><a id="lnkCreate" href="create.php">Create</a></li>
					<li><a id="lnkAccount" href="account.php">Account</a></li>
					<li><a id="lnkAboutMe" href="about.php">About Me</a></li>
					<li><a id="lnkLogout" href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-9 window-wrapper">
			<div class="modal-header">
				<p>
					<strong>Registry: </strong><?php echo $registryName; ?>
					<input type="button" name="btnAddEntry" class="btn btn-success" style="float: right;" value="Add Entry" />
				</p>
			</div>
			
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th>Name</th>
						<th>Product</th>
						<th>Price</th>
						<th>Notes</th>
						<th>Image</th>
						<th>Need</th>
						<th>Bought</th>
						<th style="display: none;">Update</th>
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