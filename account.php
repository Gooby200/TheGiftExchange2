<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}
	
	$warning1 = "";
	$warning2 = "";
	
?>
<html>
	<head>
		<title>Account Settings</title>
		
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
			<ul>
				<li><a id="lnkHome" href="home.php">Home</a></li>
				<li><a id="lnkView" href="view.php">View</a></li>
				<li><a id="lnkCreate" href="create.php">Create</a></li>
				<li><a id="lnkAccount" class="active" href="account.php">Account</a></li>
				<li><a id="lnkAboutMe" href="about.php">About Me</a></li>
				<li><a id="lnkLogout" href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="account.php">
				<p class="modal-header"><strong>General account settings</strong></p>
				<input type="text" name="txtFirstName" placeholder="First Name" class="form-control form-text" required />
				<input type="text" name="txtLastName" placeholder="Last Name" class="form-control form-text" required />
				<div class="form-group form-text">
					<small><strong>Birthdate</strong></small>
					<input type="date" name="txtDate" class="form-control" required />
				</div>
				<input type="submit" name="btnPersonalInfo" value="Change Personal Information" class="btn btn-md btn-success btn-block form-text" />
				<span class="warning"><?php echo $warning1; ?></span>
			</form>
		</div>
		<br />
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="account.php">
				<p class="modal-header"><strong>Change account password</strong></p>
				<input type="email" name="txtEmail" placeholder="Email" class="form-control form-text" required />
				<input type="password" name="txtCurrentPassword" placeholder="Current Password" class="form-control form-text" required />
				<input type="password" name="txtNewPassword" placeholder="New Password" class="form-control form-text" required />
				<input type="password" name="txtConfirmPassword" placeholder="Confirm New Password" class="form-control form-text" required />
				<input type="submit" name="btnChangePassword" value="Change Password" class="btn btn-md btn-success btn-block form-text" />
				<span class="warning"><?php echo $warning2; ?></asp:Literal></span>
			</form>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>