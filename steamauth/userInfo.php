<?php
	include("settings.php");
function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
    if (empty($_SESSION['steam_uptodate']) or $_SESSION['steam_uptodate'] == false or empty($_SESSION['steam_personaname'])) {
        $UserInfoUrl = file_get_contents_curl("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$_SESSION['steamid']);
        $UserFriendsUrl = file_get_contents_curl("http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&relationship=friend");
        $UserGamesUrl = file_get_contents_curl("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=".$steamauth['apikey']."&steamid=".$_SESSION['steamid']."&include_appinfo=1&include_played_free_games=1&format=json");
        $content = json_decode($UserInfoUrl, true);
        $userFriendList = json_decode($UserFriendsUrl, true);
        $usergames = json_decode($UserGamesUrl, true);

        usort($usergames['response']['games'], "cmp");

        $_SESSION['steam_games'] = $usergames['response']['games'];
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

    $steamprofile['games'] = $_SESSION['steam_games'];
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
        $steamUserNamesUrl = file_get_contents_curl("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=41AF33A7F00028D1E153D748597DEEF3&steamids=".$steamID);
        $content = json_decode($steamUserNamesUrl, true);
        $pieces = explode(",%20", $steamID);
        if(count($pieces) > 99) {
            for($x = 100; $x <= count($pieces) - 2; $x++) {
                if($x >= count($pieces) - 2) {
                    $steamfriendsExtended .= $pieces[$x];
                } else {
                    $steamfriendsExtended .= $pieces[$x] . ',%20';
                }
            }
        $steamfriendsExtendedUrl = file_get_contents_curl("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=41AF33A7F00028D1E153D748597DEEF3&steamids=".$steamfriendsExtended);
        $steamfriendsExtendedContent = json_decode($steamfriendsExtendedUrl, true);
            foreach($steamfriendsExtendedContent['response']['players'] as $player) {
                array_push($content['response']['players'], $player);
            }

        }
        return $content['response']['players'];
    }

    function compare_firstname($a, $b) {
        return strnatcmp($a['personaname'], $b['personaname']);
    }

    function cmp($a, $b) {
        return $b['playtime_forever'] - $a['playtime_forever'];
    }

    if(isset($_GET['appid']) && isset($_GET['steamid'])) {
            $steamAchievementsUrl = file_get_contents_curl("http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?appid=".$_GET['appid']."&key=41AF33A7F00028D1E153D748597DEEF3&steamid=".$_GET['steamid']."&l=en&name=en");

        echo $steamAchievementsUrl;

    }

    if(isset($_GET['appid']) && !isset($_GET['steamid'])) {
            $steamAchievementsContent = file_get_contents_curl("http://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2/?key=41AF33A7F00028D1E153D748597DEEF3&appid=".$_GET['appid']."");
            echo $steamAchievementsContent;
    }

    if(isset($_GET['items']) && isset($_GET['steamid'])) {

        $itemsArray = array('CSGO' => '', 'Dota2' => '', 'TF2' => '');
        $CSGOItemsUrl = file_get_contents_curl("http://steamcommunity.com/profiles/".$_GET['steamid']."/inventory/json/730/2");
        $DOTAItemsUrl = file_get_contents_curl("http://steamcommunity.com/profiles/".$_GET['steamid']."/inventory/json/570/2");
        $TF2ItemsUrl = file_get_contents_curl("http://steamcommunity.com/profiles/".$_GET['steamid']."/inventory/json/440/2");
        $CSGOItems = json_decode($CSGOItemsUrl, true);
        $DotaItems = json_decode($DOTAItemsUrl, true);
        $TF2Items = json_decode($TF2ItemsUrl, true);
        foreach($CSGOItems['rgDescriptions'] as $key=>$value) {
            $itemsArray['CSGO'][$key] = $value;
        }
        foreach($DotaItems['rgDescriptions'] as $key=>$value) {
            $itemsArray['Dota2'][$key] = $value;
        }
        foreach($TF2Items['rgDescriptions'] as $key=>$value) {
            $itemsArray['TF2'][$key] = $value;
        }
        echo json_encode($itemsArray);
    }
?>

