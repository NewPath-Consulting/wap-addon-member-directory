/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, CheckboxControl } from '@wordpress/components';
import { __experimentalText as Text } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { getContactFields } from './index';
import FilterControls from './components/FilterControls';
import contactFields from './ContactFields';
import generateShortcode  from './index';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { props } from 'bluebird';
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
	return (
		<div { ...useBlockProps() }>
			<InspectorControls key="setting">
				<FilterControls attributes={attributes} setAttributes={setAttributes}></FilterControls>
			</InspectorControls>
			<div {...useBlockProps.save()} >
				<p>Wild Apricot Member Directory</p>
					<ul>
						{attributes.fields_applied.map((field) => {
							// console.debug(field);
							return (
								<li className="filter" key={ field.id } data-system-code={ field.id }>
									{ contactFields.getFieldName(field.id) }
								</li>
							)
						})}
					</ul>
					<p>
						{ generateShortcode(attributes) }
					</p>
			</div>
		</div>
	);
}
