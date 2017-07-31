<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}
	
	if (isset($_POST["btnCreate"])) {
		if (isset($_POST["txtRegistryName"])) {
			$result = createRegistry($_SESSION["userID"], $_POST["txtRegistryName"], isset($_POST["chkEditRegistry"]), isset($_POST["chkBoxPrivate"]));
			if ($result != "false" && $result != "-1") {
				header("Location: registry.php?id=$result");
			}
		}
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
			});
		</script>
	</head>
	<body>
		<div id="navigationbar" class="clearfix">
			<div class="col-lg-offset-2">
				<ul>
					<li><a id="lnkHome" href="home.php">Home</a></li>
					<li><a id="lnkView" href="view.php">View</a></li>
					<li><a id="lnkCreate" class="active" href="create.php">Create</a></li>
					<li><a id="lnkAccount" href="account.php">Account</a></li>
					<li><a id="lnkAboutMe" href="about.php">About Me</a></li>
					<li><a id="lnkLogout" href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="create.php">
				<p class="modal-header"><strong>Name your registry</strong></p>
				<input type="text" name="txtRegistryName" placeholder="Registry Name" class="form-control form-text" />
				<fieldset>
					<legend>Who can see this registry?</legend>
						<label class="form-text"><input type="radio" name="viewPermission" value="0" />Anyone</label>
						<label class="form-text"><input type="radio" name="viewPermission" value="1" />Any Logged In User</label>
						<label class="form-text"><input type="radio" name="viewPermission" value="2" />Only Invited Members</label>
						<label class="form-text"><input type="radio" name="viewPermission" value="3" />Admins Only</label>
				</fieldset>
				<input type="submit" name="btnCreate" value="Create" class="btn btn-md btn-success btn-block" />
			</form>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>