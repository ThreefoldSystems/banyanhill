<div id="general_auth_settings">
	<form method="post" action="options.php">
		<?php settings_fields( $config_name . '_group' );?>

		<h3><?php _e('Display/Visual/UX'); ?></h3>

		<table class="form-table widefat">
			<tbody>
				<tr>
					<th scope="row" class="field_title">
						<?php _e('Display Before The Login Box?'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name; ?>[teaser]" value="none" <?php if( ! empty( $config['teaser'] ) && $config['teaser'] == 'none') echo 'checked'; ?>>
								<?php _e('Show no teaser'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name; ?>[teaser]" value="excerpt" <?php if( ! empty( $config['teaser'] ) && $config['teaser'] == 'excerpt') echo 'checked'; ?>>
								<?php _e('Show the Excerpt'); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[teaser]" value="more_tag" <?php if( ! empty( $config['teaser'] ) && $config['teaser'] == 'more_tag') echo 'checked'; ?>>
								<?php _e('Show Content before the More Tag'); ?>
							</label>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Enable Customer Details In JS Format'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[show_user_js]" value="1" <?php if( ! empty( $config['show_user_js'] ) && $config['show_user_js'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[show_user_js]" value="0" <?php if( empty( $config['show_user_js'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enable customer details (name, email, customer number) in JavaScript variables for use on front end.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th class="field_title" scope="row">
						<?php _e('Header Meta Tag With Pubcodes'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[header_meta_tag_pubcodes]" value="1" <?php if( ! empty( $config['header_meta_tag_pubcodes'] ) && $config['header_meta_tag_pubcodes'] == 1) echo 'checked'; ?>>
								<?php _e('On');?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[header_meta_tag_pubcodes]" value="0" <?php if( empty( $config['header_meta_tag_pubcodes'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enable 'mw-pubcodes' meta tag in the header on posts/pages that have pubcodes attached.
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>



		<h3 id="mw_auth_login_options_anchor"><?php _e('Login Options'); ?></h3>

		<table class="form-table widefat">
			<tbody>
				<tr>
					<th scope="row" class="field_title">
						<?php _e('Only Allow Valid User Login'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[valid_user_login]" value="1" <?php if ( ! empty( $config['valid_user_login'] ) && $config['valid_user_login'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[valid_user_login]" value="0" <?php if ( empty( $config['valid_user_login'] )) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Only allow users that have valid subscriptions to log in.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Only Allow One Session At A Time Per User'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[one_login_session_at_a_time_per_user]" value="1" <?php if( ! empty( $config['one_login_session_at_a_time_per_user'] ) && $config['one_login_session_at_a_time_per_user'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[one_login_session_at_a_time_per_user]" value="0" <?php if( empty( $config['one_login_session_at_a_time_per_user'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								This will ensure that the same user is not logged in on multiple devices. It will automatically logout all other inactive sessions when the user is logged in a new session.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Enable Duplicate Email'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[dup_user]" value="1" <?php if( ! empty( $config['dup_user'] ) && $config['dup_user'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[dup_user]" value="0" <?php if( empty( $config['dup_user'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enabling duplicate emails will allow two users who share the same email to login.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Enable Cache Buster'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[cache_buster]" value="1" <?php if( ! empty( $config['cache_buster'] ) && $config['cache_buster'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[cache_buster]" value="0" <?php if( empty( $config['cache_buster'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enabling this will append a var to the url on failed login eg. ?login=failed.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Enable Login Rate Limiting'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[rate_limiting]" value="1" <?php if( ! empty( $config['rate_limiting'] ) && $config['rate_limiting'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[rate_limiting]" value="0" <?php if( empty( $config['rate_limiting'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Prevents hacking attempts by blocking the IP of users who try to log in 50 times in one hour.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Password Reset Mode'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[use_new_password_reset]" value="1" <?php if($config['use_new_password_reset'] == 1) echo 'checked'; ?>>
								Tokenized mode - Email a tokenized reset link (Recommended)
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[use_new_password_reset]" value="0" <?php if($config['use_new_password_reset'] == 0) echo 'checked'; ?>>
								<span style="color: red;">Legacy mode - Email a plain text password (Not Recommended)</span>
							</label>

							<p class="description">
								Allows password reset link to be sent to users.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('PubSvs Password Hashing'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[password_hashing]" value="1" <?php if( ! empty( $config['password_hashing'] ) && $config['password_hashing'] == 1) echo 'checked'; ?>>
								On (Password reset security level: High)
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[password_hashing]" value="0" <?php if( empty( $config['password_hashing'] ) ) echo 'checked'; ?>>
								Off (Password reset security level: Low)
							</label>

							<p class="description">
								Only enable this if PubSvs have hashed your passwords.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Enable Secure Login/Magic Link For Password Reset'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[magic_link]" value="1" <?php if( ! empty( $config['magic_link'] ) && $config['magic_link'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[magic_link]" value="0" <?php if( empty( $config['magic_link'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enable the use of a secure login link for forgot password. This mode sends the user a login link which allows them to log in the site without a password. More information <strong><a href="http://docs.threefoldsystems.com:8090/display/shareit/36601858/CUJfb9061aca2434c879b91eeb6d7ec4e13LBB" target="_blank">here</a></strong>.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Trigger Password Reset Email On Failed Attempts'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[failed_login_email]" value="1" <?php if( ! empty( $config['failed_login_email'] ) && $config['failed_login_email'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[failed_login_email]" value="0" <?php if( empty( $config['failed_login_email'] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Trigger a password reset email if the user fails their login a set number of times.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Number Of Attempts To Trigger Email'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="number" name="<?php echo $config_name;?>[failed_login_number]" value="<?php echo ( ! empty( $config[ 'failed_login_number' ] ) ? $config[ 'failed_login_number' ] : '' ); ?>">
							</label>

							<p class="description">
								If the failed attempt emails are set to 'On', allow this number of attempts before sending the email.
							</p>
						</div>
					</td>
				</tr>


				<tr>
					<th scope="row" class="field_title">
						<?php _e('PDF File Authentication'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[auth_rewrite_htaccess]" value="1" <?php if( ! empty( $config[ 'auth_rewrite_htaccess' ] ) && $config['auth_rewrite_htaccess'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[auth_rewrite_htaccess]" value="0" <?php if( empty( $config[ 'auth_rewrite_htaccess' ] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Enable PDF protection. More information <strong><a href="http://docs.threefoldsystems.com:8090/display/shareit/35946525/BXZ38192cd574b848ee8e3e7fec8d6091b6BXG" target="_blank">here</a></strong>.
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>


		<h3><?php _e('General'); ?></h3>

		<table class="form-table widefat">
			<tbody>
				<tr>
					<th scope="row" class="field_title">
						<?php _e('Extend User Session Expiration'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[no_expire]" value="1" <?php if( ! empty( $config[ 'no_expire' ] ) && $config['no_expire'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[no_expire]" value="0" <?php if( empty( $config[ 'no_expire' ] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								This will attempt to prevent users from being logged out by increasing the Wordpress authentication cookie expiration time.
							</p>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" class="field_title">
						<?php _e('Create Authcode Custom Post Type'); ?>
					</th>

					<td>
						<div class="radio">
							<label>
								<input type="radio" name="<?php echo $config_name;?>[mw_authcode_show]" value="1" <?php if( ! empty( $config[ 'mw_authcode_show' ] ) && $config['mw_authcode_show'] == 1) echo 'checked'; ?>>
								<?php _e('On'); ?>
							</label>

							<br/>

							<label>
								<input type="radio" name="<?php echo $config_name;?>[mw_authcode_show]" value="0" <?php if( empty( $config[ 'mw_authcode_show' ] ) ) echo 'checked'; ?>>
								<?php _e('Off'); ?>
							</label>

							<p class="description">
								Turn MW Authcode Custom Post Type - This can be used for Custom Menus / Dashboards etc. More information <strong><a href="http://docs.threefoldsystems.com:8090/display/shareit/36601911/USY9b27b513e1fd4dbfbeb20ffc0d2dfa61HKG" target="_blank">here</a></strong>.
							</p>
						</div>
					</td>
				</tr>
			</tbody>
		</table>



        <h3><?php _e('Emails'); ?></h3>

        <table class="form-table widefat">
            <tbody>
            <tr>
                <th class="field_title" scope="row">
                    <?php _e('HTML Emails'); ?>
                </th>

                <td>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[html_email]" value="1" <?php if( ! empty( $config['html_email'] ) && $config['html_email'] == 1) echo 'checked'; ?>>
                            <?php _e('On');?>
                        </label>

                        <br>

                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[html_email]" value="0" <?php if( empty( $config['html_email'] ) ) echo 'checked'; ?>>
                            <?php _e('Off'); ?>
                        </label>


                        <p class="description">
                            Enable HTML content in your outbound emails.
                        </p>
                    </div>
                </td>
            </tr>

            <tr>
                <th class="field_title" scope="row">
                    <?php _e('Send emails through'); ?>
                </th>

                <td>
                    <select id='mw-mail-toggle' name="<?php echo $config_name; ?>[sending_emails_through]">
                        <option value="0" <?php if ( ! isset( $config['sending_emails_through'] ) || $config['sending_emails_through'] == 0 ) echo 'selected'; ?>>PHP's mail() function</option>
                        <option value="1" <?php if ( isset( $config['sending_emails_through'] ) && $config['sending_emails_through'] == 1 ) echo 'selected'; ?>>Message Central</option>
                        <option value="2" <?php if ( isset( $config['sending_emails_through'] ) && $config['sending_emails_through'] == 2 ) echo 'selected'; ?>>SparkPost</option>
                    </select>

                    <div class="mw-settings-description">
                        <div class="mw-email-default-configuration">
                            <p>
                                Not very reliable as most of the mail tends to go to a spam folder. You also have an option to use a plugin such as <a target="_blank" href="https://wordpress.org/plugins/wp-mail-smtp/">WP Mail SMTP by WPForms</a> to send emails through SMTP which is reasonably quick and efficient.
                            </p>
                        </div>

                        <div class="mw-email-mc-configuration">
                            <p>
                                You will need to enter Message Central details on <a target="_blank" href="<?php echo admin_url( 'admin.php' ) . '?page=agora-mc'; ?>">Message Central</a> settings page.
                                Keep in mind that depending on the amount of emails going through Message Central at any given time, it may take 5-10 minutes for emails to be received.
                            </p>
                        </div>

                        <div class="mw-email-sp-configuration">
                            <p>
                                You will need to set up and configure a <a target="_blank" href="https://app.sparkpost.com/">SparkPost</a> or <a target="_blank" href="https://app.eu.sparkpost.com/">SparkPost EU</a> (GDPR Compliance) account.
                            </p>

                        </div>
                    </div>
                </td>
            </tr>

            <tr class="mw-email-sp-configuration">
                <th scope="row" class="field_title">
                    SparkPost Api Key
                </th>

                <td>
                    <div class="radio">
                        <label>
                            <input size="40" type="text" placeholder="i.e. 643edcdtrser2ee64e19a4sd543wes" name="<?php echo $config_name;?>[sparkpost_api_key]" value="<?php echo ( ! empty( $config[ 'sparkpost_api_key' ] ) ? $config[ 'sparkpost_api_key' ] : '' ); ?>">
                        </label>

                        <p class="description">
                            You can get the Api Key on the 'API Keys' page after logging into <a target="_blank" href="https://app.sparkpost.com/">SparkPost</a> or <a target="_blank" href="https://app.eu.sparkpost.com/">SparkPost EU</a> (GDPR Compliance) account.
                        </p>
                    </div>
                </td>
            </tr>

            <tr class="mw-email-sp-configuration">
                <th class="field_title" scope="row">
                    <?php _e('SparkPost Email From'); ?>
                </th>

                <td>
                    <div class="radio">
                        <label>
                            <input size="40" type="text" placeholder="i.e. info@threefoldsystems.com" name="<?php echo $config_name;?>[sparkpost_email_from]" value="<?php echo ( ! empty( $config[ 'sparkpost_email_from' ] ) ? $config[ 'sparkpost_email_from' ] : '' ); ?>">
                        </label>

                        <p class="description">
                            This field is optional. If field is empty - admin email set on the 'Settings' page will be used. This email address's domain must match the sending domain set in Sparkpost. This email address will be seen by people receiving emails, it will say email has been sent from this email address.
                        </p>
                    </div>
                </td>
            </tr>

            <tr class="mw-email-sp-configuration">
                <th class="field_title" scope="row">
                    <?php _e('SparkPost Account Region'); ?>
                </th>

                <td>
                    <div class="radio">
                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[sparkpost_region]" value="0" <?php if( empty( $config['sparkpost_region'] ) ) echo 'checked'; ?>>
                            US
                        </label>

                        <br />

                        <label>
                            <input type="radio" name="<?php echo $config_name;?>[sparkpost_region]" value="1" <?php if( ! empty( $config['sparkpost_region'] ) && $config['sparkpost_region'] == 1) echo 'checked'; ?>>
                            EU (GDPR Compliance)
                        </label>

                        <p class="description">
                            SparkPost provides different API URLs based on the region. If you have made an account using <a target="_blank" href="https://app.eu.sparkpost.com/">SparkPost EU</a> (GDPR Compliance), use <strong>EU</strong> region. Otherwise use the default <strong>US</strong> region.
                        </p>
                    </div>
                </td>
            </tr>

            <tr>
                <th></th>

                <td>
                    <strong>Once the settings have been updated and 'Save' button is clicked below, you can <a href="#" data-remodal-target="mw_send_test_email_modal" name="mw_send_test_email_modal">send a test email</a> to see if emails come through successfully.</strong>
                </td>
            </tr>
            </tbody>
        </table>



        <h3><?php _e('Webhooks'); ?></h3>

        <table class="form-table widefat">
            <tbody>
            <tr>
                <th scope="row" class="field_title">
                    Enable webhook for user login?
                </th>

                <td>
                    <select id='mw-user-webhook-toggle' name="<?php echo $config_name; ?>[webhooks_user]">
                        <option value="0" <?php if ( isset( $config['webhooks_user'] ) && $config['webhooks_user'] == 0 ) echo 'selected'; ?>>Disabled</option>
                        <option value="1" <?php if ( ! isset( $config['webhooks_user'] ) || $config['webhooks_user'] == 1 ) echo 'selected'; ?>>Enabled</option>
                    </select>

                    <p class="description">
                        This will enable a webhook that will be fired when a successful subscriber login happens.
                    </p>

                    <br >

                    <div class="mw-settings-description mw-user-webhook-toggle-area">
                        <p><strong>Webhook URL (POST request will be made to):</strong></p>

                        <label>
                            <input size="50" type="text" name="<?php echo $config_name;?>[webhooks_user_url]" value="<?php echo ( ! empty( $config[ 'webhooks_user_url' ] ) ? $config[ 'webhooks_user_url' ] : '' ); ?>">
                        </label>

                        <br >
                        <br >

                        <p><strong>Please select the items that you would like to be captured:</strong></p>

                        <label title="Date and time in 'Y-m-d H:i:s' format">
                            <input type="checkbox" value="1" name="<?php echo $config_name;?>[webhooks_user_items_time]" <?php if ( isset( $config['webhooks_user_items_time'] ) && $config['webhooks_user_items_time'] == 1 ) echo 'checked'; ?>> Time Of Login
                        </label><br />

                        <label title="customerNumber, userName">
                            <input type="checkbox" value="1" name="<?php echo $config_name;?>[webhooks_user_items_accounts]" <?php if ( isset( $config['webhooks_user_items_accounts'] ) && $config['webhooks_user_items_accounts'] == 1 ) echo 'checked'; ?>> Accounts
                        </label><br />

                        <label title="temp, itemDescription, itemNumber, circStatus, startDate, expirationDate, finalExpirationDate, lastIssue">
                            <input type="checkbox" value="1" name="<?php echo $config_name;?>[webhooks_user_items_subscriptions]" <?php if ( isset( $config['webhooks_user_items_subscriptions'] ) && $config['webhooks_user_items_subscriptions'] == 1 ) echo 'checked'; ?>> Subscriptions
                        </label><br />

                        <label title="temp, itemDescription, itemNumber, orderType, orderStatus, allowAccess, quantityReturned, quantityOrdered ,quantityShipped, orderDate">
                            <input type="checkbox" value="1" name="<?php echo $config_name;?>[webhooks_user_items_products]" <?php if ( isset( $config['webhooks_user_items_products'] ) && $config['webhooks_user_items_products'] == 1 ) echo 'checked'; ?>> Products
                        </label><br />

                        <label title="temp, itemDescription, itemNumber, termExpirationDate, expirationTime, expirationDate, startTime, startDate, quantityOrdered, quantityRemaining, participantStatus">
                            <input type="checkbox" value="1" name="<?php echo $config_name;?>[webhooks_user_items_ambs]" <?php if ( isset( $config['webhooks_user_items_ambs'] ) && $config['webhooks_user_items_ambs'] == 1 ) echo 'checked'; ?>> AMBs
                        </label><br />
                    </div>
                </td>
            </tr>
            </tbody>
        </table>


		<?php submit_button(__( 'Save'), 'primary', 'submit'); ?>
	</form>
</div>


<!--Remodal for exporting Base settings-->
<div class="remodal mw_send_test_email_modal" data-remodal-id="mw_send_test_email_modal">
	<button data-remodal-action="close" class="remodal-close"></button>
	<h1>Send a test email</h1>
	<hr />

	<?php

	if ( isset( $config['sending_emails_through'] ) && $config['sending_emails_through'] == 1 ) {
		?>
		<h3>You are sending email using Message Central</h3>
		<?php
	} else if ( isset( $config['sending_emails_through'] ) && $config['sending_emails_through'] == 2 ) {
		?>
		<h3>You are sending email using SparkPost</h3>
		<?php
	} else {
		?>
		<h3>You are sending email using PHP's mail() function</h3>
		<?php
	}
	?>

	<p>
		Update your settings if you would like to send the test email using a different mode.
	</p>

	<p>
		Put in your email address below and submit the form to test email sending.
	</p>

	<br />

	<div class="mw_returned_message_test_email"></div>

	<form id="mw_send_test_email" name="mw_send_test_email">
		<?php if ( function_exists('wp_nonce_field')) { wp_nonce_field('mw_test_email_nonce', 'mw_test_email_nonce'); } ?>

		<label for="mw_send_test_email_input">Your Email Address</label><br />

		<input size="40" type="email" id="mw_send_test_email_input" name="mw_send_test_email_input" placeholder="i.e. info@threefoldsystems.com"><br />
		<input type="submit" class="button button-primary" value="Send Test Email">
	</form>
</div>