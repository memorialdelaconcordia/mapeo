<div class="modal fade" id="modal_agregar_organizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar Organización</h4>
            </div>
            <div class="modal-body">
                <form name="form_agregar_organizacion" id="form_agregar_organizacion" method="post" action="/include/agregarOrganizacion.php">
                    <div class="form-group">
                        <label for="nombre_organizacion">Nombre de la organización</label>
                        <input class="form-control" name="nombre_organizacion" id="nombre_organizacion" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="persona_organizacion">Persona responsable de la organización</label>
                        <input class="form-control" name="persona_organizacion" id="persona_organizacion" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="telefono_organizacion">Teléfono</label>
                        <input class="form-control" name="telefono_organizacion" id="telefono_organizacion" type="number" />
                    </div>
                    <div class="form-group">
                        <label for="email_organizacion">Correo electrónico</label>
                        <input class="form-control" name="email_organizacion" id="email_organizacion" type="email" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agregar Organización</button>
                    </div>
                </form>
                <script type='text/javascript'>
                    //Para limpiar los controles del modal dialog al cerrarse:
                    $('#modal_agregar_organizacion').on('hidden.bs.modal', function () {
                        $('.modal-body').find('input').val('');
                    });     
                </script>                   
            </div>
        </div>
    </div>
</div>