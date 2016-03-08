$(document).ready(function(){
    $('a').click(function() {
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top - 20
        }, 900);
        return false;
    });

    $(".viewAchievements").on('click', function() {
        $("#achievement-list").empty();
        var steamID = $(this).attr('steamid');
        var appID = $(this).attr('appid');
        var achievementsData = "";

        $.get("steamauth/userInfo.php?appid=" + appID, function(data, status){
            achievementsData = JSON.parse(data);
        });

        $.get("steamauth/userInfo.php?appid=" + appID + "&steamid=" + steamID, function(data, status) {
            var playerAchievements = JSON.parse(data);
            if(playerAchievements.playerstats.success && playerAchievements.playerstats.achievements != undefined) {
                for(var i = 0; i < playerAchievements.playerstats.achievements.length; i++) {
                    if(playerAchievements.playerstats.achievements[i].achieved == 1) {
                        $("#achievement-list").append("<p><img src='" + achievementsData.game.availableGameStats.achievements[i].icon + "'></p><p>Name: " + playerAchievements.playerstats.achievements[i].name + "</p><p>Description: " +       playerAchievements.playerstats.achievements[i].description + "</p></p><p>Achieved: " + playerAchievements.playerstats.achievements[i].achieved + "</p>");
                    } else {
                        $("#achievement-list").append("<p><img src='" + achievementsData.game.availableGameStats.achievements[i].icongray + "'></p><p>Name: " + playerAchievements.playerstats.achievements[i].name + "</p><p>Description: " +       playerAchievements.playerstats.achievements[i].description + "</p></p><p>Achieved: " + playerAchievements.playerstats.achievements[i].achieved + "</p>");
                    }
                }
            } else {
                $("#achievement-list").append("<p>No achievements for this game :(</p>");
            }
        });
    });

    $("#itemClick").on('click', function() {
        $("#item-list").empty();
        var steamID = $(this).attr('steamid');

        $.get("steamauth/userInfo.php?items=1&steamid=" + steamID, function(data, status){
            var ItemsObj = JSON.parse(data);
            for(var key in ItemsObj.CSGO)
                {
                    $("#item-list").append("<img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.CSGO[key].icon_url + "'><p>Name: " + ItemsObj.CSGO[key].name + "</p>");
                }
            for(var key in ItemsObj.Dota2)
                {
                    $("#item-list").append("<img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.Dota2[key].icon_url + "'><p>Name: " + ItemsObj.Dota2[key].name + "</p>");
                }
            for(var key in ItemsObj.TF2)
                {
                    $("#item-list").append("<img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.TF2[key].icon_url + "'><p>Name: " + ItemsObj.TF2[key].name + "</p>");
                }

        });

    });
//Hämta alla items -> http://steamcommunity.com/profiles/76561197995308584/inventory/json/730/2
    
    $('.gamesBtn').on('click', function(){
        console.log(123);
       $('#game-list').show();
       $('#friendlist').slideUp();
    });
    
    $('.friendsBtn').on('click', function(){
        $('#friendlist').slideDown();
            setTimeout(function(){
              $('#game-list').hide();  
            }, 200);
    });
    
    $('.dashboardBtn').on('click', function(){
        $('#friendlist').slideDown();
        $('#game-list').show();
    });
    
});
