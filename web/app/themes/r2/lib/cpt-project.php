<?php
/**
 * Project Post Type
 */

namespace Firebelly\PostTypes\Project;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$projects = new PostType(['name' => 'project', 'plural' => 'Projects', 'slug' => 'project'], [
  'taxonomies' => ['property_type', 'project_location'],
  'supports'   => ['title', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$projects->filters(['property_type', 'project_location']);
$projects->icon('dashicons-building');
$projects->register();

// Custom taxonomies
$property_type = new Taxonomy([
  'name'     => 'property_type',
  'slug'     => 'property_type',
  'plural'   => 'Property Types',
]);
$property_type->register();

$project_location = new Taxonomy([
  'name'     => 'project_location',
  'slug'     => 'project_location',
  'plural'   => 'Project Locations',
]);
$project_location->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $project_info = new_cmb2_box([
    'id'            => $prefix . 'project_info',
    'title'         => __( 'Project Info', 'cmb2' ),
    'object_types'  => ['project'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $project_info->add_field([
    'name'        => 'Header Images',
    'id'          => $prefix . 'header_images',
    'type'        => 'file_list',
    'query_args'  => array( 'type' => 'image' ),
  ]);

  $project_info->add_field([
    'name'      => 'Locality',
    'id'        => $prefix . 'locality',
    'desc'      => 'Example: Goose Island, Chicago',
    'type'      => 'text',
  ]);
  $project_info->add_field([
    'name'      => 'Square footage',
    'id'        => $prefix . 'square_footage',
    'type'      => 'text',
  ]);
  $project_info->add_field([
    'name'      => 'Downloads',
    'id'        => $prefix . 'downloads',
    'type'      => 'file_list',
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get Projects
 */
function get_projects($options=[]) {
  if (empty($options['numberposts'])) $options['numberposts'] = -1;
  $args = [
    'numberposts' => $options['numberposts'],
    'post_type'   => 'project',
  ];
  if (!empty($options['property_type'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'property_type',
        'field' => 'slug',
        'terms' => $options['property_type']
      ]
    ];
  }
  if (!empty($options['project_location'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'project_location',
        'field' => 'slug',
        'terms' => $options['project_location']
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
  $projects = get_posts($args);
  if (!$projects) return false;
  $rows = array_chunk($projects, 4, true);
  $output = '';
  foreach ($rows as $row):
    $output .= '<div class="project-row grid spaced animate-in-series">';
    foreach ($row as $project):
      ob_start();
      \Firebelly\Utils\get_template_part_with_vars('templates/article', 'project', [ 'project' => $project]);
      $output .= ob_get_clean();
    endforeach;
    $output .= '</div>';
  endforeach;
  return $output;
}