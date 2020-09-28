jQuery(document).ready(function($){
	$('body').addClass('min_disqus');
	
	var trigger = $('.related-posts');
	var page = 1;
	var queryIds;
	var queryIdNum = 0;
	var queryObj;
	var lastScrollTop = 0;
	var loading = false;
	var loadMoreLimit = 5;
	var loadMoreCurrent = 0;
	var scrollHandling = {
			allow: true,
			reallow: function() {
				scrollHandling.allow = true;
			},
			delay: 400 //(milliseconds) adjust to the highest acceptable value
		};
	
	$.fn.isInViewport = function() {
		var elementTop = $(this).offset().top;
		var elementBottom = elementTop + $(this).outerHeight();

		var viewportTop = $(window).scrollTop() + parseFloat($('#et-navigation').css('height'));
		var viewportBottom = viewportTop + $(window).height();

		return elementBottom > viewportTop && elementTop < viewportBottom;
	};
	
	jQuery(function ($) {	
		var checkBHJSCount = 0;
		var checkBHJSLimit = 10;			
		var checkBHJSTimer = setInterval(function() {
			if (typeof getParameterByName === 'function') {
				if (getParameterByName('post_ids')) {
					queryIds = getParameterByName('post_ids');
				} else if ($('article:first-of-type').data('scroll-posts')) {
					queryIds = $('article:first-of-type').data('scroll-posts');
				} else {
					queryIds = '';
				}
				//split string & clean resulting array of any empty values
				queryIds = queryIds.toString().split(',').filter(function(e){return e});

				clearInterval(checkBHJSTimer);
			} else if (checkBHJSCount <= checkBHJSLimit) {
				checkBHJSCount++;
			} else {
				// is not mobile
				clearInterval(checkBHJSTimer);
			}
		}, 100); // check every 100ms for 1 second
	});	
	
	var updateHistoryState = function(article) {
		var title = article.attr('data-title');
		var url = article.attr('data-url');
			history.pushState(null, title, '/' + url + '/' + location.search);
			document.title = title;			
	};
	
	var resetDisqus = function(article) {
		if (typeof DISQUS === 'undefined') return;
		
		$('#disqus_thread').removeAttr('id').attr('class', 'disqus_thread_holder').siblings('#disqus_display').remove();
		article.parent().find('.disqus_thread_holder').attr('id', 'disqus_thread').removeAttr('style');
		
		DISQUS.reset({
			reload: true,
			config: function () {  
			this.page.identifier = parseInt(article.attr('id').replace('post-',''));  
			this.page.url = 'https://banyanhill.com/' + article.attr('data-url') + '/';
				
			//intentionally overwrite any associated callbacks
			this.callbacks.onReady = [function () {
				showHideDisqus();
			}];
			}
		});
	};
	
	var showHideDisqus = function() {
		$('#disqus_thread').after('<div id="disqus_display"><span>SHOW COMMENTS</span></div>');
		$('#disqus_display').on('click', function() {
			if (jQuery(this).hasClass('active')) {
				jQuery('#disqus_thread').animate({
					height: 150
				}, 0, function() {
					height: $('#disqus_thread').get(0).scrollHeight;

					jQuery('#disqus_display span').fadeOut(function() {
						$(this).text('SHOW COMMENTS').fadeIn();
					}).parent().removeClass('active');
					$('#disqus_thread').removeClass('active');

					$([document.documentElement, document.body]).animate({
						scrollTop: $('#disqus_thread').offset().top - parseInt(jQuery('#main-header').attr('data-fixed-height'))
					}, 500);				
				});
			} else {
				jQuery('#disqus_thread').animate({
					height: $('#disqus_thread').get(0).scrollHeight
				}, 0, function() {
					jQuery('#disqus_display span').fadeOut(function() {
						$(this).text('HIDE COMMENTS').fadeIn();
					}).parent().addClass('active');
					$('#disqus_thread').addClass('active');
				});
			}
		});			
	};
	
	var sendPageView = function(article) {		
		dataLayer.push({
			'event': 'lazyLoadEvent',
			'lazyLoadLabel': document.title.split(' | ')[0], 
			'lazyLoadCategory': 'PageView', 
			'lazyLoadAction': 'Lazy Load Post',
		});		
	};
	
	var loadNewFixedAd = function() {
		var aid, bid;
		
		if ($('#text-5.fixed').length === 0) {
			return;
		} else {
			document.cookie = 'wppas_user_stats=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			document.cookie = 'wppas_pvbl=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			jQuery('#text-5.fixed').find('[class^=pasli]').not('.bx-clone').each(function() {
				bid = $(this).attr('bid');
				aid = $(this).attr('aid');

				var currentAd = $(this);
				var adData = {
					action : 'rotation_load_banner',
					id: bid,
					aid: aid 
				};

				$.post(beloadmore.url, adData)
					.done(function(data) {
					currentAd.children().first().replaceWith(data);			
				}).fail(function(xhr, textStatus, e) {
					console.log(xhr.responseText);
				});					
			});
		}
	};
	
	var insertArticleAd = function(targetElem) {
		targetElem.find('.entry-content > p:first-of-type')[0].parentNode.insertBefore(document.querySelector('#content-area .post-content.entry-content iframe'), targetElem.find('.entry-content *:nth-child(6)')[0]);
	};

	$(window).scroll(function(){
        var windowScrollTop = $(this).scrollTop();
        if (windowScrollTop > lastScrollTop) {
            var currentDiv = $('[id^=lazyload-][data-attop="false"]:first');
            if (currentDiv && currentDiv.length > 0) {
                if (windowScrollTop >= currentDiv.offset().top) {
                    var article = currentDiv.find('article');
					
					currentDiv.attr('data-attop', true);
                    updateHistoryState(article);
					sendPageView(article);
					resetDisqus(article);
					//insertArticleAd(currentDiv);
                    //loadNewFixedAd();
                }
            }
        } else {
            var currentTopDivs = $('[id^=lazyload-][data-attop="true"]');
            if (currentTopDivs && currentTopDivs.length > 0) {
                $.each(currentTopDivs,function(i,elem) {
                	if (windowScrollTop <= $(elem).offset().top) {					
                		$(elem).attr('data-attop', false);
						
                		if ($(elem).prev('[id^=lazyload]').length > 0) {
							var article = $(elem).prev('[id^=lazyload]').find('article');
							
                			updateHistoryState(article);
							sendPageView(article);
							resetDisqus(article);
							//insertArticleAd($(elem).prev('[id^=lazyload]'));
                			//loadNewFixedAd();
                		} else {
							var article = $('.et_pb_extra_column_main > article');
							
                			updateHistoryState(article);
							sendPageView(article);
							resetDisqus(article);
                			//loadNewFixedAd();
                			//insertArticleAd(article);
                		}
                	}					
                });
            }
        }
        lastScrollTop = windowScrollTop;	
		
		if (!trigger.isInViewport()) return;
		
		if(!loading && scrollHandling.allow) {			
			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);
			var offset = $(trigger).offset().top - $(window).scrollTop();

			if( 1000 > offset ) {
				var loadMorePosts = function() {
					if (loadMoreCurrent < loadMoreLimit) {
						loading = true;

						if (queryIds && queryIdNum < queryIds.length) {
							queryObj = {'p' : parseInt(queryIds[queryIdNum])};
						} else {
							if (queryIds && beloadmore.query.post__not_in.length === 1) {
								for (var i = 0; i < queryIds.length; i++) {
									beloadmore.query.post__not_in.push(parseInt(queryIds[i]));
								}	
							}

							queryObj = beloadmore.query;
						}

						var data = {
							action: 'be_ajax_load_more',
							nonce: beloadmore.nonce,
							page: page,
							query: queryObj,
							qs_source: location.search
						};

						if($('.et_pb_extra_column_main .load_more_placeholder').length === 0) {
							$('.et_pb_extra_column_main').append('<div class="load_more_placeholder"><img src="/wp-content/themes/Extra/images/pagination-loading.gif" alt="Loading Next Article" /> Loading Next Article</div>');
						}

						$.post(beloadmore.url, data, function(res) {
							loading = false;
							loadMoreCurrent ++;

							if (queryIds && queryIdNum < queryIds.length) {
								queryIdNum ++;
							} else {
								page ++;
							}							
							if(res.success && res.data.content !== '') {
								$('.et_pb_extra_column_main .load_more_placeholder').replaceWith( res.data.content );
								trigger = $('#lazyload-' + res.data.post_ID + ' .related-posts');
								
								lazyLoadImages();
								//if (typeof reviveAsync === 'object') reviveAsync['623abf93e179094d5059d128355ac65e'].refresh();
							} else {
								console.log('error loading ID:' + res.data.loop.query.p);
								loadMorePosts();
							}
						}).fail(function(xhr, textStatus, e) {
							console.log('fail:' + xhr.responseText);
						});					
					} else {
						$('.et_pb_extra_column_main .load_more_placeholder').hide();
					}					
				};
				loadMorePosts();
			}
		}
	});
	
	showHideDisqus();
});