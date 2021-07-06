import { PanelRow, CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import contactFields from '../ContactFields';

export default class Field extends React.Component {
    constructor(props) {
        super(props);
        this.state = ({
            attributes: props.attributes,
            setAttributes: props.setAttributes,
            field: props.field
        });
    }

    render() {
        return(
            <PanelRow>
                <FieldCheckbox 
                    field={this.state.field}
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                >
                </FieldCheckbox>
            </PanelRow>
        );
    }
}

// export default class Filter extends React.Component {
//     constructor(props) {
//         super(props);
//         this.state = ({
//             attributes: props.attributes,
//             setAttributes: props.setAttributes,
//             field: props.field,
//             type: props.field.type,
//             allowed_values: props.field.allowed_values
//         });
//     }

//     render() {
//         if (this.state.type == 'Boolean') {
//             return(

//             )
//         }
//     }
// }

function FieldCheckbox(props) {
    let setAttr = props.setAttributes;
    let arr = props.attributes.fields_applied
    let exists = contains(arr, props.field) == -1 ? false : true;

    const [ isChecked, setChecked ] = useState(exists);

    useEffect(() => {
        let in_array = contains(arr, props.field);
        // if the item is checked but not in the field array, add it
        if (isChecked && in_array == -1) {
            arr.push(props.field);
            // add it to fields applied
        } else if (!isChecked && in_array != -1) {
            arr.splice(in_array, 1);
            // remove it
        }

        setAttr({fields_applied: arr});
        console.log(props.attributes.fields_applied)
    });
    
    return (
        <CheckboxControl
            label={ contactFields.getFieldName(props.field.id) }
            checked={ isChecked }
            onChange={ setChecked }
        >
        </CheckboxControl>
    );
}

function contains(array, item) {
    for (var i = 0; i < array.length; i++) {
        if (array[i].id == item.id) {
            return i;
        }
    }

    return -1;
}