/*global admin_google_vars, document, google, window, jQuery, alert, wpestate_placeMarker*/
var map = '';
var selected_city = '';
var geocoder;
var gmarkers = [];
var propertyMarker_submit='';


function wprentals_initialize_map_internal() {
    "use strict";
    var myPlace, mapOptions, marker;
    
    if(admin_google_vars.wprentals_map_type==1){
            geocoder = new google.maps.Geocoder();
            myPlace = new google.maps.LatLng(admin_google_vars.general_latitude, admin_google_vars.general_longitude);
            mapOptions = {
                flat: false,
                noClear: false,
                zoom: 17,
                scrollwheel: true,
                draggable: true,
                center: myPlace,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
            google.maps.visualRefresh = true;


            marker = new google.maps.Marker({
                position: myPlace
            });
            marker.setMap(map);
            gmarkers.push(marker);
            google.maps.event.addListener(map, 'click', function (event) {
                wprentals_placeMarker_google_internal(event.latLng);
            });
    }else if(admin_google_vars.wprentals_map_type==2){
        
        var mapCenter = L.latLng( admin_google_vars.general_latitude, admin_google_vars.general_longitude);
        map =  L.map( 'googleMap',{
            center: mapCenter, 
            zoom: 15
        }).on('load', function(e) {
            jQuery('#gmap-loading').remove();
        });
       
                        
        var tileLayer = L.tileLayer(  'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        } );

        map.addLayer( tileLayer );
        map.on('click', function(e){
    
            map.removeLayer( propertyMarker_submit );
            var markerCenter        =   L.latLng( e.latlng);
            propertyMarker_submit   =   L.marker(e.latlng).addTo(map);
            propertyMarker_submit.bindPopup('<div class="submit_leaflet_admin">Latitude: ' + e.latlng.lat + ' Longitude: ' + e.latlng.lng+'</div>').openPopup();
            document.getElementById("property_latitude").value =  e.latlng.lat ;
            document.getElementById("property_longitude").value = e.latlng.lng;
        });


        var markerCenter        =   L.latLng(mapCenter);
        propertyMarker_submit   =   L.marker( markerCenter ).addTo(map);
        propertyMarker_submit.bindPopup('<div class="submit_leaflet_admin">Latitude: ' + admin_google_vars.general_latitude + ' Longitude: ' +  admin_google_vars.general_longitude+'</div>').openPopup();
       
        
    }
}



function wprentals_placeMarker_google_internal(location) {
    "use strict";
    var infowindow, marker;
    wpestate_removeMarkersadmin();
    marker = new google.maps.Marker({
        position: location,
        map: map
    });
    gmarkers.push(marker);
    infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
    });

    infowindow.open(map, marker);
    document.getElementById("property_latitude").value = location.lat();
    document.getElementById("property_longitude").value = location.lng();
}

if (typeof google === 'object' && typeof google.maps === 'object') {
    google.maps.event.addDomListener(document.getElementById('estate_property-googlemap').getElementsByClassName("handlediv")[0], 'click', function () {
        "use strict";
        google.maps.event.trigger(map, "resize");
    });
}


if (typeof google === 'object' && typeof google.maps === 'object') {
    google.maps.event.addDomListener(window, 'load', wprentals_initialize_map_internal);
}else{
    wprentals_initialize_map_internal();
}




function wpestate_removeMarkersadmin(){
    for (i = 0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}

function wpestate_admin_codeAddress() {
    "use strict";
    var address, full_addr, state, country, infowindow;
    address = document.getElementById('property_address').value;
    full_addr = address + ',' + selected_city;
    state = document.getElementById('property_state').value;

    if (state) {
        full_addr = full_addr + ',' + state;
    }

    country = document.getElementById('property_country').value;
    if (country) {
        full_addr = full_addr + ',' + country;
    }


    geocoder.geocode({'address': full_addr}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            wprentals_internalmap_set_postion(results[0].geometry.location.lat(),results[0].geometry.location.lng());

        } else {
            alert(admin_google_vars.geo_fails + status);
        }
    });
}

jQuery('#admin_place_pin').on('click',function (event) {
    "use strict";
    event.preventDefault();
    wpestate_admin_codeAddress();
});

jQuery('#property_citychecklist label').on('click',function (event) {
    "use strict";
    selected_city = jQuery(this).text();
});



if (document.getElementById('property_address')) {
 
    if( parseInt(admin_google_vars.wprentals_places_type)===2){

        var placesAutocomplete = places({
            appId:      admin_google_vars.wp_estate_algolia_app_id,
            apiKey:  admin_google_vars.wp_estate_algolia_api_key,
            type: 'address',
            language: 'en',
            templates: {
                 value: function(suggestion) {
                   return suggestion.name;
                 }
             },
            container: document.querySelector('#property_address')
        });

        placesAutocomplete.on('change', function(place) {
            wprentals_submit_agolia_codeAddress_internal(place.suggestion.latlng.lat,place.suggestion.latlng.lng);
        });
    }
}

function wprentals_submit_agolia_codeAddress_internal(listing_lat,listing_lon){
  
        if(admin_google_vars.wprentals_map_type==2){
            map.removeLayer( propertyMarker_submit );

            var markerCenter    =   L.latLng( listing_lat,listing_lon );
            propertyMarker_submit      =   L.marker( markerCenter ).addTo(map);
            map.setView(markerCenter, 15);
            propertyMarker_submit.bindPopup('<div class="submit_leaflet_admin">Latitude: ' + listing_lat + ' Longitude: ' + listing_lon+'</div>').openPopup();
            document.getElementById("property_latitude").value =  listing_lat ;
            document.getElementById("property_longitude").value = listing_lon;
        }else{
            wprentals_internalmap_set_postion(listing_lat,listing_lon);
        }
}



function wprentals_internalmap_set_postion(listing_lat,listing_long){
    
    wpestate_removeMarkersadmin();
    var myLatLng = new google.maps.LatLng( listing_lat, listing_long);
    map.setCenter(myLatLng);
    var marker = new google.maps.Marker({
        map: map,
        position: myLatLng
    });
    
    gmarkers.push(marker);
    var infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + listing_lat + '<br>Longitude: ' + listing_long
    });

    infowindow.open(map,marker);
    document.getElementById("property_latitude").value  =   listing_lat ;
    document.getElementById("property_longitude").value =   listing_long;
    
}