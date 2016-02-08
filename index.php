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

            } else {
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
    $content .= '</div>';
    if(isset($_GET['chat']))
    {
    $content .="<div style='background-color: white; margin: 10px; display: inline-block;'<h2>You're now chatting with " . $_GET['chat'] . "</h2></div>";
    }
    $content .= '<div id="friendlist">';
    $content .= '<h1 id="friendHeader">Friendslist</h1>';

    foreach($steamprofile['friendlist'] as $key=>$friend)
    {
        /* Måste kika vidare på detta, vi får tillbaka en randomized array av vår friendListArray anrop via steam(den är alltid randomized). Måste synka arrayenerna så vi visar rätt date för rätt steamid.
        $varDate = '';
        $varDate = $friend['friend_since'];
        $dt = new DateTime("@$varDate");
        */
        $content .= '<div class="friendCell">';
        $content .= '<span><img style="width: 40px; border-radius: 5px; vertical-align: middle;" src="' . $friendListArray[$key]['avatarfull'] . '"></span><span class="friendName">' . $friendListArray[$key]['personaname'] . '</span><br />';
        /*$content .= '<h3>Friend since: '.$dt->format('Y-m-d').'</h3>';*/
        $content .= '<a href="?chat=' . $friendListArray[$key]['steamid'] . '">Chat</a>';
        $content .= '</div>';

    }
    $content .= '</div>';
    echo $content;



}
?>
</body>
</html>
