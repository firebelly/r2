<?php

$categories = get_terms(array(
  'taxonomy'    => 'team_member_category',
  'hide_empty'  => true,
));

?>

<div class="filters">
  <div class="toggle-container">
    <button class="filters-toggle">Filter by <svg class="icon icon-arrow-down" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-down"/></svg></button>
  </div>

  <div id="team-filters" class="filters-container">
    <div class="-inner">
      <?php if (!empty($categories)): ?>
        <div class="filter-group">
          <h5 class="group-label">Department</h5>
          <ul>
            <?php foreach ($categories as $category): ?>
              <li><button class="filter" data-filter=".<?= $category->slug ?>"><?= $category->name ?> <svg class="icon" aria-hidden="true" role="presentation"><use xlink:href="#icon-close"/></svg></button></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif ?>

      <button class="filters-close">Close</button>
    </div>
  </div>
</div>