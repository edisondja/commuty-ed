<div class="col-md-3">
  <div class="menu-fijo" style="color: balck;">
    </br>
    <ul class="list-group" style="color:black; background: transparent;">

        {if $foto_perfil!==''}
            <li class="list-group-item" style="color:white; background: transparent;">
              <img src="{$foto_perfil}" class="rounded" style="background:white; margin:2px; width:50px; height:50px;">
              {$nombre} {$apellido}
            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> Posts {$cantidad_tableros_usuario->tableros}
            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-heart"></i> Likes {$cantidad_tableros_likes}
            </li>
        {else}
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="registrer.php" style="text-decoration: none; color:white;">Registrate</a>
            </li>
            <li class="list-group-item" style="color:white; background: transparent;">
              <i class="fa-solid fa-book"></i> 
              <a href="contactar.php" style="text-decoration: none; color:white;">Contactanos</a>
            </li>
        {/if}
    </ul>
  </div>
</div>
