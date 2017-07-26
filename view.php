<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}
	
	$lstRegistriesOptions = getBelongRegistries($_SESSION["userID"]);
	
	//work on this later
	$lstInvitesOptions = "";
	
	if ($lstInvitesOptions == "") {
		$lstInviteStyle = 'style="display: none;"';
	}
	
	if (isset($_POST["btnGo"])) {
		if (isset($_POST["lstRegistries"]) && $_POST["lstRegistries"] != "") {
			header("Location: registry.php?id=" . $_POST["lstRegistries"]);
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
					<li><a id="lnkView" class="active" href="view.php">View</a></li>
					<li><a id="lnkCreate" href="create.php">Create</a></li>
					<li><a id="lnkAccount" href="account.php">Account</a></li>
					<li><a id="lnkAboutMe" href="about.php">About Me</a></li>
					<li><a id="lnkLogout" href="logout.php">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3 form-wrapper">
			<p class="modal-header"><strong>View registries and invites</strong></p>
			<form method="post" action="view.php">
				<div class="input-group form-text">
					<select name="lstRegistries" class="form-control">
						<?php echo $lstRegistriesOptions; ?>
					</select>
					<span class="input-group-btn" style="min-width: 111px;">
						<input type="submit" name="btnGo" value="View Registry" class="btn btn-md btn-success btn-block" />
					</span>
				</div>
			</form>
			<form method="post" action="view.php" <?php echo $lstInviteStyle; ?>>
				<div class="input-group">
					<select name="lstInvites" class="form-control">
						<?php echo $lstInvitesOptions; ?>
					</select>
					<span class="input-group-btn" style="min-width: 111px;">
						<input type="submit" name="btnViewInvite" value="View Invite" class="btn btn-md btn-success btn-block" />
					</span>
				</div>
			</form>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>