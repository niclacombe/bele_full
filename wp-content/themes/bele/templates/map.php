<?php /* Template Name: Carte Interactive */ ?>

<?php get_header(); ?>

<div class="map">

  <div class="map__map-container">

    <img class="below" src="<?= get_template_directory_uri() . '/assets/img/carte_baronnies.png;' ?>" alt="">

    <div class="svgContainer over">
      <object id="svgMap" data="<?= get_template_directory_uri(); ?>/assets/img/carte-bele.svg" type="image/svg+xml"></object>
    </div>

    
  </div>

  <div class="map__data">
    <div class="map__data__loading sticky">
      <img src="<?= get_template_directory_uri(); ?>/assets/img/spinner.gif" alt="spinner">
    </div>

    <div class="map__data__container sticky">
      <h3 id="comteNom">Titre</h3>
      <h4><strong>Dirigeant : </strong><span id="comteDirigeant"></span></h4>
      <h4>Scribe : <span id="comteScribe"></span></h4>
      <hr>
      <h3>Trames en cours</h3>
      <ul id="comteTrames">
      </ul>
  
    </div>
    
  </div>


</div>


<?php get_footer(); ?>