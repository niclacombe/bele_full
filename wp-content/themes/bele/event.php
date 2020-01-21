<?php get_header(); ?>

<div class="event1" id="event1">

<div class="entete1">
  <h2 class="eventitre">Gladiamort thématique</h2>
  <h3>12 avril 2019 - 20h16</h3>
  <h3><a href="#">RÉSERVER</a></h3>


<button class="collapsiblevent">Voir plus</button>

<div class="content">

  <p>Le célèbre festival en plein air qui rassemble chaque année les plus valeureux gerriers des Terres de Bélénos. Nombreux prix à gagner. INSCRIPTIONS OUVERTES JUSQU'AU 8 AVRIL</p>

  <div class="tarifs">
    <h3>TARIFS</h3>
    <p>La demi-journée : 25$</p>
    <p>La soirée : 15$</p>
    <p>Moins de 13 ans : gratuit</p>
  </div>

  <div class="emplacement">
    <h3>EMPLACEMENT</h3>
    <p>Arène de Bélénos, accès par l'entrée 112 rang 5, Ste Clothilde de Horton</p>
  </div>

  <div class="programmation">
    <h3>PROGRAMMATION</h3>
    <p>Midi : début de l'activité</p>
    <p>13h : défilé de halebardes home made</p>
    <p>15h : atelier survie avec les orcs herboristes</p>
    <p>19h : début des combats</p>
    <p>23h : remise des prix et cloture</p>
   </div>

</div>
  </div>

</div>

<div class="event2" id="event2">

<div class="entete2">
  <h2 class="eventitre">Banquet suivant</h2>
  <h3>20 mars 2019 - 20h16</h3>
  <h3><a href="#">RÉSERVER</a></h3>


<button class="collapsiblevent">Voir plus</button>

<div class="content">

  <p>Le célèbre festival en plein air qui rassemble chaque année les plus valeureux gerriers des Terres de Bélénos. Nombreux prix à gagner. INSCRIPTIONS OUVERTES JUSQU'AU 8 AVRIL</p>

  <div class="tarifs">
    <h3>TARIFS</h3>
    <p>La demi-journée : 25$</p>
    <p>La soirée : 15$</p>
    <p>Moins de 13 ans : gratuit</p>
  </div>

  <div class="emplacement">
    <h3>EMPLACEMENT</h3>
    <p>Arène de Bélénos, accès par l'entrée 112 rang 5, Ste Clothilde de Horton</p>
  </div>

  <div class="programmation">
    <h3>PROGRAMMATION</h3>
    <p>Midi : début de l'activité</p>
    <p>13h : défilé de halebardes home made</p>
    <p>15h : atelier survie avec les orcs herboristes</p>
    <p>19h : début des combats</p>
    <p>23h : remise des prix et cloture</p>
   </div>

</div>
  </div>

</div>

<script>
var coll = document.getElementsByClassName("collapsiblevent");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}
</script>



<?php get_footer(); ?>
