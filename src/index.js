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
registerBlockType( 'create-block/wawp-addon-member-directory', {
	attributes: {
		fields_applied: { // holds the fields applied by the user to pass into the shortcode
			type: 'array',
			default: []
		},
		fields: { // TODO: get these from WA API
			type: 'array',
			default: [
				'First name',
				'Last name',
				'Middle name',
				'Organization'
			]
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

export const getContactFields = () => {
	const CF_API_URL = "/wp-json/wawp/v1/contacts/fields/";

	fetch(CF_API_URL)
		.then((resp) => {
			return resp.text();
		})
		.then((data) => {
			console.log(data);
			// var result = JSON.parse(data);
			// console.log(result);
			// return result;
		});

} 