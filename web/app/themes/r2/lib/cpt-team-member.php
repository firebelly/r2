<?php
/**
 * Team Member Post Type
 */

namespace Firebelly\PostTypes\TeamMember;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$team_members = new PostType(['name' => 'team_member', 'plural' => 'Team Members', 'slug' => 'team'], [
  'taxonomies' => ['category'],
  'supports'   => ['title', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$team_members->filters(['category']);
$team_members->icon('dashicons-groups');

// Custom taxonomies
$category = new Taxonomy('category');
$category->register();

$team_members->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $member_info = new_cmb2_box([
    'id'            => $prefix . 'member_info',
    'title'         => __( 'Contact Info', 'cmb2' ),
    'object_types'  => ['team_member'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $member_info->add_field([
    'name'      => 'Title',
    'id'        => $prefix . 'member_title',
    'type'      => 'text',
  ]);
  $member_info->add_field([
    'name'      => 'Email',
    'id'        => $prefix . 'member_email',
    'type'      => 'text_email',
  ]);
  $member_info->add_field([
    'name'      => 'Telephone',
    'id'        => $prefix . 'member_phone',
    // Todo: auto-format the field
    'desc'      => 'Format example: +1 312 300 2376',
    'type'      => 'text',
  ]);
  $member_info->add_field([
    'name'      => 'LinkedIn Profile',
    'id'        => $prefix . 'member_linkedin',
    'type'      => 'text_url',
  ]);
  $member_info->add_field([
    'name'      => 'Bio',
    'id'        => $prefix . 'member_bio',
    'type'      => 'wysiwyg',
    'desc'      => 'Bios are only displayed for Principles',
    'options' => [
       'textarea_rows' => 5,
     ],
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Update post meta for sorting people by last name
 */
function team_member_sort_meta($post_id) {
  if (wp_is_post_revision($post_id))
    return;

  $post_title = get_the_title($post_id);
  // Remove ", Ph.D" from name
  $post_title = preg_replace('#, ?(.*)$#', '', $post_title);

  if (strpos($post_title, ' ') !== FALSE) {
    list($first, $last) = preg_split('/ ([^ ]+)$/', $post_title, 0, PREG_SPLIT_DELIM_CAPTURE);
    // Remove middle name if present
    $first = preg_replace('# (.*)$#', '', $first);
  } else {
    $first = $post_title;
    $last = $post_title;
  }
  update_post_meta($post_id, '_first_name', $first);
  update_post_meta($post_id, '_last_name', $last);
}
add_action('save_post_team_member', __NAMESPACE__.'\\team_member_sort_meta');

/**
 * Get Team Members
 */
function get_team_members($options=[]) {
  if (empty($options['numberposts'])) $options['numberposts'] = -1;
  $args = [
    'numberposts' => $options['numberposts'],
    'post_type'   => 'team_member',
  ];
  if (!empty($options['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => $options['category']
      ]
    ];
  }

  // By default use Intuitive CPO hand-ordering, otherwise sort by last name
  if (!empty($options['order-by']) && $options['order-by'] === 'name') {
    $args['order'] = 'ASC';
    $args['orderby'] = 'meta_value';
    $args['meta_key'] = '_last_name';
  }

  // Display all matching posts using article-{$post_type}.php
  $team_members = get_posts($args);
  if (!$team_members) return false;
  $rows = array_chunk($team_members, 4, true);
  $output = '';
  foreach ($rows as $row):
    $output .= '<div class="member-row grid spaced animate-in-series">';
    foreach ($row as $team_member):
      ob_start();
      \Firebelly\Utils\get_template_part_with_vars('templates/article', 'team_member', [ 'team_member' => $team_member]);
      $output .= ob_get_clean();
    endforeach;
    $output .= '</div>';
  endforeach;
  return $output;
}