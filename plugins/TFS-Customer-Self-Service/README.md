# Intro

The Customer Self Service Plugin (TFS CSS), allows website owners to add a complete Customer Self Service portal that 
interacts with Middleware 2 in their WordPress website by just dropping a shortcode.


# Install  

Install the TFS CSS Plugin using the WordPress plugin manager, note that you will require the Agora Middleware Base 
(version 1.4 and above) and Authentication plugins (1.3 and above), please make sure you are using a correct affiliate 
token once the plugin is activated it will create a new page on your installation called by default 
'/customer-self-service/', you will need to be logged in with a customer credentials.

# Required Plugins
* [Base](https://github.com/Pubsvs/Middleware-Base) (1.4 and above)
* [Authentication](https://github.com/Pubsvs/Middleware-Authentication) (1.3 and above)

# Customization
* Run npm install
* Run grunt
* Now you can make changes to main js and sass files

# Changelog
## 1.5.2 29 August 2018
* Add admin panel option 'is backend' to subscriptions 

## 1.5.1 23 May 2018
* Swapped order of Credit Card information

## 1.5 26 March 2018
* Added Credit Card information

## 1.4.1 26 March 2018
* Edit Lifetime logic
** NOTE: requires Middleware-Base 1.14

## 1.4 13 March 2018
* Retrieve postal address for subscriptions
* Return rate field with subscriptions
** NOTE: requires Middleware-Base 1.13

## 1.3.4 20 February 2018
* Headings separating language variable sections

## 1.3.3 27 November 2017
* Bypass dependency with constant 'TFS_CSD_BYPASS'

## 1.3.2 23 November 2017
* Pass brand color from Authentication settings to Login block on CSD pages
* Improved dependency checker

## 1.3.1 18 September 2017
* Use Final Expiration Issue date, not Expiration Issue date

## 1.3 6 September 2017
* Use Language vars in mw-login-block
** NOTE: requires Middleware-Authentication 1.9.5


## 1.2.8.3 21 June 2017
* Bug fix for state field error
* Hide menu and prompt page reload on password change - due to nonces being invalidated
** NOTE: requires plugin to be deactivated and reactivated to load new language variable


## 1.2.8.2 26 Apr 2017
* Bug fix for change address - valid countryCode and state send through when change country is disabled
* State is auto-filled in input box for non-US and non-Canadian customers

## 1.2.8.1 24 Apr 2017
* Bug fix for password check

## 1.2.8 5 Apr 2017
* Reorder and language changes to Admin settings
* Users can turn off auto renewal on subscriptions
* Hide temp subscriptions
** NOTE requires minimum MW Authentication Plugin version 1.8

## 1.2.7 7 Mar 2017
* Fix for eletters not displaying in some cases

## 1.2.6 7 Mar 2017
* Improved compatibility with older versions of php


## 1.2.5 3 Feb 2017
* Change opium prepop shortcode links to use new opium functionality
* ability to set xcode for eletters

## 1.2.4 13 Jan 2017
* Bug fix - Contact support notice showing when change country was turned on instead of off
* Bug fix - Address wouldn't submit if change country was turned off
* Bug fix - Fix logic on display allowed subscriptions - duplicate subscriptions showing up
* Bug fix - disable tabs while screens are loading - cannot open two screens at once
* Bug fix - 'Email already in use' error on e-letters - new logic and calls added
* Bug fix - Set username as primary email now works when password verification is turned on
* Bug fix - Set username as primary email now returns data in both themes
* Bug fix - contact page only shows when set with shortcode - now works with text/html
* Set username as primary email now uses it's own template, not html returned from a transient
* Fix bad conditional logic and bad html
* Feature - phone number now required in address section
* Feature - hide unsubscribed subscriptions option added to admin
* Feature - hide unsubscribed subscriptions on an sub by sub basis
* Feature - Return language variable if no subscriptions found
* Feature - Add alternative theme
* Feature - Alt theme - My profile now optional
* Feature - Alt theme - My profile gives name which can be clicked to show subscription memberships and membership from date
* Feature - Change email link directs to the change email tab
* SCSS - bullet points no longer created by css content in the before
* SCSS - label style now only applies within the dashboard and doesn't affect the site
* SCSS - renew now button placement
* SCSS - modal input fix
* SCSS - better style consistency on forms
* SCSS - various small style tweaks
* SCSS - BEM implemented on ul and li
* JS - On error in address section, scroll to top of section
* JS - loadSpinner, loadAjaxData, toggleChevronsOpen, toggleChevronsClose getTabsClass functions added to js to reduce duplicated code
* JS - click to close js added to new theme

## 1.2.3 6 Jan 2017
* Add option to hide publications if the customer isn't subscribed
* CSS updates
* Fix for bug on activation

## 1.1 10 Nov 2016
* Opium Renewals
* Admin UX Enhancements
* add ISSUES REMAINING option to subscriptions screen
* Add overlay screen to keep user from 'updating' until Advantage updates (15 minutes)
* fix Address errors
* fix Email errors
* Show prompt to change Username
* Show prompt Email Address on Subscriptions screen
* add RENEW button to Subscriptions screen
* add LOGOS to subscriptions screen
* Create core classes
* rework template files
* Rewrite SASS to update email on other subscriptions (after updating their email address)
* Language Variables
* fix font UX issues and other UI/UX fixes


## 1.0 8 Aug 2016
* Initial version of the plugin

# Documentation

### You can find all the documentation in confluence
