<?php
    /**
     * @author Webfairy MediaT CMS - www.Webfairy.net
     */

    include dirname(__FILE__).'/lib/engine.php';
        $Webfairy = new Webfairy();
            $Webfairy->start(false, false, false);

    $langs = $Webfairy->getLangsArray(explode(',', $_GET['pkgs']));

    header("Content-type:application/javascript; charset: UTF-8");

    echo sprintf('var LangArr = %s;', json_encode($langs));
?>

function Language(){
    var __construct = function() {
        lang = "<?php echo $Webfairy->getLang(); ?>";
        return;
    }()

    this.getStr = function(str, defaultStr) {
        var retStr = eval('LangArr.' + str);
        if (typeof retStr != 'undefined')
        {
            return retStr;
        } else {
            return defaultStr;
        }
    }
}
var translator = new Language();
