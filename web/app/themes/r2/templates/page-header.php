<?php
  use Roots\Sage\Titles;

  $post_meta = get_post_meta($post->ID);
?>

<div class="page-header">
  <?php if (!empty($post_meta['_cmb2_intro_title'])): ?>
    <h1 class="intro-title"><?= $post_meta['_cmb2_intro_title'][0] ?></h1>
  <?php else: ?>
    <h1 class="intro-title"><?= Titles\title(); ?></h1>
  <?php endif ?>

  <?php if (!empty($post_meta['_cmb2_intro_para'])): ?>
    <div class="intro-text">
      <p><?= $post_meta['_cmb2_intro_para'][0] ?></p>
    </div>
  <?php endif ?>
</div>
