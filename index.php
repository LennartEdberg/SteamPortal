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
<script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>
</head>
<body>
<?php
if(!isset($_SESSION['steamid'])) {

    $content = '';
    $content = '<header>';
    $content .= "<form action=\"?login\" method=\"post\"> <input id=\"sign-in-btn\" type=\"image\" src=\"http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png\"></form>";
    steamlogin(); //login button

    $content .= '<section id="intro-container">';
    $content .= '<h1 class="intro-headerText">The ultimate way to interact with your friends on steam</h1>';
    $content .= '<a href="#friends-container"><button>HOW IT WORKS</button></a>';
    $content .= '</section>';

    $content .= '<section id="friends-container">';
    $content .= '<div class="friends-text">';
    $content .= '<h2>Easy to communicate with your friends in a private chat and group chats</h2>';
    $content .= '</div>';
    $content .= '<figure class="friends-img">';
    $content .= '<img src="img/friendsList.png">';
    $content .= '</figure>';
    $content .= '</section>';
    $content .= '</header>';
    echo $content;

}  else {
    include ('steamauth/userInfo.php');
    //Protected content

        $friendlistLength = count($steamprofile['friendlist']);

        foreach($steamprofile['friendlist'] as $friend) {
            if($id >= $friendlistLength)
            {
                $steamfriendIDs .= $friend['steamid'];

            } else {
                $steamfriendIDs .= $friend['steamid'] . ',%20';
            }
        }

    $friendListArray = getSteamNames($steamfriendIDs);
    $cleanFriendIDs = preg_replace('/,%20/', ',', $steamfriendIDs);

    $content = '';
    $content .= "<input id='steam_name' type='hidden' value='" . $steamprofile['personaname'] . "'>";
    $content .= "<input id='steam_steamID' type='hidden' value='" . $_SESSION['steamid'] . "'>";
    $content .= "<input id='steam_numFriends' type='hidden' value='" . count($friendListArray) . "'>";
    $content .= "<input id='steam_friendIDs' type='hidden' value='" . $steamfriendIDs . "'>";
    $content .= '<div id="sidebar">';
    $content .= '<img id="profile_picture" src="'.$steamprofile['avatarfull'].'" title="" alt="" />';
    $content .="<h2>Welcome " . $steamprofile['personaname'] . "</h2>";
    $content .= "<form action=\"steamauth/logout.php\" method=\"post\"><input value=\"Logout\" type=\"submit\" /></form>";
    $content .= '</div>';

    $content .= '<div id="friendlist">';
    $content .= '<h1 id="friendHeader">Friendslist</h1>';
    $content .= '<h2 id="chatter">a</h2>';
    ?>
    <script type="text/javascript">
    window.onload = function() {

    var ref = new Firebase('https://steamportal.firebaseio.com/');

        var userRef = ref.child("users");
        var steamname = $('#steam_name').val();
        var steamID = $('#steam_steamID').val();
        var steamFriendIDs = $('#steam_friendIDs').val();
        var steamFriendCount = $('#steam_numFriends').val();
        console.log(steamFriendIDs);
        userRef.child(steamID).set({
            name: steamname,
            numFriends: steamFriendCount
        });

        for(var i = 0; i <= steamFriendCount; i++) {
            userRef.child(steamID).child('chats').child(i).set({
                messages: 'Dummy'
            });
        }

        $('.chatLink').on('click', function() {
            $('#chatter').html("You're talking to: " + $(this).attr('value'));
            });
    }
    </script>
    <?php
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
        $content .= '<p class="chatLink" value="'.$friendListArray[$key]['steamid'].'">Chat</p>';
        $content .= '</div>';

    }
    $content .= '</div>';
    echo $content;
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
