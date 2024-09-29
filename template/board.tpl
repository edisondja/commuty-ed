
<br/><br/>
<div class="row">
    <div class="col-md-3"></div>

      <div class="col-sm-6" style='margin-bottom:15px;'>

                <div class='card text-white bg-dark mb-3' id="board{$tablero.id_tablero}">
                            <div class='body' style='padding:5px'>
                              <div class='title'><strong><a href='{$url_board}/profile_user.php?user={$tablero.usuario}'> <img class='imagenPerfil' src='{$dominio}/{$tablero.foto_url}'/></a>
                                {$tablero.nombre} {$tablero.apellido} 
                                <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:" ":"_"}">
                                <i class="fa-solid fa-eye"></i></strong></div>
                                </a>

                                <div style="float: right;">
                                {if $user_session!=''}
                                  {if $id_user==$tablero.id_user}
                                      <i class="fa-solid fa-pen-to-square" style="cursor:pointer;"></i>
                                  {/if}
                                {/if}
                                </div>
                              
                              <p style='padding-left: 10px;'>{$tablero.descripcion}​</p>
                              <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:" ":"_"}">
                                {if $tablero.imagen_tablero!==''}
                                  
                                  <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:" ":"_"}">
                                  <img src="{$dominio}/{$tablero.imagen_tablero}" class="card-img-top" alt="...">
                                  </a>

                                {else}

                                  <a href="{$dominio}/single_board.php?id={$tablero.id_tablero}/{$tablero.titulo|replace:" ":"_"}">
                                  </a>
                                {/if}
                              </a>

                         
                            </div>
                            <p class='p'  style='padding:5px;'>
                              
                            </p>

                              <div class="card-footer" style='float:right'>
                                    <div style='float:right'>
                                      <i class="fa-solid fa-thumbs-up"style='display:none'></i>
                                      <i class="fa-solid fa-bookmark" style='display:none'></i>
                                      <i class="fa-regular fa-share-from-square" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-thumbs-up" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-comment-dots" style='cursor:pointer'></i>
                                      <i class="fa-regular fa-bookmark" style='cursor:pointer'></i>
                                      {if $user_session!=''}
                                        {if $id_user==$tablero.id_user}
                                            <i class="fa fa-trash" data-value='{$tablero.id_tablero}' style="cursor: pointer;" aria-hidden="true"></i>
                                          {/if}
                                        {else}
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