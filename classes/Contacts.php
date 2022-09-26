<?php
namespace WAWP\Memdir_Block\classes;

class Contacts {
    private $contacts = array();
    public function __construct($contactsFromAPI) {
      $this->contacts = $contactsFromAPI;
    }

    public function filterFieldValues($valuesToKeep) {
      foreach ($this->contacts as &$contact) {
        $fieldValues = array();

        foreach ($valuesToKeep as $value) {
          foreach ($contact['FieldValues'] as $customField) {
            if (strcasecmp($customField['FieldName'], $value) == 0) {
              $fieldValues[] = $customField;
            }
          }
        }
        $contact['FieldValues'] = $fieldValues;
      }
    }

    public function getFieldValuesOnly() {
      return array_map(function($contact) {
        $contact['FieldValues']['Id'] = $contact['Id'];
        return $contact['FieldValues'];
      }, $this->contacts);


      // return array_column($this->contacts, 'FieldValues');
    }

    public function searchByKeywords($keywords) {
      if (empty($keywords)) {
          return $this->contacts;
      }

      $foundContacts = [];
      foreach ($this->contacts as $contact) {
        foreach ($contact as $customFields) {

          if (isset($customFields['Value']) && is_array($customFields['Value'])) {
            if (isset($customFields['Value']['Label']) && stripos($customFields['Value']['Label'], $keywords) !== false) {
              array_push($foundContacts, $contact);
            }
          } else {
            if (isset($customFields['Value']) && stripos($customFields['Value'], $keywords) !== false) {
              array_push($foundContacts, $contact);
            }
          }
        }
      }
      return $foundContacts;
    }
}
?>