

<div class="row"> 

        {include file='back_office_components/menu_backoffice.tpl'}
        
        {if $option == 'usuarios'}  

            {include file='back_office_components/modulo_usuario.tpl'}

        {else if $option=='publicaciones'}
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            {include file='back_office_components/modulo_boards.tpl'}


        {else if $option=='configuraciones'}
            
            <!-- Aqui se coloca el modulo de ver los posts de los usuarios-->
            {include file='back_office_components/modulo_configuracion.tpl'}

        {else if $option=='envar_correos'}

            {include file='back_office_components/enviar_correo.tpl'}
            
        {else if $option=='adm_banners'}   
            
            {include file='back_office_components/modulo_banner.tpl'}

        {else if $option=='reportes'}    
            {include file='back_office_components/modulo_reportes.tpl'}
        {/if}

</div>


<style>
    .content-container {
        margin-top: 50px;
        padding: 30px;
        background-color:#1a1c1d;
        border-radius: 10px;
    }
    h3 {
        margin-bottom: 30px;
    }
    .flex-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .checkbox-p {
        margin-right: 10px;
    }
    .table-dark th, .table-dark td {
        color: #f8f9fa;
    }
    .table-dark th {
        background-color: #6c757d;
    }
    .table-dark td {
        background-color: #495057;
    }
</style>