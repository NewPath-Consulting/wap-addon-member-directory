import { Panel, PanelBody, ToggleControl } from '@wordpress/components';
import { __experimentalText as Text } from '@wordpress/components';
import Field from './Filter';
import contactFields from '../ContactFields';

export default class FilterControls extends React.Component {
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
                                >
                                </Field>
                            )
                        })
                    }
                </PanelBody>
                <PanelBody title="Filters" initialOpen={ false }>
                {
                    this.state.attributes.fields_applied.map((field) => {
                        return <Text isBlock>{ contactFields.getFieldName(field.id) }</Text>;
                    })
                }
                </PanelBody>
            </Panel>
        );
    }
}

class SearchControls extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        <PanelBody title="Enable Search" initialOpen={ true }>
            <PanelRow>
                <Toggole
            </PanelRow>
        </PanelBody>
    }
}