<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the
 * ibhr_omega theme.
 */

function ibhr_omega_breadcrumb($vars) {
  $output = '<ul class="breadcrumb">';

  // Optional: Add the site name to the front of the stack.
  if (!empty($vars['prepend'])) {
    $site_name = empty($vars['breadcrumb']) ? "<strong>". check_plain(variable_get('site_name', '')) ."</strong>" : l(variable_get('site_name', ''), '<front>', array('purl' => array('disabled' => TRUE)));
    array_unshift($vars['breadcrumb'], $site_name);
  }

  $depth = 0;
  $separator = '  » ';
  foreach ($vars['breadcrumb'] as $link) {

    // If the item isn't a link, surround it with a strong tag to format it like
    // one.
    if (!preg_match('/^<a/', $link) && !preg_match('/^<strong/', $link)) {
      $link = '<strong>' . $link . '</strong>';
    }

    $output .= "<span class='breadcrumb-link breadcrumb-depth-{$depth}'>{$link}</span>";
    $depth++;

    if ($link !== end($vars['breadcrumb'])) {   // Add separators, unless we're on the last item
      $output .= "<span class='bc-separator'>{$separator}</span>" ;
    }
  }

  $output .= '</ul>';

  if ($depth > 1) { // Only show breadcrumbs if we have more than 2 links or if we are on the exempted pages
    return $output;
  }
}

function ibhr_omega_preprocess_html(&$vars) {
  $viewport = array(
   '#tag' => 'meta',
   '#attributes' => array(
     'name' => 'viewport',
     'content' => 'width=device-width, initial-scale=1, user-scalable=yes',
   ),
  );
  drupal_add_html_head($viewport, 'viewport');
}

function ibhr_omega_preprocess_page() {
  if (request_path() == 'contact-us') { // Contact page
    drupal_add_css(drupal_get_path('theme', 'ibhr_omega') . '/css/contact.css', array('group' => CSS_THEME));
  }
  else if (arg(0) == 'reviews') { // Review page
    drupal_add_css(drupal_get_path('theme', 'ibhr_omega') . '/css/reviews.css', array('group' => CSS_THEME));
  }
  else if ((arg(0) == 'node' && preg_match('/^\d+$/', arg(1)) && empty(arg(2))) ) { // Node view page.
    $node = menu_get_object();
    if ($node->type == 'media_gallery') {
      drupal_add_css(drupal_get_path('theme', 'ibhr_omega') . '/css/media-gallery.css', array('group' => CSS_THEME));
    }
  }
  else if (arg(0) == 'user' && (user_is_anonymous())) {
    drupal_add_css(drupal_get_path('theme', 'ibhr_omega') . '/css/login.css', array('group' => CSS_THEME));
  }
}

/**
 * Implements hook_preprocess_maintenance_page()
 */
function ibhr_omega_preprocess_maintenance_page() {
  drupal_add_css(drupal_get_path('theme', 'ibhr_omega') . '/css/maintenance.css', array('group' => CSS_THEME));
}
