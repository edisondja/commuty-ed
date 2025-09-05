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
        <br/><br/><br/>
        {if $estado!=='baneado'}
        <div class="card mb-3 card-custom">
        <div style="position: absolute; right: 10px; top: 10px;">
            <div class="dropdown custom-dropdown">
                <button class="btn btn-custom dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-align-justify"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Reportar</a></li>
                    <li><a class="dropdown-item" href="#">Agregar a favorito</a></li>
                    <li><a class="dropdown-item" href="#">Puntear</a></li>
                </ul>
            </div>
        </div>

            <div class="card-body">
                <input type='hidden' value='{$id_tablero}' id='id_tablero'/>
                {if $user_session!=''}
                    <input type='hidden' value='{$user_session}' id='usuario'/>
                    <input type='hidden' value='{$foto_perfil}' id='foto_url'/>
                {else}
                    <input type='hidden' value='0' id='id_usuario'/>
                    <input type='hidden' value='0' id='usuario'/>
                    <input type='hidden' value='0' id='foto_url'/>
                {/if}
                <img src="{$foto_usuario}" alt="{$usuario}" class="profile-img">
                <strong class="username-text">{$usuario}</strong>
                <h5 class="card-title title-text">{$titulo}</h5>
                <p class="card-text description-text" id='descripcion'>{$descripcion}</p>

           <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner fixed-size-carousel">
                    
                    {* Caso: hay multimedia *}
                    {if $multimedias_t|@count > 0}
                        {foreach from=$multimedias_t item=multimedia name=mediaLoop}
                            {if $multimedia.tipo_multimedia == 'imagen'}
                                <div class="carousel-item {if $smarty.foreach.mediaLoop.first}active{/if}">
                                    <img src="{$multimedia.ruta_multimedia|replace:"../":""}" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                                </div>
                            {else}
                                <div class="carousel-item {if $smarty.foreach.mediaLoop.first}active{/if}">
                                    <video src="{$multimedia.ruta_multimedia|replace:"../":""}" 
                                        class="d-block w-100 img-fluid card-img-top fixed-size-video" 
                                        controls></video>
                                </div>
                            {/if}
                        {/foreach}

                    {* Caso: no hay multimedia, solo OG imagen *}
                    {elseif $og_imagen != ''}
                        <div class="carousel-item active">
                            <img src="{$dominio}/{$og_imagen}" 
                                class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                        </div>
                    {/if}
                </div>

                {* Controles solo si hay más de una multimedia *}
                {if $multimedias_t|@count > 1}
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                {/if}
            </div>

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

                <div class="card card-comments" id="coments">
                    <ul class="list-group list-group-flush" id='data_coments'>
                    </ul>
                </div>

                <div class="card card-comment-input">
                    <ul class="list-group list-group-flush">
                        {if $id_user!=''}
                            <div id="interface_og"></div>
                            <div class="list-group-item flex-container barContentComent fixed-bottom">
                                <img src="{$foto_perfil}" class="rounded comment-profile-img">
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
<script type="text/javascript" src='js/single_board.js'></script>
<script type="text/javascript" src='js/action_coments.js'></script>
{/literal}

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
</style>
