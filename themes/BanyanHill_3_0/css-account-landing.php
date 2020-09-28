<!--
* Customer Self Service Plugin

* Template: css-account-landing

* @param

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->
<div class="tfs_css_account_profile">
	<div id="profileMain">
		<p class="tfs_css_profile_image"><?php 
			$user = wp_get_current_user();
			$display_avatar = get_user_meta( $user->ID , 'display_avatar', true );
			$img_args = array();
			$img_args['force_default'] = ($display_avatar === 'false' ? true : false);
			
			//echo '<span class="corner-ribbon top-right orange">Coming Soon!</span>';
			
			echo get_avatar( $user->ID, 150, 'mm', '', $img_args );
			?></p>
		<div class="tfs_css_toggle_avatar">
			<?php echo do_shortcode( '[bh_display_avatar]' ); ?>
		</div>
			<div class="tfs_css_toggle_label">Display my <a href="https://en.gravatar.com/" target="_blank">Gravatar account</a> image on this site</div>
			<style>
				
				/* The switch - the box around the slider */
				.switch {
				  position: relative;
				  display: inline-block;
				  width: 40px;
				  height: 24px;
				}

				/* Hide default HTML checkbox */
				.switch input {
				  opacity: 0;
				  width: 0;
				  height: 0;
				}

				/* The slider */
				.slider {
				  position: absolute;
				  cursor: pointer;
				  top: 0;
				  left: 0;
				  right: 0;
				  bottom: 0;
				  background-color: #ccc;
				  -webkit-transition: .4s;
				  transition: .4s;
				}

				.slider:before {
				  position: absolute;
				  content: "";
				  height: 16px;
				  width: 16px;
				  left: 4px;
				  bottom: 4px;
				  background-color: white;
				  -webkit-transition: .4s;
				  transition: .4s;
				}

				input:checked + .slider {
				  background-color: #f60;
				}

				input:focus + .slider {
				  box-shadow: 0 0 1px #f60;
				}

				input:checked + .slider:before {
				  -webkit-transform: translateX(16px);
				  -ms-transform: translateX(16px);
				  transform: translateX(16px);
				}

				/* Rounded sliders */
				.slider.round {
				  border-radius: 34px;
				}

				.slider.round:before {
				  border-radius: 50%;
				}	
				.tfs_css_toggle_avatar {
					flex: 0 0 60px;
					display: flex;
				}

				.tfs_css_toggle_label, .tfs_css_toggle_note {
					flex: 1;
					font-size: 11px;
					text-transform: uppercase;
					line-height: 13px;
					margin: 10px 0;
				}

				#tfs_css_content .tfs_css_account_profile p.tfs_css_profile_image {
					margin: 0 auto;
					flex: 0 0 150px;
				}

				.tfs_css_toggle_note {
					flex: 1 0 100%;
					margin: 10px 0;
				}

				#tfs_css_body .tfs_css_toggle_label a {
					text-decoration: underline !important;
					color: #f60;
				}
			</style>
		<!--p class="tfs_css_profile_image_edit">Edit Image</p-->
		<p class="tfs_css_profile_logged_in">Last Logged In: <span><?php echo get_last_login( array('format' => 'M. j, Y') ); ?></span></p>
		<h4>Login Details</h4>
		<div class="tfs_css_profile_details_container">
			<p class="tfs_css_profile_logged_in_count"><span><?php echo do_shortcode( '[bh_login_count]' ); ?> times since</span> <span><?php echo do_shortcode( '[bh_reg_date format="M. j, Y"]' ); ?></span></p>
			<p class="tfs_css_profile_logged_in_streak">Current Streak: <span><?php echo do_shortcode( '[bh_login_streak]' ); ?></p>
			<p class="tfs_css_profile_logged_in_streak">Best Streak: <span><?php echo do_shortcode( '[bh_login_streak_max]' ); ?></p>
		</div>
		<p class="tfs_css_profile_text"><a href="#" data-featherlight="#tfs_login_tips_container" >What is this?</a></p>
		<div style="display: none">
			<div id="tfs_login_tips_container">
				<h2>Login Details</h2>
				<p>In an effort to reward our most loyal users, Banyan Hill now has a rewards system where users can redeem points for swag!</p>
				<ul>
					<li><p>Log in at least once every 24 hours to increase your login streak. <span class="tfs_css_profile_login_track"><strong>Next points opportunity:</strong> <?php 
						$last_login = new DateTime( get_last_login( array('format' => 'M. j, Y h:i:s A') ) );
						$datetime = $last_login->modify('+1 day');
						echo $datetime->format('M. j, Y h:i:s A'); 
						?></span></p></li>
				</ul>				
				<p>We will begin offering more points opportunities in the future, so stay tuned for more details!</p>
			</div>
		</div>
	</div>
	<!--div id="profileTrackers">
		<div id="profileCompletion" class="tfs_css_profile_stats_box">
			<h2>Profile Completion</h2>
			<span class="tfs_css_profile_total">COMING SOON</span>
		</div>
		<div id="profilePointsTotal" class="tfs_css_profile_stats_box">
			<h2>Rewards Points</h2>
			<span class="tfs_css_profile_total">COMING SOON</span>
		</div>	
		<div id="profileREferralStatus" class="tfs_css_profile_stats_box">
			<h2>Referrals Status</h2>
			<span class="tfs_css_profile_total">COMING SOON</span>
		</div>		
	</div-->
	<div>
		<a href='https://chat.tccmaryland.com/bhill/chatbeacon/content/windows/chat.html?accountid=1&siteid=35&queueid=76&theme=slim&popout=true' onclick="window.open(this.href, 'cbchat','left=20,top=20,width=315,height=570,resizable=0'); return false;">
			<img src='https://chat.tccmaryland.com/bhill/chatbeacon/api/v1/account/1/visitor/00000000-0000-0000-0000-000000000000/site/35/session/00000000-0000-0000-0000-000000000000/img/type/1/76'/>
		</a>
	</div>
	<div class="text-center">
		<button class="tfs_css_button init_profile_tour" href="javascript:void(0);" onclick="initTour()">Start Tour</button>
	</div>
</div>
<div class="tfs_css_content_area">		
	<h2>User Information</h2>
	<?php tfs_css()->template_manager->process_template( 'css-change-address' ); ?>
    <!--div class="tfs_css_account_landing">
        <?php if (!empty($content['account-landing'])) {
            echo html_entity_decode( $content['account-landing'] );
        } else {
        ?>
            <h2>Welcome to your dashboard!</h2>

            <p>Here you can update your home address, change your password or renew subscriptions.</p>

            <p>Have a look and see that your information correct. Everything in your dashboard can be updated online, anytime.</p>

            <p>And as always, if you have any questions or need help, please give us a call.</p>
        <?php } ?>
    </div-->
</div>
<script type="text/javascript">
jQuery(function ($) {
	if (!isMobile.matches && typeof introJs !== 'function') {
		jQuery.getScript('https://cdn.banyanhill.com/wp-content/themes/BanyanHill_3_0/js/intro-js/intro.min.js', function() {
			jQuery.getScript('https://s3.amazonaws.com/cdn.banyanhill.com/wp-content/themes/BanyanHill_3_0/js/intro-js/intro.profile.js', function() {
				jQuery( window ).on( "load", function() {
					initTour();
				});
			});
		});
		jQuery('head').append( jQuery('<link rel="stylesheet" type="text/css" />').attr('href', 'https://s3.amazonaws.com/cdn.banyanhill.com/wp-content/themes/BanyanHill_3_0/js/intro-js/introjs.min.css') );
		
		jQuery(document).on('click', '.init_profile_tour', function() {
			Cookies.remove('is_tour_profile_first_time_user');
			
			initTour();
			return false;
		});
	}
});
</script>