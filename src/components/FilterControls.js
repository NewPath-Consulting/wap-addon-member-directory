import { Panel, PanelBody } from '@wordpress/components';
import { __experimentalText as Text } from '@wordpress/components';
import { Fragment } from 'react';
import Filter from './Filter';

// TODO: get filter data here
const getContactFields = () => {
	const CF_API_URL = "/wp-json/wawp/v1/contacts/fields/";

	return fetch(CF_API_URL)
		.then((resp) => {
			return resp.text();
		})
		.then((data) => {
			// console.log(data);
			var result = JSON.parse(data);
			// console.log(result);
			return result;
		});
} 

let system_fields = [];
let common_fields = [];
let member_fields = [];

getContactFields().then((e) => {
    e.forEach((field) => {
        if (field.IsSystem) {
            system_fields.push({name: field.FieldName});
        } else if (field.MemberOnly) {
            member_fields.push({name: field.FieldName});
        } else {
            common_fields.push({name:field.FieldName});
        }
    });
});


export default class FilterControls extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            system: system_fields,
            common: common_fields,
            member: member_fields, 
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
                                <Filter 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field.name}
                                    key={field.name}
                                >
                                </Filter>
                            )
                        })
                    }
                </PanelBody>
                <PanelBody title="Common Fields" initialOpen={false}>
                {
                        this.state.common.map((field) => {
                            return (
                                <Filter 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field.name}
                                    key={field.name}
                                >
                                </Filter>
                            )
                        })
                    }
                </PanelBody>
                <PanelBody title="Member Fields" initialOpen={false}>
                {
                        this.state.member.map((field) => {
                            return (
                                <Filter 
                                    attributes={this.state.attributes} 
                                    setAttributes={this.state.setAttributes} 
                                    field={field.name}
                                    key={field.name}
                                >
                                </Filter>
                            )
                        })
                    }
                </PanelBody>
            </Panel>
        );
    }
}
