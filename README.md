=== NewPath WildApricotPress Add-on – Member Directory ===
Contributors: nataliebrotherton, asirota
Tags: wildapricot, wild apricot, membership, membership directory
Requires at least: 5.7
Tested up to: 6.0
Requires PHP: 7.4
Stable Tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

NewPath WildApricotPress Add-on – Member Directory enables WordPress websites to render native WildApricot member directories and member profiles, without using iframes!

== Description ==

This plugin contains a WildApricot-powered member directory block (`wa-contacts`) which displays many member profiles with search options and a member profile block (`wa-profile`), which displays a single member profile each with customizable fields.

To install this add-on, you must install [NewPath Wild Apricot Press](https://wordpress.org/plugins/newpath-wildapricot-press/) first. Visit the [NewPath WildApricot Press website](https://newpathconsulting.com/wap), to obtain a license key. This is a free add-on so you can use the same license key as NewPath WildApricot Press. Once you actviate this plugin, add a license key under WildApricot Press > Licensing.

## Features

- Configure all membership directory options in the block editor
- Support for common, membership and system fields
- Custom CSS can be applied or inherited from the theme
- All block functions are WildApricot API-powered

Check out the [FAQ section](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/faq) and [screenshots](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots) for more details.

## License
Please visit the [NewPath WildApricot Press website](https://newpathconsulting.com/wap/) to obtain your license key or to inquire further about the plugin!

== Installation ==

The NewPath WildApricot Press plugin license is available on [the NewPath WildApricot Press website](https://newpathconsulting.com/wap). Your license includes 2 free add-ons, the member directory and iframe widget blocks. Future commercials WAP add-ons that generate revenue for your organization will have a separate license fee.

To activate the plugin, enter your license key in **WildApricot Press > Licensing**. Once you enter your license key and click "Save", you're good to go!

== Frequently Asked Questions ==

= How do I add a membership directory? =

The NewPath WildApricotPress Add-on – Member Directory block is accessed in the block editor, like any other block. You can also use a [slash (/) command](https://wordpress.org/support/article/adding-a-new-block/#slash-command) when you're in a block, in the URL field. Type / when in a block and type 'wap' or 'wild apricot' and the relevant blocks will appear.

[Screenshot - adding a membership directory block](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)

= How do I configure a membership directory? =

To configure a member directory, you can select system fields, common fields and membership fields, from your Wild Apricot database. These will be included in the member directory.


> Block Options


Click the Block options and select the fields to include, under System Fields, Common Fields and Member Fields. Click the down arrow to show the available fields that can be selected for the membership directory block.

[Screenshot - Member directory membership fields](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)


> Filters


The Filters drop-down will enable you to use a previously created "Saved Search" from Wild Apricot. The saved search will filter and create a member directory that will always reflect the latest membership data based on your search criteria. When the "Saved Search" results changes, so does the member directory block that uses this search.

[Screenshot - Selecting a WildApricot Saved Search](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)

IMPORTANT: If you want to maintain member privacy, do not publicize the criteria of a saved search. As in Wild Apricot, members will appear in a saved searchs, regardless of the field used for the filter. An example of how publicizing the saved search criterial could expose sensitive member information: if there is a field "Number of Guns Owned", and a filter for "Guns > 0" was used for a member directory, and the saved search was publicized, that would expose the members who pass this criteria even if the Number of Guns Owned field was not in the member directory layout.


> Enable Search


You can also enable a quick search of the member directory to visitors. All the fields in the member directory will be searched. Any fields *not* included in the direcory will not be searchable.

[Screenshot - Enable search](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)


> Profile Link


Enabling a user profile link will show a link from each profile to a more detailed detailed, individual profile of a member. This option uses the `wa-profile` shortcode.  Use the "User Profile" fields section to select which fields you would like to show in the single member profile.

[Screenshot -  Selecting User profile fields](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)


> Page Size


The page size option can be used to restrict how many members are shown on one page of a member directory block. Once enabled a set of page indictators will appear under the member directory to allow a visitor to page through the membership directory.

[Screenshot - Selecting number of results per page]https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)


> Hide Restricted Fields


This toggle will hide any privacy-restriced fields from showing in the member directory (ie member information can only be viewed by members). This is controlled by contact and global privacy settings in Wild Apricot. For security, fields that are set to "admin only" cannot be viewed in the member directory block.

[Screenshot - Hide restricted fields](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)


= What shortcodes are supported? =

> Member Directory Shortcode

The `wa-contacts` shortcode is built dynamically, based on the options selected under the block.

The below syntax is built automatically by the block, but it can be included manually, into any block or content area in WordPress.

`[wa-contacts <database fields to include>  page-size=<number of records to show> search saved-search=<saved search ID> profile hide_restricted_fields]
[/wa-contacts]`

The `profile`, `search` and `hide_restricted_fields` can be used to turn on respective block options.


> Member Profile Shortcode

The WAP Member Profile block builds the `wa-profile` shortcode based on the Block options.

[Screenshot - Member profile](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)

The options allow the selection of any system, common or membership fields to show in the single profile. A unique Wild Apricot User ID must be filled out to identify which contact or member is displayed in this block.

[Screenshot - Members Profile Block Options](https://wordpress.org/plugins/newpath-wildapricotpress-add-on-member-directory/#screenshots)

The shortcode syntax is built into the block based on options chosen in  Block, but it can be included manually into any block or content area in WordPress.

`[wa-profile <database fields> user-id=<Wild Apricot UserID> hide_restricted_fields]`

The `hide_restricted_fields` can be used to turn on this option.

= How do I customize the CSS of the Member Directory? =

Each element in the member directory and member profile has a unique CSS class inserted which can be customized in the CSS style sheet.

> Member Directory IDs and Classes

The member directory will be in a class called `wp-block-wawp-member-addons-member-directory`.

All hidden fields will be in a class called `hidden`.

The following IDs contain the various block options:


`
<div id="enable_search" data-search-enabled="false"></div>
<div id="page_size" data-page-size="1"></div>
<div id="saved_search" data-saved-search="0"></div>
<div id="profile_link" data-profile-link="true"></div>
<div id="hide_restricted_fields" data-hide-restricted-fields="false"></div>
`


All contacts will be paginated in the class `wa-contacts`. The pagination will be contained in a class called `wa-pagination`. Each page of the pagination is in a class called `wa-pagination-page`. Each contact or member is in a class called `wa-contact`. Each field value will use the class of the field name as well as a custom attribute called `wa-data-label` with the name of the field as the value.

Here is an example of a `wa-contact` element which contains one member and the View profile link.

`
<div class="wa-contact">
<div class="donor" data-wa-label="Donor">
<div class="my-first-name" data-wa-label="My First name">Carol</div>
<div class="email" data-wa-label="Email">carol@newpathconsulting.com</div>
<div class="wa-profile-link" id="profile-link" data-user-id="49286448">View profile</div>
</div>
`

> Member Profile IDs and Classes

The member profile will be rendered using a class called `wa-profile`.

Each field will have an ID using the same name of the field. For example here is a profile with just the `City` field, and the value `Toronto`:

`
<div id="city" class="field">

<span class="field-name">City</span>

<span class="city field-value" data-wa-label="City">Toronto</span>

</div>
`

The row contains the ID city and a class field. Each element of the row has the `field-name` class and the value has a class `fieldname field-value`. A custom attribute `data-wa-label` is included for convenience.

== Screenshots ==

1. Adding a membership directory block
2. Member directory membership fields
3. Member profile
4. Members Profile Block Options
5. Using a Saved Search for a Member Directory
6. Enabling Searching the Member Directory
7. Turning on ability to link to a full member profile
8. Changing the maximum number of members per page
9. Enabling Hiding Restricted Fields

== Changelog ==

= August 9 2022 - 1.0.0 - first public release =

= June 30 2022 - 1.0b3 - fixed license code = 
