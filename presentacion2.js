function initMap() {
    
    var map;
    
    var lat = $('#latitud').val();    
    var lng = $('#longitud').val();
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    
    var center = {lat: lat, lng: lng};  
    
    var mapProp = {
          center: center,
          zoom: 10,
          mapTypeId: 'roadmap'
    };

    map = new google.maps.Map(document.getElementById("mapa"), mapProp);

    var marker = new google.maps.Marker({
        position: center,
        map: map
      });

}