<?php

$locations = get_terms(array(
  'taxonomy'    => 'project_location',
  'hide_empty'  => true,
));

$property_types = get_terms(array(
  'taxonomy'    => 'property_type',
  'hide_empty'  => true
));

?>

<div class="filters">
  <div class="toggle-container">
    <button class="filter-by filters-toggle">Filter by<span>:</span> <svg class="icon icon-arrow-down" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-down"/></svg></button>
    <div class="filter-labels">
      <div>
        <button class="filters-toggle" data-group="status">Status <svg class="icon icon-arrow-down" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-down"/></svg></button>
      </div>
      <div>
        <button class="filters-toggle" data-group="location">Location <svg class="icon icon-arrow-down" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-down"/></svg></button>
      </div>
      <div>
        <button class="filters-toggle" data-group="property-type">Property Type <svg class="icon icon-arrow-down" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-down"/></svg></button>
      </div>
    </div>
  </div>

  <div id="project-filters" class="filters-container">
    <button class="clear-filters hidden">Clear All Filters</button>

    <div class="-inner">
      <div id="status" class="filter-group">
        <h5 class="group-label">Status</h5>
        <ul>
          <li><button class="filter" data-filter=".current">Current Projects <svg class="icon" aria-hidden="true" role="presentation"><use xlink:href="#icon-close"/></svg></button></li>
          <li><button class="filter" data-filter=".past">Past Projects <svg class="icon" aria-hidden="true" role="presentation"><use xlink:href="#icon-close"/></svg></button></li>
        </ul>
      </div>

      <?php if (!empty($locations)): ?>
        <div id="location" class="filter-group">
          <h5 class="group-label">Location</h5>
          <ul>
            <?php foreach ($locations as $location): ?>
              <li><button class="filter" data-filter=".<?= $location->slug ?>"><?= $location->name ?> <svg class="icon" aria-hidden="true" role="presentation"><use xlink:href="#icon-close"/></svg></button></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif ?>

      <?php if (!empty($property_types)): ?>
        <div id="property-type" class="filter-group">
          <h5 class="group-label">Property Type</h5>
          <ul>
            <?php foreach ($property_types as $property_type): ?>
              <li><button class="filter" data-filter=".<?= $property_type->slug ?>"><?= $property_type->name ?> <svg class="icon" aria-hidden="true" role="presentation"><use xlink:href="#icon-close"/></svg></button></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif ?>

      <button class="filters-close">Close</button>
    </div>
  </div>
</div>