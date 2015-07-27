<?php

/**
 * @file
 * template.php
 */

function nexus_preprocess_page(&$vars){
    $path = $_GET['q'];

    if (strpos($path,'node/1') !== false) {
        drupal_set_title('The Source for Federal Spending Data');
    }
}