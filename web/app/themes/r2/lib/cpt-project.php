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

$projects->register();

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
    'name'      => 'Project Status',
    'id'        => $prefix . 'status',
    'type'      => 'select',
    'options'   => array(
      'current' => __( 'Current', 'cmb2' ),
      'past'    => __( 'Past', 'cmb2' ),
    ),
  ]);
  $project_info->add_field([
    'name'      => 'Description',
    'id'        => $prefix . 'description',
    'type'      => 'wysiwyg',
    'options' => array(
      'media_buttons' => false,
      'textarea_rows' => get_option('default_post_edit_rows', 8),
    ),
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
    'name'  => 'Callout Headline',
    'id'    => $prefix . 'callout_headline',
    'type'  => 'text',
    'desc'  => 'A headline for a brief callout, ex: "Going Fast"'
  ]);
  $project_info->add_field([
    'name'    => 'Callout Copy',
    'id'      => $prefix . 'callout_copy',
    'type'    => 'wysiwyg',
    'desc'    => 'A brief callout about the project',
    'options' => array(
      'media_buttons' => false,
      'textarea_rows' => get_option('default_post_edit_rows', 3),
    ),
  ]);
  $project_info->add_field([
    'name' => 'Downloads',
    'id'   => $prefix . 'downloads_title',
    'type' => 'title',
    'desc' => 'Add multiple downloads and their labels.'
  ]);
  $downloads_group = $project_info->add_field([
    'id'              => $prefix .'downloads',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Download {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Download', 'cmb2' ),
      'remove_button' => __( 'Remove Download', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $project_info->add_group_field( $downloads_group, [
    'name' => 'Label',
    'id'   => 'label',
    'type' => 'text',
  ]);
  $project_info->add_group_field( $downloads_group, [
    'name' => 'File',
    'id'   => 'file',
    'type' => 'file',
  ]);

  $project_images = new_cmb2_box([
    'id'            => $prefix . 'project_images_group',
    'title'         => __( 'Project Images', 'cmb2' ),
    'object_types'  => ['project'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $project_images_group = $project_images->add_field([
    'id'              => $prefix .'project_images',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Image {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Image', 'cmb2' ),
      'remove_button' => __( 'Remove Image', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $project_images->add_group_field( $project_images_group, [
    'name' => 'Image',
    'id'   => 'file',
    'type' => 'file',
  ]);
  $project_images->add_group_field( $project_images_group, [
    'name'    => 'Size',
    'id'      => 'size',
    'type'    => 'select',
    'options' => array(
        'small'   => __( 'Small', 'cmb2' ),
        'medium'  => __( 'Medium', 'cmb2' ),
        'large'   => __( 'Large', 'cmb2' )
    ),
  ]);
  $project_images->add_group_field( $project_images_group, [
    'name'  => 'Black and white?',
    'id'    => 'bw',
    'type'  => 'checkbox',
    'desc'  => 'Should this image be treated as a black and white image?'
  ]);

  $comparison_images = new_cmb2_box([
    'id'            => $prefix . 'project_comparison_images_group',
    'title'         => __( 'Comparison Images', 'cmb2' ),
    'object_types'  => ['project'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $comparison_images_group = $comparison_images->add_field([
    'id'              => $prefix .'project_comparison_images',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Comparison {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Comparison', 'cmb2' ),
      'remove_button' => __( 'Remove Comparison', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $comparison_images->add_group_field( $comparison_images_group, [
    'name' => 'Before Image',
    'id'   => 'before',
    'type' => 'file',
  ]);
  $comparison_images->add_group_field( $comparison_images_group, [
    'name' => 'After Image',
    'id'   => 'after',
    'type' => 'file',
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
    $output .= '<div class="project-row grid spaced">';
    foreach ($row as $project):
      ob_start();
      \Firebelly\Utils\get_template_part_with_vars('templates/article', 'project', [ 'project' => $project]);
      $output .= ob_get_clean();
    endforeach;
    $output .= '</div>';
  endforeach;
  return $output;
}