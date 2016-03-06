$(document).ready(function(){
    $('a').click(function() {
        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top - 0
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



});
