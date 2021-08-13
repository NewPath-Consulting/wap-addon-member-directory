/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( 'wawp-member-addons/member-directory', {
	attributes: {
		fields_applied: { // holds the fields applied by the user to pass into the shortcode
			type: 'array',
			default: [],
			source: 'query',
			selector: 'li.filter',
			query: {
				id: {
					type: 'string',
					source: 'attribute',
					attribute: 'data-system-code'
				},
				name: {
					type: 'string',
					source: 'attribute',
					attribute: 'data-name'
				}
			}
		},
		enable_search: {
			type: 'boolean',
			default: false,
			selector: '#enable_search',
			attribute: 'data-search-enabled'
		},
		page_size: {
			type: 'number',
			default: 10,
			selector: '#page_size',
			attribute: 'data-page-size'
		},
		saved_search: {
			type: 'number',
			default: 0,
			selector: '#saved_search',
			attribute: 'data-saved-search'
		},
		profile_link: {
			type: 'boolean',
			default: false,
			selector: '#profile_link',
			attribute: 'data-profile-link'
		},
		profile_fields: {
			type: 'array',
			default: [],
			source: 'query',
			selector: '.profile-field',
			query: {
				id: {
					type: 'string',
					source: 'attribute',
					attribute: 'data-system-code'
				},
				name: {
					type: 'string',
					source: 'attribute',
					attribute: 'data-name'
				}				
			}
		},
		hide_restricted_fields: {
			type: 'boolean',
			default: true,
			selector: '#hide_restricted_fields',
			attribute: 'data-hide-restricted-fields'
		}
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save,
} );

export function renderHiddenFields(attributes) {
	return (
		<div className="hidden">	
			<ul>
			{
				attributes.fields_applied.map((field) => {
					return (
						<li className="filter" key={ field.id } data-system-code={ field.id } data-name={ field.name }>
						{ field.name }
						</li>
					);
				})
			}
			</ul>
			<ul>
			{
				attributes.profile_fields.map((field) => {
					return (
						<li className="profile-field" key={field.id} data-system-code={ field.id } data-name={ field.name }>
							{ field.name }
						</li>
					);
				})
			}
			</ul>
			<div id="enable_search" data-search-enabled={attributes.enable_search ? "true" : "false"}></div>
			<div id="page_size" data-page-size={attributes.page_size}></div>
			<div id="saved_search" data-saved-search={attributes.saved_search}></div>
			<div id="profile_link" data-profile-link={attributes.profile_link}></div>
			<div id="hide_restricted_fields" data-hide-restricted-fields={attributes.hide_restricted_fields}>{ attributes.hide_restricted_fields }</div>
		</div>
	);
}

export function generateShortcode(attributes) {
	var shortcode_str = '[wa-contacts ';

	var len = attributes.fields_applied.length;

	for (let i = 0; i < len; i++) {
		shortcode_str += '\'' + attributes.fields_applied[i].name + '\'';
		shortcode_str += ' ';
	}

	shortcode_str += 'page-size=' + attributes.page_size;

	if (attributes.enable_search) {
		shortcode_str += ' search';
	}

	let search = attributes.saved_search;
	if (search != 0 && search != undefined) {
		shortcode_str += ' saved-search=' + attributes.saved_search;
	}

	if (attributes.profile_link) {
		shortcode_str += ' profile';
	}
	
	if (attributes.hide_restricted_fields) {
		shortcode_str += ' hide_restricted_fields';
	}

	shortcode_str += "] [/wa-contacts]";

	return <p>{shortcode_str}</p>;
}