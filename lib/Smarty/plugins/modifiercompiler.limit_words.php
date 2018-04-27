<?php

function smarty_modifiercompiler_limit_words($params, $compiler)
{
    if (!isset($params[1])) {
        $params[1] = 5;
    }

    return 'Webfairy::limit_words('.$params[0].','.$params[1].')';
}
