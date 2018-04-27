{literal}
<style type="text/css">
#webfairy-switcher {
    direction:ltr;
    position: fixed;
    width: 135px;
    top: 100px;
    left: 0px;
    background: none repeat scroll 0% 0% #F7F7F7;
    padding: 0px 10px 10px;
    border: 1px solid #FFF;
    box-shadow: 0px 0px 2px #FFF inset, 0px 0px 5px #999;
    z-index: 2147483647;
    font-size:16px
}
#webfairy-switcher-btn {
    padding: 0 6px;
    display: block;
    background: none repeat scroll 0% 0% #F7F7F7;
    position: absolute;
    right: -34px;
    top: 20px;
    box-shadow: 0px 0px 2px #FFF inset, 2px 0px 5px #999;
    cursor: pointer;
    font-size:25px;
}
#webfairy-switcher ul,
#webfairy-switcher ol {
    clear: both;
    overflow: hidden;
    padding:0;
    margin:0;
}
#webfairy-switcher ul li {
    float: left;
    list-style: none;
    margin: 1px;
}
#webfairy-switcher ul li a {
    display: block;
    width: 25px;
    height: 25px;
    border: 1px solid #000;
    cursor: pointer;
}
#webfairy-switcher ol li{
    list-style-type: square;
    list-style-position: inside;
    padding: 2px 0px;    
}
#webfairy-footer-note{
    position: fixed;
    bottom: 0;
    font-size: 12px;
    padding: 5px;
    background: #FFE601;
    color: #000;
    text-align: center;
    width: 100%;
    z-index:9999;
}
</style>


<script type="text/javascript">
	jQuery(document).ready(function() {
		$open = false ;
		jQuery("#webfairy-switcher").css('left','-135px');
		jQuery("#webfairy-switcher-btn").click(function() {
			if( $open ){
				jQuery(this).parent().animate({left: '-135px'}, 500);
				$open = false ;
			}else{
				jQuery(this).parent().animate({left: '0'}, 500);
				$open = true ;
			}
		});
	
	});
</script>
{/literal}

<div id="webfairy-switcher" style="left: 0px;">
    <a id="webfairy-switcher-btn">
        <i class="fa fa-cog"></i>
    </a>
    <h4>Main Skin</h4>
    <ul>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?color=67C8F2&return={$return_url}" style="background-color:#67C8F2"></a>
        </li>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?color=D63E3D&return={$return_url}" style="background-color:#D63E3D"></a>
        </li>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?color=BCCB2F&return={$return_url}" style="background-color:#BCCB2F"></a>
        </li>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?color=F88C00&return={$return_url}" style="background-color:#F88C00"></a>
        </li>
    </ul>
    
    <h4>Text Direction</h4>
    <ol>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?dir=rtl&return={$return_url}">RTL</a> | <a href="{$config.base_url}/demo_switcher.php?dir=ltr&return={$return_url}">LTR</a>
        </li>
    </ol>  
    
    <h4>Width</h4>
    <ol>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?width=full&return={$return_url}">Full</a> | <a href="{$config.base_url}/demo_switcher.php?width=fixed&return={$return_url}">Fixed</a>
        </li>
    </ol>  
      
    <h4>Main Language</h4>
    <ol>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?lang=ar&return={$return_url}">Arabic</a>
        </li>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?lang=en&return={$return_url}">English</a>
        </li>
    </ol>    
    
    <h4>Sidebar/Main</h4>
    <ol>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?main_col=10&return={$return_url}">10/2</a> | <a href="{$config.base_url}/demo_switcher.php?main_col=9&return={$return_url}">9/3</a> | <a href="{$config.base_url}/demo_switcher.php?main_col=8&return={$return_url}">8/4</a>
        </li>
    </ol>
       
    <ol>
        <li>
            <a href="{$config.base_url}/demo_switcher.php?rest=true&return={$return_url}">Rest All</a>
        </li>
    </ol>    
</div>
<div id="webfairy-footer-note">
This Is "Read Only" Demo Of "Webfairy Mediat" (you can not ADD, EDIT, DELETE, UPLOAD)
</div>