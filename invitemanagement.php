<?php include 'includes/functions.php' ?>
<?php
	if (!isLoggedIn()) {
		session_destroy();
		
		if (isset($_GET["id"]) && $_GET["id"] != null && trim($_GET["id"]) != "" && isset($_GET["token"]) && $_GET["token"] != null && trim($_GET["token"])) {
			$registryID = $_GET["id"];
			$token = $_GET["token"];
			
			header("Location: index.php?id=$registryID&token=$token");
		} else {
			header("Location: index.php");
		}
		
		return;
	}
	
	$registryInvitations = getAcceptInvitedRegistries($_SESSION["userID"]);
	
	//if the user doesn't have any invitations, send them back to view.php
	if ($registryInvitations == "") {
		header("Location: view.php");
	}
?>
<html>
	<head>
		<title>Invitation Management</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
		<link rel="stylesheet" type="text/css" href="main.css" />
		
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip(); 
			});
			
			function manage(a, b, c) {
				$.ajax({
					type: "POST",
					url: "includes/functions.php",
					data: {
						action:  "ManageInvite",
						a: a,
						b: b,
						c: c
					},
					success: function(data) {
						if (data == 1) {
							$("#invite" + b).attr("style", "display: none;");
						} else {
							alert("There was an error processing your request.");
						}
					}
				});
			}
		</script>
		<style>
			.table th, .table td { 
				 border-top: none !important; 
			}
			
			.table > tbody > tr > td {
				 vertical-align: middle;
			}
		</style>
	</head>
	<body>
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
			<p class="modal-header"><strong>Invitation Management</strong></p>
			
			<div class="table-responsive">
				<table class="table table-striped">
					<tbody>
						<?php echo $registryInvitations; ?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
	<footer>
		<div class="col-lg-3 footer-wrapper">
			<?php include 'includes/master_footer.php' ?>
		</div>
	</footer>
</html>