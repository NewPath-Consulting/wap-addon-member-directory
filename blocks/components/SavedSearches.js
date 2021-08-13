const getSavedSearches = async () => {
	const CF_API_URL = "/wp-json/wawp/v1/savedsearches/";

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

export class SavedSearches {
    constructor() {
        this.data = [];
        this.populateSavedSearches();
    }

    populateSavedSearches() {
        getSavedSearches().then((data) => {
            this.data = data;
        });
    }

    getSearchOptions() {
        var options = [];

        options.push(SavedSearches.getNullSearchOption());

        this.data.forEach((search) => {
            options.push({label: search.Name, value: search.Id});
        });

        return options;
    }

    getFirstSearchOption() {
        var opt = this.data[0]
        return { label: opt.Name, value: opt.Id };
    }

    static getNullSearchOption() {
        return {
            value: 0,
            label: 'Select a saved search'
        };
    }
}

const savedSearches = new SavedSearches();

export { savedSearches };