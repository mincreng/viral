<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
        {user_profile_header row=$row}

        <div class="row">
          <div class="col-md-12">
              {if $user.isLoggedIn}
                  <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <form id="share-form" action="{$config.base_url}/ajax.php?c=post_timeline&uid={$row.id}" method="post" role="form" enctype="multipart/form-data" class="share-box">
                            <div class="share-form">
                              <ul class="post-types">
                                <li class="post-type">
                                  <a class="status" href="#"><i class="fa fa-file"></i> {tr('status')}</a>
                                </li>
                                <li class="post-type">
                                  <a class="photos" href="#"><i class="fa fa-camera"></i> {tr('photo')}</a>
                                </li>
                                <li class="post-type">
                                  <a class="videos" href="#"><i class="fa fa-film"></i> {tr('video')}</a>
                                </li>
                                <li class="post-type">
                                  <a class="location" href="#"><i class="fa fa-map-marker"></i> {tr('place')}</a>
                                </li>
                              </ul>
                              <div class="share">
                                <div class="arrow"></div>
                                <div><textarea name="message" cols="40" rows="1" id="status_message" class="form-control message" placeholder="{tr('what_on_mind')}"></textarea> </div>
                                <div class="image hide">
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="image"/>                              
                                </div>
                                <div class="video hide">
                                  <input type="text" class="form-control" placeholder="{tr('url')}" id ="videoUrl" name="videoUrl" />
                                </div>
                                <div class="place hide">
                                  <input id="geocomplete" class="form-control" type="text" name="location" placeholder="{tr('enter_location')}">
                                  <div class="map_canvas"></div>
                                  <input type="hidden" name="lat" />
                                  <input type="hidden" name="lng" />
                                </div>
                                <input type="hidden" name="shareType" class="shareType" value="status"/>
                              </div>
                              <input type="submit" name="submit" value="{tr('post_btn')}" id="btn-share" class="btn btn-sm btn-default btn-share" />
                          </div>
                        </form>    
                    </div>
                  </div>          
              {/if}

            <noscript>
                 {if count($posts) > 0}    
                    <ul class="timeline">
                        {timeline_posts posts=$posts style='b'} 
                    </ul>
                {else} 
                    <div class="col-md-12">
                        <p class="msg"><i class="fa fa-envelope"></i> {tr('no_posts')}</p>
                    </div>                       
                {/if}         
                <br />      
                {$pagination}      
            </noscript>

            <ul id="timeline" class="timeline hide-if-js hide-if-no-js" data-uid="{$row.id}"></ul>
            
            <p id="loaderCircle" class="text-center hide-if-no-js">
                <i class="fa fa-spin fa-refresh"></i>
            </p>  

          </div>
        </div>    

    </div>
  </div>
</div>