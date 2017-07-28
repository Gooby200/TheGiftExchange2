<?php include 'includes/functions.php' ?>
<?php
	if (isLoggedIn()) {
		header("Location: home.php");
	}
	
	$warning1 = "";
	$warning2 = "";
		
	$pwResetStyle = 'style="display: none;"';
	$emailPasswordStyle = '';
	
	$email = "";
	$token = "";
	
	if (isset($_GET["email"]) && isset($_GET["token"])) {
		$email = $_GET["email"];
		$token = $_GET["token"];
				
		if (($email != null || $email != "") && ($token != null || $token != "")) {
			if (verifyTokenInformation($email, $token)) {
				$emailPasswordStyle = 'style="display: none;"';
				$pwResetStyle = '';
			} else {
				$emailPasswordStyle = '';
				$pwResetStyle = 'style="display: none;"';
			}
		} else {
			$emailPasswordStyle = '';
			$pwResetStyle = 'style="display: none;"';
		}
	}
	
	if (isset($_POST["btnReset"])) {
		if (isset($_POST["hEmail"]) && isset($_POST["hToken"])) {
			$email = $_POST["hEmail"];
			$token = $_POST["hToken"];
		}
		
		if (($email != null || $email != "") && ($token != null || $token != "")) {
			if (isset($_POST["txtPassword"]) && isset($_POST["txtConfPass"])) {
				if (($_POST["txtPassword"] != null || trim($_POST["txtPassword"]) != "") && ($_POST["txtConfPass"] != null || trim($_POST["txtConfPass"]) != "")) {
					if ($_POST["txtPassword"] == $_POST["txtConfPass"]) {
						if (verifyTokenInformation($email, $token)) {
							if (updatePassword($email, $_POST["txtPassword"])) {
								$userID = verifyAccountByEmail($email, $_POST["txtPassword"]);
								successfulLogin($userID);
							}
						}	
					}
				}
			}
		}
	}
	
	if (isset($_POST["btnSend"])) {
		if (isset($_POST["txtEmail"])) {
			$warning2 = "<br />This feature is not yet implemented.";
			
			if (doesEmailAlreadyExist($_POST["txtEmail"])) {
				//email token link
			}
		}
	}
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
			<div class="col-lg-offset-7">
				<ul>
					<li><a id="lnkRegister" href="register.php">Register</a></li>
					<li><a id="lnkLogin" href="index.php">Login</a></li>
				</ul>
			</div>
		</div>
		<div name="pnlPasswordReset" class="col-lg-3 form-wrapper" <?php echo $pwResetStyle; ?>>
			<form method="post" action="forgotpassword.php">
				<p class="modal-header"><strong>Password reset</strong></p>
				<input type="hidden" name="hEmail" value="<?php echo $email; ?>" />
				<input type="hidden" name="hToken" value="<?php echo $token; ?>" />
				<input type="password" name="txtPassword" placeholder="Password" class="form-control form-text" required />
				<input type="password" name="txtConfPass" placeholder="Confirm Password" class="form-control form-text" required />
				<input type="submit" name="btnReset" value="Reset Password" class="btn btn-md btn-success btn-block" />
				<span class="warning"><?php echo $warning1 ?></span>
			</form>
		</div>
		<div name="pnlEmailPassword" class="col-lg-3 form-wrapper" <?php echo $emailPasswordStyle; ?>>
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