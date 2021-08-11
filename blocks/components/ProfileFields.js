import React from "react";
import { Field } from "./Field";
import { PanelBody } from "@wordpress/components";

export class ProfileFields extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: props.data,
            attributes: props.attributes,
            setAttributes: props.setAttributes
        };
    }

    render() {
        return(
            <PanelBody title="User Profile Fields" initialOpen={ false }>
            {
                this.state.data.map((field) => {
                    return (
                        <Field
                            attributes={this.state.attributes}
                            setAttributes={this.state.setAttributes}
                            field={field}
                            profile={true}
                        >
                        </Field>
                    )
                })
            }
            </PanelBody>
        );
    }
}