
/****** MANEJO DEL MAPA *************************************************************/

    //var lat = <?php echo isset($latitud) ? $latitud : '14.634016'; ?>;
    var lat = $('#latitud').val();
    //var lng = <?php echo isset($longitud) ? $longitud : '-90.515467'; ?>;    
    var lng = $('#longitud').val();
    
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    
    var center = {lat: lat, lng: lng};
    var map;
    var markers = [];
    
    function initMap() {
        
        var mapProp = {
              center: center,
              zoom: 10,
              mapTypeId: 'roadmap'
        };
    
        map = new google.maps.Map(document.getElementById("mapa"), mapProp);
    
        addMarker(center);
    
        map.addListener('click', function(e) {
           addMarker({lat: e.latLng.lat(), lng: e.latLng.lng()});
        });
    }
    
    // Adds a marker to the map and push to the array.
    function addMarker(location) {
    
        deleteMarkers();
        
        var marker = new google.maps.Marker({
          position: location,
          map: map
        });
    
        map.panTo(location);
        
        markers.push(marker);
    
        var latDoc = document.getElementById("latitud");
        latDoc.value = location.lat;
        var lngDoc = document.getElementById("longitud");
        lngDoc.value = location.lng;            
    }
    
    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
    }
      
    // Removes the markers from the map, but keeps them in the array.
    function clearMarkers() {
        setMapOnAll(null);
    }
    
    // Deletes all markers in the array by removing references to them.
    function deleteMarkers() {
        clearMarkers();
        markers = [];
    }         
    
    //Botón "Actualizar coordenadas en el mapa":
    function updateLocation() {
        var latDoc = document.getElementById("latitud").value;
        var lngDoc = document.getElementById("longitud").value;
    
        var newCenter = {lat: parseFloat(latDoc), lng: parseFloat(lngDoc)};
    
        map.panTo(newCenter);
        addMarker(newCenter);
    }

/*******************************************************************************/

    
/********* PARA SUBIR LA IMAGEN DE PORTADA ************************************************/

    //Lee el archivo de imagen y lo muestra en la página junto con el cropper:
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#image').attr('src', e.target.result);
    
                $('#image_cropper').show();
    
                $('#image').cropper({
                    aspectRatio: 16 / 9,
                    crop: function(event) {
                       //console.log(event.detail.x);
                       //console.log(event.detail.y);
                       //console.log(event.detail.width);
                       //console.log(event.detail.height);
                       //console.log(event.detail.rotate);
                       //console.log(event.detail.scaleX);
                       //console.log(event.detail.scaleY);
                    }
                  });
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    
    //$("#form_crear_sitio").submit( function(event) {
    function envioDatos(form) {
        //event.preventDefault();
        
        var formData = new FormData(form);
        //Se elimina el campo "imagen_portada" de los datos de la forma a enviar,
        //debido a que esta información (la imagen) se enviará desde el cropper. 
        formData.delete("imagen_portada");
    
        var cropper = $('#image').data('cropper');
        var idSitio = $('#id').val();
    
        //No se habilitó nunca el cropper y.. 
        if(typeof cropper == "undefined") {
    
            //Es una edición de un sitio (el id del sitio ya existe),
            //y no se está modificando la imagen de portada:
            if(idSitio != '') {
    
                $.ajax('agregar_monumento.php', {
                  method: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  dataType: 'json',
                  success: function (data) {
                    window.location.href = "/adm/monumento/agregar_monumento2.php?id=" + data.id + "&msg=updateSuccess"
                  },
                  error: function (data) {
                    console.log(data)
                    alert('Se produjo un error al guardar el sitio.');
                  }
                });
                
            } else {
                //TODO error
            }
            
        } else { //el cropper se habilitó por lo que hay que subir la imagen recortada:
            
            cropper.getCroppedCanvas().toBlob(function (blob) {
    
                formData.append('imagen_portada', blob);
    
                $.ajax('agregar_monumento.php', {
                  method: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  dataType: 'json',
                  success: function (data) {
                    console.log('Upload success');
                    console.log(data);
                    
                    window.location.href = "/adm/monumento/agregar_monumento2.php?id=" + data.id + "&msg=success"
                  },
                  error: function (data) {
                      console.log(data)
                      alert('Se produjo un error al guardar el sitio.');
                  }
                });
              });
        }
        
    }

/***********************************************************************/

    
/************** OTROS ************************************/    

$(document).ready(function () {

    //Validación de la forma (jQuery Validate):
    //TODO marcar en rojo los campos con errores.
    $('#form_crear_sitio').validate({ //Initialize the plugin...
        rules: {
            titulo: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                }                       
            },
            latitud: {
                required: true,
                number: true                    
            },
            longitud: {
                required: true,
                number: true                            
            },                    
            descripcion_corta: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                }                       
            },
            //TODO tipos de archivo
            imagen_portada: {
                required: function(element) {
                    var idImagenPortada = $('#id_imagen_portada').val();
                    
                    //Si el sitio ya tiene una imagen de portada,
                    //entonces el campo no es obligatorio, de lo contrario sí lo es:
                    if (idImagenPortada.length > 0) {
                        return false;
                    } else {
                        return true;
                    }
                }         
            }
        },
        submitHandler: function(form) {
            envioDatos(form);
            //form.submit();
        },        
        //https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        }
    });

    
    $("#usuario_edicion").select2();

    //Se oculta la sección de edición de la fotografía al cargarse la página:
    $('#image_cropper').hide();
    
    //Para mostrar el ícono de loading:
    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading"); },
        ajaxStop: function() { $body.removeClass("loading"); }
    });    
    
});     

