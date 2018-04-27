<?php /* Smarty version Smarty-3.1.18, created on 2015-05-24 23:32:09
         compiled from "D:\nx\htdocs\Mediat\WebFairy-Mediat\1.4\tpl\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:423855623549e194e1-32459879%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dcba4bda3df5aabddde852ca7b6b7fbb60e52065' => 
    array (
      0 => 'D:\\nx\\htdocs\\Mediat\\WebFairy-Mediat\\1.4\\tpl\\index.tpl',
      1 => 1432221487,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '423855623549e194e1-32459879',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'grid' => 0,
    'config' => 0,
    'posts' => 0,
    'pagination' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55623549ed1516_69034094',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55623549ed1516_69034094')) {function content_55623549ed1516_69034094($_smarty_tpl) {?><div class="row">
      <div class="col-md-<?php echo $_smarty_tpl->tpl_vars['grid']->value['main'];?>
 <?php if ($_smarty_tpl->tpl_vars['config']->value['reverse_col_order']) {?>col-md-push-<?php echo $_smarty_tpl->tpl_vars['grid']->value['sidebar'];?>
<?php }?>">
        <noscript>
             <?php if (count($_smarty_tpl->tpl_vars['posts']->value)>0) {?>    
                <ul class="posts-list-b">
                    <?php smarty_template_function_posts_list($_smarty_tpl,array('posts'=>$_smarty_tpl->tpl_vars['posts']->value,'style'=>'b'));?>
 
                </ul>
            <?php } else { ?> 
                <div class="col-md-12">
                    <p class="msg"><i class="fa fa-envelope"></i> <?php echo tr('no_posts');?>
</p>
                </div>                       
            <?php }?>         
            <br />      
            <?php echo $_smarty_tpl->tpl_vars['pagination']->value;?>
      
        </noscript>

        <ul id="bricks-container"></ul>    
        
        <p id="loaderCircle" class="text-center hide-if-no-js">
            <i class="fa fa-spin fa-refresh"></i>
        </p>    

      </div>
      <div class="col-md-<?php echo $_smarty_tpl->tpl_vars['grid']->value['sidebar'];?>
 <?php if ($_smarty_tpl->tpl_vars['config']->value['reverse_col_order']) {?>col-md-pull-<?php echo $_smarty_tpl->tpl_vars['grid']->value['main'];?>
<?php }?>">
        <?php smarty_template_function_sidebar($_smarty_tpl,array());?>

      </div>      
  </div><?php }} ?>
