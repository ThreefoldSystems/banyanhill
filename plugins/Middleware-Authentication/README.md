# Introduction

The Authentication plugin provides advanced authentication functionality based on Pub codes, Product Codes, and AMB. It also allows for other plugins to extend it's capabilities and add other authentication methods.

[Read the wiki for more information](https://github.com/Pubsvs/Middleware-Authentication/wiki)

# Installation

It's best to clone the repository from Github, but you can just download the zip file and unzip it in your wp-content/plugins/ directory if you're not using version control.

## Dependencies

This plugin **requires** the following to work:

1. [Middleware Base Plugin](https://github.com/Pubsvs/Middleware-Base)

# Changelog
## 1.23.1 - November 22 2019
* Update to registering pubcode taxonomy priority

## 1.23 - November 11 2019
* Update get login by id, generate magic link email, tokenized login

## 1.22.1 - October 29 2019
* Bugfix for not persisting changes to user's biographical info

## 1.22 - August 16 2019
* !!! This release will require you to re-activate Middleware Authentication plugin as this will load new language variables.
* Made form validation feedback configurable with language vars

## 1.21 - June 28 2019
* Bugfix for fb redirect, stop actioning an item without verifying it exists
* Rewrite of single sign on to use JWTs - changes to both link generation and sign in method. This SHOULD be backward compatible but please check  


## 1.20 - May 28 2019
* Add functions to make and decrypt JWTs

## 1.19.1 - May 20 2019
* Added the defined const AGORA_MIDDLEWARE_AUTH_VERSION to the plugin this contain the current version of the plugin

## 1.19 - February 14 2019
* Language variable explanations
* Sparkpost integration
* Create a webhook for customer login events

## 1.18 - December 10 2018
### This release will require you to re-activate Middleware Authentication plugin as this will load new language variables.
* Bugfix for cancelled accounts accessing AMBs
* Bugfix for temp accounts accessing products
* Bugfix for JS email validation
* Send email to the customer after successful password reset
* Show message if the customer is having trouble logging in immediately after resetting password
* Update Login with facebook button & login with facebook functionality

## 1.17.1 - October 25 2018
* Bugfix for email validation.

## 1.17 - July 24 2018
### This release will require you to re-activate Middleware Authentication plugin as this will load new language variables.
### This release is using new styles and views, if you have copied over login/password reset views into your theme - you will need to update them with the new code.
* Login UX/UI Improvements
* Issues with activating both plugins at the same time
* Add HTML meta tags to posts that are tagged with auth codes
* Other minor bug fixes.

## 1.16 - June 14 2018
* Add legacy password reset mode

## 1.15 - June 1 2018
* Product Authentication
* Allow administrators to view PDFs
* Check access manually (mw_current_user_can_access filter)

## 1.14 - May 3 2018
* Fix tokenized login

## 1.13 - April 30 2018
* Remove legacy password reset mode - tokenized password reset mode will be enabled by default
* Compatibility with PHP 7
* Check $_POST and $_GET requests before sanitization to avoid PHP warnings

## 1.12.2 - April 16 2018
* Encrypt user's email address during password reset process

## 1.12.1 - April 12 2018
* Don't sanitize user's password unless it's necessary

## 1.12 - March 29 2018
* Veracode security scans
* PubSVS - Password Hashing endpoint
* Only allow valid logins
* Only allow one session at a time

## 1.11.1 - March 23 2018
* Bugfix character encoding on forgot password view

## 1.11 - March 15 2018
* Bugfix for save post authcodes
* Fix typo in facebook redirect

## 1.10 - February 22 2018
* UI improvements
* Bugfix tokenized login
* Bugfix for get_authcodes_by_name
* Sanitize inputs
* Plugin update notice

## 1.9.6 - October 13 2017
* Added DocBlocks and commented code

## 1.9.5 - September 11 2017
* Changes to trial subscriptions
* Use language vars in login block
* Allow admins access PDFs
** Note, this requires the plugin to be deactivated and reactivated

## 1.9.4 - July 11 2017
* Changes to trial subscriptions
* Update to temp password reset functionality

## 1.9.3 - May 17 2017
* Changes to trial subscriptions

## 1.9.2 - May 3 2017
* Add get_trial_amb_by_days function to determine if current user is on a trial AMB

## 1.9.1 - May 2 2017
* Bug fix to session in the tfs_change_pwd

## 1.9 - April 25 2017
* Redesign Login block and forgot password page
* agora_reset_password class added to handle forgot password, multi-user login, and forgot username functionality 
* Fixes for multi user login
* Fixes for auto login
* Add secure login link functionality

## 1.8 - February 21 2017
* Dynamically toggle between 'Reset' and 'Request' language for forgot password
* Automated lost password emails
* Fixes for login block and forgot password page
* agora publication class now includes temp flag - needed for CSD

## 1.7 - November 10 2016
* Add self update feature to the plugin
* Remove outdated 'tests' folder from plugin files
* Add filter to auth_cookie_expiration to increase expiry time to 20 years in order to prevent session expiry
** Note, this is toggled on and off via an admin panel option
* Show/Hide MW Auth cpt via Setting

## 1.6 - 22 August 2016
* Bug fix - HTML email option not updating
* Ability to login via Facebook
* Auto login user after a password reset
* Language change to forgot password setting
* Health check now uses wp option rather than transient

## 1.5 - 15 July 2016
* Improve UI for authcode edit form
* Improve UI for login block
* Force user to reset password if they are using a temporary password (prefix: nd312_)
** Note, the plugin will need to be deactivated and reactivated to auto generate the temporary password page

## 1.4 - 1 July 2016
* Improve UI for pubcode picker
* Improve UI for Admin settings
* Change language on reset password page ('Request Username' instead of 'Reset Username')
* Fixed bug in password_reset so invalid emails return correct language
* Htaccess pdf authentication rules set by default
* New functionality sets transient when user resets password, and prevents them from resetting again for 15 minutes
** Note, the plugin will need to be deactivated then reactivated to load a new language variable
* Added function get_user_authcodes to return authcodes for a user
* Fixed bug [#154] oldest email being returned for user instead of newest

## 1.3 - April 8 2016
* Added support for 'Grace' period in pubcodes
* Improved default language for password reset process
* Added support for HTML emails
** Note, this needs to be enabled via an option in the Authentication section of the plugin admin
* Bugfix for strict php to check that a variable exists before accessing.

## 1.2.5.8 - April 7 2016
* Fixed bug with Legacy password reset

## 1.2.5.7 - February 23rd 2016
* Single Sign On fix for nginx

## 1.2.5.6 - February 23rd 2016
* Hotfix for broken Pubcode Picker UI

## 1.2.5.5 - February 19th 2016
* Added support for graceFlag field

## 1.2.5.4 - January 20 2016
* Wrap wp core function call to eliminate issue on activation
* Health check call disable until conflict with W3TC is resolved

## 1.2.5.3 - January 19 2016
* Single sign on remove 's' from the url

## 1.2.5.2 - January 15 2016
* Missing single sign on call

## 1.2.5.1 - January 12 2016
* Shortcode picker hotfix and jquery dependency

## 1.2.5 - January 6 2016
* Changes made to this version depend on the changes made to the [Middleware Base Plugin](https://github.com/Pubsvs/Middleware-Base) version 1.2.0.

* More shortcodes: customer first name, full name, email, account number
* Option to have customer information in JavaScript format in for use in google analytics
* Middleware Shortcode picker on post edit page
* Write file authentication rules into the .htaccess automatically
* Bulk Edit for Pubcodes allowing to change auth. codes on multiple posts at once
* Improve UI for Pubcode Picker [#114](https://github.com/Pubsvs/Middleware-Authentication/issues/114)
* Excerpt/read more disappearing after failed login [#132](https://github.com/Pubsvs/Middleware-Authentication/issues/132)

## 1.2.4.1 - December 7 2015
* Bugfix Password Reset response bug [#134](https://github.com/Pubsvs/Middleware-Authentication/issues/134)

## 1.2.4 - October 26 2015
* Changes made to this version depend on the changes made to the [Middleware Base Plugin](https://github.com/Pubsvs/Middleware-Base) version 1.1.0.

* Add caching fix for failed logins [#118](https://github.com/Pubsvs/Middleware-Authentication/issues/118)
* Php warnings [#127](https://github.com/Pubsvs/Middleware-Authentication/issues/127)
* Stop sending emails when email changes [#121](https://github.com/Pubsvs/Middleware-Authentication/issues/121)
* Password Reset Process [#125](https://github.com/Pubsvs/Middleware-Authentication/issues/125)
* Fix MC lost password API create mailing call
* Fix Preg Replace bug for @ and . symbol in usernames
* Circ Status Rules fix with spaces (P, Q, R, X, W)

## 1.2.3 - July 7 2015
* Bugfix Usernames with apostrophes [#85](https://github.com/Pubsvs/Middleware-Authentication/issues/85)
* Bugfix W3TC Cache conflict [#118](https://github.com/Pubsvs/Middleware-Authentication/issues/118)
* Bugfix Issue with symlinks [#115](https://github.com/Pubsvs/Middleware-Authentication/issues/115)

##  1.2.2.4 - May 29 2015
* Bugfix for compatibility bewtween MC tracking vars and Wordpress search [#109](https://github.com/Pubsvs/Middleware-Authentication/issues/109)

## 1.2.2.3 - May 22 2015
* Support for new MC auto login features [#103](https://github.com/Pubsvs/Middleware-Authentication/issues/103)
* Requires Version 1.0.7 of the Base Plugin

## 1.2.2.2 - January 29 2015
* Bugfix for tokenized login [#99](https://github.com/Pubsvs/Middleware-Authentication/issues/99)
* Support for background Single Sign On [#88](https://github.com/Pubsvs/Middleware-Authentication/issues/88)
* Support for MC API mailings [#86](https://github.com/Pubsvs/Middleware-Authentication/issues/86)
* Bugfix for usernames containing apostrophes [#85](https://github.com/Pubsvs/Middleware-Authentication/issues/85)
* Bugfix for [#69](https://github.com/Pubsvs/Middleware-Authentication/issues/69)

## 1.2.2.1 - January 22 2015
* Bugfix for file download access [Issue #94](https://github.com/Pubsvs/Middleware-Authentication/issues/94)
* Bugfix to gracefully handle failed tokenized logins [Issue #97](https://github.com/Pubsvs/Middleware-Authentication/issues/97)

## 1.2.2 - December 16 2014
* Fixed bug where auth plugin was disabling itself when WP updated other plugins. [#80](https://github.com/Pubsvs/Middleware-Authentication/issues/80)
* Support for lost password requests of regular WP users [#78](https://github.com/Pubsvs/Middleware-Authentication/issues/78)
* Support for customizable messages on lost password emails [#67](https://github.com/Pubsvs/Middleware-Authentication/issues/67)

## 1.2.1.1 - December 11 2014
* Minor change to authentication class to fix compatibility with legacy code

## 1.2.1 - December 1 2014
* Added feature to handle single sign-on across domains
* Introduced `agora_before_save_aggregate_data` filter to allow clearing of MW data before saving to database.

## 1.2.0.2 - November 16 2014
* Fixed several php notice level warnings to make working with debug mode on cleaner.

## 1.2.0.1 - October 16 2014
* Bugfix for Issue [#72](https://github.com/Pubsvs/Middleware-Authentication/issues/72)

## 1.2.0 - October 1 2014
* Added One-time use tokenized Login feature [#34](https://github.com/Pubsvs/Middleware-Authentication/issues/34)
    * Note: this feature requires version 1.0.3 of the [Base Plugin](https://github.com/Pubsvs/Middleware-Base).
* Fixed bug with Login widget feedback [#58](https://github.com/Pubsvs/Middleware-Authentication/pull/58)
* Addressed bug with duplicate email addresses [#55](https://github.com/Pubsvs/Middleware-Authentication/issues/55)
* Rate Limiting on Logins [#54](https://github.com/Pubsvs/Middleware-Authentication/issues/54)
* WP_CLI command to convert from old CF plugin to Pub Services plugin [#57](https://github.com/Pubsvs/Middleware-Authentication/pull/57)

## 1.1.7 - September 15 2014
* Fixed Issue [#64](https://github.com/Pubsvs/Middleware-Authentication/issues/64) Return shortcode output instead of echo

## 1.1.6 - August 12, 2014
* Fixed Issue [#45](https://github.com/Pubsvs/Middleware-Authentication/issues/45). Users with previous cancelled or Expired Subscription can't access content.
* Fixed Issue [#48](https://github.com/Pubsvs/Middleware-Authentication/issues/48). Prevent Middleware Calls for admin users
* Fixed Issue [#52](https://github.com/Pubsvs/Middleware-Authentication/issues/52). When listAuth plugin was enabled, users were being created incorrectly.

## 1.1.5 - July 31, 2014
* PDF download authentication
    * New Class agora_file_access
* No-index Meta tag
* Fixed Issue [#38](https://github.com/Pubsvs/Middleware-Authentication/issues/38)

## 1.1.4 - July 7, 2014
* Content segment protection shortcode
* Event tracking for rulepoint integration

## 1.1.3 - May 28, 2014
* Feature development for [#21](https://github.com/Pubsvs/Middleware-Authentication/issues/21)
* Moved authentication object to the core
* New Object types:
  * agora_advantage_item
  * agora_publication
  * agora_product
  * agora_access_maintenance_billing
* New method: $core->authentication->get_user_subscriptions()
  * Inspects the users middleware data, and compares all their product/subscription/amb items with the defined auth codes and returns an array of matching objects.
* New filter: agora_get_adv_item_classname
  * Allows other plugins to add to the types of subscription/purchase objects

## 1.1.2 - May 23, 2014
* Bug fixes for [Issue #18](https://github.com/Pubsvs/Middleware-Authentication/issues/18)

## 1.1.1 - May 22, 2014
* Some refactoring and changes to allow for the [list Authentication plugin](https://github.com/Pubsvs/Middleware-ListAuth)
* Added new filters
  * load_middleware_aggregate_data
    * Add/modify data on the users middleware_data array.
  * agora_mw_auth_types
    * Hook to allow other plugins to add new Authentication Types
  * agora_mw_default_rule
    * Hook to allow other plugins to create default rules for their Authentication Types
  * agora_mw_find_purchase
    * Plugins will be sent the middleware_data array, and an auth code, should return a $subscription_object for the rule system to inspect
  * agora_mw_auth_field_structure
    * Hook to allow other plugins to add their own variable location information so the rule system can inspect a $subscription_object array
  * agora_middleware_admin_menu
    * Instead of storing the Admin page tabs in wordpress options, they're now added on the fly using this filter.

## 1.1 - April 1, 2014
* Added support for Rule-based authentication. Allowing for:
  * AMB (Access Maintenance Billing)
  * Product Authentication
  * Subtype
  * Member Cat / Member Org

### Impact

Moving to version 1.1 is a fairly significant change. You will need to update the Authentication code definitions to allow for it.

#### The Advantage Code
*Advantage Code* is a catch-all term for the unique code that identifies a product, subscription, or AMB service in Advantage.
For example,  pub codes are usually 3 character abbreviations, product and AMB codes are longer.

We use the term *Authentication Type* to refer to the specific type of Advantage code we're talking about. Additional plugins can hook into the 'agora_mw_auth_types' filter to add extra authentication types for example "List Authentication" would refer to listcode to see if a user is subscribed to a particular e-mail newsletter. See [list Authentication plugin](https://github.com/Pubsvs/Middleware-ListAuth)

Previously, an auth code had just a name and a description. Where the name corresponded to the Pubcode.
The rule system allows for multiple auth codes using the same Advantage code but Wordpress does not allow duplicate term names so now we have an Auth Code name, an *Advantage Code*, an *Auth Type*, and a Description.

When installing 1.1.x The plugin will pick up the previous Auth codes but you will need to edit them to fill the *Advantage Code*, and *Auth Type* fields. See the [wiki page](https://github.com/Pubsvs/Middleware-Authentication/wiki/Authentication) for more information about auth codes and the rule system

## 1.0 - December 10, 2013
* Basic pubcode authentication

