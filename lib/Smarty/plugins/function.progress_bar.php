<?php
function smarty_function_progress_bar($params, $template)
{
    $percent = $params['percent'];

    $class = '';
    
    if($percent > 80){
        $class = 'progress-bar-success';
    }elseif($percent > 40){
        $class = 'progress-bar-warning';
    }elseif($percent > 0){
         $class = 'progress-bar-danger';
    }

    return '<div class="progress progress-striped">
      <div class="progress-bar '.$class.'" role="progressbar" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$percent.'%;">
        '.$percent.'%
      </div>
    </div>';

}
