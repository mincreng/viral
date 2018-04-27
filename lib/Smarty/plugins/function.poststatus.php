<?php
    function smarty_function_poststatus($params, $template)
    {
        if ((boolean) $params['post']['published'] == true) {
            return sprintf('<span class="label label-success">%s</span>', tr('published'));
        } else {
            return ((int) $params['post']['publishedon'] == 0) ?  sprintf('<span class="label label-warning">%s</span>', tr('waiting_approval')) : sprintf('<span class="label label-danger">%s</span>', tr('unpublished'));
        }
    }
