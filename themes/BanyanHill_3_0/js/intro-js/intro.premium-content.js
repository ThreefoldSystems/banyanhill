var initTour = function() {
	var visitCount;
	var d = new Date();
		d.setTime(d.getTime() + (365*24*60*60*1000));

	if (!document.cookie.split(';').filter(function(item) {
		return item.indexOf('is_prem_first_time_user') >= 0;
	}).length || getParameterByName('takeTour') !== null) {					
		visitCount = 0;
		document.cookie = 'is_prem_first_time_user=0; Path=/; Expires=' + d.toUTCString();
	} else {
		document.cookie.split(';').filter(function(item) {
			if (item.indexOf('is_prem_first_time_user') >= 0) {
				visitCount = parseInt(item.split('=')[1]);
				visitCount++;

				document.cookie = 'is_prem_first_time_user=' + visitCount + '; Path=/; Expires=' + d.toUTCString();
			}
		});	

		if (visitCount > 1) return;
	}				

	if ( typeof introJs === 'function' ) {
		var intro = introJs();
		var lastChance;
		var animationInt;
		var animationCount = 0;					

		if (visitCount === 0) {
			lastChance = '';
		} else {
			lastChance = '<p class="ijs-footnote">NOTE: You will not see this message again.</p>';
		}

		var introArgs = {
			showStepNumbers: false,
			disableInteraction: true,
			skipLabel: 'Exit',
			showBullets: false,
			showProgress: true,
			scrollToElement: true,
			scrollTo: 'element',
			steps: [
			  { 
				element: document.querySelector('#et-boc'),
				intro: "<h3>Welcome to your Premium Content!</h3><p>This page gives you quick access to all of your <span class='orange'>Banyan Hill</span> subscriptions. Check portfolios, trade alerts and modify your subscriptions here. Click <span class='orange'>Next</span> for more details.</p>" + lastChance,
				position: 'top-left-aligned',
				tooltipClass: 'introjs-extra-wide',
				scrollTo: 'tooltip'
			  },
			  {
				element: document.querySelector('#blueSectionHeader'),
				intro: "<p>Your <span class='orange'>Account Summary</span> is located here. You can view account details and access <span class='orange'>Account Option</span> quick links as well.</p><p class='ijs-footnote'>TIP: The <span class='orange'>Renew Subscriptions</span> link provides a quick summary of your service expiration dates.</p>",
				position: 'top-right-aligned',
				tooltipClass: 'introjs-wide'	
			  },
			  {
				element: document.querySelector('.row.sub-access'),
				intro: "<p>Your active subscriptions are displayed here. Click the <span class='orange'>Expert</span> or the <span class='orange'>Logo</span> to learn more about each.</p><p class='ijs-footnote'>TIP: Each <span class='orange'>paid service</span> has a quick menu located on the right side of the page.</p>",
				position: 'top',
				scrollTo: 'tooltip'
			  },						
			  {
				element: document.querySelector('.sub-access .currentSubScript'),
				intro: "<p>Each service's <span class='orange'>Model Portfolio</span> and <span class='orange'>Trade Alerts</span> will display in a modal window for your convienience.</p><p class='ijs-footnote'>TIP: You can click <span class='orange'>view page</span> on the modal window to get a more detailed view.</p>",
				position: 'right'
			  },
			  {
				element: document.querySelector('.sub-access .currentSubScript'),
				intro: "<p>You can also <span class='orange'>View</span> or <span class='orange'>Hide</span> other menu options.</p><p>These options will open a new browser tab.</p><p class='ijs-footnote'>TIP: When browsing these options, this page will stay open so you can quickly return.</p>",
				position: 'right'
			  },												
			  {
				element: document.querySelector('.my-account'),
				intro: "<p>Access your <span class='orange'>account details</span> here.</p><p class='ijs-footnote'>Change your account details &ndash; username, password, email address.</p><p class='ijs-footnote'>Manage your subscriptions &ndash; change status, renewal date.</p>",
				position: 'bottom',
				disableInteraction: false,
				tooltipClass: 'introjs-wide'
			  },							
			  {
				element: document.querySelector('.login-logout'),
				intro: "<p>Here you can <span class='orange'>log out</span> of your Banyan Hill account.</p>",
				position: 'bottom',
				disableInteraction: false
			  },
			  {
				element: document.querySelector('.contact-menu-item'),
				intro: "<p><span class='orange'>Contact Us</span> for any other questions; we're always a phone call or email away!</p>",
				position: 'bottom'					  
			  },							
			]
		};
		
		if (typeof newAlertNotify !== 'undefined' || typeof newTranscriptNotify !== 'undefined') {
			var alertText = '';
			var alertNote = "<span class='orange'>Trade Alerts</span> listed here require action on your part &ndash; typically buying/selling of stocks or options.";
			
			if (typeof newAlertNotify !== 'undefined' && typeof newTranscriptNotify !== 'undefined') {
				alertText = "Trade Alerts</span> and <span class='orange'>Transcripts";
			} else if (typeof newAlertNotify !== 'undefined') {
				alertText = "Trade Alerts";
			} else {
				alertText = "Transcripts";
				alertNote = "You do not have any <span class='orange'>Trade Alerts</span>, future " + alertNote;
			}
			
			introArgs.steps.splice(1, 0,
				{
					element: document.querySelector('#alertPlaceholder'),
					intro: "<p><span class='orange'>Great!</span> It looks like new <span class='orange'>" + alertText + "</span> have been posted since your last visit.</p><p>We've made it easier to quickly view all of your <span class='orange'>Transcripts</span> and <span class='orange'> Trade Alerts</span> in one location. " + alertNote + "</p><p class='ijs-footnote'>This notification will only appear when items have been posted since your last login.</p><p class='ijs-footnote'>You can get a detailed list of all the latest posts under each of your subscribed services below.</p>",
					position: 'top-left-aligned',
					tooltipClass: 'introjs-extra-wide'							  
				},
			);
		}
		
		if (document.querySelector('.row.no-access')) {
			introArgs.steps.splice((typeof newAlertNotify !== 'undefined' || typeof newTranscriptNotify !== 'undefined' ? 4 : 5), 0,
				{
					element: document.querySelector('.row.no-access'),
					intro: "<p>Learn More about our <span class='orange'>Premium Research Services</span> here.</p>",
					position: 'top',
					scrollTo: 'tooltip'
				},
			);
		}		

		intro.setOptions(introArgs);

		intro.onbeforechange(function() {
			jQuery('#page-container').removeClass('et-fixed-header');
		}).onafterchange(function(targetElement) {						
			if (this._currentStep === 0) {
				jQuery('html').addClass('introjs-active').bind('mousewheel', function() {
					return false;
				});						
			}

			if (this._currentStep === (typeof newAlertNotify !== 'undefined' || typeof newTranscriptNotify !== 'undefined' ? 5 : 4) ) {
				if (jQuery('.subscription_block.currentSubScript').find('.view-more').length === 0) {
					jQuery('.view-more-btn')[0].click();	
				}

				var animate = function() {
					if (jQuery('.introjs-helperLayer').outerHeight() === jQuery('.currentSubScript').outerHeight() || animationCount > 30) {
						console.log('clear');
						clearInterval(animationInt);
					} else {
						console.log('animate');
						jQuery('.introjs-helperLayer, .introjs-disableInteraction').height(jQuery('.currentSubScript').outerHeight() + 10);
						animationCount++;
						// try to animate for 300ms
					}
				};

				animationInt = setInterval(animate, 15);

				jQuery('.currentSubScript .view-more a[target="_blank"] > div').addClass('highlight');
			} else {
				clearInterval(animationInt);
				jQuery('.currentSubScript .view-more a[target="_blank"] > div').removeClass('highlight');
			}

			setTimeout(function() {
				jQuery('#page-container').removeClass('et-fixed-header');
			}, 500);
		}).oncomplete(function() {
			jQuery('html').removeClass('introjs-active').unbind('mousewheel');
			jQuery('.tour').removeClass('tour');

			Cookies.remove('is_prem_first_time_user');
			Cookies.set('is_prem_first_time_user', '2', { expires: 365 });

			clearInterval(animationInt);
		}).onexit(function() {
			jQuery('html').removeClass('introjs-active').unbind('mousewheel');
			jQuery('.tour').removeClass('tour');

			clearInterval(animationInt);
		}).start();	
	}

	jQuery("html, body").animate({ scrollTop: 0 }, 400);
};