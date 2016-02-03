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
    $content = '';
    $content .= '<div id="wrapper">';
    $content .= '<h1>Welcome!</h1>';
    $content .= '<h1>Please sign in to retrieve your profile</h1>';
    $content .= '</div>';
    $content .= "<form action=\"?login\" method=\"post\"> <input id=\"sign-in-btn\" type=\"image\" src=\"http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png\"></form>";
    echo $content;
    steamlogin(); //login button
}  else {
    include ('steamauth/userInfo.php');
    //Protected content

    $friendlistLength = count($steamprofile['friendlist']);
    $id = 1;
    foreach($steamprofile['friendlist'] as $friend)
    {
        if($id >= $friendlistLength)
        {
            $steamfriendIDs .= $friend['steamid'];

        } else{
            $steamfriendIDs .= $friend['steamid'] . ',%20';
        }
        $id++;
    }

    $friendListArray = getSteamNames($steamfriendIDs);

    $content = '';
    $content .= '<div id="sidebar">';
    $content .= '<img id="profile_picture" src="'.$steamprofile['avatarfull'].'" title="" alt="" />';
    $content .="<h2>Welcome " . $steamprofile['personaname'] . "</h2>";
    $content .= "<form action=\"steamauth/logout.php\" method=\"post\"><input value=\"Logout\" type=\"submit\" /></form>";
    foreach ($steamprofile['friendlist'] as $key=>$friend) {
        $content .= 'SteamName: ' . $friendListArray[$key]['personaname'] . '<br />';
    }
    $content .= '</div>';
    echo $content;



}
?>
</body>
</html>
