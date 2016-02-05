<?php
	include("settings.php");
    if (empty($_SESSION['steam_uptodate']) or $_SESSION['steam_uptodate'] == false or empty($_SESSION['steam_personaname'])) {
        $UserInfoUrl = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']);
        $UserFriendsUrl = file_get_contents("http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&relationship=friend");
        $content = json_decode($UserInfoUrl, true);
        $userFriendList = json_decode($UserFriendsUrl, true);

        /*$dt = new DateTime("@$snorre");
        echo $dt->format('Y-m-d');*/
        $_SESSION['steam_friendslist'] = $userFriendList['friendslist']['friends'];
        $_SESSION['steam_steamid'] = $content['response']['players'][0]['steamid'];
        $_SESSION['steam_communityvisibilitystate'] = $content['response']['players'][0]['communityvisibilitystate'];
        $_SESSION['steam_profilestate'] = $content['response']['players'][0]['profilestate'];
        $_SESSION['steam_personaname'] = $content['response']['players'][0]['personaname'];
        $_SESSION['steam_lastlogoff'] = $content['response']['players'][0]['lastlogoff'];
        $_SESSION['steam_profileurl'] = $content['response']['players'][0]['profileurl'];
        $_SESSION['steam_avatar'] = $content['response']['players'][0]['avatar'];
        $_SESSION['steam_avatarmedium'] = $content['response']['players'][0]['avatarmedium'];
        $_SESSION['steam_avatarfull'] = $content['response']['players'][0]['avatarfull'];
        $_SESSION['steam_personastate'] = $content['response']['players'][0]['personastate'];
        if (isset($content['response']['players'][0]['realname'])) {
	           $_SESSION['steam_realname'] = $content['response']['players'][0]['realname'];
	       } else {
	           $_SESSION['steam_realname'] = "Real name not given";
        }
        $_SESSION['steam_primaryclanid'] = $content['response']['players'][0]['primaryclanid'];
        $_SESSION['steam_timecreated'] = $content['response']['players'][0]['timecreated'];
        $_SESSION['steam_uptodate'] == false;
    }

    $steamprofile['friendlist'] = $_SESSION['steam_friendslist'];
    $steamprofile['steamid'] = $_SESSION['steam_steamid'];
    $steamprofile['communityvisibilitystate'] = $_SESSION['steam_communityvisibilitystate'];
    $steamprofile['profilestate'] = $_SESSION['steam_profilestate'];
    $steamprofile['personaname'] = $_SESSION['steam_personaname'];
    $steamprofile['lastlogoff'] = $_SESSION['steam_lastlogoff'];
    $steamprofile['profileurl'] = $_SESSION['steam_profileurl'];
    $steamprofile['avatar'] = $_SESSION['steam_avatar'];
    $steamprofile['avatarmedium'] = $_SESSION['steam_avatarmedium'];
    $steamprofile['avatarfull'] = $_SESSION['steam_avatarfull'];
    $steamprofile['personastate'] = $_SESSION['steam_personastate'];
    $steamprofile['realname'] = $_SESSION['steam_realname'];
    $steamprofile['primaryclanid'] = $_SESSION['steam_primaryclanid'];
    $steamprofile['timecreated'] = $_SESSION['steam_timecreated'];

    function getSteamNames($steamID) {
        $steamUserNamesUrl = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=41AF33A7F00028D1E153D748597DEEF3&steamids=".$steamID);
        $content = json_decode($steamUserNamesUrl, true);
        return $content['response']['players'];
    }

?>

