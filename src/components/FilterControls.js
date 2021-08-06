import { Panel, PanelBody, PanelRow, ToggleControl, SelectControl } from '@wordpress/components';
import { __experimentalText as Text } from '@wordpress/components';
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { Field, Filter } from './Filter';
import contactFields from '../ContactFields';
import {SavedSearches, savedSearches} from '../SavedSearches';
import { ProfileFields } from './ProfileFields';

export default class FieldControls extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            system: contactFields.getSystemFields(),
            common: contactFields.getCommonFields(),
            member: contactFields.getMemberFields(), 
            attributes: props.attributes,
            setAttributes: props.setAttributes
        };
    }
    render() {
        return (
            <Panel>
                <PanelBody title="System Fields" initialOpen={ false }>
                    {
                        this.state.system.map((field) => {
                            return (
                                <Field 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field}
                                    profile={false}
                                >
                                </Field>
                            )
                        })
                    }
                </PanelBody>
                <PanelBody title="Common Fields" initialOpen={ false }>
                {
                        this.state.common.map((field) => {
                            return (
                                <Field 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field}
                                    profile={false}
                                >
                                </Field>
                            )
                        })
                    }
                </PanelBody>
                <PanelBody title="Member Fields" initialOpen={ false }>
                    {
                        this.state.member.map((field) => {
                            return (
                                <Field 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field}
                                    profile={false}
                                >
                                </Field>
                            )
                        })
                    }
                </PanelBody>
                <SavedSearchControl 
                    attributes={this.state.attributes} 
                    setAttributes={this.state.setAttributes}>
                </SavedSearchControl>
                <SearchControl 
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                >
                </SearchControl>
                <ProfileLinkControl
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                ></ProfileLinkControl>
                <ProfileFields
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                    data={[...this.state.system, ...this.state.common, ...this.state.member]}
                ></ProfileFields>
                <PageSizeControl
                    attributes={this.state.attributes}
                    setAttributes={this.state.setAttributes}
                >
                </PageSizeControl>
            </Panel>
        );
    }
}

const SavedSearchControl = (props) => {
    const [ search, setSearch ] = useState(props.attributes.saved_search);

    return (
        <PanelBody title="Filters" initialOpen={ false }>
            <PanelRow>        
                <SelectControl 
                    label="Saved search"
                    value={ search }
                    options={ savedSearches.getSearchOptions() }
                    onChange={ (newSearch) => {
                        setSearch(newSearch);
                        props.setAttributes({saved_search: Number(newSearch)});
                    }}
                />
            </PanelRow>
        </PanelBody>
    )
}

function SearchControl(props) {
    let attributes = props.attributes;
    let setAttributes = props.setAttributes;

    const [ isChecked, setChecked ] = useState(attributes.enable_search);

    useEffect(() => {
        var new_val;
        if (isChecked && !attributes.enable_search) {
            new_val = true;
        } else if (!isChecked && attributes.enable_search) {
            new_val = false;
        }

        setAttributes({enable_search: new_val});
    });

    return (
        <PanelBody title="Enable Search" initialOpen={ true }>
            <PanelRow>
                <ToggleControl
                    label="Enable search"
                    checked={ isChecked }
                    onChange={ setChecked }
                >
                </ToggleControl>
            </PanelRow>
        </PanelBody>
    );
}

function ProfileLinkControl(props) {
    const [ isChecked, setChecked ] = useState(props.attributes.profile_link);

    useEffect(() => {
        props.setAttributes({profile_link: isChecked});
    });

    return (
        <PanelBody title="Profile Link" initialOpen={ true }>
            <PanelRow>
                <ToggleControl
                    label="Profile link"
                    checked={ isChecked }
                    onChange={ setChecked }
                ></ToggleControl>
            </PanelRow>
        </PanelBody>
    );
}

function PageSizeControl(props) {
    let attributes = props.attributes;
    let setAttributes = props.setAttributes;

    const [ value, setValue ] = useState(attributes.page_size);

    useEffect(() => {
        setAttributes({page_size: Number(value)});
    });

    return (
        <PanelBody title="Page Size" initialOpen={ false }>
            <PanelRow>
                <NumberControl
                    label="Number of members per page"
                    isShiftStepEnabled={ true }
                    onChange={ setValue }
                    shiftStep={ 5 }
                    value={ value }
                ></NumberControl>
            </PanelRow>
        </PanelBody>
    );
}