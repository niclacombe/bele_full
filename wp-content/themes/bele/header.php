<!DOCTYPE html>
<html>

<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-115021969-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-115021969-1');
  </script>


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta property="og:image" content="http://www.terres-de-belenos.com/wp-content/themes/bele/assets/img/banniere.jpg" />
  <meta property="og:url" content="http://www.terres-de-belenos.com/">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo get_bloginfo('name'); ?>">
  <meta property="og:description" content="Une référence dans le GN au Québec, avec environ 600 joueurs par activité et situé au Centre-du-Québec. Un univers très accessible, un jeu complet sans être complexe, et appuyé d’un grand terrain avec de nombreux bâtiments habités, pour un jeu constant toute la fin de semaine.">
  <meta property="fb:app_id" content="325188314695192">

  <title><?php echo get_bloginfo('name'); ?></title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

  <!-- Bootstrap core JavaScript -->
  <!--<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.slim.min.js"></script>-->
  


  <!-- Styles fix -->
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/theme.css" rel="stylesheet">
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/accueil.css" rel="stylesheet">
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/info.css" rel="stylesheet">
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/event.css" rel="stylesheet">
  <link href="<?php echo get_template_directory_uri(); ?>/assets/css/carte.css" rel="stylesheet">




  <?php wp_head(); ?>

</head>

<body>
  <div class="header">
    <button id="menu-toggle"><span class="fas fa-bars"></span></button>

    <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>


    <div class="banniere">
      <a href="<?php echo site_url(); ?>">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/banniere.jpg" alt="Les Terres de Belenos">
      </a>
    </div>

  </div>
