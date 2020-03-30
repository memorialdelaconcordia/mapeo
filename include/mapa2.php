<script>
    var markers = [];
    var markersDb = <?php
        echo json_encode($json_puntos);
    ?>;
    
    var sitiosGeo = [];

    var map;
    
    function initMap() {
        
        //Centro del mapa:
        var center = {lat: 15.7740714, lng: -90.1832344};
        
        var mapProp = {
            center: center,
            zoom: 7,
            mapTypeId: 'roadmap'
        };
        
        map = new google.maps.Map(document.getElementById("mapa"), mapProp);
    
        var pathFoto = "/multimedia/";

        //Se recorre la información de la base de datos:
        for (var i = 0; i < markersDb.length; i++) {

            //TODO parseFloat
            var posicion = {lat: parseFloat(markersDb[i].latitud), lng: parseFloat(markersDb[i].longitud)};

			var image = {                                                                //letter|fillcolor|textcolor
			        url: "https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|1897EB|000000",
			        size: new google.maps.Size(23, 35),
			        origin: new google.maps.Point(0, 0),
			        anchor: new google.maps.Point(10, 34),
			        scaledSize: new google.maps.Size(23, 35)
			      };

            var marker = new google.maps.Marker({
                position: posicion,
                animation: google.maps.Animation.DROP,
                map: map,
                icon: image
            });
            

            //Foto oficial:
            if(markersDb[i].foto_oficial == null || markersDb[i].foto_oficial == '') {
                markersDb[i].foto_oficial = pathFoto + '/thumbnails/default.JPG';
            } else {
                var index = markersDb[i].foto_oficial.lastIndexOf("/") + 1;
                var filename = markersDb[i].foto_oficial.substr(index);
                markersDb[i].foto_oficial = pathFoto + '/thumbnails/' + filename;
            }
            
            //Descripción corta:
            if((markersDb[i].descripcion_corta == '')||(markersDb[i].descripcion_corta == null)) {
                markersDb[i].descripcion_corta='';
            }

            //TARJETA DEL SITIO SOBRE EL MAPA:
            marker.content = '<div class="container-fluid">' + 
                                '<div class="row">' +
                                    '<div class="col-sm-4">' +
                                        '<a href="presentacion2.php?id='+markersDb[i].id_monumento+'">' + 
                                            '<img class="small-view-img" src="'+markersDb[i].foto_oficial+'">' +
                                        '</a>' +
                                    '</div>' +       
                                    '<div class="col-sm-8 text-center">' +         
                                        '<h5 class="">'+markersDb[i].titulo+'</h5>' + 
                                        '<p class="">'+markersDb[i].descripcion_corta+'</p>' +
                                        '<a class="" href="presentacion2.php?id='+markersDb[i].id_monumento+'">' + 
                                        	'<span class="link-spanner"></span>' +
                                        '</a>' +
                                    '</div>' +
                                     
                                '</div>' +
                             '</div>';
    
            marker.titulo = markersDb[i].titulo;
            marker.foto = markersDb[i].foto_oficial;
            marker.id = markersDb[i].id_monumento;

            //TODO Revisar.                 
            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(this.content);
                infowindow.open(this.getMap(),this);
            });

            markers.push(marker);
        }

        //Clusters:
        //var markerCluster = new MarkerClusterer(map, markers, {imagePath: '../images/m'});

        
        //*************************************************************
        //Geolocalización:
    
        var infoWindowGeolocation = new google.maps.InfoWindow({map: map});
        
        // Try HTML5 geolocation:
        if (navigator.geolocation) {
            
            navigator.geolocation.getCurrentPosition(function(position) {
                
              var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
              };

              infoWindowGeolocation.setPosition(pos);
              infoWindowGeolocation.setContent('Su ubicación');
              map.setCenter(pos);

              //Si no hay sitios para mostrar (luego de haber hecho una búsqueda):
			  if(markers.length == 0) {

                  var divTituloUbicacionesCercanas = $('#titulo-ubicaciones-cercanas');

                  //Título:
	  				var $titulo = $([
	                          '<div class="text-center" style="padding: 10;">',
	                          '    <h3>No hay resultados para la búsqueda realizada.</h3>',
	                          '</div>'
	                        ].join("\n"));
	  					
	  				divTituloUbicacionesCercanas.append($titulo);
                  
			  }	else {

	              for (var i = 0; i < markers.length; i++) {
	                  sitiosGeo.push({
	                      id: markers[i].id,
	                      nombre: markers[i].titulo,
	                      foto: markers[i].foto,
	                      googleMapsURL: 'https://www.google.com/maps/dir/?api=1&destination='+markers[i].getPosition().lat()+','+markers[i].getPosition().lng(),
	                      distancia: getDistanceFromLatLonInKm(pos.lat, pos.lng, markers[i].getPosition().lat(), markers[i].getPosition().lng()),
	                      lat: markers[i].getPosition().lat(),
	                      lng: markers[i].getPosition().lng() 
	                  });
	                }

	                //Se ordenan los sitios del más carcano al más lejano...
	                sitiosGeo.sort(function(a, b){return a.distancia - b.distancia});

	  			  if(sitiosGeo.length > 0) {

	                  var divTituloUbicacionesCercanas = $('#titulo-ubicaciones-cercanas');
	                  var divUbicacionesCercanas = $('#ubicaciones-cercanas');

	                 	//Título:
	  				var $titulo = $([
	                          '<div class="text-center" style="padding: 10;">',
	                          '<h3>Sitios más cercanos a su ubicación:</h3>',
	                          '</div>'
	                        ].join("\n"));
	  					
	  				divTituloUbicacionesCercanas.append($titulo);

	  			    //TARJETA SITIO
	                  for (var i = 0; i < 4 && i < sitiosGeo.length; i++) {
	                      var $ubicacion = $([
	                          '<div class="col-md-3 text-center">',
	                          '<div class="card mb-3" style="max-width: 250px; border: 0; ">',
	                          '  <a class="" href="presentacion2.php?id='+sitiosGeo[i].id+'"><img class="card-img-top" src="' + sitiosGeo[i].foto + '" alt=""></a>',
	                          '  <div class="card-body">',
	                          '    <a class="titulo-lugar" href="presentacion2.php?id='+sitiosGeo[i].id+'"><p class="card-title">' + sitiosGeo[i].nombre + '</p></a>',
	                          '    <p class="card-text">Distancia al sitio: ' + sitiosGeo[i].distancia + ' Km.</p>',
	                          '    <a href="' + sitiosGeo[i].googleMapsURL + '" class="card-icons" target="_blank"><i class="material-icons" style="font-size: 36px; margin-right: 10px;" data-toggle="tooltip" title="¿Cómo llegar?">navigation</i></a>',	
	                          '    <a href="#" onclick="mostrarEnElMapa(' + sitiosGeo[i].lat + ',' + sitiosGeo[i].lng + ')" class="card-icons"><i class="material-icons" style="font-size: 36px; margin-left: 10px;" data-toggle="tooltip" title="Mostrar en el mapa">place</i></a>',         
	                          '  </div>',               
	                          '</div>',
	                          '</div>'
	                        ].join("\n"));

	                      divUbicacionesCercanas.append($ubicacion);
	                  }						    
	  			  }

			  }

          }, function() { //Error al obtener la ubicación:
            handleLocationError(true, infoWindowGeolocation, map.getCenter());
          });
            
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindowGeolocation, map.getCenter());
        }
        //**********************************************************************************
        
    } //END function initMap()

    
    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        /*infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');*/

      //Si no hay sitios para mostrar (luego de haber hecho una búsqueda):
	  if(markers.length == 0) {

          var divTituloUbicacionesCercanas = $('#titulo-ubicaciones-cercanas');

          //Título:
			var $titulo = $([
                      '<div class="text-center" style="padding: 10;">',
                      '    <h3>No hay resultados para la búsqueda realizada.</h3>',
                      '</div>'
                    ].join("\n"));
				
			divTituloUbicacionesCercanas.append($titulo);
          
	  }	else {

	        // Se obtienen números aleatorios (hasta un máximo de 4 o hasta que haya lugares disponibles):
	        var arr = []
	        while((arr.length < markers.length) && (arr.length < 4)) {
	            var randomnumber = getRandomInt(0, markers.length - 1);
	            if(arr.indexOf(randomnumber) > -1) continue;
	            arr[arr.length] = randomnumber;
	        }

            // Se seleccionan los lugares a mostrar en la págian en base a los números aleatorios 
            // obtenidos en el paso anterior:
	        for(var i = 0; i < arr.length; i++) {
	            agregarASitiosGeo(arr[i]);
	        } 

	        // Se pintan los lugares en la página:
	        if(sitiosGeo.length > 0) {

	            var divUbicacionesCercanas = $('#ubicaciones-cercanas');

				//TARJETA SITIO 
	            for (var i = 0; i < sitiosGeo.length; i++) {

                    // mx-auto: for horizontally centering fixed-width block level content—that is, content that has display: block and a width set—by setting the horizontal margins to auto.
                    // Ref: https://getbootstrap.com/docs/4.0/utilities/spacing/#horizontal-centering
                    
	                var $ubicacion = $([
	                    '<div class="col-md-3 text-center d-flex align-items-center">',
	                    '  <div class="card mx-auto mb-3" style="max-width: 250px; border: 0;">',
	                    '    <a class="titulo-lugar" href="presentacion2.php?id='+sitiosGeo[i].id+'"><img class="card-img-top" src="' + sitiosGeo[i].foto + '" alt=""></a>',
	                    '    <div class="card-body">',
	                    '      <a class="titulo-lugar" href="presentacion2.php?id='+sitiosGeo[i].id+'"><p class="card-title">' + sitiosGeo[i].nombre + '</p></a>',
	                    '      <a href="' + sitiosGeo[i].googleMapsURL + '" class="card-icons" target="_blank"><i class="material-icons" style="font-size: 36px; margin-right: 10px;" data-toggle="tooltip" title="¿Cómo llegar?">navigation</i></a>',	
	                    '      <a href="#" onclick="mostrarEnElMapa(' + sitiosGeo[i].lat + ',' + sitiosGeo[i].lng + ')" class="card-icons"><i class="material-icons" style="font-size: 36px; margin-left: 10px;" data-toggle="tooltip" title="Mostrar en el mapa">place</i></a>',         
	                    '    </div>',               
	                    '  </div>',
	                    '</div>'
	                  ].join("\n"));

                    //Se agrega la columna al row ubicaciones-cercanas: 
	                divUbicacionesCercanas.append($ubicacion);
	            }	            

	        }
	  }

    }

    
    //https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
    function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
      var R = 6371; // Radius of the earth in km
      var dLat = deg2rad(lat2-lat1);  // deg2rad below
      var dLon = deg2rad(lon2-lon1); 
      var a =   
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
        Math.sin(dLon/2) * Math.sin(dLon/2)
        ; 
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
      var d = R * c; // Distance in km
      //https://stackoverflow.com/questions/11832914/round-to-at-most-2-decimal-places-only-if-necessary
      return Math.round(d * 100) / 100;
    }

    
    function deg2rad(deg) {
        return deg * (Math.PI/180)
    }
    

    function mostrarEnElMapa(lat,lng) {
        map.setCenter({lat: lat, lng: lng});
        map.setZoom(18);
    }
    
    /**
     * Returns a random integer between min (inclusive) and max (inclusive)
     * Using Math.round() will give you a non-uniform distribution!
     */
    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function agregarASitiosGeo(i) {

        sitiosGeo.push({
            id: markers[i].id,
            nombre: markers[i].titulo,
            foto: markers[i].foto,
            googleMapsURL: 'https://www.google.com/maps/dir/?api=1&destination='+markers[i].getPosition().lat()+','+markers[i].getPosition().lng(),
            distancia: -1,
            lat: markers[i].getPosition().lat(),
            lng: markers[i].getPosition().lng() 
        });
    }
    
    //TODO
    function toggleBounce() {
      if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
      } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      }
    }    
    
</script>           