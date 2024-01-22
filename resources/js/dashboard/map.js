"use strict";

// Class definition
var KTMapJS = function () {
    var form;
    let map;
    let markers = [];
    const initCordinates = { lat: 7.8871574, lng: -0.7397095 };
  
    var initMap = () => {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: initCordinates,
            mapTypeId: "terrain",
        });
    }

    // Adds a marker to the map and push to the array.
    var addMarker = (latLng, contentString) => {
        const marker = new google.maps.Marker({
            position: latLng,
            map: map,
            animation: google.maps.Animation.DROP,
        });

        const infowindow = new google.maps.InfoWindow({
            content: contentString,
        });

        marker.addListener("click", () => {
            infowindow.open(map, marker);
        });

        markers.push(marker);
    }

    // Sets the map on all markers in the array.F
    var setMapOnAll = (map) => {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    // Removes the markers from the map, but keeps them in the array.
    var hideMarkers = () => {
        setMapOnAll(null);
    }

    // Shows any markers currently in the array.
    var showMarker = () => {
        setMapOnAll(map);
    }

    // Deletes all markers in the array by removing references to them.
    var deleteMarkers = () => {
        hideMarkers();
        markers = [];
    }

    return {
        // Public Functions
        init: function () {
            if (ENABLE_GOOGLE_SERVICE == 0) {
                return;
            }

            initMap();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTMapJS.init();
});