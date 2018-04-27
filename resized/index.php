<?php
    $root = dirname(dirname(__FILE__));

    include $root.'/lib/engine.php';

    Webfairy::cleanTmp(dirname(__FILE__), array('index.php'));

    $legal_sizes = array(
        'thumb' => array(100,80),
        'small' => array(240,210),
        'medium' => array(600,315),
        'large' => array(1024,1024),
    );

    foreach ($legal_sizes as $key => $value) {
        $legal_sizes['auto-height-'.$key] = array($value[0],0);
    }

    $f = $_GET['f'];
    $s = $_GET['s'];

    $ext = pathinfo($f, PATHINFO_EXTENSION);

    if (array_key_exists($s, $legal_sizes) == true) {
        include $root.'/lib/tools/image.php';

        $img = new Zebra_Image();

        $img->source_path = $root.DS.'uploads'.DS.$f;
        $img->target_path = dirname(__FILE__).DS.$s.'_'.$f;

        list($width, $height) = $legal_sizes[$s];

        if ($img->resize($width, $height, ZEBRA_IMAGE_CROP_TOPCENTER)) {
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img->target_path)).' GMT');
            header('Content-Type: image/'.$ext);
            header('Cache-control: max-age='.(60*60*24*365));
            header('Expires: '.gmdate(DATE_RFC1123, time()+60*60*24*365));

            exit(file_get_contents($img->target_path));
        }
    }
