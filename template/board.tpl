<br/><br/>
<div class="row">
    <div class="col-md-3"></div>

    <div class="col-sm-5" style="margin-bottom:15px;">
        <div class="card card-board mb-3" id="board{$tablero.id_tablero}">
            <div class="body" style="padding:5px">
                <div class="title">
                <div class="board-header">

                        <!-- Perfil -->
                        <a href="{$url_board}/profile_user.php?user={$tablero.usuario}" class="profile-link">
                            <img class="imagenPerfil" src="{$dominio}/{$tablero.foto_url}" />
                            <strong>{$tablero.nombre} {$tablero.apellido}</strong>
                        </a>

                        <!-- Acciones -->
                        <div class="actions" style="float:right;">
                            {if $user_session!=''}
                                {if $id_user==$tablero.id_user}
                                    <i class="fa-solid fa-pen-to-square edit-icon"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal_update"
                                    data-value="{$tablero.id_tablero}"
                                    style="cursor:pointer;">
                                    </i>
                                {/if}
                            {/if}

                            <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:' ':'_'}">
                                <i class="fa-solid fa-eye view-icon"></i>
                            </a>
                        </div>

                    </div>
                </div>

                <p class="description-text" id="text{$tablero.id_tablero}">{$tablero.descripcion}</p>

                {if $tablero.imagen_tablero!==''}
                    <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:" ":"_"}">
                        <img src="{$dominio}/{$tablero.imagen_tablero}" class="card-img-top board-image" alt="...">
                    </a>
                {/if}
            </div>

            <div class="card-footer footer-icons" style="float:right">
                <div style="float:right">
                    <i class="fa-solid fa-thumbs-up" style="display:none"></i>
                    <i class="fa-solid fa-bookmark" style="display:none"></i>
                    <i class="fa-regular fa-share-from-square share-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-thumbs-up like-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-comment-dots comment-icon" style="cursor:pointer"></i>
                    <i class="fa-regular fa-bookmark bookmark-icon" style="cursor:pointer"></i>
                    {if $user_session!=''}
                        {if $id_user==$tablero.id_user}
                            <i class="fa fa-trash delete-icon" data-value="{$tablero.id_tablero}" style="cursor: pointer;" aria-hidden="true"></i>
                        {/if}
                    {/if}
                </div>
            </div>
        </div>

    </div>

    {if $counter==true}
        <!-- Visualizar publicidad de banners -->
        {include file="ads.tpl"}    
    {/if}
</div>

<style>
.card-board {
    background-color: #1f2a2f;
    color: white;
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card-board:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

.imagenPerfil {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    margin-right: 5px;
}

.title strong a {
    color: #20c997;
    text-decoration: none;
}

.title .edit-icon, .title .view-icon {
    color: #ffffff;
    margin-left: 5px;
}

.description-text {
    color: #cfd8dc;
    padding-left: 10px;
    margin-top: 5px;
}

.board-image {
    object-fit: cover;
    width: 100%;
    max-height: 300px;
    border-radius: 5px;
    margin-top: 5px;
}

.footer-icons i {
    color: #20c997;
    margin-left: 8px;
    transition: transform 0.2s ease;
}

.footer-icons i:hover {
    transform: scale(1.2);
    color: #17a589;
}

.delete-icon {
    color: #ff6b6b;
}

.share-icon {
    color: #f8f9fa;
}

.like-icon, .comment-icon, .bookmark-icon {
    color: #20c997;
}
</style>
