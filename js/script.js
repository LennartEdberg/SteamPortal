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
        
        var steamID = $(this).attr('steamid');

        $.get("steamauth/userInfo.php?items=1&steamid=" + steamID, function(data, status){
            var ItemsObj = JSON.parse(data);
            for(var key in ItemsObj.CSGO)
                {
                    $("#CSGO-list").append("<div class='item-wrap'><img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.CSGO[key].icon_url + "'><p>" + ItemsObj.CSGO[key].name + "</p></div>");
                }
            for(var key in ItemsObj.Dota2)
                {
                    $("#DOTA-list").append("<div class='item-wrap'><img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.Dota2[key].icon_url + "'><p>" + ItemsObj.Dota2[key].name + "</p></div>");
                }
            for(var key in ItemsObj.TF2)
                {
                    $("#TF2-list").append("<div class='item-wrap'><img src='http://cdn.steamcommunity.com/economy/image/" + ItemsObj.TF2[key].icon_url + "'><p>" + ItemsObj.TF2[key].name + "</p></div>");
                }
        });

    });
//Hämta alla items -> http://steamcommunity.com/profiles/76561197995308584/inventory/json/730/2
    
    $('.gamesBtn').on('click', function(){
       
        if($('#friendlist').is(":visible")){
            $('#game-list').show();
            $('#friendlist').slideUp();
        }
        
        if($('#item-list').is(":visible")) {
            $('#game-list').show();
            $('#item-list').slideUp();
        }
    });
    
    $('.friendsBtn').on('click', function(){
        $('#friendlist').slideDown();
            setTimeout(function(){
              $('#game-list').hide();  
            }, 200);
        $('#item-list').slideUp();
    });
    
    $('.dashboardBtn').on('click', function(){
        $('#friendlist').slideDown();
        $('#game-list').show();
        $('#item-list').slideUp();
    });
    
    $('#item-list').hide();
    $('.itemss').on('click', function(){
            
        if($('#game-list').is(":visible")) {
            $('#item-list').show();
                $('#game-list').slideUp();
            }
    });
    
    $('#CSGO-btn').on('click', function(){
        $('#CSGO-list').slideDown();
        $('#DOTA-list').hide();
        $('#TF2-list').hide();
    })
    $('#Dota-btn').on('click', function(){
        $('#DOTA-list').slideDown();
        $('#CSGO-list').hide();
        $('#TF2-list').hide();
    })
    $('#TF-btn').on('click', function(){
        $('#TF-list').slideDown();
        $('#DOTA-list').hide();
        $('#CSGO-list').hide();
    })
    
});
