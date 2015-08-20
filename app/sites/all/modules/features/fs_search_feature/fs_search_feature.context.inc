<?php
/**
 * @file
 * fs_search_feature.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function fs_search_feature_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'fs_search_results';
  $context->description = '';
  $context->tag = '';
  $context->conditions = array(
    'path' => array(
      'values' => array(
        'search' => 'search',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'fs_search-fs_search_results' => array(
          'module' => 'fs_search',
          'delta' => 'fs_search_results',
          'region' => 'content',
          'weight' => '-10',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;
  $export['fs_search_results'] = $context;

  return $export;
}
