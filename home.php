<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}
	
	$name = getFirstName($_SESSION["userID"]);
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
			<ul>
				<li><a id="lnkHome" class="active" href="home.php">Home</a></li>
				<li><a id="lnkView" href="view.php">View</a></li>
				<li><a id="lnkCreate" href="create.php">Create</a></li>
				<li><a id="lnkAccount" href="account.php">Account</a></li>
				<li><a id="lnkAboutMe" href="about.php">About Me</a></li>
				<li><a id="lnkLogout" href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div class="col-lg-8 window-wrapper">
			<p class="modal-header"><strong>Welcome <?php echo $name ?>!</strong></p>
			<div class="section">
				<p class="p-header">Viewing a registry and invites</p>
				<ul>
					<li>To view a registry you belong to or to accept / decline invitations to other registries, click the "View" button on the navigation to begin exploring gift registrys.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Creating a registry</p>
				<ul>
					<li>To create a new registry, click the "Create" button on the navigation, choose a name for your registry and invite anyone you want into that registry! You may invite users
					by selecting users from the userlist or by inserting their email and sending them an email invite.</li>
					<li>By checking the "Can others add to this registry, you are allowing other users to add items into your registry. This type of registry is for general family gifts. If you 
					leave the checkbox unchecked, then you are the only one who can add items to that registry.</li>
					<li>By setting the registry to private, then only the users you invite to that registry can see what is inside of it. If the registry is not private, then anyone can view 
					that registry.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Changing personal information</p>
				<ul>
					<li>Through the account page on the navigation menu, you may change your name, birthdate and your password.</li>
				</ul>
			</div>
		</div>
	</body>
	<footer>
		<div class="col-lg-8 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>