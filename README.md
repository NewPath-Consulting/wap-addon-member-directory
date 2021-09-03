# WAP Member Directory Add-on
This plugin contains a member directory block (`wa-contacts`) which displays many member profiles with search options and a member profile block (`wa-profile`), which displays a single member profile each with customizable fields. The code for each block resides in respective folders in `blocks/`.

# Installation
To install this add-on, you must have installed [Wild Apricot Press] (https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md ahead of time. You will also need to obtain a license key, at the [NewPath website](https://newpathconsulting.com/wawp) site, to use fir this add-on. 

The add-on, zip archive, can be installed using: Plugins -> Add New. Once installed, go to the [WAP License Key screen](https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md#licensing-wawp) > (WAP Settings > Licensing), to add your WAP license key. Free add-ons, such as: Member Directory, use the same license key, as the core WAP plugin.

# Usage

## Using the block in the block editor

The WAP membership directory block is accessed in the block editor, like any other block. You can also use a [slash (/) command](https://wordpress.org/support/article/adding-a-new-block/#slash-command) when you're in a block, in the URL field. Type / when in a block and type 'wa' or 'wild apricot' and the relevenat blocks will appear.

<img width="207" alt="add profile block" src="https://user-images.githubusercontent.com/458134/131161812-ebc09bed-c157-4550-8cd4-170a482c96b3.png">

## Configuring a WAP Member Directory block

To configure a member directory, you can select system fields, common fields and membership fields, from your Wild Apricot database. These will be included in the directory.

### Block Options

Click the Block options and select the fields to include, under System Fields, Common Fields and Member Fields.

### Filters

The Filters drop-down will enable you to use a "Saved Search" in Wild Apricot to create a member directory, connected to a search, that will always reflect the latest membership data. When the "Saved Search" results changes, so does the member directory, using this search.

IMPORTANT: If you want to maintain member privacy, do not publicize the criteria of a saved search. As in Wild Apricot, members appear in saved searchs, regardless of the field used, and filtered, in the public. An example of how this could expose sensitive member information, would be, if there was a field "Number of Guns Owned", and a filter for "Guns > 0" was published on a page, that indicated that phrase, the filter, even if a user made this information accessible to admin only, would now be exposed to the public.

### Enable Search

You can also enable a search box to enable a quick search of the directory to front-end users.

### Profile Link

To allow a visitor to click to a more detailed profile of a member, select the profile link toggle. Use the "User Profile" fields, drop-down menu, to select which fields you would like to show, in the single member profile.

### Page Size

The page size option can be used to restrict how many members are shown on one page. A page interface control menu, will appear at the bottom of the block, to show more members.

### Hide Restricted Fields

This toggle will allow users to only view content respective to their levels, ie member information can only be viewed by members. This is controlled by user and global privacy settings from Wild Apricot. For security, admin only information cannot be viewed through this block, please access this directly through Wild Apricot. 
<img width="953" alt="member profile block configuration options" src="https://user-images.githubusercontent.com/458134/131162876-cb02ccf9-6921-4c06-bd89-d59025bb03bb.png">


## Member Directory Shortcode

The `wa-contact` shortcode is built dynamically, based on the options selected under the block.

The below syntax is built automatically by the block, but it can be included manually, into any block or content area in WordPress.

`[wa-contacts <database fields to include>  page-size=<number of records to show> saved-search=<saved search ID> profile hide_restricted_fields]
[/wa-contacts]`

The `profile` and `hide_restricted_fields` can be used to turn on these options.


# Configuring the WAP Member Profile Block

To configure a member profile block, select system fields, common fields and membership fields, from your Wild Apricot database. These will be included in the single profile.

### Block Options
Click the Block options and select the fields to include under "System Fields", "Common Fields" and "Member Fields".

<img width="953" alt="member profile block configuration options" src="https://user-images.githubusercontent.com/458134/131164172-dfeb2a2d-e328-4c7a-894d-35fd004ec1c0.png">


### Hide Restricted Fields

This toggle will only allow users to view content, respective to their levels, ie member information can only be viewed by members. This is controlled by user and global privacy settings from Wild Apricot. For security, admin only information cannot be viewed through this block, please access this directly through Wild Apricot. 

## Member Profile Shortcode

The `wa-profile` shortcode is built dynamically based on the options selected under block.

The below syntax is built automatically by the block but it can be included manually into any block or content area in WordPress.

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

The whole profile will be in a class called `wa-profile`.

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
