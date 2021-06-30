import { PanelRow, CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

export default class Filter extends React.Component {
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
                <FilterCheckbox 
                    field={this.state.field}
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                >
                </FilterCheckbox>
            </PanelRow>
        );
    }
}

function FilterCheckbox(props) {
    let setAttr = props.setAttributes;
    let arr = props.attributes.fields_applied
    let exists = contains(arr, props.field) == -1 ? false : true;

    const [ isChecked, setChecked ] = useState(exists);

    let field_obj = {name: props.field};

    useEffect(() => {
        let in_array = contains(arr, props.field);
        // if the item is checked but not in the field array, add it
        if (isChecked && in_array == -1) {
            arr.push(field_obj);
            // add it to fields applied
        } else if (!isChecked && in_array != -1) {
            // let idx = arr.indexOf(field_obj);
            arr.splice(in_array, 1);
            // remove it
        }

        setAttr({fields_applied: arr});
        console.log(props.attributes.fields_applied)
    });
    
    return (
        <CheckboxControl
            label={ props.field }
            checked={ isChecked }
            onChange={ setChecked }
        >
        </CheckboxControl>
    );
}

function contains(array, item) {
    for (var i = 0; i < array.length; i++) {
        if (array[i].name == item) {
            return i;
        }
    }

    return -1;
}