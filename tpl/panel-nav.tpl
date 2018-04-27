<h1><i class="fa fa-wrench"></i> {tr('panel')}</h1>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#panel-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="panel-navbar-collapse">
      <ul class="nav navbar-nav">
            {navbar_render data=$panel_nav}
      </ul>
    </div>
  </div>
</nav>

{* <ol class="breadcrumb">
  <li><a href="#"><i class="fa fa-home"></i> {tr('home')}</a></li>
  {foreach $breadcrumb as $item}
    <li>{if isset($item.url)}<a href="{$item.url}">{$item.text}</a>{else}{$item.text}{/if}</li>
  {/foreach}
</ol> *}