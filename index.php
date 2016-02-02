<?php
    require ('steamauth/steamauth.php');

	# You would uncomment the line beneath to make it refresh the data every time the page is loaded
	// $_SESSION['steam_uptodate'] = false;
?>
<html>
<head>
    <title>Steam Portal</title>
<link rel="stylesheet" href="css/style.css">
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
</head>
<body>
<?php
if(!isset($_SESSION['steamid'])) {


    $content .= '<div id="wrapper">';
    $content .= '<h1>Welcome!</h1>';
    $content .= '<h1>Please sign in to retrieve your profile</h1>';
    $content .= '</div>';
    echo $content;

    steamlogin(); //login button
}  else {
    include ('steamauth/userInfo.php');
    //Protected content
    echo "Welcome back " . $steamprofile['personaname'] . "</br>";
    echo "here is your avatar: </br>" . '<img src="'.$steamprofile['avatarfull'].'" title="" alt="" />'; // Display their avatar!

    logoutbutton();
}
?>
</body>
</html>
