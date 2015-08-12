<?php
/**
 * @file
 * fs_search_features.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function fs_search_features_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "context" && $api == "context") {
    return array("version" => "3");
  }
}
