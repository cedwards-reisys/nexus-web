<?php
/**
 * @file
 * fs_search_feature.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function fs_search_feature_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "context" && $api == "context") {
    return array("version" => "3");
  }
}
