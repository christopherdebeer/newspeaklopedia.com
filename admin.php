<?php if(($_SERVER['PHP_AUTH_USER'] == file_get_contents( './adminuser.txt' )) AND ($_SERVER['PHP_AUTH_PW'] == file_get_contents( './adminpassword.txt' ))) { 

	$words_file = "./words.json";
	$words_string = file_get_contents( $words_file );
	$words = json_decode( $words_string, true );

	if ($_POST["word"] && $_POST["replacement"]) {
		if (!is_array( $words['translations'][$_POST["replacement"]]) ) { $words['translations'][$_POST["replacement"]] = array(); }
		array_push( $words['translations'][$_POST["replacement"]], $_POST['word'] );
		file_put_contents($words_file, json_encode($words) );
	} 

	if ($_POST["censor"]) {

		if ($_POST["undo"] == 'true') {
			if(($key = array_search($_POST["censor"], $words['censored'])) !== false) {
		    	unset($words['censored'][$key]);
			}
		} else {
			array_push( $words['censored'], $_POST['censor'] );
		}
		
		file_put_contents($words_file, json_encode($words) );
	}

	?>
	<!DOCTYPE html>
  	<html>
	  	<head>
	  		<title>Admin</title>
	  		<link rel="stylesheet" href="ingsoc_assets/css/admin.css">
	  	</head>
	  	<body>
			
			<!-- <img src="ingsoc_assets/images/logo.png" alt="INSOC"> -->
	      	<h1><a href="admin.php">Ministry of Truth</a></h1>
	      	<p>Newspeaklopedia administrator action service.</p>
			

	      	<script>var words = JSON.parse( '<?php echo json_encode( $words ); ?>' );</script>
			
			<h2>Translations</h2>
	      	<table id="translations" border="1"></table>
	      	<br>
	      	<form action="admin.php" method="post" class="translation">
	      		<label for="replacement">Newspeak</label>
				<input name="replacement" type="text" val="translation">
				<label for="word">Oldspeak</label>
				<input name="word"type="text" val="word">				
				<input type="submit">
			</form>

	      	<h2>Censored</h2>
	      	<table id="censored" border="1"></table>
	      	<br>
			<form action="admin.php" method="post" class="censor">
				<label for="censor">Censor</label>
				<input name="censor" type="text" val="word">
				<input type="submit">
			</form>
			

			<script src="ingsoc_assets/js/jquery.js"></script>
	  		<script src="ingsoc_assets/js/underscore.js"></script>
	  		<script src="ingsoc_assets/js/admin.js"></script>
		</body>
	</html>

<?php } else {
    //Send headers to cause a browser to request
    //username and password from user
    header("WWW-Authenticate: " .
        "Basic realm=\"Ministry of Truth Inner Party Area\"");
    header("HTTP/1.0 401 Unauthorized");

    //Show failure text, which browsers usually
    //show only after several failed attempts
    print("This page is protected by HTTP " .
        "Authentication.<br>\nUse <b>leon</b> " .
        "for the username, and <b>secret</b> " .
        "for the password.<br>\n");
} ?>