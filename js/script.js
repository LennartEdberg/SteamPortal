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
        $.get("steamauth/userInfo.php?appid=" + appID + "&steamid=" + steamID, function(data, status){
        var playerAchievements = JSON.parse(data);

            if(playerAchievements.playerstats.success) {
                for(var i = 0; i < playerAchievements.playerstats.achievements.length; i++) {
                    $("#achievement-list").append("<p>Name: " + playerAchievements.playerstats.achievements[i].name + "</p><p>Description: " + playerAchievements.playerstats.achievements[i].description + "</p></p><p>Achieved: " + playerAchievements.playerstats.achievements[i].achieved + "</p>")
                }
            } else {
                $("#achievement-list").append("<p>No achievements for this game :(</p>");
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
