<?php
/**
 * @file
 * fs_visualization_gallery_feature.features.inc
 */

/**
 * Implements hook_views_api().
 */
function fs_visualization_gallery_feature_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function fs_visualization_gallery_feature_node_info() {
  $items = array(
    'visualization' => array(
      'name' => t('Visualization'),
      'base' => 'node_content',
      'description' => t('Visualization information for the Visualization Gallery'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
