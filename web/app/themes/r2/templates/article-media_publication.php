<?php
$title = $media_publication->post_title;
$link = get_post_meta($media_publication->ID, '_cmb2_media_url', true);
$publication = strtoupper(get_post_meta($media_publication->ID, '_cmb2_publication_name', true));
$date = get_post_meta($media_publication->ID, '_cmb2_media_date', true);
?>

<li>
  <?php if (!empty($link)): ?>
    <a href="<?= $link ?>" target="_blank"><?= $title ?> <?= $publication ?> <?= $date ?></a>
  <?php endif ?>
</li>