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
import contactFields from './ContactFields';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( 'create-block/wawp-addon-member-directory', {
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
				}
			}
		},
		enable_search: {
			type: 'boolean',
			default: false
		},
		page_size: {
			type: 'number',
			default: 10
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

export default function generateShortcode(attributes) {
	var shortcode_str = '[wa-contacts ';

	var len = attributes.fields_applied.length;

	for (let i = 0; i < len; i++) {
		let id = attributes.fields_applied[i].id;
		shortcode_str += '\'' + contactFields.getFieldName(id) + '\'';
		shortcode_str += ' ';
	}

	shortcode_str += 'page-size=' + attributes.page_size;

	if (attributes.enable_search) {
		shortcode_str += ' search';
	}

	shortcode_str += "] [/wa-contacts]";

	return shortcode_str;
}