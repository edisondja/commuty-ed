
<div class="col-md-3">
<div class="menu-fijo"></br>
    <ul class="list-group"> 

        {if $foto_perfil!==''}
            <li class="list-group-item"><img src="{$foto_perfil}" class="rounded" style="margin: 2px;width:50px;height:50px;">
                {$nombre} {$apellido}
            </li>
            <li class="list-group-item"><i class="fa-solid fa-book"></i> Posts 850</li>
            <li class="list-group-item"><i class="fa-solid fa-heart"></i> Likes 500</li>
        {else}
            <li class="list-group-item"><i class="fa-solid fa-book"></i> <a href="registrer.php" style="text-decoration: none; color:antiquewhite;">Registrate</a></li>
            <li class="list-group-item"><i class="fa-solid fa-book"></i> <a href="contactar.php" style="text-decoration: none; color:antiquewhite;">Contactanos</a></li>
        {/if}
    </ul>
</div>
</div>

