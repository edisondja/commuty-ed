<div class="row">

    <div class="col-md-3">
        <br/>
        <svg class="bd-placeholder-img rounded float-start" style="width:100%;display:none;"
            xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 200x200"
            preserveAspectRatio="xMidYMid slice"
            focusable="false"><title>Placeholder</title><rect width="100%"
            height="100%" fill="#868e96"></rect><text x="3%" y="50%"
            fill="#dee2e6" dy=".3em">La publicidad sera colocada aca 200x200</text></svg>
    </div>

    <div class="col-md-6">
      <!-- Modal Reportar Publicación -->
        <div class="modal fade" id="report_modal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Reportar Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="mb-3">
                <label for="razon_reporte" class="form-label">Razón del reporte</label>
                <textarea class="form-control" id="razon_reporte" rows="3" placeholder="Escribe la razón del reporte..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="enviar_rpt">Enviar Reporte</button>
            </div>
            </div>
        </div>
        </div>
        <br/><br/><br/>
          {if $ads_1}
            {if $ads_1->tipo=='video'}

            <a href={$ads_1->link_banner} target="_blank">
                    <div class="card-body" style="text-align:center">>
                        <video src='{$ads_1->imagen_ruta}' style='width:320px; height:80px;' />
                    </div>
                </a>
               
            {else if $ads_1->tipo=='imagen'}

                <a href={$ads_1->link_banner} target="_blank">
                    <div class="card-body" style="text-align:center">
                        <image src='{$ads_1->imagen_ruta}' style='width:320px; height:80px;' />
                    </div>
                </a>

            {else if $ads_1->tipo=='texto'}

                  <div class="card-body">
                       <h5>{$ads_1->titulo}</h5>
                       <p>{$ads_1->descripcion}</p>
                  </div>

            {else if $ads_1->tipo=='banner'}
         
                   {$ads_1->script_banner}
                  
            {/if}
            
        {/if}
        {if $estado!=='baneado' && $estado!=='error'}
        <div class="card mb-3 card-custom">
        <div style="position: absolute; right: 10px; top: 10px;">
            <div class="dropdown custom-dropdown">
                <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-align-justify"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li id="reportar_publicacion"><a class="dropdown-item" href="#">Reportar</a></li>
                    <li id="agregar_a_favorito"><a class="dropdown-item" href="#">Agregar a favorito</a></li>
                    <li id="agregar_calificacion"><a class="dropdown-item" href="#">Puntear</a></li>
                </ul>
            </div>
        </div>

            <div class="card-body">
                <input type='hidden' value='{$id_tablero}' id='id_tablero'/>
                {if $user_session!=''}
                    <input type='hidden' value='{$id_user}' id='id_usuario'/>
                    <input type='hidden' value='{$user_session}' id='usuario'/>
                    <input type='hidden' value='{$foto_perfil}' id='foto_url'/>
                {else}
                    <input type='hidden' value='0' id='id_usuario'/>
                    <input type='hidden' value='0' id='usuario'/>
                    <input type='hidden' value='0' id='foto_url'/>
                {/if}
                <img src="{$foto_usuario}" width="50" height="50" alt="{$usuario}" class="profile-img">
                <strong class="username-text">{$usuario}</strong>
                <h5 class="card-title title-text">{$titulo}</h5>
                <p class="card-text description-text" id='descripcion'>{$descripcion}</p>

          {assign var="total_multimedia" value=$multimedias_t|@count}

            {* CASO 1: Más de una multimedia (Se activa el Carrusel) *}
            {if $total_multimedia > 1}
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner fixed-size-carousel">
                        {foreach from=$multimedias_t item=multimedia name=mediaLoop}
                            {* Limpiar la ruta: quitar ../ y /videos/ -> videos/ *}
                            {assign var="ruta_limpia" value=$multimedia.ruta_multimedia|replace:"../":""|replace:"/videos/":"videos/"|replace:"/imagenes_tablero/":"imagenes_tablero/"}
                            <div class="carousel-item {if $smarty.foreach.mediaLoop.first}active{/if}">
                                {if $multimedia.tipo_multimedia == 'imagen'}
                                    <img src="{$dominio}/{$ruta_limpia}" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                                {else}
                                    <video src="{$dominio}/{$ruta_limpia}" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-video" controls></video>
                                {/if}
                            </div>
                        {/foreach}
                    </div>
                    
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>

            {* CASO 2: Exactamente una multimedia (Sin estructura de carrusel) *}
            {elseif $total_multimedia == 1}
                <div class="fixed-size-carousel">
                    {assign var="solo_uno" value=$multimedias_t[0]}
                    {assign var="ruta_limpia" value=$solo_uno.ruta_multimedia|replace:"../":""|replace:"/videos/":"videos/"|replace:"/imagenes_tablero/":"imagenes_tablero/"}
                    {if $solo_uno.tipo_multimedia == 'imagen'}
                        <img src="{$dominio}/{$ruta_limpia}" 
                            class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                    {else}
                        <video src="{$dominio}/{$ruta_limpia}" 
                            class="d-block w-100 img-fluid card-img-top fixed-size-video" controls></video>
                    {/if}
                </div>

            {* CASO 3: No hay multimedia pero hay imagen de respaldo (OG) *}
            {elseif $og_imagen != ''}
                <div class="fixed-size-carousel">
                    <img src="{$dominio}/{$og_imagen}" 
                        class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                </div>
            {/if}

                <div class="card card-comments">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style='margin-left:85%; display:none;' id='cerrar_comentarios'>
                            <svg style='color:#515151;' xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </li>
                    </ul>
                </div>
                  {if $ads_2}
                        {if $ads_2->tipo=='video'}

                        <a href={$ads_2->link_banner} target="_blank">
                                <div class="card-body" style="text-align:center">>
                                    <video src='{$ads_2->imagen_ruta}' style='width:320px; height:80px;' />
                                </div>
                            </a>
                        
                        {else if $ads_1->tipo=='imagen'}

                            <a href={$ads_2->link_banner} target="_blank">
                                <div class="card-body" style="text-align:center">
                                    <image src='{$ads_2->imagen_ruta}' style='width:320px; height:80px;' />
                                </div>
                            </a>

                        {else if $ads_2->tipo=='texto'}

                            <div class="card-body">
                                <h5>{$ads_1->titulo}</h5>
                                <p>{$ads_1->descripcion}</p>
                            </div>

                        {else if $ads_2->tipo=='banner'}
                    
                            {$ads_2->script_banner}
                            
                        {/if}
                        
                    {/if}
                {if $like_login_user=='tiene_like'} 
                    <i class="fa-solid fa-heart heart-liked" style="cursor:pointer" id="like"></i>
                    <span id="likes_c">
                        {if $likes->likes>1}
                            {$likes->likes} personas y tu le gusta esto
                        {else}
                            {$likes->likes} Te gusta esto
                        {/if}
                    </span>
                {else}
                    <i class="fa-regular fa-heart heart-default" style="cursor:pointer" id="like"></i>
                    <span id="likes_c">{$likes->likes}</span>
                {/if}
                
                    <i class="fa fa-eye"></i>
                    <span>{$total_views}</span>
                
                <!-- Sistema de Calificación con Estrellas -->
                <div class="rating-section single-rating-section" data-tablero-id="{$id_tablero}">
                    <div class="rating-display">
                        <div class="stars-container" data-rating="0">
                            {for $i=1 to 5}
                                <span class="star" data-value="{$i}" title="Calificar con {$i} {if $i==1}estrella{else}estrellas{/if}">
                                    <i class="far fa-circle"></i>
                                </span>
                            {/for}
                        </div>
                        <div class="rating-info">
                            <span class="rating-average" data-tablero="{$id_tablero}">0.0</span>
                            <span class="rating-separator">/</span>
                            <span class="rating-max">5.0</span>
                            <span class="rating-count">(0 calificaciones)</span>
                        </div>
                    </div>
                </div>

                <div class="card card-comments" id="coments">
                    <ul class="list-group list-group-flush" id='data_coments'>
                    </ul>
                </div>

                <div class="card card-comment-input">
                    <ul class="list-group list-group-flush">
                        {if $id_user!=''}
                            <div id="interface_og"></div>
                            <div class="list-group-item flex-container barContentComent fixed-bottom">
                                <img src="{$foto_perfil}" width="50" height="50" class="rounded comment-profile-img">
                                <textarea id="text_coment" class='textComent' rows='1' cols='25' placeholder='write a comment'></textarea>
                                <svg style='height: 35px;margin: 2px;' id='send_coment' xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor" class="bi bi-arrow-down-square-fill" viewBox="0 0 16 16">
                                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </div>
                        {else}
                            <li class="list-group-item">
                                <a href=''>i want comment need a account now</a>
                                <div id='send_coment' style='display:none'></div>
                            {/if}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <br/>
    {elseif $estado=='error'}
        <p class="h3 title_block">Publicación no encontrada</p>
        <div class="card card-custom">
            <div class="card-body" style="text-align:center; padding:40px;">
                <i class="fas fa-exclamation-triangle" style="font-size:60px; color:#ffc107; margin-bottom:20px;"></i>
                <h4 style="color:#cfd8dc;">La publicación que buscas no existe o ha sido eliminada.</h4>
                <a href="/" class="btn btn-primary mt-3">Volver al inicio</a>
            </div>
        </div>
    {else}
        <p class="h3 title_block">Contenido bloqueado por los administradores</p>
        <div class="card">
            <div class="card-body">
                <img class="card-img-top fixed-size-image" src="{$dominio}/assets/block_content.png"/>
            </div>
        </div>
    {/if}
   </div>
   <!--Componente de publicidad -->
   {include file='ads.tpl'}
</div>

{literal}
<script type="text/javascript">
    // Establecer variables globales para el sistema de calificación
    if (typeof window.dominio === 'undefined') {
        window.dominio = document.getElementById('dominio')?.value || window.location.origin;
    }
    if (typeof window.id_usuario === 'undefined') {
        window.id_usuario = document.getElementById('id_usuario')?.value || 0;
    }
</script>
<script type="text/javascript" src='js/comments_system.js'></script>
<script type="text/javascript" src='js/single_board.js'></script>
<script type="text/javascript" src='js/rating_system.js'></script>
<script type="text/javascript" src='js/vast_player.js'></script>
{/literal}
<script type="text/javascript">
    // Inicializar reproductor VAST para videos del tablero
    document.addEventListener('DOMContentLoaded', function() {
        const idTablero = '{$id_tablero}';
        const videos = document.querySelectorAll('.fixed-size-video');
        
        videos.forEach(function(video, index) {
            // Asignar ID único si no tiene
            if (!video.id) {
                video.id = 'tablero-video-' + index;
            }
            // Inicializar VAST Player con el reproductor específico del tablero
            initVastPlayerForTablero(video.id, idTablero).then(player => {
                if (player) {
                    console.log('VAST Player inicializado para:', video.id);
                }
            });
        });
    });
    
    // Función para inicializar reproductor VAST desde el tablero específico
    async function initVastPlayerForTablero(videoElementId, idTablero) {
        try {
            const formData = new FormData();
            formData.append('action', 'obtener_reproductor_tablero');
            formData.append('id_tablero', idTablero);
            
            const response = await axios.post(
                '/controllers/actions_board.php',
                formData
            );
            
            if (response.data.success && response.data.reproductor) {
                const rep = response.data.reproductor;
                return new VastPlayer(videoElementId, {
                    vastPreroll: rep.vast_url || null,
                    vastMidroll: rep.vast_url_mid || null,
                    vastPostroll: rep.vast_url_post || null,
                    skipDelay: parseInt(rep.skip_delay) || 5,
                    midrollTime: parseInt(rep.mid_roll_time) || 30
                });
            }
        } catch (e) {
            console.warn('No se pudo cargar configuración VAST:', e);
        }
        return null;
    }
</script>

<style>
.card-custom {
    background-color: #1f2a2f;
    color: white;
    border-radius: 10px;
}

.btn-custom {
    background-color: #20c997;
    color: white;
    border: none;
}

.btn-custom:hover {
    background-color: #17a589;
}

.profile-img {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin: 5px;
}

.username-text {
    color: #20c997;
    margin-left: 5px;
}

.title-text {
    color: #ffffff;
}

.description-text {
    color: #cfd8dc;
}

.card-comments {
    background-color: #243537;
    color: white;
    border-radius: 5px;
    margin-top: 15px;
}

.card-comment-input {
    background-color: #243537;
    border-radius: 5px;
    margin-top: 10px;
    padding: 5px;
}

.comment-profile-img {
    width: 34px;
    height: 38px;
    margin: 2px;
}

.textComent {
    width: 65%;
    border-radius: 5px;
    padding: 5px;
    border: 1px solid #20c997;
    background-color: #1f2a2f;
    color: white;
}

.fixed-size-carousel {
    height: 300px;
}

.fixed-size-image {
    object-fit: cover;
    height: 300px;
    width: 100%;
}

.fixed-size-video {
    height: 300px;
    width: 100%;
}

.heart-liked {
    color: #20c997;
}

.heart-default {
    color: white;
}

/* Estilos para el sistema de comentarios */
.comment-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.comment-avatar, .reply-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
}

.comment-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.comment-username, .reply-username {
    color: #20c997;
    font-weight: bold;
}

.comment-date, .reply-date {
    color: #888;
    font-size: 0.85em;
    float: right;
}

.comment-text, .reply-text {
    margin: 10px 0;
    color: #cfd8dc;
    line-height: 1.5;
}

.comment-actions {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #333;
}

.comment-actions i {
    margin-right: 15px;
    color: #888;
    transition: color 0.3s;
}

.comment-actions i:hover {
    color: #20c997;
}

.comment-replies {
    margin-top: 15px;
    margin-left: 50px;
    padding-left: 15px;
    border-left: 2px solid #20c997;
    list-style: none;
}

.reply-item {
    display: flex;
    margin-bottom: 15px;
    padding: 10px;
    background-color: rgba(32, 201, 151, 0.1);
    border-radius: 5px;
}

.reply-content {
    flex: 1;
}

.reply-text {
    margin-top: 5px;
}

.box_comment {
    margin-bottom: 15px;
    padding: 15px;
    background-color: #243537;
    border-radius: 5px;
}

/* Fondo del modal (semi-transparente) */
#report_modal .modal-content {
    background-color: #008080; /* Verde azulado */
    color: #fff; /* Texto blanco para contraste */
    border-radius: 12px;
    border: 2px solid #4d6857ff; /* Amarillo dorado */
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Header */
#report_modal .modal-header {
    border-bottom: 2px solid #546e51ff;
}

/* Botón cerrar */
#report_modal .btn-close {
    filter: invert(1); /* Hace que la "x" blanca se vea en fondo oscuro */
}

/* Body */
#report_modal .modal-body {
    background-color: #006666; /* Verde más oscuro */
    border-radius: 8px;
    padding: 15px;
}

/* Footer */
#report_modal .modal-footer {
    border-top: 2px solid #487255ff;
}

/* Botones */
#report_modal .btn-primary {
    background-color: #20c997; /* Verde azulado brillante */
    border-color: #20c997;
}

#report_modal .btn-primary:hover {
    background-color: #17a2b8;
    border-color: #17a2b8;
}

#report_modal .btn-secondary {
    background-color: #ffd700; /* Amarillo */
    color: #000;
    border-color: #064935ff;
}

#report_modal .btn-secondary:hover {
    background-color: #e6c200;
    border-color: #2c4d40ff;
}

/* Textarea */
#report_modal textarea.form-control {
    background-color: #0c2e2eff; /* Verde clarito */
    color: white;
    border: 1px solid #063d3fff;
    border-radius: 6px;
}

/* Sistema de Calificación en Single Board */
.single-rating-section {
    padding: 15px;
    border-top: 1px solid #2d3748;
    margin-top: 15px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

.single-rating-section .rating-display {
    justify-content: center;
    flex-wrap: wrap;
}

.single-rating-section .stars-container {
    gap: 4px;
}

.single-rating-section .star {
    font-size: 14px;
    width: 18px;
    height: 18px;
}

.single-rating-section .rating-info {
    font-size: 16px;
    margin-left: 15px;
}

.single-rating-section .rating-average {
    font-size: 20px;
}

.single-rating-section .rating-count {
    font-size: 14px;
}

/* Reutilizar estilos del sistema de calificación */
.single-rating-section .star {
    cursor: pointer;
    color: #4a5568;
    transition: all 0.3s ease;
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.single-rating-section .star:hover {
    transform: scale(1.3);
}

.single-rating-section .star i {
    display: block;
    transition: all 0.3s ease;
}

.single-rating-section .star:not(.active) i.fa-circle {
    color: #4a5568;
    opacity: 0.5;
}

.single-rating-section .star.active i.fa-circle {
    color: #20c997;
    font-weight: 900;
    opacity: 1;
    text-shadow: 0 0 10px rgba(32, 201, 151, 0.6);
}

.single-rating-section .star:hover i.fa-circle {
    color: #20c997;
    transform: scale(1.1);
    text-shadow: 0 0 8px rgba(32, 201, 151, 0.4);
}

.single-rating-section .star.hover-active i.fa-circle {
    color: #20c997;
    opacity: 0.8;
}

.single-rating-section .star.active i.far.fa-circle::before {
    content: "\f111";
    font-weight: 900;
}

.single-rating-section .star:not(.active) i.far.fa-circle::before {
    content: "\f111";
    font-weight: 400;
}

.single-rating-section .rating-info {
    display: flex;
    align-items: baseline;
    gap: 4px;
    font-size: 12px;
    color: #cfd8dc;
    flex-wrap: wrap;
}

.single-rating-section .rating-average {
    font-weight: 700;
    color: #20c997;
    font-size: 16px;
}

.single-rating-section .rating-separator {
    color: #718096;
    font-size: 14px;
}

.single-rating-section .rating-max {
    color: #718096;
    font-size: 14px;
}

.single-rating-section .rating-count {
    color: #718096;
    font-size: 14px;
    margin-left: 4px;
}

@keyframes pulse-rating {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

.single-rating-section .star.active {
    animation: pulse-rating 0.3s ease;
}

</style>


