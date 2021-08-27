# WAP Member Directory Addon
This plugin contains a member directory block (`wa-contacts`) and a member profile block (`wa-profile`). The code for each block resides in respective folders in `blocks/`.

# Installation
To install this add on you must have installed [Wild Apricot Press](https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md) ahead of time. You also will need to use a license key to add this add on. License keys can be obtained on the [NewPath website](https://newpathconsulting.com/wawp).

The addon zip archive can be installed using Plugins -> Add New. Once installed, you should go to the [WAP License Key screen](https://github.com/NewPath-Consulting/Wild-Apricot-For-Wordpress/blob/master/README.md#licensing-wawp) to add your WAP license key. Free addons like Member Directory use the same license key as WAP.

# Usage

## Using the block in the block editor

The WAP membership directory block is accessed in the block editor like any other block. You can also use a [slash (/) command](https://wordpress.org/support/article/adding-a-new-block/#slash-command) when youre in a block. Type / when in a block and type 'wa' or 'wild apricot' and the relevenat blocks will appear.

<img width="207" alt="add profile block" src="https://user-images.githubusercontent.com/458134/131161812-ebc09bed-c157-4550-8cd4-170a482c96b3.png">


## Configuring a WAP Member Directory block

To configure a member directory you can select system fields, common fields and membership fields from your Wild Apricot database. These will be included in the directory.

### Block Options
Click the Block options and select the fields to include under System Fields, Common Fields and Member Fields.

### Filters

The Filters drop down will enable you to use a Saved Search in Wild Apricot to create a member directory connected to search that always reflects the latest membership data. When the Saved Search results change, so does the member directory using this search.

### Enable Search

You can also enable a search box to enable a quick search of the directory.

### Profile Link
To allow a visitor to click to a longer profile of a member select the profile link toggle. Use the User Profile Fields drop down to select which fields you would like to show in the single member profile.

### Page Size

The page size option can be used to restrict how many members show on one page. A paging user interface control will appear to show more members.

### Hide Restricted Fields

This toggle will hide any restricted fields that are not available to the public or members using the privacy features of Wild Apricot.

<img width="953" alt="member profile block configuration options" src="https://user-images.githubusercontent.com/458134/131162876-cb02ccf9-6921-4c06-bd89-d59025bb03bb.png">


## Member Directory Shortcode

The `wa-contact` shortcode is built dynamically based on the options selected under block.

The below syntax is built automatically by the block but it can be included manually into any block or content area in WordPress.

`[wa-contacts <database fields to include>  page-size=<number of records to show> saved-search=<saved search ID> profile hide_restricted_fields]
[/wa-contacts]`

The `profile` and `hide_restricted_fields` can be used to turn on these options.


## Profile Short Code

## Customizing the CSS

## Developer Notes

1. Run `npm install` in the root directory of this repo.
2. To build the blocks in development mode, enter the command `npm run start:member-directory` for the directory block and `npm run start:member-profile` for the profile block.
3. To build the blocks in production mode, enter the command `npm run build:member-directory` for the directory block and `npm run build:member-profile` for the profile block.
