<?php

/**
 * @file
 * template.php
 */


/**
 * Implements hook_preprocess_page().
 *
 * @see page.tpl.php
 */
function nexus_preprocess_page(&$variables) {

}

function nexus_preprocess_menu_link(&$variables) {

    // create modal for feedback menu link
    if ( $variables['element']['#original_link']['link_path'] === 'node/14' ) {
        $variables['element']['#attributes']['class'][] = 'ctools-use-modal';
        $variables['element']['#attributes']['class'][] = 'ctools-modal-modal-popup-medium';
        $variables['element']['#href'] = 'modal_forms/nojs/webform/13';
    }
}