

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
        <br/>
        <br/><br/>
        {if $estado!=='baneado'}
        <div class="card text-white bg-dark mb-3">
        <div style="position: absolute; right: 10px; top: 10px;">
        <div class="dropdown custom-dropdown">
        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-align-justify"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Reportar</a></li>
            <li><a class="dropdown-item" href="#">Agregar a favorito</a></li>
            <li><a class="dropdown-item" href="#">Puntear</a></li>
        </ul>
    </div>
    </div>

            <div class="card-body" style=''>
                <input type='hidden' value='{$id_tablero}' id='id_tablero'/>
                {if $user_session!=''}

                <input type='hidden' value='{$user_session}' id='usuario'/>
                <input type='hidden' value='{$foto_perfil}' id='foto_url'/>
                {else}
                <input type='hidden' value='0' id='id_usuario'/>
                <input type='hidden' value='0' id='usuario'/>
                <input type='hidden' value='0' id='foto_url'/>
                {/if}
                <img src="{$foto_usuario}" alt="{$usuario}" style="border-radius:100px;width:50px;height:50px;margin:5px;">
                <strong>{$usuario}</strong>
                <h5 class="card-title">{$titulo}</h5>
                <p class="card-text" style='margin-top:3px;' id='descripcion'>{$descripcion}</p>

                <img src="" class="img-fluid card-img-top" style='' />
                <div id="carouselExampleControls" class="carousel slide" >
                    <div class="carousel-inner fixed-size-carousel">
                        {if  $multimedias_t=='' &&  $og_imagen!==''}

                        <img src="{$og_imagen}" style='' class="card-img-top fixed-size-image" alt="...">

                        {/if}
                        {if $multimedias_t}
                        <div class="carousel-item active">
                            <img src="{$og_imagen}" class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                        </div>

                        {foreach from=$multimedias_t item=multimedia}

                        {if $multimedia.tipo_multimedia=='imagen'}

                        <div class="carousel-item">
                            <img src="{$dominio}{$multimedia.ruta_multimedia}" class="d-block w-100 img-fluid card-img-top fixed-size-image" alt="...">
                        </div>
                        {else}
                        <div class="carousel-item">
                            <video src="{$dominio}{$multimedia.ruta_multimedia}" class="d-block w-100 img-fluid card-img-top fixed-size-video" controls></video>
                        </div>
                        {/if}

                        {/foreach}

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <div class="card" style="margin-top: 2%;background: #2e3633;">
                    <ul class="list-group list-group-flush">
                        {/if}
                        
                        <li class="list-group-item" style='margin-left:85%; display:none;' id='cerrar_comentarios'>
                            <svg style='color:#515151;' xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </li>
                    </ul>
                </div>

                <i class="fa-regular fa-heart" style="cursor:pointer"></i>
                <span>30,400</span>
                &nbsp;
                <i class="fa-regular fa-bookmark" style="cursor:pointer"></i>
                <span>12,300</span>
                <div class="card" id="coments" style="margin-top: 2%;">
                    <ul class="list-group list-group-flush" id='data_coments'>
                          
                    </ul>
                </div>
                <div class="card" style="margin-top: 2%;">

                    <ul class="list-group list-group-flush">
                        {if $id_user!=''}
                        <div id="interface_og " style=""></div>
                        <div class="list-group-item flex-container barContentComent fixed-bottom">

                            <img src="{$foto_perfil}" class="rounded" style="margin: 2px;width:34px;height:38px;">


                            <textarea id="text_coment" class='textComent' style="width:65%;" rows='1' cols='25' placeholder='write a comment'></textarea>
                            <svg style='height: 35px;margin: 2px;' id='send_coment' xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="currentColor" class="bi bi-arrow-down-square-fill" viewBox="0 0 16 16">
                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5a.5.5 0 0 1 1 0z"/>
                            </svg>
                        </div>
                        {else}
                        <li class="list-group-item">
                            <a href=''>i want comment need a account now</a>
                            <div id='send_coment' style='display:none' ></div>
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
                <img  class="card-img-top fixed-size-image" src="{$dominio}/assets/block_content.png"/>
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
