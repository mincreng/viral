<?php
    function smarty_function_ad_render($params, $template)
    {
        static $count = 0;
        $count++;
        $print = true;
        $adsArray = array();
        $return = '';

        if (isset($params['repeat']) == true) {
            if ($count > $params['repeat']) {
                if (isset($params['assign'])) {
                    $template->assign($params['assign'], null);
                }

                return;
            }
        }

        foreach ($template->tpl_vars['adsArray']->value as $ad_key => $ad_value) {
            $adsArray[$ad_key] = $ad_value[rand(0, count($ad_value) - 1)];
        }
        if (array_key_exists($params['size'], $adsArray) == true) {
            if ($adRow = $adsArray[$params['size']]) {
                $code = sprintf(
                    '<div data-id="%s" class="ad-code ad-%s no-js">%s</div>',
                    $adRow['id'],
                    $params['size'],
                    $adRow['code']
                );

                if (isset($params['assign'])) {
                    $print = false;
                    $template->assign($params['assign'], $code);
                }

                if ($print) {
                    return $code;
                }
            }
        }
    }
