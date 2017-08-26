# FB Dynamic Ads for Real Estate #
**Contributors:** [davebonds](https://profiles.wordpress.org/davebonds)  
**Author link:** https://davebonds.com  
**Tags:** facebook, facebook ads, dynamic listings, real estate, impress listings  
**Requires at least:** 3.5  
**Tested up to:** 3.8.1  
**Stable tag:** 1.1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Adds XML feed formatted for FB Dynamic Ads for Real Estate to the IMPress Listings plugin.

## Description ##

This plugin adds an XML feed for IMPress Listings formatted to standards and requirements for [FB Dynamic Ads for Real Estate](https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/) Listing Catalogs.

Documentation for Facebook Dynamic Ad Listing Catalogs here: [https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/catalog](https://developers.facebook.com/docs/marketing-api/dynamic-ads-for-real-estate/catalog)

Support for this plugin is handled on [Github](https://github.com/davebonds/fb-dynamic-ads-real-estate). 

## Installation ##

1. In the 'Plugins' menu in WordPress, search for FB Dynamic Ads for Real Estate
2. Activate the plugin through the 'Plugins' menu in WordPress

## Frequently Asked Questions ##

### How do I access the listing catalog XML file? ###

The plugin adds a new feed url at yourdomain.com/feed/fb-catalog/

### How do I have create a listing catalog with more than the default 10 listings? ###

The feed template supports a query parameter of ?numposts=X - where X is the number of listing posts to display.

### Will this plugin support other real estate listing plugins? ###

Not likely unless there is an overwhelming interest to do so.


## Screenshots ##

1. Coming soon

## Changelog ##

### 1.1.0 ###
* New: Added metabox to listing edit screen to select fields formatted for listing catalog feed (availability, property type, listing type)
* New: Added availability, property type, and listing type fields to XML
* New: Added 'neighborhood' field to XML (uses locations' default taxonomy)
* New: Add settings to input Facebook account identifiers

### 1.0 ###
* Initial release


## Roadmap ##

* Use FB account identifiers to post to API to create catalogs, build audiences, and more.
