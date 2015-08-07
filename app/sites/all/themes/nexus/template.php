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

    $path = $_GET['q'];

    if (strpos($path,'node/1') !== false) {
        drupal_set_title('The Source for Federal Spending Data');
    }
}

