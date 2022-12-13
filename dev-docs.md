# WildApricot Press Add-on - Member Directory Developer's Guide

#### By Natalie Brotherton

##### *Updated December 2022*

The Member Directory plugin is one of the free add-on blocks for WAP. It enables uses to embed a native member directory and native member profiles in a Gutenberg block.

The plugin contains two separate blocks: the WAP Member Directory and the WAP Member Profile.

The functionality for the plugin is separated into two separate parts: Javascript and PHP. The Javascript part is responsible for the Gutenberg blocks and some other client-side block functionality. The PHP side is responsible for handling the WildApricot member data. The two sides communicate via REST routes defined in PHP.

## PHP files and functionality
The PHP side of the plugin handles grabbing data from the Wild Apricot API and rendering the data in a shortcode to display either the directory or a profile.

### Classes
#### `CacheService`
Handles saving and retrieving data from the "cache" (transients in the options table).

Use example:
```php
// retrieve static instance
$cacheService = CacheService::getInstance();

// save value
$cacheService->saveValue('key_to_save', $my_value);

// retrieve the value
$my_value = $cacheService->getValue('key_to_save');
```

Since the items stored in the cache are [transients](https://developer.wordpress.org/apis/transients/), they will be deleted from the options table after a set amount of time. In this case, the cache items are stored for **one hour**.

#### `Contacts`
This class is used to perform operations on a contacts list from WA.

This includes:
* Filtering certain values in a contact list
* Obtaining only the field values from a contacts list
* Searching for keywords in a contact list

#### `ContactsAPI`
This class contains most of the functionality for the member directory. 

It registers several REST routes used by the Javascript side of the plugin. See more about the REST routes in the REST routes section.

It also handles rendering the member directory shortcode.

#### `ContactsListingPersistor`

#### `ContactsUtils`
Contains helper functions for parsing contacts data. This includes using the arguments from the shortcodes to build `filter` and `select` queries to pass in to the Wild Apricot API. 

#### `UserProfileShortcode`
Handles rendering the user profile shortcode.

#### `WaApiClient`
Interfaces with the WAP API class `WA_API` to obtain the user authentication (access token and account ID) which are used to authenticate requests to the Wild Apricot API in the `makeRequest` function.

#### `WAService`
Interfaces with `WaApiClient` to connect to the WA API.

Has specific functions implemented to requests specific routes in the API such as
* `getContactFields()` retrieves custom fields for contacts/members
* `getContactsList()` retrieves list of contacts with a `filter` and/or `select` statement applied
* `getSavedSearches()` retrieves list of saved search IDs
* `getSavedSearch()` retrieves a saved search corresponding to the ID argument passed in
* `getPicture()` retrieves the picture using the URL argument passed in




## Block files and functionality

### Member directory

### Member profile

## REST routes

## Shortcodes
### `wa-contacts`

### `wa-profile`