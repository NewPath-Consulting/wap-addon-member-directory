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
import { useState } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { props } from 'bluebird';

const toggleCheckbox = (item) => {
	var array = props.attributes.fields_applied;
	var isChecked = array.includes(item);
	if (isChecked) {
		let index = array.indexOf(property);
		array.splice(index, 1);
	} else {
		array.push(item);
	}

	props.setAttributes({fields_applied: array});
}

const MyCheckboxControl = (property) => {
	const isChecked = props.attributes.fields_applied.includes(property);
	return (
		<CheckboxControl
		label={ property }
		checked={ isChecked }
		onChange={ toggleCheckbox(property) }
		/>
	);
};

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
				<PanelBody title="Filter Members" initialOpen={ true }>
					<PanelRow>
						{ attributes.fields.array.forEach(element => {
							MyCheckboxControl(element);
						}) }
						{/* { MyCheckboxControl() } */}
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps.save()} >
				{/* placeholder for editing */}
				<p>Wild Apricot Member Directory</p>
			</div>
		</div>
	);
}
