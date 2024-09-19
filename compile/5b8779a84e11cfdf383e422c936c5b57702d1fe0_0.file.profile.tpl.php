<?php
/* Smarty version 4.5.3, created on 2024-09-13 04:31:49
  from 'C:\xampp\htdocs\ventasrd\template\profile.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_66e3a415ed9e99_94716900',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5b8779a84e11cfdf383e422c936c5b57702d1fe0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\profile.tpl',
      1 => 1720312247,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:board.tpl' => 1,
  ),
),false)) {
function content_66e3a415ed9e99_94716900 (Smarty_Internal_Template $_smarty_tpl) {
?>      
      <div class="row">
        <div class="col-md-3">
                <br/>
                        <?php if ($_smarty_tpl->tpl_vars['user_session']->value != '') {?>
                            <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['id_user']->value;?>
' id='id_usuario'/>
                            <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['user_session']->value;?>
' id='usuario'/>
                            <input type='hidden' value='<?php echo $_smarty_tpl->tpl_vars['foto_perfil']->value;?>
' id='foto_url'/>
                        <?php } else { ?>
                            <input type='hidden' value='0' id='id_usuario'/>
                            <input type='hidden' value='0' id='usuario'/>
                            <input type='hidden' value='0' id='foto_url'/>
                        <?php }?>
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
                                <td><img src='<?php echo $_smarty_tpl->tpl_vars['dominio']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['data_profile']->value->foto_url;?>
' style='margin:5px;height:100px;width:100px;border-radius:100px'/></td>
                                <td>
                                <strong style='margin:auto'><?php echo $_smarty_tpl->tpl_vars['data_profile']->value->usuario;?>
 <i class="fa-solid fa-square-check" style='color:#1bd093'></i>
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
                    
                        <p style='margin:auto;text-align: -webkit-center;color:#190d0d'><?php echo $_smarty_tpl->tpl_vars['data_profile']->value->bio;?>
 
                        </p>
            
            </div>

            </div>
        </div>
          

             
</div><hr/>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['boards']->value, 'tablero');
$_smarty_tpl->tpl_vars['tablero']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tablero']->value) {
$_smarty_tpl->tpl_vars['tablero']->do_else = false;
?>
                            
    <?php $_smarty_tpl->_subTemplateRender("file:board.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div> <br/>
                
    
    
        <?php echo '<script'; ?>
 type="text/javascript" src=''><?php echo '</script'; ?>
>
    


<?php }
}
