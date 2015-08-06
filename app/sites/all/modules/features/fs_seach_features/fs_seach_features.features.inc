<?php
/**
 * @file
 * fs_seach_features.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function fs_seach_features_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "context" && $api == "context") {
    return array("version" => "3");
  }
}
