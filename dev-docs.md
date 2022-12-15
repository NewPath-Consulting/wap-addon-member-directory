# WildApricot Press Add-on - Member Directory Developer's Guide

#### By Natalie Brotherton

##### *Updated December 2022*

The Member Directory plugin is one of the free add-on blocks for WAP. It enables uses to embed a native member directory and native member profiles in a Gutenberg block.

The plugin contains two separate blocks: the WAP Member Directory and the WAP Member Profile.

The functionality for the plugin is separated into two separate parts: Javascript and PHP. The Javascript part is responsible for the Gutenberg blocks and some other client-side block functionality. The PHP side is responsible for handling the WildApricot member data. The two sides communicate via REST routes defined in PHP.

Before reading this documentation and contributing to this project, it is highly recommended to have a basic understanding of PHP, React & JSX syntax, and Gutenberg blocks. 

## Setup
To build the block files in this project, you must have `npm` installed on your machine. 

The below chart describes commands for building each block.

Block | Watch (dev) | Build (prod)
------|-------------|--------------
Member directory | `npm run start:member-directory` | `npm run build:member-directory`
Member profile | `npm run start:member-profile` | `npm run build:member-profile`

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

#### `ContactsUtils`
Contains helper functions for parsing contacts data. This includes using the arguments from the shortcodes to build `filter` and `select` queries to pass in to the Wild Apricot API. 

#### `UserProfileShortcode`
Handles rendering the user profile shortcode.

#### `WaApiClient`
Interfaces with the WAP API class `WA_API` to obtain the user authentication (access token and account ID) which are used to authenticate requests to the Wild Apricot API in the `makeRequest` function.

#### `WAService`
Interfaces with `WaApiClient` to connect to the WA API.

Has specific functions implemented to request specific routes in the API such as
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
There are two separate blocks included in this plugin, for the member directory and member profile respectively. 

The purpose of the blocks is to provide an interface for building the shortcodes described above. 

All block files are located in the `blocks/` folder. The two blocks also share several classes and custom React components located in `blocks/components/`.

### Shared classes and components

The custom components are responsible for rendering settings fields in the block editor. Some of these components, like contact fields for example, use data from Wild Apricot to render the settings fields. 

#### `ContactFields`
The `ContactFields` class is responsible for retrieving all the contact fields from Wild Apricot via the custom REST endpoint defined on the PHP side. 

The fields are separated into three categories: System, Common, and Member fields. The block editor will also display the fields in these categories. The class maintains separate data structures for the fields.

The file containing the definition of this class exports an instance `contactFields` at the bottom which is used by the member directory and profile blocks to access custom field data.

#### `<Field>`
The `Field` component is a custom component that renders a single checkbox for a custom field.

Checking a field box will add the field to a list of fields to be applied in the shortcode in the block's attributes. 

### Member directory

All the member directory files are located under `blocks/member-directory`.

#### Attributes and block settings
The attributes of this block roughly align with the shortcode parameters. 

The only exception is `profile_fields`. `profile_fields` is a list of fields (similar to `fields_applied`) to include in the user profile that will display if the user enables the profile link option in the shortcode. Read more about the functionality of the profile link in the profile link section.

The chart below describes how the block attributes, settings, and shortcode parameters relate to eachother.

Attribute        | Type      | Settings | Shortcode parameter
-----------------|-----------|----------|---------------------
`fields_applied` | `array`   | Checkboxes in 3 categories<ul><li>System Fields</li><li>Common Fields</li><li>Member Fields</li></ul> | List of fields
`enable_search`  | `boolean` | "Enable Search" toggle | `search`
`page_size`      | `number`  | "Page Size" input box | `page-size`
`saved_search`   | `number`  | "Filters" dropdown | `saved-search`
`profile_link`   | `boolean` | "Profile Link" toggle | `profile`
`profile_fields` | `array`   | "User Profile Fields" checkboxes | N/A
`hide_restricted_fields` | `boolean` | "Hide Restricted Fields" toggle | `hide_restricted_fields`

All the settings for the member directory are controlled by the `FieldControls` class defined in `FilterControls.js` There is a separate component for each attribute with the exception of the contact fields.

When the user enters the block editor, each attribute is parsed from its HTML source in the block content. This means its value is extracted from the HTML element with the specified selector.

For example,
```js
enable_search: {
    type: 'boolean',
    default: false,
    selector: '#enable_search',
    attribute: 'data-search-enabled'
},
```
For the `enable_search` attribute, its value will be extracted from the element with the `enable_search` ID and the value will come from the `data-search-enabled` attribute in that element. For more information on attribute sources, read [here](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/).

#### Rendering the block
The block has two distinct parts: hidden fields and the shortcode. 

##### Hidden fields
Hidden fields are used to keep track of the user profile fields (which aren't rendered in the shortcode) and rendering the fields used for the directory so they can be extracted when the block is parsed.

##### Shortcode
The shortcode is where the actual directory will be rendered. The shortcode string is simply concatenated with the block attributes for the arguments.

#### Functionality controlled by external JavaScript
Since there is some functionality of the block that needs to happen on the client side, meaning it is reactive to the user and it needs to happen after the block renders, there are some additional JavaScript files in `js/` serving that functionality.

##### Search
Users can search through directories when the search setting is enabled. This will cause a search box to be rendered in the shortcode.

The actual functionality for that search is controlled in `js/wafw.js`. This code will wait for the user to submit a search, and this could be any query or keyword, then uses the REST endpoint to search the WA database with the user's query. 

The result of the search will then display in place of the directory content.

##### Pagination
Since there could potentially be a large list of contacts to display in the directory, it is paginated by a set page size. As mentioned previously, the page size is first determined in the block settings, then it is passed in to the shortcode. The shortcode then enqueues the pagination scripts and sends the page size value to the scripts using `wp_localize_script`. 

The files controlling pagination are `pagination.js` and `pagination.min.js`, which is a minified version of the jQuery plugin [pagination.js](http://pagination.js.org). `pagination.js` simply identifies the objects to be paginated (all the contacts) and passes it in along with the page size value to the pagination library function.

##### Profile link
The profile link can be enabled through the block settings. Enabling this setting will cause a "link" to an individual member profile to be rendered in each member profile. 

`js/profiles.js` controls the functionality for the profile link.

Clicking this "link" will cause the member directory content to be replaced with the profile shortcode for the profile on which the link was clicked.

In order to obtain the profile shortcode quickly and dynamically, it is served by the REST API. The `profiles.js` code simply passes in the necessary parameters for the profile shortcode (fields, user ID, hide restricted fields toggle) via URL queries in the REST endpoint.

### Member profile

All files for the member profile block are located under 

#### Attributes and block settings

The setup of the member profile attributes is similar to the member directory in that they mirror the shortcode arguments. 

The chart below describes how the attributes, block settings, and shortcode arguments relate to eachother.

Attribute        | Type      | Settings | Shortcode parameter
-----------------|-----------|----------|---------------------
`fields_applied` | `array`   | Checkboxes in 3 categories<ul><li>System Fields</li><li>Common Fields</li><li>Member Fields</li></ul> | List of fields
`user_id`        | `string`  | Input box | `user-id`
`hide_restricted_fields` | `boolean` | "Hide Restricted Fields" toggle | `hide_restricted_fields`

Like the member directory, the field settings display in three separate categories: System Fields, Common Fields, and Member Fields.

The member profile attributes also use the block HTML as a data source.

#### Rendering the block
Like the attributes, rendering the profile block works extrememly similar to the member directory. 

The block content includes hidden fields from which the attributes data is extracted. It also includes the profile shortcode with the attributes data plugged in. The profile shortcode will render the profile itself.

### Styles
Custom styling for each block is located in the `scss` files in the block directories. `style.scss` styles the rendered block and `editor.scss` styles the block in the editor.

## How to use (from a developer's POV)

Also refer to `README.md` for a basic walkthrough and screenshots of how to use the plugin.

1. Install and activate
    * If the WA API credentials and/or license are missing/invalid, the plugin will not activate and display an admin notice prompting the user to add their missing credentials.
    * If WAP is not installed and active, the plugin will not activate and display an admin notice prompting the user to install and activate WAP.
2. Find the blocks
    * To use the blocks, open the post/page editor in **block mode**. The blocks do not work in classic mode.
    * Both blocks can be found in the widgets category, or by searching "WAP", or by typing `/wap` in the post content area and choosing one of the suggested options.
3. Configure block settings
    * As described in previous sections, all the options for the blocks are in the block settings.
    * Expand the fields dropdowns to select which fields to include in the directory/profile, toggle search, page size, etc.
    * Enter User ID for the profile block.
4. View the blocks
    * Save and view the post/page to view the rendered blocks.
    * The directory will simply display as a grid, with each member in their own cell. Each cell contains the field information checked off in the settings
    * The profile will display the field title and values separated by tabs. 
5. Member directory functionality
    * On the directory, you can scroll through the pages and search for contacts (if search is enabled)
    * If the profile link is enabled, there will be a link to each member's profile on their cell. Clicking on the link will replace the directory content with the respective profile content, exactly how it looks in the profile block with the addition of a "back" arrow, which will display the directory again when clicked.
