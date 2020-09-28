var initTour = function() {
	var visitCount;
	var d = new Date();
		d.setTime(d.getTime() + (365*24*60*60*1000));

	if (!document.cookie.split(';').filter(function(item) {
		return item.indexOf('is_tour_first_time_user') >= 0;
	}).length || getParameterByName('takeTour') !== null) {
		visitCount = 0;
		document.cookie = 'is_tour_first_time_user=0; Path=/; Expires=' + d.toUTCString();
	} else {
		document.cookie.split(';').filter(function(item) {
			if (item.indexOf('is_tour_first_time_user') >= 0) {
				visitCount = parseInt(item.split('=')[1]);
				visitCount++;

				document.cookie = 'is_tour_first_time_user=' + visitCount + '; Path=/; Expires=' + d.toUTCString();
			}
		});

		if (visitCount > 1) return;
	}

	if ( typeof introJs === 'function' ) {
		var intro = introJs();
		var welcomeText, lastChance;

		if (visitCount === 0) {
			welcomeText = 'Welcome!';
			lastChance = '';
		} else {
			welcomeText = 'Welcome Back!';
			lastChance = '<p class="ijs-footnote">NOTE: You will not see this message again.</p>';
		}

		var introArgs = {
			showStepNumbers: false,
			disableInteraction: true,
			skipLabel: 'Exit',
			showBullets: false,
			showProgress: true,
			steps: [
			  {
				intro: "<h3>" + welcomeText + "</h3><p style='text-align: center;'>Click <span class='orange'>Next</span> to start the tour and familiarize yourself with the new Banyan Hill website!</p><div class='welcome-image'><img src='https://cdn.banyanhill.com/wp-content/uploads/2020/07/15132523/experts-welcome-walkthrough_new.jpg' /></div><br /><p class='ijs-footnote'>End the tour at any time by clicking <span class='orange'>Exit</span>. Click <span class='orange'>Take Tour</span> in the website footer to replay.</p>" + lastChance,
				tooltipClass: 'introjs-extra-wide',
				position: 'auto'
			  },
			  {
				element: document.querySelector('.login-logout'),
				intro: "<p>Here you can <span class='orange'>log " + (isUserLoggedIn === true ? "out</span> of" : "in</span> to") + " your Banyan Hill account.</p>",
				position: 'bottom',
				disableInteraction: false
			  },
			  {
				element: document.querySelector('#et-menu'),
				intro: "<p><span class='orange'>Categories</span> cover an extensive range of valuable investing topics.</p><p class='ijs-footnote'>Hover over the highlighted items below to learn more.</p>",
				position: 'top',
				disableInteraction: false,
				tooltipClass: 'introjs-wide'
			  },
			  {
				element: document.querySelector('#menu-item-348093'),
				intro: "<p>Make sure to explore the <span class='orange'>sub-categories</span> as well!</p>",
				position: 'top',
				disableInteraction: false
			  },
			  {
				element: document.querySelector('.premium-menu-item'),
				intro: "<p><span class='orange'>Premium Content</span> provides access to your paid subscriptions.</p><p class='ijs-footnote'>Your latest <strong>Trade Alerts</strong> and <strong>Portfolio Summaries</strong> can be found here as well as FAQs and information to help you get started.</p>",
				position: 'bottom',
				tooltipClass: 'introjs-wide'
			  },
			  {
				element: document.querySelector('.contact-menu-item'),
				intro: "<p><span class='orange'>Contact Us</span> for any other questions; we're always a phone call or email away!</p>",
				position: 'bottom'
			  },
			  {
				element: document.querySelector('.featured-posts-slider-module'),
				intro: "<p>Today's most <span class='orange'>Recent Articles</span> are displayed here.</p><p class='ijs-footnote'>TIP: Navigate the slideshow using the arrows on either side of the legend here.</p>",
				position: 'right',
				disableInteraction: false
			  },
			  {
				element: document.querySelector('#et-info'),
				intro: "<p>Join the Banyan Hill community on <span class='orange'>social media</span>. Be sure to follow us for <span class='orange'>daily market insights</span>, <span class='orange'>event announcements</span> and <span class='orange'>special offers</span>.</p>",
				position: 'right',
				tooltipClass: 'introjs-wide'
			  },
			  {
				element: document.querySelector('.et_pb_extra_column_sidebar .Newsletter_new'),
				intro: "<p>Thank you for taking our tour and welcome to the new Banyan Hill website!</p><p class='ijs-footnote'><span class='orange'>P.S.</span> Don't forget to sign up for our daily insights and tips, delivered straight to your inbox.</p>",
				position: 'left',
				disableInteraction: false
			  }
			]
		};

		if (isUserLoggedIn === true) {
			introArgs.steps.splice(2, 0,
				{
				element: document.querySelector('.my-account'),
				intro: "<p>Access your <span class='orange'>account details</span> here.</p><p class='ijs-footnote'>Change your account details &ndash; username, password, email address.</p><p class='ijs-footnote'>Manage your subscriptions &ndash; change status, renewal date.</p>",
				position: 'bottom',
				disableInteraction: false,
				tooltipClass: 'introjs-wide'
			  	}
			);
		}

		intro.setOptions(introArgs);

		intro.onbeforechange(function(){
			jQuery('#page-container').removeClass('et-fixed-header');
		}).onafterchange(function(targetElement) {
			if (this._currentStep === 0) {
				jQuery('html').addClass('introjs-active').bind('mousewheel', function() {
					return false;
				});
			}

			if (this._currentStep === (isUserLoggedIn === true ? 4 : 3)) {
				jQuery(targetElement).parents('li').addClass('tour');
			} else {
				jQuery('.tour').toggleClass('tour');
			}

			setTimeout(function() {
				jQuery('#page-container').removeClass('et-fixed-header');
			}, 500);
		}).oncomplete(function() {
			jQuery('html').removeClass('introjs-active').unbind('mousewheel');
			jQuery('.tour').removeClass('tour');

			Cookies.remove('is_tour_first_time_user');
			Cookies.set('is_tour_first_time_user', '2', { expires: 365 });
		}).onexit(function() {
			jQuery('html').removeClass('introjs-active').unbind('mousewheel');
			jQuery('.tour').removeClass('tour');
		}).start();

		jQuery("html, body").animate({ scrollTop: 0 }, 400);
	}
};
