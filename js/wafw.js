const SEARCH_API_URL = "/wp-json/wafw/v1/contacts/search/";
let searchForms = document.querySelectorAll(".wa-contacts-search");

searchForms.forEach(searchForm => {
  let searchInput = searchForm["wa-contacts-search"];

  searchForm.addEventListener("submit", e => {
    document.body.style.cursor = "wait";
    e.preventDefault();
    e.target.submit.blur();

    const siteSelector = searchForm.parentElement.querySelector(".sites");

    let searchRequest = `${SEARCH_API_URL}?search-id=${
      searchForm.dataset.searchId
    }&q=${searchInput.value}`;

    if (siteSelector) {
      searchRequest = `${searchRequest}&site=${siteSelector.value}`;
    }


    fetch(searchRequest)
      .then(function(resp) {
        return resp.text();
      })
      .then(function(data) {
        const result = JSON.parse(data);
        const resultsContainer = searchForm.parentElement.querySelector(
          ".wa-contacts-items"
        );
        resultsContainer.innerHTML = result.results[0];
        document.body.style.cursor = "default";
        toggleSearchDisplay();
      });
  });
});

let sitesSelectors = document.querySelectorAll(".wa-contacts .sites");

sitesSelectors.forEach(sitesSelector => {
  const searchID = sitesSelector.parentElement.querySelector(
    ".wa-contacts-search"
  ).dataset.searchId;

  sitesSelector.addEventListener("change", e => {

    let searchRequest = `${SEARCH_API_URL}?search-id=${searchID}&site=${sitesSelector.value}`;
    let searchBox = sitesSelector.parentElement.querySelector('.wa-contacts-search');

    if (searchBox && searchBox['wa-contacts-search'].value) {
      searchRequest = `${searchRequest}&q=${searchBox['wa-contacts-search'].value}`;
    }

    fetch(searchRequest)
      .then(resp => {
        return resp.text();
      })
      .then(data => {
        const result = JSON.parse(data);
        const resultsContainer = sitesSelector.parentElement.querySelector(
          ".wa-contacts-items"
        );
        resultsContainer.innerHTML = result.results[0];
        document.body.style.cursor = "default";
      });
  });
});

function toggleSearchDisplay() {
  console.log('toggleSearchDisplay called');
  // need to hide page, show regular contacts display
  var contacts_page = document.getElementsByClassName('wa-pagination')[0];
  var page_controls = document.getElementsByClassName('paginationjs')[0];
  contacts_page.style.display = "none";
  page_controls.style.display = "none";
  var search_result = document.getElementsByClassName('wa-contacts-items')[0];
  search_result.style.display = "grid";
}