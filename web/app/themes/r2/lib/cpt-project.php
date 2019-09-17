<?php
/**
 * Project Post Type
 */

namespace Firebelly\PostTypes\Project;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$projects = new PostType(['name' => 'project', 'plural' => 'Projects', 'slug' => 'project'], [
  'taxonomies' => ['category'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$projects->filters(['category']);
$projects->icon('dashicons-building');

// Custom taxonomies
$category = new Taxonomy('category');
$category->register();

$projects->register();