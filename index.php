<?php include 'includes/functions.php' ?>
<?php
	if (isset($_POST["btnSubmit"])) {
		$username = $_POST["txtUsername"];
		$password = $_POST["txtPassword"];
		
		if (isset($username) && isset($password)) {
			if (($userID = verifyAccount($username, $password)) != "-1") {
				successfulLogin($userID);
			}
		}
	}
?>
<html>
	<head>
		<title>Login</title>
		
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
				<li style="float: right;"><a id="lnkLogin" class="active" href="#">Login</a></li>
			</ul>
		</div>
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="index.php">
				<p class="modal-header"><strong>Welcome to TheGiftExchange.net!</strong></p>
				<input type="text" name="txtUsername" class="form-control form-text" placeholder="Username" required />
				<input type="password" name="txtPassword" class="form-control form-text" placeholder="Password" required />
				<input type="submit" value="Login" name="btnSubmit" class="btn btn-md btn-success btn-block form-text" />
				<span>Forgot password? <a href="forgotpassword.aspx" style="color: lightgreen; text-decoration: none;">Recover account</a></span>
			</form>
		</div>
	</body>
	<footer>
		<?php include 'includes/master_footer.php' ?>
	</footer>
</html>