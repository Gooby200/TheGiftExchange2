<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}

	//get necessary variables
	$registryID = $_GET["id"];	
	$userID = $_SESSION["userID"];
	$warning1 = "";
	$txtRegistryName = "";
	$viewPermission = "";
	$addPermission = "";
	$scripts = "";
	
	if (trim($registryID) != "" && $registryID != null) {
		if (isUserOwner($registryID, $userID)) {
			if (isset($_POST["btnUpdate"])) {
				if (isset($_POST["txtRegistryName"])) {
					//update the registry
					if (updateRegistrySettings($userID, $registryID, $_POST["txtRegistryName"], $_POST["addPermission"], $_POST["viewPermission"])) {
						$warning1 = "Registry successfully updated.";
					} else {
						$warning1 = "There was an issue updating your registry.";
					}
				} else {
					$warning1 = "Registry name cannot be left blank.";
				}
			}
			
			if (isset($_POST["btnRemove"])) {
				//only the owner can delete the registry
				if (isUserOwner($registryID, $userID)) {
					if (deleteRegistry($registryID)) {
						header("Location: view.php");
					} else {
						$warning1 = "We failed to delete the registry. Please contact support.";
					}
				} else {
					$warning1 = "You are not an admin of this registry.";
				}
			}
			
			//display the registry information
			$txtRegistryName = getRegistryName($registryID);
			$viewPermission = registryPrivatePermission($registryID);
			$addPermission = registryAddItemPermission($registryID);
			
			$scripts = "<script>
							var viewPermission = $viewPermission;
							var addPermission = $addPermission;
						</script>";
		} else {
			//if the user is not the owner of the registry, he doesn't have permission to view this page
			header("Location: view.php");
		}
	} else {
		//page needs a registry id to continue
		header("Location: view.php");
	}
?>
<html>
	<head>
		<title>Home</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
		<link rel="stylesheet" type="text/css" href="main.css" />
		
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip(); 
				$("input[name=viewPermission][value=" + viewPermission + "]").prop("checked", true);
				$("input[name=addPermission][value=" + addPermission + "]").prop("checked", true);
			});
		</script>
	</head>
	<body>
		<?php echo $scripts; ?>
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
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="registrysettings.php?id=<?php echo $registryID; ?>">
				<p class="modal-header"><strong>Rename your registry</strong></p>
				<input type="text" name="txtRegistryName" placeholder="Registry Name" class="form-control form-text" value="<?php echo $txtRegistryName; ?>" />
				<p class="modal-header"><strong>Who can see this registry?</strong></p>
				<div class="radio">
					<label class="form-text"><input type="radio" name="viewPermission" value="0" checked />Anyone</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="viewPermission" value="1" />Any Logged In User</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="viewPermission" value="2" />Only Invited Members</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="viewPermission" value="3" />Admins Only</label>
				</div>
				<p class="modal-header"><strong>Who can add items?</strong></p>
				<div class="radio">
					<label class="form-text"><input type="radio" name="addPermission" value="0" />Anyone</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="addPermission" value="1" />Any Logged In User</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="addPermission" value="2" />Only Invited Members</label>
				</div>
				<div class="radio">
					<label class="form-text"><input type="radio" name="addPermission" value="3" checked />Admins Only</label>
				</div>
				<input type="submit" name="btnUpdate" value="Update Registry Settings" class="btn btn-md btn-success btn-block" />
				<input type="submit" name="btnRemove" value="Delete Registry" class="btn btn-md btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this registry?')" />
			</form>
			<span style="color: red; font-weight: bold;"><?php echo $warning1; ?></span>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>