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

### REST API
As previously mentioned in `ContactsAPI`, there are several custom REST routes defined. These REST routes serve the WA data to the blocks.

The WordPress REST API can be accessed at `wp-json`. The plugin also has its own namespaces for endpoints which is required for custom REST routes.

For example, the full URL (relative to the WP website URL) for the contact fields endpoint would be `/wp-json/wawp/v1/contacts/fields/`. 

You can read more [here](https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/) about custom REST routes in WordPress.

Route   |   Function  | Used by | Arguments
--------|-------------|---------|-----------
`/wafw/v1/contacts/search/` | Used by the member directory search box to search a specific contact | `js/wafw.js` | None
`/wawp/v1/contacts/fields/` | Retrieve list of contact fields to display as checkboxes in the block editor settings | `blocks/components/ContactFields.js` | None
`/wawp/v1/savedsearches/` | Retrieves list of saved searches to display as a dropdown in the block editor settings | `blocks/components/SavedSearches.js` | None
`/wawp/v1/profiles/` | Retrieves profiles shortcode to display as content in the member directory when user clicks on a profile link | `js/wafw.js` | <ul><li>`int` `id` User ID</li><li>`bool` `hideResFields` Hide restricted fields</li><li>`array` `fields` List of contact fields to display</li></ul>

### Shortcodes
The plugin registers two shortcodes for the member directory and member profile respectively.

#### `wa-contacts`
The member directory shortcode can be invoked with `[wa-contacts]`. It takes in several **named** arguments along with a **list of fields** (each as a string) to render for each member in the directory.

Argument       | Type  | Description
---------------|-------|------------
`page-size`    | `int` | The number of members to display on each page of the directory.
`search`       | `bool` | Flags whether to enable search on the directory. If enabled, the shortcode will render a search box.
`saved-search` | `int` | The ID of the WA saved search from which the user wishes to render members.
`profile`      | `bool` | Flags whether to display a link to each members' profile on their cell in the directory.
`hide_restricted_fields` | `bool` | Flags whether to display fields which are restricted in Wild Apricot.

**Note:** Not passing in any fields will result in the block rendering an empty shortcode.

**Example:**
```
[wa-contacts 'User ID' 'My First name' 'Last name' page-size=10 profile] [/wa-contacts] 
```
This shortcode passes in the fields `User ID`, `My First name`, and `Last name`. The shortcode will render all of these fields for every user in the directory. 

The shortcode also indicates the page size is 10 users per page and it **enables** the profile link to link to each member.

Notice the `bool` arguments are toggled simply by **including** or ***not* including** the argument name in the shortcode.



#### `wa-profile`
The member profile shortcode can be invoked with `[wa-profile]`. Similar to the member directory shortcode, `[wa-profile]` takes in named arguments as well as a list of fields to render in the profile.

Argument    | Type | Description
------------|------|------------
`user-id`   |`int` | **[Required]** The ID of the user for which to display the profile.
`hide_restricted_fields` | `bool` | Flags whether to display fields which are restricted in Wild Apricot.

**Note:** Not passing in any fields and not passing in a user ID will result in the block rendering an empty shortcode.

**Example:**
```
[wa-profile 'User ID' 'My First name' 'Last name' 'Organization' 'Job Title' 'City' user-id=60085794 hide_restricted_fields] 
```
This shortcode passes in several fields: `User ID`, `My first name`, `Last name`, `Organization`, `Job Title`, and `City`. It also passes in a user ID and enables hiding restricted fields.

## Block files and functionality

### Member directory

### Member profile

## REST routes

## Shortcodes
### `wa-contacts`

### `wa-profile`