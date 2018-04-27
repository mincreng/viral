/*
 * @author Webfairy MediaT CMS - www.Webfairy.net
 */

/*!
 * jQuery Upvote - a voting plugin
 * @homepage        https://janosgyerik.github.io/jquery-upvote
 */

;(function($) {
    "use strict";
    var namespace = 'upvote';
    var dot_namespace = '.' + namespace;
    var upvote_css = 'upvote';
    var dot_upvote_css = '.' + upvote_css;
    var upvoted_css = 'upvote-on';
    var dot_upvoted_css = '.' + upvoted_css;
    var downvote_css = 'downvote';
    var dot_downvote_css = '.' + downvote_css;
    var downvoted_css = 'downvote-on';
    var dot_downvoted_css = '.' + downvoted_css;
    var star_css = 'star';
    var dot_star_css = '.' + star_css;
    var starred_css = 'star-on';
    var dot_starred_css = '.' + starred_css;
    var count_css = 'count';
    var dot_count_css = '.' + count_css;
    var enabled_css = 'upvote-enabled';

    function init(dom, options) {
        return dom.each(function() {
            var jqdom = $(this);
            methods.destroy(jqdom);

            var count = parseInt(jqdom.find(dot_count_css).text(), 10);
            count = isNaN(count) ? 0 : count;
            var initial = {
                id: jqdom.attr('data-id'),
                count: count,
                upvoted: jqdom.find(dot_upvoted_css).size(),
                downvoted: jqdom.find(dot_downvoted_css).size(),
                starred: jqdom.find(dot_starred_css).size(),
                callback: function() {}
            };

            var data = $.extend(initial, options);
            if (data.upvoted && data.downvoted) {
                data.downvoted = false;
            }

            jqdom.data(namespace, data);
            render(jqdom);
            setupUI(jqdom);
        });
    }

    function setupUI(jqdom) {
        jqdom.find(dot_upvote_css).addClass(enabled_css);
        jqdom.find(dot_downvote_css).addClass(enabled_css);
        jqdom.find(dot_star_css).addClass(enabled_css);
        jqdom.find(dot_upvote_css).on('click.' + namespace, function() {
            jqdom.upvote('upvote');
        });
        jqdom.find('.downvote').on('click.' + namespace, function() {
            jqdom.upvote('downvote');
        });
        jqdom.find('.star').on('click.' + namespace, function() {
            jqdom.upvote('star');
        });
    }

    function _click_upvote(jqdom) {
        jqdom.find(dot_upvote_css).click();
    }

    function _click_downvote(jqdom) {
        jqdom.find(dot_downvote_css).click();
    }

    function _click_star(jqdom) {
        jqdom.find(dot_star_css).click();
    }

    function render(jqdom) {
        var data = jqdom.data(namespace);
        jqdom.find(dot_count_css).text(data.count);
        if (data.upvoted) {
            jqdom.find(dot_upvote_css).addClass(upvoted_css);
            jqdom.find(dot_downvote_css).removeClass(downvoted_css);
        } else if (data.downvoted) {
            jqdom.find(dot_upvote_css).removeClass(upvoted_css);
            jqdom.find(dot_downvote_css).addClass(downvoted_css);
        } else {
            jqdom.find(dot_upvote_css).removeClass(upvoted_css);
            jqdom.find(dot_downvote_css).removeClass(downvoted_css);
        }
        if (data.starred) {
            jqdom.find(dot_star_css).addClass(starred_css);
        } else {
            jqdom.find(dot_star_css).removeClass(starred_css);
        }
    }

    function callback(jqdom) {
        var data = jqdom.data(namespace);
        data.callback(data);
    }

    function upvote(jqdom) {
        var data = jqdom.data(namespace);
        if (data.upvoted) {
            data.upvoted = false;
            --data.count;
        } else {
            data.upvoted = true;
            ++data.count;
            if (data.downvoted) {
                data.downvoted = false;
                ++data.count;
            }
        }
        render(jqdom);
        callback(jqdom);
        return jqdom;
    }

    function downvote(jqdom) {
        var data = jqdom.data(namespace);
        if (data.downvoted) {
            data.downvoted = false;
            ++data.count;
        } else {
            data.downvoted = true;
            --data.count;
            if (data.upvoted) {
                data.upvoted = false;
                --data.count;
            }
        }
        render(jqdom);
        callback(jqdom);
        return jqdom;
    }

    function star(jqdom) {
        var data = jqdom.data(namespace);
        data.starred = ! data.starred;
        render(jqdom);
        callback(jqdom);
        return jqdom;
    }

    function count(jqdom) {
        return jqdom.data(namespace).count;
    }

    function upvoted(jqdom) {
        return jqdom.data(namespace).upvoted;
    }

    function downvoted(jqdom) {
        return jqdom.data(namespace).downvoted;
    }

    function starred(jqdom) {
        return jqdom.data(namespace).starred;
    }

    var methods = {
        init: init,
        count: count,
        upvote: upvote,
        upvoted: upvoted,
        downvote: downvote,
        downvoted: downvoted,
        starred: starred,
        star: star,
        _click_upvote: _click_upvote,
        _click_downvote: _click_downvote,
        _click_star: _click_star,
        destroy: destroy
    };

    function destroy(jqdom) {
        return jqdom.each(function() {
            $(window).unbind(dot_namespace);
            $(this).removeClass(enabled_css);
            $(this).removeData(namespace);
        });
    }

    $.fn.upvote = function(method) {  
        var args;
        if (methods[method]) {
            args = Array.prototype.slice.call(arguments, 1);
            args.unshift(this);
            return methods[method].apply(this, args);
        }
        if (typeof method === 'object' || ! method) {
            args = Array.prototype.slice.call(arguments);
            args.unshift(this);
            return methods.init.apply(this, args);
        }
        $.error('Method ' + method + ' does not exist on jQuery.upvote');
    };  
})(jQuery);

function IsScrollbarAtBottom() {
    var documentHeight = $(document).height();
    var scrollDifference = $(window).height() + $(window).scrollTop();
    return (documentHeight == scrollDifference);
}

(function($) {

    $.posts = function(element, options) {

        var defaults = {
            params : {},
            itemsStyle: 'b',
            page: 1,
            adEvery: getRandomInt(5,8),
            fadeInDelay: 2000,
            loaderElement: '#loaderCircle'
        }

        var $this = this;

        $this.settings = {}

      var $element = $(element),
          $handler = $('li.brick-item', $element),
          apiURL = site_url + '/ajax.html?c=posts',
          lastRequestTimestamp = 0,
          isLoading = false,
          $window = $(window),
          $document = $(document);


        $this.init = function() {
            $this.settings = $.extend({}, defaults, options);
            $element.addClass('posts-list-' + $this.settings.itemsStyle)
            $window.on('scroll', onScroll);
            $this.loadData();
        }

        $this.loadData = function() {
            isLoading = true;
            $($this.settings.loaderElement).show();
            $.ajax({
                type: "post",
                url: apiURL,
                data: {
                    page: $this.settings.page,
                    style: $this.settings.itemsStyle,
                    adevery: $this.settings.adEvery,
                    params: $this.settings.params
                },
                dataType : 'json',
                success: onLoadData
            })            
        };
        
        $this.RestParams = function(params) {
            $element.empty();
            var NewParams = $.extend(true,{},$this.settings.params, params);
            $.posts($element,
                {
                    params:NewParams,
                    itemsStyle:$this.settings.itemsStyle
                }
            );
        }

        var onScroll = function(event) {
            if (!isLoading) {
              var closeToBottom = ($window.scrollTop() + $window.height() > $document.height() - 100);
              if (closeToBottom) {
                var currentTime = new Date().getTime();
                if (lastRequestTimestamp < currentTime - 1000) {
                  lastRequestTimestamp = currentTime;
                  $this.loadData();
                }
              }
            }
        };
        var applyLayout = function($newposts) {
            if ($handler.wookmarkInstance) {
                $handler.wookmarkInstance.clear();
            }
            
            $element.append($newposts);
            $handler = $('li.brick-item', $element);
            
            $element.imagesLoaded( function() {
                if($this.settings.itemsStyle == 'a'){
                    $window.resize(function() {
                      var windowWidth = $window.width(),
                          newOptions = { flexibleWidth: '50%' };
                    
                      if (windowWidth < 1024) {
                        newOptions.flexibleWidth = '100%';
                      }
                    
                      $handler.wookmark(newOptions);
                    }); 
                             
                    $handler.wookmark({
                        align: is_rtl == true && 'right',
                        direction: is_rtl == true && 'right',
                        autoResize: true,
                        container: $element,
                        offset: 8,
                        itemWidth: 210,
                        flexibleWidth: '50%'  
                    });                    
                }
                $newposts.each(function() {
                var $self = $(this);
                window.setTimeout(function() {
                  $self.css('opacity', 1);
                }, Math.random() * $this.settings.fadeInDelay);
                });  
            });            

        };
        
        var onLoadData = function(response) {
            isLoading = false;
            $($this.settings.loaderElement).hide();
            $this.settings.page++;
            $newposts = $(response.data);
            if (response.message == 'no_data') {
              $document.off('scroll', onScroll);
            }
            applyLayout($newposts);
        };

        $this.init();

    }

    $.fn.posts = function(options) {
        return this.each(function() {
            if (undefined == $(this).data('posts')) {
                var $this = new $.posts(this, options);
                $(this).data('posts', $this);
            }
        });
    }

})(jQuery);

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function sprintf(s) {
	var bits = s.split('%');
	var out = bits[0];
	var re = /^([ds])(.*)$/;
	for (var i=1; i<bits.length; i++) {
		p = re.exec(bits[i]);
		if (!p || arguments[i]==null) continue;
		if (p[1] == 'd') {
			out += parseInt(arguments[i], 10);
		} else if (p[1] == 's') {
			out += arguments[i];
		}
		out += p[2];
	}
	return out;
}




popup = function(url, params) {
    var k, popup, qs, v;
    if (params == null) {
      params = {};
    }
    popup = {
      width: 500,
      height: 350
    };
    popup.top = (screen.height / 2) - (popup.height / 2);
    popup.left = (screen.width / 2) - (popup.width / 2);
    qs = ((function() {
      var _results;
      _results = [];
      for (k in params) {
        v = params[k];
        _results.push("" + k + "=" + (this.encodeURIComponent(v)));
      }
      return _results;
    }).call(this)).join('&');
    if (qs) {
      qs = "?" + qs;
    }
    return window.open(url + qs, 'targetWindow', "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,left=" + popup.left + ",top=" + popup.top + ",width=" + popup.width + ",height=" + popup.height);
};

permalink_ajax = function(){
    $('input[id*="title_"]').blur(function(){
        input = $(this);            
        input_arr = input.attr('id').split('_');
        post_id = input_arr[1];
        
        $.post(site_url + '/ajax.html?c=get_post_permalink',{ 'post_id' : post_id , 'name' : input.val() },
            function(json){
                console.log(json);
                if(json){
                    $('input#name_' + post_id).val(json.post_name);                        
                }
            }
        ,'json');
    });        
}



InitAds = function() {
    Ads = [];
    $.each($('.ad-code'), function( index, value ) {
        if($(value).hasClass('no-js')){
            Ads.push($(value).data('id'));
            $(value).removeClass('no-js');            
        }
    });  
    if(Ads.length){
        $.post(site_url + '/ajax.html?c=adviews',{'ads' : Ads}); 
    }
    $(document).on('click','.ad-code',function(){
        $.post(site_url + '/ajax.html?c=adclicks',{'id' : $(this).data('id')}); 
    });    
}

highlighter_str = function(e){
    return '<span class="highlight">' + e + '</span>';
}

upvote_callback = function(data){
    $.post(
        site_url + '/ajax.html?c=vote',
        { id: data.id, up: data.upvoted, down: data.downvoted, star: data.starred }
    ).done(function(response) {
        if(response != ''){
            alert(response);      
        }
    });
}

all_init = function(){
    
    setTimeout(function() {
        $("time.timeago").timeago();
        $("[rel=tooltip]").tooltip();
    }, 100);    
    
    $('.fb_share').on('click',function(){
        popup('https://www.facebook.com/sharer/sharer.php', {
            u: $(this).data('url')
        });
        return false;    
    });
    $('.tw_share').on('click',function(){
        popup('https://twitter.com/intent/tweet', {
          text: $(this).data('title'),
          url: $(this).data('url')
        });   
        return false; 
    });
    $('.gplus_share').on('click',function(){
        popup('https://plus.google.com/share', {
          url: $(this).data('url')
        }); 
        return false;   
    }); 
    $('.digg_share').on('click',function(){
        popup('https://digg.com/submit', {
          url: $(this).data('url'),
          title: $(this).data('title')
        }); 
        return false;   
    }); 
    $('.linkedin_share').on('click',function(){
        popup('http://www.linkedin.com/shareArticle', {
          url: $(this).data('url'),
          title: $(this).data('title'),
          mini: true
        }); 
        return false;   
    }); 
    $('.pint_share').on('click',function(){
        popup('https://www.pinterest.com/pin/create/button/', {
          url: $(this).data('url'),
          media: $(this).data('media'),
          description: $(this).data('title')
        }); 
        return false;   
    }); 
    $('.post-item.no-js').each(function() {
        $(this).removeClass('no-js');
        $(this).find('.vote').upvote({callback: upvote_callback});
    });
};

$(document).ready(function () {
    NProgress.start();
    all_init();
    InitAds();
    $(document).on("click", ".filters > li a", function(e) {
        e.preventDefault();
    	$(this).parent().parent().children("li.active").removeClass("active"),
        $(this).parent().addClass("active");
    	
        var f = $("li.active.filter a",'.filters').attr("id"),
    		s = $("li.active.sort a",'.filters').attr("id"),
    		a = $("li.active.sort a").text();
        $(".sort-text").text(a);
        
        $('ul#bricks-container').data('posts').RestParams({'get':{'filter':f},'sort':s});

    });

    $(window).scroll(function() {
    	if ($(this).scrollTop() > 50) {
    		$('#back-to-top').fadeIn();
    	} else {
    		$('#back-to-top').fadeOut();
    	}
    });    
    
    $('#back-to-top').on('click',function () {
        $('body,html').animate({ scrollTop: 0 }, 800);
        return false;
    });    

    $('ul.deep-active li.active').parents('li').addClass('active');
    $('div[itemprop="articleBody"] img:not(.img-responsive)').addClass('img-responsive');
});
$(window).load(function () {
    NProgress.done();
    if ($(window).width() >= 992){
        $('#sidebar.fixedbar').affix({
              offset: {
                top: 100
              }
        }).on('affixed.bs.affix',function(){
            var parent = $(this).parent();
            $(this).css({width: parent.width()});
        });        
    }    
});
$(document).ajaxComplete(function() {
    all_init();
    InitAds();
});
