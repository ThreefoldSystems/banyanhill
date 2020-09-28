# Introduction

This plugin allows wordpress to interact with the Portfolio Tracker API and build tables with the
data.  Once this plugin is installed you will be able to place a shortcode on a page or
post and have it build a portfolio table based on the portfolio ID given in the shortcode.

# Installation

Clone the repository from Github or download the zip file and unzip it in your
wp-content/plugins/ directory.  Once activated you will see a Portfolio Tracker admin
tab where you can enter your API key, configure your cache settings and get your
portfolio ID's.

# Shortcode Attributes

- id        (required)
- template  (optional)
- group     (optional)

# Example Shortcode

```html
[portfolio_tracker id="1234" template="custom-template" group="Income"]
```

# Changelog
## 1.3 - March  2019
* Add table with return % graph template

## 1.2.2 - March 5 2019
* Fix for http/https issue

## 1.2.1 - April 13 2018
* Fix for caching issue

## 1.2 - April 12 2018
* Display 5yr performance without writing to file

## 1.1 - April 4 2018
* Improved Mobile Responsiveness
* Added functions to retrieve 5yr performance data

