// TODO: get filter data here
const getContactFields = () => {
	const CF_API_URL = "/wp-json/wawp/v1/contacts/fields/";

	return fetch(CF_API_URL)
		.then((resp) => {
			return resp.text();
		})
		.then((data) => {
			var result = JSON.parse(data);
			return result;
		});
} 

class ContactFields {
    constructor() {
        this.data = [];
        this.system = [];
        this.member = [];
        this.common = [];
        this.populateFieldData();
    }

    populateFieldData() {
        getContactFields().then((e) => {
            e.forEach((field) => {
                let cat = '';
                if (field.IsSystem) {
                    cat = 'system';
                    this.system.push({id: field.SystemCode});
                } else if (field.MemberOnly) {
                    cat = 'member';
                    this.member.push({id: field.SystemCode});
                } else {
                    cat = 'common';
                    this.common.push({id: field.SystemCode});
                }
                this.data[field.SystemCode] = {
                    name: field.FieldName,
                    type: field.Type,
                    allowed_values: field.AllowedValues,
                    access: field.Access,
                    category: cat,
                    support_search: field.SupportSearch,
                    order: field.Order
                };
            });
        });
    }

    getFieldName(system_code) {
        // if (this.data[system_code] == undefined) {
        //     console.log("system code: ", system_code);
        //     console.log("typeof systemcode: ", typeof system_code);
        //     console.log("data: ", this.data);
        //     console.log("data size: ", this.data.length);
        // }
        if (this.data.length == 0 || this.data == null) {
            this.populateFieldData();
        }
        return this.data[system_code].name;
    }

    getSystemFields() {
        return this.system;
    }

    getCommonFields() {
        return this.common;
    }

    getMemberFields() {
        return this.member;
    }

    getData() {
        return this.data;
    }

    isMultiple(system_code) {
        return this.data[system_code].type == 'MultipleChoice';
    }


}

const contactFields = new ContactFields();

export default contactFields;