# Intro

This plugin forms part of a series of related plugins that allow Agora affiliates to interact with the middleware 2 REST api.

By itself this plugin doesn't really do anything, it just gives you a place to save some settings and change some text variables. You'll need other plugins in the series to really get stuck in.

If you have access to the Github repo for this or related plugins you are asked to report bugs using the issue tracker. You are also encouraged to submit your own contributions via pull-requests

# Quick Start


Grab a copy the plugin by cloning this repository, or downloading the Zip file. Full [installation instructions can be found here](https://github.com/Pubsvs/Middleware-Base/wiki/Installation). **It is highly recommended that you clone the repository to make it easier to pull updates in the future.**

[The debug plugin](https://github.com/Pubsvs/Middleware-Debug) is a good place to start. It doesn't do anything to the front end of your site and gives you a screen in wordpress where you can see data coming back from middleware. It can even be used with the old Crowd Favorite plugin installed.

# Related Plugins

* [Authentication](https://github.com/Pubsvs/Middleware-Authentication)
* [Debug](https://github.com/Pubsvs/Middleware-Debug)

# Changelog
## 1.23.1 - December 11 2019
* Fixed Typo 

## 1.23 - November 11 2019
* Added get_aggregate_data_by_customer_number call
* Added put_update_affiliate_tags call

## 1.22.1 - October 29 2019
* Update the Get Account by email endpoint

## 1.22 - June 20 2019
* Don't JSON encode XML
* Added update delivery code endpoint

## 1.21 - May 20 2019
* Updated Guzzle Librarys in Vendor
* Added order stream call for Boss

## 1.20.1 - May 20 2019
* Update the workflowevent endpoint so that the eventTypeId must be passed through to it 
* Added the defined const AGORA_MIDDLEWARE_BASE_VERSION to the plugin this contain the current version of the plugin

## 1.20 - April 9 2019
* Update the workflow event endpoint so that it can be used with diffrent eventTypeId and can or cant use term number
* Update The name Listcode to be Listname

## 1.19 - February 14 2019
* Language variable explanations
* Sparkpost integration

## 1.17 - July 24 2018
* Login UX/UI Improvements
* Issues with importing settings
* Issues with activating both plugins at the same time
* Message central password reset content name made unique for each affiliate

## 1.16 - April 30 2018
* Compatibility with PHP 7
* Check $_POST and $_GET requests before sanitisation to avoid PHP warnings

## 1.15.1 - April 24 2018
* Call Added to create Advantage workflow event to trigger cancel subscription

## 1.15 - March 29 2018
* Veracode security scans
* PubSVS - Password Hashing endpoint

## 1.14 - Mar 13 2018
* Call Added to Get Future Subscriptions by Subref

## 1.13 - Mar 13 2018
* Call Added for Credit Card Information
* Add UX to allow Affiliate Code to be stored in Admin
* Added call to Get Subscription, Postal Address By Sub Ref
* Fix issue with 'Check for updates' link on plugins screen

## 1.12 - February 22 2018
* UI improvements
* Sanitize inputs
* Plugin update notice

## 1.11 - October 13 2017
* Added IP to POST request header
* Added Server Name to get_aggregate_data_by_login call
* Added DocBlocks and commented code

## 1.10 - September 11 2017
* Added call to getOrderDetailsByCustomerNumberAndOwningOrg
* Added call to findItemsAndChoicesByPromoCode

## 1.9.1 - July 31 2017
* Update auto update token

## 1.9 - April 25 2017
* Disable report back feature

##  1.8 Mar 7 2017
* Added call to findOrderDetailByOrdernumber
* Added call to updateSubscriptionAutoRenewFlag

## 1.7.1 Dec 16 2016
* MW Caching Bug fix

## 1.7 Dec 16 2016
* Added MW Caching use with caution - disabled by default

## 1.6 September 15 2016
* Add self update feature to the plugin
* Remove outdated 'tests' folder from plugin files
* Option to manually set mailing ID for Message Central forgot password mailing

## 1.5.1 September 2 2016
* Add new timeout filter to agora_mc_wrapper to prevent get_all_mailings_by_orgid from timing out

## 1.5 August 22 2016
* Import/Export Base Settings, Message Central Settings, Language Variables and Authentication Settings
* Health check call re-enabled
* Health check now uses wp option rather than transient
* New option added to Message Central settings for affilates to set their own lists for MC fired forgot password emails
* Experimental user caching functionality added
* Improve UI - reorder Message Central option page

## 1.4.2 August 2 2016
* Bugfix for incorrect url in put_create_affiliate_tags

## 1.4.1 July 8 2016
* Added methods updateCustomerSignup, findSubscriptionByEmailAddress, findSubscriptionEmailAddressBySubRef, updateSubscriptionEmailAddress, updateUsername

## 1.4 July 1 2016
* Bugfix for Middlware Connection Check
* Added method that obscures all bar the last 4 characters of tokens on the frontend
* Bugfix for [#59] incorrect urls in get_affiliate_tags_by_id, put_unsub_customer_signup and get_customer_email_by_id

## 1.3 May 4 2016
* Added support for Middleware call 1.7 updateEmailAddress
* Improved error reporting when making middleware calls.
* New icon to match Wordpress Look & Feel

##1.2.4 March 24 2016
* Added method that gets customer email address by contact ID, Org.ID and Stack

##1.2.3 March 10 2016
* Added method that gets all lists for a customer by email


##1.2.2 February 4 2016
* Added method that gets records combining subscription and postal address using a purchase order number
* Added method to find the lowest customer number using the customer’s e-mail address

## 1.2.1 January 20 2016
* Health check call disable until conflict with W3TC is resolved

## 1.2.0 January 7 2016
* Added health check

## 1.1.0 October 26 2015
* Added IP address verification, previously in the Debug Plugin
* Added support for updatePassword Middleware Call

## 1.0.9 August 14 2015
* Bugfix for [#48](https://github.com/Pubsvs/Middleware-Base/issues/48)

## 1.0.8 June 3 2015
* Support URL of the requesting page to be passed back to Middleware [#40](https://github.com/Pubsvs/Middleware-Base/issues/40)
* Bugfix for [#31](https://github.com/Pubsvs/Middleware-Base/issues/31)
* Support for MC Targetting Services [#32](https://github.com/Pubsvs/Middleware-Base/issues/32)
* Support for findPostalAddressesByEmailAddresses call [#33](https://github.com/Pubsvs/Middleware-Base/issues/33)

##  1.0.7 May 22 2015
* Support for new MC VID validation [#29](https://github.com/Pubsvs/Middleware-Base/issues/29)

## 1.0.6 December 12 2014
* Bugfix for Rulepoint client

## 1.0.5 November 16 2014
* Fixed several php notice level warnings to make working with debug mode on cleaner.
* Added support for unsubCustomerSignup Middleware Call

## 1.0.4 September 8 2014
* Added the 'agora()' function into the global space to make interacting with the plugin easier.

## 1.0.3 September 4 2014
* Added call for get customer number by contact ID & org ID
* Added validation for Vid and numeric values to input framework

## 1.0.2 July 10 2014
* Added call for Find Email Fulfillment History

## 1.0.1 June 16 2013

* Added call for update customer address data
* Changed input validator to allow for apostrophes and hyphens in names

## 1. November 2013

* Version 1.

# Documentation

### [Overview](https://github.com/Pubsvs/Middleware-Base/wiki/Overview)
An overview of the base plugin, and the core framework

### [Installation](https://github.com/ciaranmg/Middleware-Base/wiki/Installation)
Installing and configuring the Middleware plugin(s)

### [The core framework](https://github.com/ciaranmg/Middleware-Base/wiki/Core-Framework)
The core framework methods and functionality

### [Middleware Connection](https://github.com/ciaranmg/Middleware-Base/wiki/Middleware-Interface)
Making middleware calls

### [View Framework](https://github.com/ciaranmg/Middleware-Base/wiki/View-Framework)
Working with templates relating to the Middleware plugin(s).

### [Session Framework](https://github.com/ciaranmg/Middleware-Base/wiki/Session-Framework)
Session utilities used by the plugin

### [Database Wrapper](https://github.com/ciaranmg/Middleware-Base/wiki/Database-Interface)
A simple class for interacting with data in the Wordpress database
