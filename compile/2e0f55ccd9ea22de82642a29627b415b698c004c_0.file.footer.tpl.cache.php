<?php
/* Smarty version 4.5.3, created on 2024-11-05 23:55:43
  from 'C:\xampp\htdocs\ventasrd\template\footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.3',
  'unifunc' => 'content_672aa26f2aecb0_22716080',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2e0f55ccd9ea22de82642a29627b415b698c004c' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ventasrd\\template\\footer.tpl',
      1 => 1715445650,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_672aa26f2aecb0_22716080 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '305531210672aa26f224653_19775917';
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
