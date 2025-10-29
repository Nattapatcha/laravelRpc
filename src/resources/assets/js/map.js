var Map = function(options) {
    var settings = {}; 

    settings = $.extend(true, {}, settings, options);

    var init = function() {
        renderMap();
    };

    var renderMap = function()
    {
        var mapContainer = document.getElementById('map');

        if (mapContainer == undefined) {
            return false;
        }

        var map = new google.maps.Map(mapContainer, {
          zoom: 15,
          center: settings.position
        });

        console.log(settings);

        new google.maps.Marker({
            position: settings.position,
            map,
            title: ""
        });
    }

    return {
        init: init
    };
}

$(function() {
    var options = {};

    if (window.global != undefined) {
        options = window.global
    }

    if ($("#map").length) {
        var map = new Map(options);
        map.init();
    }
});
