<?php
    require ('steamauth/steamauth.php');

	# You would uncomment the line beneath to make it refresh the data every time the page is loaded
	// $_SESSION['steam_uptodate'] = false;
?>
<html>
<head>
    <title>Steam Portal</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>
<body>

<header>
    <?php
    if(!isset($_SESSION['steamid'])) {
        $content = '';
        $content .= "<form action=\"?login\" method=\"post\" id=\"login-form\"> <input id=\"sign-in-btn\" type=\"image\" src=\"http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png\"><h2>SteamPortal</h2></form>";
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
</header>

<section id="intro-container">
    <h1 class="intro-headerText">The ultimate way to interact with your friends on steam</h1>
    <a href="#friends-container"><button>HOW IT WORKS</button></a>
</section>
    
<section id="friends-container">
    <div class="friends-text">
        <h2>Easy to communicate with your friends in a private chat and group chats</h2>
    </div>
    <figure class="friends-img">
        <img src="img/friendsList.png">
    </figure>
</section>

<script src="js/script.js"></script>
</body>
</html>
