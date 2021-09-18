=== WAP Member Directory Add-on ===
Contributors: asirota
Tags: wildapricot, sso, membership
Requires at least: 5.7
Tested up to: 5.7
Requires PHP: 7.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WAP Member Directory Add-on enables WordPress websites to render native member directories and member profiles from Wild Apricot.

# WAP Member Directory Add-on
This plugin contains a member directory block (`wa-contacts`) which displays many member profiles with search options and a member profile block (`wa-profile`), which displays a single member profile each with customizable fields. The code for each block resides in respective folders in `blocks/`.

# Installation
To install this add-on, you must install [Wild Apricot Press](https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md) first. You will also need to obtain a license key, at the [NewPath website](https://newpathconsulting.com/wap), to enable this add-on. 

The add-on can be installed using the standard plugin installation procedure. Go to Plugins -> Add New and upload the plugin archive file. Once installed, go to the [WAP License Key screen](https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md#licensing-wawp) > (WAP Settings > Licensing), to add your WAP license key. Free add-ons, such as the Member Directory, use the same license key, as the core WAP plugin.

# Usage

## Using the block in the block editor

The WAP membership directory block is accessed in the block editor, like any other block. You can also use a [slash (/) command](https://wordpress.org/support/article/adding-a-new-block/#slash-command) when you're in a block, in the URL field. Type / when in a block and type 'wap' or 'wild apricot' and the relevant blocks will appear.

![wap blocks](https://user-images.githubusercontent.com/458134/132218267-279747ca-7d16-4cae-8bf1-9b357e7938dd.jpg)

## Configuring a WAP Member Directory block

To configure a member directory, you can select system fields, common fields and membership fields, from your Wild Apricot database. These will be included in the member directory.

### Block Options

Click the Block options and select the fields to include, under System Fields, Common Fields and Member Fields. Click the down arrow to show the available fields that can be selected for the membership directory block.

![wap member directory membership fields](https://user-images.githubusercontent.com/458134/132218960-0fc68691-074c-46ff-9fd6-3b4d765a6a06.jpg)

### Filters

The Filters drop-down will enable you to use a previously created "Saved Search" from Wild Apricot. The saved search will filter and create a member directory that will always reflect the latest membership data based on your search criteria. When the "Saved Search" results changes, so does the member directory block that uses this search.

![wap saved search](https://user-images.githubusercontent.com/458134/132219102-408453b9-9b74-4373-9ee8-182f872a452e.jpg)

IMPORTANT: If you want to maintain member privacy, do not publicize the criteria of a saved search. As in Wild Apricot, members will appear in a saved searchs, regardless of the field used for the filter. An example of how publicizing the saved search criterial could expose sensitive member information: if there is a field "Number of Guns Owned", and a filter for "Guns > 0" was used for a member directory, and the saved search was publicized, that would expose the members who pass this criteria even if the Number of Guns Owned field was not in the member directory layout.

### Enable Search

You can also enable a quick search of the member directory to visitors. All the fields in the member directory will be searched. Any fields *not* included in the direcory will not be searchable.

![wap enable search](https://user-images.githubusercontent.com/458134/132219282-cafe9377-0e6b-43df-9b54-d2ef0996a1ee.jpg)

### Profile Link

Enabling a user profile link will show a link from each profile to a more detailed detailed, individual profile of a member. This option uses the [`wa-profile` shortcode](https://github.com/NewPath-Consulting/wawp-addon-member-directory/blob/master/README.md#configuring-the-wap-member-profile-block).  Use the "User Profile" fields section to select which fields you would like to show in the single member profile.

![wap user profile fields](https://user-images.githubusercontent.com/458134/132219772-7cf515b3-275b-4249-978c-2b1c5af5d89a.jpg)

### Page Size

The page size option can be used to restrict how many members are shown on one page of a member directory block. Once enabled a set of page indictators will appear under the member directory to allow a visitor to page through the membership directory.

![wap page size](https://user-images.githubusercontent.com/458134/132219903-f2567257-7cfc-41f8-9f6d-1cbd99b06bc9.jpg)

### Hide Restricted Fields

This toggle will hide any privacy-restriced fields from showing in the member directory (ie member information can only be viewed by members). This is controlled by contact and global privacy settings in Wild Apricot. For security, fields that are set to "admin only" cannot be viewed in the member directory block.

![wap hide restricted fields](https://user-images.githubusercontent.com/458134/132220147-a9eab400-2d84-4eaf-8fdb-1e8252060a97.jpg)

## Member Directory Shortcode

The `wa-contacts` shortcode is built dynamically, based on the options selected under the block.

The below syntax is built automatically by the block, but it can be included manually, into any block or content area in WordPress.

`[wa-contacts <database fields to include>  page-size=<number of records to show> search saved-search=<saved search ID> profile hide_restricted_fields]
[/wa-contacts]`

The `profile`, `search` and `hide_restricted_fields` can be used to turn on respective block options.


## Member Profile Shortcode

The WAP Member Profile block builds the `wa-profile` shortcode based on the Block options.

![wap member profile](https://user-images.githubusercontent.com/458134/132220887-6718fa24-51e9-4339-954b-92e7cd415832.jpg)

The options allow the selection of any system, common or membership fields to show in the single profile. A unique Wild Apricot User ID must be filled out to identify which contact or member is displayed in this block.

![WAP Profile Block Options 2](https://user-images.githubusercontent.com/458134/132221415-e58afaf7-7701-4682-b69a-567f50786ab8.jpg)

The shortcode syntax is built into the block based on options chosen in  Block, but it can be included manually into any block or content area in WordPress.

`[wa-profile <database fields> user-id=<Wild Apricot UserID> hide_restricted_fields]`

The `hide_restricted_fields` can be used to turn on this option.


## Customizing the CSS

Each element in the member directory and member profile has a unique CSS class inserted which can be customized in the CSS style sheet.

### Member Directory IDs and Classes

The member directory will be in a class called `wp-block-wawp-member-addons-member-directory`.

All hidden fields will be in a class called `hidden`.

The following IDs contain the various block options:

```
<div id="enable_search" data-search-enabled="false"></div>

<div id="page_size" data-page-size="1"></div>

<div id="saved_search" data-saved-search="0"></div>

<div id="profile_link" data-profile-link="true"></div>

<div id="hide_restricted_fields" data-hide-restricted-fields="false"></div>
```

All contacts will be paginated in the class `wa-contacts`. The pagination will be contained in a class called `wa-pagination`. Each page of the pagination is in a class called `wa-pagination-page`. Each contact or member is in a class called `wa-contact`. Each field value will use the class of the field name as well as a custom attribute called `wa-data-label` with the name of the field as the value.

Here is an example of a `wa-contact` element which contains one member and the View profile link.

```
<div class="wa-contact">
<div class="donor" data-wa-label="Donor">
<div class="my-first-name" data-wa-label="My First name">Carol</div>
<div class="email" data-wa-label="Email">carol@newpathconsulting.com</div>
<div class="wa-profile-link" id="profile-link" data-user-id="49286448">View profile</div>
</div>
```

### Member Profile IDs and Classes

The member profile will be rendered using a class called `wa-profile`.

Each field will have an ID using the same name of the field. For example here is a profile with just the `City` field, and the value `Toronto`:

```
<div id="city" class="field">

<span class="field-name">City</span>

<span class="city field-value" data-wa-label="City">Toronto</span>
</div>
```

The row contains the ID city and a class field. Each element of the row has the `field-name` class and the value has a class `fieldname field-value`. A custom attribute `data-wa-label` is included for convenience.

## Developer Notes

1. Run `npm install` in the root directory of this repo.
2. To build the blocks in development mode, enter the command `npm run start:member-directory` for the directory block and `npm run start:member-profile` for the profile block.
3. To build the blocks in production mode, enter the command `npm run build:member-directory` for the directory block and `npm run build:member-profile` for the profile block.
