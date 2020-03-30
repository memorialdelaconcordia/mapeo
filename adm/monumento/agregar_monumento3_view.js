function deleteMedia(idMultimedia, idMonumento) {
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'agregar_monumento_aux.php',
        data:{op:'1', 'id_multimedia':idMultimedia, 'id_monumento':idMonumento}
    }).done(function(data){
        alert("Multimedia eliminada con éxito.");   

        showMedia(data);
    });
}                

function modifyMedia(idMultimedia, idMonumento) {
    
    var titulo = $('#titulo_media_'+idMultimedia).val();
    var autor = $('#autor_media_'+idMultimedia).val(); 
    var fuente = $('#fuente_media_'+idMultimedia).val(); 
    var licencia = $('#licencia_media_'+idMultimedia).val(); 
    var link = $('#link_media_'+idMultimedia).val();     
   
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'agregar_monumento_aux.php',
        data: {op:'6', 
               'id_monumento':idMonumento,
               'id_multimedia':idMultimedia, 
               'titulo':titulo,
               'autor':autor,
               'fuente':fuente,
               'licencia':licencia,
               'link':link}
    }).done(function(data){
        alert("Multimedia modificada con éxito.");   

        showMedia(data);
    });
}    

function showMedia(data) {

    var multimedia = $('#multimedia_actual');
    multimedia.empty();

    var id = $('#id').val();
    
    $.each(data, function(index, element) {
        var elementoMultimedia = $([
              '<div id="multimedia_' + element.id_multimedia + '" class="row">',
              '  <div class="col-md-6">',
              '     <img src="/multimedia/' + element.direccion_archivo + '" class="img-thumbnail">',
              '  </div>',
              '  <div class="col-md-6">',
              '    <div class="form-group">',
            '      <label for="titulo_media">Título</label>',
          '      <input class="form-control" name="titulo_media[]" id="titulo_media" type="text" value="' + element.titulo + '"/>',
            '    </div>',
            '    <div class="form-group">',
            '      <label for="autor_media">Autor</label>',
          '      <input class="form-control" name="autor_media[]" id="autor_media" type="text" value="' + element.autor + '"/>',
            '    </div>', 
            '    <div class="form-group">',
            '      <label for="fuente_media">Fuente</label>',
            '      <input class="form-control" name="fuente_media[]" id="fuente_media" type="text" value="' + element.fuente + '"/>',
            '    </div>',
            '    <div class="form-group">',
            '      <label for="licencia_media">Licencia</label>',
            '      <input class="form-control" name="licencia_media[]" id="licencia_media" type="text" value="' + element.licencia + '"/>',
            '    </div>',
            '    <div class="form-group">',
            '      <label for="link_media">Link</label>',
            '      <input class="form-control" name="link_media[]" id="link_media" type="text" value="' + element.link + '"/>',
            '    </div>',
            '    <button type="button" class="btn btn-primary" onclick="modifyMedia(' + element.id_multimedia + ',' + id + ')">Modificar</button>',                
            '    <button type="button" class="btn btn-primary" onclick="deleteMedia(' + element.id_multimedia + ',' + id + ')">Eliminar</button>',    
              '  </div>',
              '</div>'
            ].join("\n"));

        multimedia.append(elementoMultimedia);
    }); 
}
   
function envioFormaMultimedia(form) {
//$('#form_new_multimedia').on('submit', function (e) {
  //e.preventDefault();
    
    var formData = new FormData(form);
      $.ajax({
        type: 'post',
        url: 'agregar_monumento_aux.php',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        dataType: 'json'
      }).done(function(data) {
          alert("Multimedia guardada con éxito.");  
    
          showMedia(data);
      });
//}); 
}

function deleteNoticia(idNoticia, idMonumento) {
    $.ajax({
        dataType: 'json',
        type:'POST',
        url: 'agregar_monumento_aux.php',
        data:{op:'3', idNoticia:idNoticia, 'id_monumento':idMonumento}
    }).done(function(data){
        alert("Noticia eliminada con éxito.");  
        
        ShowNoticias(data);
    });
}

function modifyNoticia(idNoticia, idMonumento) {
    
    var titulo = $('#titulo_noticia-'+idNoticia).val();
    var fecha = $('#fecha_noticia-'+idNoticia).val();
    var fuente = $('#fuente_noticia-'+idNoticia).val();
    var link = $('#link_noticia-'+idNoticia).val();
   
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'agregar_monumento_aux.php',
        data: {op:'7', 
               'id_monumento':idMonumento,
               'id_noticia':idNoticia, 
               'titulo':titulo,
               'fecha':fecha,
               'fuente':fuente,
               'link':link}
    }).done(function(data){
        alert("Noticia modificada con éxito.");   

        showNoticias(data);
        
        $( "input[id|='fecha_noticia']" ).datepicker({
            showOn: "button",
            buttonImage: "/images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Seleccionar fecha",
            dateFormat: "dd/mm/yy"
          });        
        
    });
}  

$('#form_nueva_noticia').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: 'agregar_monumento_aux.php',
      cache: false,
      contentType: false,
      processData: false,
      data: new FormData(this),
      dataType: 'json'
    }).done(function(data) {
        alert("Noticia guardada con éxito.");  

        showNoticias(data);
    });
});

function showNoticias(data) {

    var noticias = $('#noticias_actuales');
    noticias.empty();

    var id = $('#id').val();
    
    $.each(data, function(index, element) {
        var noticia = $([
            '<div id="noticia">',
            '    <div class="form-group">',
            '      <label for="titulo_noticia">Título</label>',
            '      <input class="form-control" name="titulo_noticia[]" id="titulo_noticia-' + element.id_noticia + '" type="text" value="' + element.titulo + '"/>',
            '    </div>', 
            '    <div class="form-group">',
            '      <label for="link_noticia">Link</label>',
            '      <input class="form-control" name="link_noticia[]" id="link_noticia-' + element.id_noticia + '" type="text" value="' + element.link + '"/>',
            '    </div>',
            '    <div class="form-group">',
            '      <label for="fecha_noticia">Fecha</label>',
            '      <input class="form-control" name="fecha_noticia[]" id="fecha_noticia-' + element.id_noticia + '" type="text" value="' + element.fecha + '" readonly />',
            '    </div>',
            '    <div class="form-group">',
            '      <label for="fuente_noticia">Fuente</label>',
            '      <input class="form-control" name="fuente_noticia[]" id="fuente_noticia-' + element.id_noticia + '" type="text" value="' + element.fuente + '"/>',
            '    </div>',
            '    <button type="button" class="btn btn-primary" onclick="modifyNoticia(' + element.id_noticia + ',' + id + ')">Modificar</button>',
            '    <button type="button" class="btn btn-primary" onclick="deleteNoticia(' + element.id_noticia + ',' + id + ')">Eliminar</button>',
            '  </div>',
            '</div>'
            ].join("\n"));

        noticias.append(noticia);
    }); 
}


$( "#btn_submit" ).click(function() {
    $( "#form_crear_sitio" ).submit();
});

