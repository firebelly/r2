<?php
/**
 * Media Post Type
 */

namespace Firebelly\PostTypes\TeamMember;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$media = new PostType(['name' => 'media', 'plural' => 'Media', 'slug' => 'media'], [
  'taxonomies' => ['year'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$media->filters(['year']);
$media->icon('dashicons-media-document');

// Custom taxonomies
$year = new Taxonomy('year');
$year->register();

$media->register();