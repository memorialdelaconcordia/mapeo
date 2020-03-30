# Mapeo de la Memoria
El Mapeo de la Memoria es un mapa digital de Guatemala en el que puedes participar enviando fotos, audios, videos o textos de lugares que recuerdan a todas las víctimas del Conflicto Armado como parte de nuestra memoria colectiva.

[https://mapeo.memorialdelaconcordia.org](https://mapeo.memorialdelaconcordia.org)

## Requisitos 
Para poder instalar la aplicación es necesario contar con los siguientes requisitos de software:

1. Base de datos MySQL 5.6+ 
2. Servidor web Apache2
3. PHP 5.5+

## Google Maps
Para poder mostrar el mapa de sitios en la aplicación se utiliza el API de Google Maps, para lo cual es necesario contar con una llave (API key) y configurarla dentro de la aplicación.

Para crear un API key puedes seguir este [instructivo](https://developers.google.com/maps/documentation/javascript/get-api-key). 

Luego, debes ingresar la llave en la página index.php en la siguiente sección, en lugar del texto LLAVE_GOOGLE_MAPS:

    <!-- Google Maps -->
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS&callback=initMap">
    </script>	

# Instalación

1. Crear una nueva base de datos en MySQL.
2. Crear un usuario con permisos completos a esta base de datos.
3. Ejecutar el script ubicado en db/script.sql para crear las tablas dentro de esta nueva base de datos.
4. Copiar el contenido del presente repositorio a una carpeta dentro del servidor Apache.
5. ¡Listo! Al ingresar desde un navegador web a esta carpeta del servidor podrás visualizar la aplicación y empezarla a utilizar.


