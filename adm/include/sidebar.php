<div class="sidebar" data-color="blue">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
    <div class="logo">
        <!-- <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            CT
        </a>  -->
        <a href="#" class="simple-text logo-normal">
            Mapeo de la Memoria
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
        
            <?php 
                
                $inicio = '<li>
                             <a href="/adm/index.php">
                               <i class="now-ui-icons arrows-1_minimal-right"></i>
                               <p>Inicio</p>
                             </a>
                           </li>';
            
                $crearSitio = '<li>
                                 <a href="/adm/monumento/agregar_monumento.php">
                                   <i class="now-ui-icons ui-1_simple-add"></i>
                                   <p>Crear sitio de memoria</p>
                                 </a>
                               </li>';
            
                $sitios = '<li>
                             <a href="/adm/campo/gestion_monumento.php">
                               <i class="now-ui-icons location_pin"></i>
                               <p>Sitios de memoria</p>
                             </a>
                           </li>';
                
                $sitiosAdmin = '<li>
                                  <a href="/adm/campo/gestionMonumentoAdmin.php">
                                    <i class="now-ui-icons location_pin"></i>
                                    <p>Sitios de memoria (Admin)</p>
                                  </a>
                                </li>';
                
                $campos = '<li>
                             <a href="/adm/admin/gestionCampo.php">
                               <i class="now-ui-icons design_bullet-list-67"></i>
                               <p>Campos</p>
                             </a>
                           </li>';
                
                $usuarios = '<li>
                               <a href="/adm/admin/gestionUsuario.php">
                                 <i class="now-ui-icons users_single-02"></i>
                                 <p>Usuarios</p>
                               </a>
                             </li>';
                
                $crearNoticia = '<li>
                               <a href="/adm/admin/noticia.php">
                                 <i class="now-ui-icons objects_globe"></i>
                                 <p>Crear noticia</p>
                               </a>
                             </li>';
                
                $sitiosSugeridos = '<li>
                                      <a href="/adm/admin/sitios_sugeridos.php">
                                        <i class="now-ui-icons business_bulb-63"></i>
                                        <p>Sitios sugeridos</p>
                                      </a>
                                    </li>';
                
                $contactos = '<li>
                                      <a href="/adm/admin/contactos.php">
                                        <i class="now-ui-icons ui-1_email-85"></i>
                                        <p>Mensajes recibidos</p>
                                      </a>
                                    </li>';
                
                $informacion = '<li>
                                      <a href="/adm/admin/informacion.php">
                                        <i class="now-ui-icons ui-1_email-85"></i>
                                        <p>Editar Páginas</p>
                                      </a>
                                    </li>';
                
                if(isset($_SESSION['rol'])) {
                    
                    //Rol de campo:
                    if(in_array(3, $_SESSION['rol'])){
                        echo $crearSitio;
                    }
                    //Rol de monumento:
                    if(in_array(2, $_SESSION['rol']) || in_array(3, $_SESSION['rol'])){
                        echo $sitios;
                    }
                    //Rol de admin:
                    if(in_array(1, $_SESSION['rol'])){
                        echo $sitiosAdmin;
                        
                        echo $campos;
                        
                        echo $usuarios;
                        
                        echo $crearNoticia;
                        
                        echo $sitiosSugeridos;
                        
                        echo $contactos;
                        
                        echo $informacion;
                    }
                    
                }
            
            ?>
        
            <!-- <li>
                <a href="/adm/admin/gestionPaginas.php">
                    <i class="now-ui-icons arrows-1_minimal-right"></i>
                    <p>Gestionar Páginas</p>
                </a>
            </li>  -->
        </ul>
    </div>
</div>