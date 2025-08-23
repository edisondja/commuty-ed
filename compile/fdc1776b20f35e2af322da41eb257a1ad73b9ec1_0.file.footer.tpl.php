<?php
/* Smarty version 3.1.48, created on 2025-08-23 19:47:31
  from '/opt/lampp/htdocs/commuty-ed/template/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_68a9feb31f6231_30068650',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fdc1776b20f35e2af322da41eb257a1ad73b9ec1' => 
    array (
      0 => '/opt/lampp/htdocs/commuty-ed/template/footer.tpl',
      1 => 1755969815,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_68a9feb31f6231_30068650 (Smarty_Internal_Template $_smarty_tpl) {
?>
            
            <?php if ($_smarty_tpl->tpl_vars['content_config']->value == 'boards') {?>
              <nav aria-label="Page navigation example" >
                <ul class="pagination"  style="margin-left:30%; margin-top:5px">
                  <li class="page-item" id='retroceder'>
                    <a class="page-link"  style='cursor:pointer' aria-label="Next">
                      <span aria-hidden="true" >Back</span>
                    </a>
                  </li>
                  <li class="page-item" id='avanzar'>
                    <a class="page-link" style='cursor:pointer' aria-label="Next">
                      <span aria-hidden="true" >Next</span>
                    </a>
                  </li>
                </ul>
              </nav>
              <input type='hidden' id='pagina' value='<?php echo $_smarty_tpl->tpl_vars['pagina']->value;?>
'/>
            <?php } else { ?>
            <?php }?>
            <br><hr>
            <footer class="bg-dark text-center text-white">
          
              
            </footer>
      </div> 
  </body>
</html><?php }
}
