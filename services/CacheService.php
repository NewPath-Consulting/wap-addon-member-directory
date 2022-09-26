<?php

namespace WAWP\Memdir_Block\services;

const CACHE_PREFIX = "NPC-WA-";
const DEFAULT_CACHE_EXPIRY = HOUR_IN_SECONDS;

class CacheService {
  private static $_instance;
  private $prefix = CACHE_PREFIX;

  public static function getInstance() {
    if (!is_object(self::$_instance)) {
        self::$_instance = new self();
    }

    return self::$_instance;
  }

  public function getValue($key) {
    return get_transient($this->prefix . md5($key));
  }

  public function saveValue($key, $value, $expiration = DEFAULT_CACHE_EXPIRY) {
    set_transient($this->prefix . md5($key), $value, $expiration);
  }

  public function clearCache() {
    global $wpdb;

    $prefix = esc_sql($this->prefix);
    $options = $wpdb->options;

    $t  = esc_sql( "_transient_timeout_$this->prefix%" );

    $sql = $wpdb -> prepare (
      "
        SELECT option_name
        FROM $options
        WHERE option_name LIKE '%s'
      ",
      $t
    );

    $transients = $wpdb->get_col( $sql );

    foreach( $transients as $transient ) {
      $key = str_replace( '_transient_timeout_', '', $transient );
      delete_transient( $key );
    }

    wp_cache_flush();
  }

  public function getPrefix() {
    return $this->prefix;
  }
}

?>