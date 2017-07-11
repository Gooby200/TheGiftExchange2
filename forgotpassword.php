<?php include 'includes/functions.php' ?>
<?php
	$warning1 = "";
	$warning2 = "";
?>
<html>
	<head>
		<title>Forgot Password</title>
		
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
				<li style="float: right;"><a id="lnkRegister" href="register.php">Register</a></li>
				<li style="float: right;"><a id="lnkLogin" href="index.php">Login</a></li>
			</ul>
		</div>
		<div name="pnlPasswordReset" class="col-lg-3 form-wrapper" style="display: none;">
			<form method="post" action="forgotpassword.php">
				<p class="modal-header"><strong>Password reset</strong></p>
				<input type="password" name="txtPassword" placeholder="Password" class="form-control form-text" required />
				<input type="password" name="txtConfPass" placeholder="Confirm Password" class="form-control form-text" required />
				<input type="submit" name="btnReset" value="Reset Password" class="btn btn-md btn-success btn-block" />
				<span class="warning"><?php echo $warning1 ?></span>
			</form>
		</div>
		<div name="pnlEmailPassword" class="col-lg-3 form-wrapper">
			<form method="post" action="forgotpassword.php">
				<p class="modal-header"><strong>Account recovery</strong></p>
				<input type="email" name="txtEmail" placeholder="Email" class="form-control form-text" required />
				<input type="submit" name="btnSend" value="Send Reset Link" class="btn btn-md btn-success btn-block" />
				<span class="warning"><?php echo $warning2 ?></span>
			</form>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>