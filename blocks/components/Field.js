import { PanelRow, CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

export class Field extends React.Component {
    constructor(props) {
        super(props);
        this.state = ({
            attributes: props.attributes,
            setAttributes: props.setAttributes,
            field: props.field, // field must be in the format {id: SystemCode, name: Name}
            profile: props.profile
        });
    }

    render() {
        return(
            <PanelRow>
                <FieldCheckbox 
                    field={this.state.field}
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                    profile={this.state.profile}
                    key={this.state.field.name}
                >
                </FieldCheckbox>
            </PanelRow>
        );
    }
}

function FieldCheckbox(props) {
    let arr = [];
    if (props.profile) {
        arr = props.attributes.profile_fields;
    } else {
        arr = props.attributes.fields_applied;
    }
    
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

        if (props.profile) {
            props.setAttributes({profile_fields: arr});
        } else {
            props.setAttributes({fields_applied: arr});
        }
        
    });
    
    return (
        <CheckboxControl
            label={ props.field.name }
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