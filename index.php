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
    usort($friendListArray, 'compare_firstname');
    foreach($steamprofile['friendlist'] as $key=>$friend) {
        $cleanFriendArray[] = $friend['steamid'];
    }

    $content = '';
    $content .= "<input id='steam_name' type='hidden' value='" . $steamprofile['personaname'] . "'>";
    $content .= "<input id='steam_steamID' type='hidden' value='" . $_SESSION['steamid'] . "'>";
    $content .= "<input id='steam_pictureurl' type='hidden' value='" . $steamprofile['avatarfull'] . "'>";
    $content .= '<div id="sidebar">';
    $content .= '<div class="menu-bar">';
    $content .= '<ul>';
    $content .= '<li class="dashboardBtn">Dashboard</li>';
    $content .= '<li class="gamesBtn">Games</li>';
    $content .= '<li class="friendsBtn">Friends</li>';
    $content .= '<li>';
    $content .= "<form action=\"steamauth/logout.php\" method=\"post\"><input value=\"Log out\" type=\"submit\" /></form>";
    $content .= '</li>';
    $content .= '<ul>';
    $content .= '</div>';
    $content .= '<div class="profile-pic-wrap">';
    $content .= '<img id="profile_picture" src="'.$steamprofile['avatarfull'].'" title="" alt="" />';
    $content .= "<h2>" . $steamprofile['personaname'] . "</h2>";
    $content .= '</div>';
    $content .= '</div>';

    $content .= '<div id="friendlist">';
    $content .= '<h1 id="friendHeader">Friendslist</h1>';
    $content .= '<h2 id="chatter"></h2>';
    ?>
    <script type="text/javascript">
    window.onload = function() {

    function convertToDate(time)
     {
         var date = new Date(time);
         var response = "" + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
         return response;
     }

    var ref = new Firebase('https://steamportal.firebaseio.com/');

        var userRef = ref.child("users");
        var steamname = $('#steam_name').val();
        var steamPicturUrl = $('#steam_pictureurl').val();
        var steamID = $('#steam_steamID').val();
        var steamFriendIDs = <?php echo json_encode($cleanFriendArray) ?>;
        var UserChatID;

        //User logs in
        userRef.child(steamID).update({
            name: steamname,
            pictureURL: steamPicturUrl
        });

        function startChat() {

            if(UserChatID) {

                userRef.child(steamID).child('chats').child(UserChatID).off('child_added');
                userRef.child(UserChatID).child('chats').child(steamID).off('child_added');
            }
            $('#chatlog').empty();
            $('#chatterID').val($(this).attr('value'));
            UserChatID = $(this).attr('value');

            userRef.child(steamID).child('chats').once('value', function(snapshot) {
                userRef.child(UserChatID).child('chats').once('value', function(snapshot2) {
                    if(snapshot.hasChild(UserChatID)) {
                        $('#chatterID').val(steamID);

                        userRef.child(steamID).child('chats').child(UserChatID).once('value', function(ChatSnapshot) {
                            $('#chatlog').empty();
                            ChatSnapshot.forEach(function(childSnapshot) {
                                childData = childSnapshot.val();
                                    $('#chatlog').append("<ul><li class='chatImg'><img src='" + childData.pictureUrl + "'></li><li class='chatMsg'><p><span class='chatName'>" + childData.name + "<time>" + convertToDate(childData.time) + "</time></span>" + childData.message + "</p></li></ul>");
                            })
                            $('#chatlog').scrollTop($('#chatlog')[0].scrollHeight);
                        })
                    }
                    else if(snapshot2.hasChild(steamID)) {
                        $('#chatterID').val(UserChatID);
                        userRef.child(UserChatID).child('chats').child(steamID).once('value', function(ChatSnapshot) {
                            $('#chatlog').empty();
                            ChatSnapshot.forEach(function(childSnapshot) {
                                childData = childSnapshot.val();
                                    $('#chatlog').append("<ul><li class='chatImg'><img src='" + childData.pictureUrl + "'></li><li class='chatMsg'><p><span class='chatName'>" + childData.name + "<time>" + convertToDate(childData.time) + "</time></span>" + childData.message + "</p></span></li></ul>");
                            })
                            $('#chatlog').scrollTop($('#chatlog')[0].scrollHeight);
                        })
                    }
                    else {
                        $('#chatterID').val(steamID);
                    }
                })
            })

            userRef.child(steamID).child('chats').child(UserChatID).limitToLast(1).on('child_added', function(DataSnapshot) {
                        DataSnapshot = DataSnapshot.val();
                        $('#chatlog').append("<ul><li class='chatImg'><img src='" + DataSnapshot.pictureUrl + "'></li><li class='chatMsg'><p><span class='chatName'>" + DataSnapshot.name + "<time>" + convertToDate(childData.time) + "</time></span>" + DataSnapshot.message + "</p></span></li></ul>");
                        $('#chatlog').scrollTop($('#chatlog')[0].scrollHeight);
                    })
            userRef.child(UserChatID).child('chats').child(steamID).limitToLast(1).on('child_added', function(DataSnapshot) {
                        DataSnapshot = DataSnapshot.val();
                        $('#chatlog').append("<ul><li class='chatImg'><span><img src='" + DataSnapshot.pictureUrl + "'></li><li class='chatMsg'><p><span class='chatName'>" + DataSnapshot.name + "<time>" + convertToDate(childData.time) + "</time></span>" + DataSnapshot.message + "</p></span></li></ul>");
                        $('#chatlog').scrollTop($('#chatlog')[0].scrollHeight);
                    })
        }

        function sendChatMsg() {

            var input = $("#chatTxtInput");
            var txtMessage = $("<div/>").html(input.val()).text();

            if($('#chatterID').val() == steamID) {
                userRef.child(steamID).once('value', function(snapshot) {
                    var data = snapshot.val()
                    userRef.child(steamID).child('chats').child(UserChatID).push({
                        message: txtMessage,
                        name: data.name,
                        pictureUrl: data.pictureURL,
                        time: Firebase.ServerValue.TIMESTAMP
                    });
                });
            } else {
                userRef.child(steamID).once('value', function(snapshot) {
                    var data = snapshot.val();
                    userRef.child(UserChatID).child('chats').child(steamID).push({
                        message: txtMessage,
                        name: data.name,
                        pictureUrl: data.pictureURL,
                        time: Firebase.ServerValue.TIMESTAMP
                    });
                });
            }
            input.val('');
        }

        
        $('.chatLink').on('click', startChat);
        $('#chatBtn').on('click', sendChatMsg);

        $('.chatLink').on('click', function(){
            if(!$('#chat').is(":visible")) {
                $('#chat').slideToggle();
            }
        });

    }
    </script>
    <?php
        
        $content .= '<div class="friend-container">';
    foreach($steamprofile['friendlist'] as $key=>$friend) {
        $content .= '<div class="friendCell">';
        $content .= '<span><img style="width: 40px; border-radius: 5px; vertical-align: middle;" src="' . $friendListArray[$key]['avatarfull'] . '"></span><span class="friendName">' . $friendListArray[$key]['personaname'] . '</span>';
        $content .= '<p class="chatLink" value="'.$friendListArray[$key]['steamid'].'">Chat</p>';
        $content .= '</div>';

    }
    $content .= '</div>';
    $content .= '<div id="chat" style="display: none;">';
    $content .= '<div id="chatlog" style="min-height: 200px; min-width: 200px; margin-bottom: 20px; margin-right: 50px;">';
    $content .= '</div>';
    $content .= '<textarea id="chatTxtInput" type="text" name="chatTxtInput" placeholder="Chat"></textarea>';
    $content .= '<p id="chatBtn" href="#">Send message</p>';
    $content .= '<input type="hidden" id="chatterID" name="chatterID">';
    $content .= '</div>';
    $content .= '</div>';
    //Överliggande div som täcker alla games, du kan placera denna var du vill!. Tycker denna bör visas som ett rutnät med bara bilder och sedan en hoover som gör dem lite mörkare/w.e.
    $content .= '<div id="game-list">';
    $content .= '<h2 id="gameListHeader">Your Games</h2>';
    foreach($steamprofile['games'] as $key=>$game) {
        if($game['img_logo_url'] && $game['has_community_visible_stats'] >= 0) {
            $content .= '<div class="gameCell">';
            $content .= '<img src="https://steamcdn-a.akamaihd.net/steam/apps/'.$game['appid'].'/header.jpg" alt="'.$game['name'].'" style="min-width: 460px;">';
            $content .= '<div class="game-info">';
            $content .= '<p>Hours played: '.$game['playtime_forever'].'</p>';
            $content .= '<span class="viewAchievements" steamID="'.$steamprofile['steamid'].'" appid="'.$game['appid'].'">View achievements</span>';
            $content .= '</div>';
            $content .= '</div>';
        }
    }
    $content .= '<div id="achievement-list">';
    $content .= '</div>';
    echo $content;
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
