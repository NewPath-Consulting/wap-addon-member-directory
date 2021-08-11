// TODO: get filter data here
const getContactFields = async () => {
	const CF_API_URL = "/wp-json/wawp/v1/contacts/fields/";

	const resp = await fetch(CF_API_URL, {
        headers : { 
            'Content-Type': 'application/json',
            'Accept': 'application/json'
           }
    });
    const data = await resp.text();
    var result_1 = JSON.parse(data);
    return result_1;
} 

class ContactFields {
    constructor() {
        this.init();
    }

    init() {
        if (this.data == undefined) {
            this.data = [];
            this.system = [];
            this.member = [];
            this.common = [];
        }
        if (this.data.length == 0) {
            this.populateFieldData();
        }
    }

    async populateFieldData() {
        const data = await getContactFields();
        data.forEach((field) => {
            let cat = '';
            if (field.IsSystem) {
                cat = 'system';
                this.system.push({ id: field.SystemCode, name: field.FieldName });
            } else if (field.MemberOnly) {
                cat = 'member';
                this.member.push({ id: field.SystemCode, name: field.FieldName });
            } else {
                cat = 'common';
                this.common.push({ id: field.SystemCode, name: field.FieldName });
            }
            this.data[field.SystemCode] = {
                name: field.FieldName,
                type: field.Type,
                allowed_values: field.AllowedValues,
                access: field.Access,
                category: cat,
                support_search: field.SupportSearch,
                order: field.Order,
                system_code: field.SystemCode
            };
        });
    }

    async getFieldName(system_code) {
        if (this.data[system_code] == undefined) {
            this.populateFieldData().then(() => {
                return this.data[system_code].name;
            });
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