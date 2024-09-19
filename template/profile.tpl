      
      <div class="row">
        <div class="col-md-3">
                <br/>
                        {if $user_session!=''}
                            <input type='hidden' value='{$id_user}' id='id_usuario'/>
                            <input type='hidden' value='{$user_session}' id='usuario'/>
                            <input type='hidden' value='{$foto_perfil}' id='foto_url'/>
                        {else}
                            <input type='hidden' value='0' id='id_usuario'/>
                            <input type='hidden' value='0' id='usuario'/>
                            <input type='hidden' value='0' id='foto_url'/>
                        {/if}
                    <svg class="bd-placeholder-img rounded float-start" style="width:100%;display:none;"
                        xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 200x200" 
                        preserveAspectRatio="xMidYMid slice" 
                        focusable="false"><title>Placeholder</title><rect width="100%" 
                        height="100%" fill="#868e96"></rect><text x="3%" y="50%" 
                        fill="#dee2e6" dy=".3em">La publicidad sera colocada aca 200x200</text></svg>
            </div> 
            <div class="col-md-6">
                    <div class="card" style='padding:20px;'>
                    <div>
                        <table >
                            <tr>
                                <td><img src='{$dominio}/{$data_profile->foto_url}' style='margin:5px;height:100px;width:100px;border-radius:100px'/></td>
                                <td>
                                <strong style='margin:auto'>{$data_profile->usuario} <i class="fa-solid fa-square-check" style='color:#1bd093'></i>
                                </strong>
                                <p>Followers 485,000 m</p>
                                <button class='btn btn-dark fa-solid fa-user-plus'></button>
                                </td>
                                <td>
                                    
                                </td>
                                <i class="fa-regular fa-sun" id="user_update" style='float:right;cursor:pointer'  data-bs-toggle="modal" data-bs-target="#updateUserModal"></i>
                                
                            </tr>
                            <tr>
                            
                            </tr>
                        </table>
                    
                        <p style='margin:auto;text-align: -webkit-center;color:#190d0d'>{$data_profile->bio} 
                        </p>
            
            </div>

            </div>
        </div>
          

             
</div><hr/>

{foreach from=$boards item=tablero}
                            
    {include file="board.tpl"}

{/foreach}
    </div> <br/>
                
    
    {literal}
        <script type="text/javascript" src=''></script>
    {/literal}


