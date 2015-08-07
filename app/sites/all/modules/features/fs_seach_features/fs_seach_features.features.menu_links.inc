<?php
/**
 * @file
 * fs_seach_features.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function fs_seach_features_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: main-menu_advanced-search:node/3
  $menu_links['main-menu_advanced-search:node/3'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/3',
    'router_path' => 'node/%',
    'link_title' => 'Advanced Search',
    'options' => array(
      'identifier' => 'main-menu_advanced-search:node/3',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: main-menu_developer-hub:http://cedwards-reisys.github.io/nexus/
  $menu_links['main-menu_developer-hub:http://cedwards-reisys.github.io/nexus/'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'http://cedwards-reisys.github.io/nexus/',
    'router_path' => '',
    'link_title' => 'Developer Hub',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_developer-hub:http://cedwards-reisys.github.io/nexus/',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -47,
    'customized' => 1,
  );
  // Exported menu link: main-menu_home:<front>
  $menu_links['main-menu_home:<front>'] = array(
    'menu_name' => 'main-menu',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => 'Home',
    'options' => array(
      'identifier' => 'main-menu_home:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Exported menu link: main-menu_search:node/4
  $menu_links['main-menu_search:node/4'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/4',
    'router_path' => 'node/%',
    'link_title' => 'Search',
    'options' => array(
      'identifier' => 'main-menu_search:node/4',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: main-menu_wireframes:node/2
  $menu_links['main-menu_wireframes:node/2'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/2',
    'router_path' => 'node/%',
    'link_title' => 'Wireframes',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_wireframes:node/2',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -46,
    'customized' => 1,
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Advanced Search');
  t('Developer Hub');
  t('Home');
  t('Search');
  t('Wireframes');

  return $menu_links;
}
