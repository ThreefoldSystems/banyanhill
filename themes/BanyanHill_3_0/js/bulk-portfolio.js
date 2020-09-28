// JavaScript Document
  jQuery(function() {
	jQuery(document).on('click', '.tab input', function(){
		jQuery(this).parent().toggleClass('active').siblings().removeClass('active');
		jQuery(this).parents('.bulk-container').find('.portfolio-placeholder').html(
			jQuery(this).siblings().find('.modelPortfolioMobileScroll').clone()
		).find('iframe').attr('src', jQuery(this).siblings().find('.modelPortfolioMobileScroll').find('iframe').data('src'));
		
		if (isMobile.matches) {
			jQuery(this).parents('.model-port-menu').removeClass('active');
			
			jQuery(this).parents('.bulk-portfolio').find('.portfolio-placeholder iframe').on('load', function() {
				jQuery([document.documentElement, document.body]).animate({
					scrollTop: jQuery(this).parents('.bulk-container').find('.portfolio-placeholder').offset().top
				}, 500);				
			});
		}
	});

	jQuery(document).on('click', '.model-port-menu > span, .model-port-menu .tab > span', function(){
		jQuery(this).parent().toggleClass('active').siblings().removeClass('active');
	});

	jQuery(document).on('click', '.wrapper a', function() {
		jQuery(this).parent().removeClass('active');
	});

	jQuery('.bulk-portfolio .wrapper').each(function() {
		jQuery(this).append(jQuery(this).parents('.bulk-container').children('a'));
	});
	
	if (jQuery('link#bulk-portfolio').length === 0) {
		jQuery('head').append( jQuery('<link rel="stylesheet" type="text/css" id="bulk-portfolio" />').attr('href', '/wp-content/themes/BanyanHill_3_0/css/bulk-portfolio.css') );		
	}

  });