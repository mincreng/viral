<?php
function smarty_function_url_format($params, $template)
{
    $s = array(
        '[return_url]'
    );
    
    $r = array(
        $template->tpl_vars['return_url']->value
    );

    return str_replace($s,$r,$params['url']);
}
