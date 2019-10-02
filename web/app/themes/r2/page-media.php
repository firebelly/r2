<?php
/*
  Template name: Media
*/

$media_sources = get_post_meta($post->ID, '_cmb2_media_sources', true);
$publications = Firebelly\PostTypes\MediaPublication\get_media_publications();
?>

<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>

  <?php if (!empty($media_sources)): ?>
    <ul class="media-sources">
      <?php foreach ($media_sources as $media_source): ?>
        <li><img src="<?= $media_source['logo'] ?>" alt="Featured in <?= $media_source['title'] ?>"></li>
      <?php endforeach ?>
    </ul>
  <?php endif ?>
</div>

<?php if (!empty($publications)): ?>
  <?= $publications ?>
<?php endif ?>
