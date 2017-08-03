<?php include 'includes/functions.php' ?>
<?php
	if (isLoggedIn()) {
		header("Location: home.php");
		return;
	}
	
	$invitationPost = "";
	if (isset($_GET["id"]) && $_GET["id"] != null && trim($_GET["id"]) != "" && isset($_GET["token"]) && $_GET["token"] != null && trim($_GET["token"])) {
		$registryID = $_GET["id"];
		$token = $_GET["token"];
		
		$invitationPost = "?id=$registryID&token=$token";
	}
	
	$result = "";
	if (isset($_POST["btnRegister"])) {
		$result = registerAccount($_POST["txtUsername"], $_POST["txtFirstName"], $_POST["txtLastName"], $_POST["txtDate"], $_POST["txtEmail"], $_POST["txtPassword"]);
				
		if ($result == "true") {
			$userID = verifyAccount($_POST["txtUsername"], $_POST["txtPassword"]);
			if (successfulLogin($userID)) {

				if (isset($_GET["id"]) && $_GET["id"] != null && trim($_GET["id"]) != "" && isset($_GET["token"]) && $_GET["token"] != null && trim($_GET["token"])) {
					$registryID = $_GET["id"];
					$token = $_GET["token"];
					
					//verify the invitation with token and id
					if (verifyInvitationToken($registryID, $token)) {
						//if invitation is verified, change the email of the invitation to that of the one that the user just logged in from
						if (updateInvitationEmail($registryID, $token, $userID)) {
							//take the user to the invitation management page
							header("Location: invitemanagement.php");
						} else {
							//was not able to change the user's email address in invitation table but the user entered correct credentials
							header("Location: home.php");
						}
					} else {
						//something doesn't match but the user entered correct credentials
						header("Location: home.php");
					}
				} else {
					//if the user is just logging in and not viewing an invitation, go to home page since they entered correct crednetials
					header("Location: home.php");
				}
				return;
			}
		}
	}
?>
<html>
	<head>
		<title>Register</title>
		 
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
					<li><a id="lnkRegister" class="active" href="#">Register</a></li>
					<li><a id="lnkLogin" href="index.php<?php echo $invitationPost; ?>">Login</a></li>
				</ul>
			</div>
		</div>
		<div class="col-lg-3 form-wrapper">
			<form method="post" action="register.php<?php echo $invitationPost; ?>">
				<p class="modal-header"><strong>Create an account</strong></p>
				<input name="txtUsername" type="text" class="form-control form-text" placeholder="Username" required />
				<input name="txtFirstName" type="text" class="form-control form-text" placeholder="First Name" required />
				<input name="txtLastName" type="text" class="form-control form-text" placeholder="Last Name" required />
				<div class="form-group form-text">
					<span name="ttBirthdate" data-toggle="tooltip" data-placement="top" title="Your birthdate will be used to notify users of the registries that you belong to that your birthday is approaching and to look at your requested items.">
					<small><strong>Birthdate</strong>&nbsp;<span class="glyphicon glyphicon-info-sign"></span></small></span>
					<input name="txtDate" type="date" class="form-control" required />
				</div>
				<input name="txtEmail" type="email" class="form-control form-text" placeholder="Email" required />
				<input name="txtPassword" type="password" class="form-control form-text" placeholder="Password" required />
				<input name="txtConfPass" type="password" class="form-control form-text" placeholder="Confirm Password" required />
				<input type="submit" name="btnRegister" value="Register" class="btn btn-md btn-success btn-block" />
			</form>
			<span style="color: red; font-weight: bold;"><?php echo $result; ?></span>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>