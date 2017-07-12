<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		destroySession();
	}
	
?>
<html>
	<head>
		<title>About Me</title>
		
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
				<li><a id="lnkAccount" href="account.php">Account</a></li>
				<li><a id="lnkAboutMe" class="active" href="about.php">About Me</a></li>
				<li><a id="lnkLogout" href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div class="col-lg-8 window-wrapper">
			<p class="modal-header"><strong>Version History</strong></p>
			<div class="section">
				<p class="p-header">Version 2.0 (##/##/####)</p>
				<ul>
					<li>Developed new design.</li>
					<li>Performed recoding of website logic.</li>
					<li>Polling system temporarily removed.</li>
					<li>Enhanced password encryption on the database with newest encryption technology.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.06 (06/30/2016)</p>
				<ul>
					<li>Changed domains to TheGiftRegistry.net.</li>
					<li>Modified notification emails to reflect new domain change.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.05 (03/27/2016)</p>
				<ul>
					<li>Added email notifications to all users in the registry where an entry was just added.</li>
					<li>Modified the main login page.</li>
					<li>Changed order of how entries were appearing. They will now appear with the newest on top inside the registry.</li>
					<li>Fixed an issue where sometimes you weren't able to delete your own entries.</li>
					<li>Added confirmation of entry delete to prevent accidental deletion.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.04 (01/09/2015)</p>
				<ul>
					<li>Added "Forgot Password" retrieval.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.03 (01/08/2015)</p>
				<ul>
					<li>Added a polling system.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.02 (01/07/2015)</p>
				<ul>
					<li>Fixed user registration. Now logs you in once successfully registered.</li>
					<li>If a link is placed inside the notes section of entries, it will now make that link clickable.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.01 (01/05/2015)</p>
				<ul>
					<li>Fixed navigation menu style.</li>
					<li>Made navigation menu mobile phone Google Chrome friendly.</li>
					<li>Fixed a bug that would sometimes make links unclickable.</li>
				</ul>
			</div>
			<br />
			<div class="section">
				<p class="p-header">Version 1.00 (12/30/2014)</p>
				<ul>
					<li>Website was made live.</li>
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