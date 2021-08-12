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

// attributes needed
// - fields
// the user id: enter it or grab it from the current logged in user
// if showing logged in user; do user-id=current and in php code:
// if user-id=current then grab the logged in id. else do nothing


/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( 'wawp-member-addons/member-profile', {
	attributes: {
		fields_applied: {
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
		user_id: { // add separate name and id attributes
			type: 'string',
			default: '',
			selector: '#user_id',
			attribute: 'data-user-id'
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
					return (<li className="filter" key={ field.id } data-system-code={ field.id } data-name={ field.name }>
					{ field.name }
					</li>)
				})
			}
			</ul>
			<div id="user_id" data-user-id={ attributes.user_id } ></div>
			<div id="hide_restricted_fields" data-hide-restricted-fields={attributes.hide_restricted_fields}>{ attributes.hide_restricted_fields }</div>
		</div>
	);
}

export function generateShortcode(attributes) {
	var shortcode_str = '[wa-profile ';

	var len = attributes.fields_applied.length;

	for (let i = 0; i < len; i++) {
		shortcode_str += '\'' + attributes.fields_applied[i].name + '\'';
		shortcode_str += ' ';
	}

	shortcode_str += ' user-id=' + attributes.user_id;

	if (attributes.hide_restricted_fields) {
		shortcode_str += ' hide_restricted_fields';
	}

	shortcode_str += ']';

	return <p>{shortcode_str}</p>
}