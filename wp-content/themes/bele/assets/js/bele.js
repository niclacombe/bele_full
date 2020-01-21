
$('#menu-toggle').on('click',function(){
	var nav = $('.header .menu-main-menu-container');

	nav.slideToggle();
});



var svg = document.getElementById('svgMap');

svg.addEventListener("load", function(){
  var svgDoc = svg.contentDocument;
  var svgRoot = svgDoc.documentElement;

  var baronnies = $( svgRoot ).find('.baronnie');

  $.ajax({
    'url' : '/bdmvc/assets/map/config.json',
    'method' : 'GET',
    'success' : function(data){
      applyConfig(data, svgRoot);
    },
    'error' : function(err){
      console.log(err);
    }
  });

});

function applyConfig(data, svgRoot){
  var data = data.baronnies,
  baronnies = $( svgRoot ).find('.baronnie');

  var ids = [];

  baronnies.each(function(index, el) {
    var color = data[ $(el).attr('id') ].Couleur,
        idComte = data[ $(el).attr('id') ].IdComte;;

    $(el).attr({
      'fill' : color,
      'fill-opacity' : 0,
      'stroke' : color,
      'idComte' : idComte,
    });

    ids.push( $(el).attr('id') );

    $(el).mouseover(function(){
      baronnies.attr('fill-opacity', 0);

      var sameComte = $( svgRoot ).find('.baronnie[idComte="' + idComte + '"]');

      sameComte.each(function(index, b) {
        $(b).attr('fill-opacity', 0.4);
      });
      
    }).mouseleave(function(){
      baronnies.attr('fill-opacity', 0);
    }).click(function(){
      $('.map__data__container').fadeOut(150);
      $('.map__data__loading').fadeIn(150);
      $.ajax({
        'url' : '/bdmvc/index.php/Histoire/ajax_getTrames/' + idComte,
        'method' : 'GET',
        'success' : function(data){
          updateData(JSON.parse(data));
        },
        'error' : function(err){
          console.log(err);
        }
      });
    });
  });
}

function updateData(data){
  $('#comteNom').html(data.comte.Nom);
  $('#comteDirigeant').html(data.comte.Dirigeant);
  $('#comteScribe').html(data.comte.Scribe);

  var htmlTrames = '';
  if(data.trames.length >= 1){
    data.trames.forEach(function(item, index){
      htmlTrames += '<li>';
      htmlTrames += '<a class="trame" data-toggle="' +item.Id +'" href="#">';
      htmlTrames += item.Nom;
      htmlTrames += '</a>';
      htmlTrames += '<div class="trame__description" id="' +item.Id +'">';
      htmlTrames += '<p><em>';
      htmlTrames += item.Description;
      htmlTrames += '</em></p>';
      htmlTrames += '</div>';
      htmlTrames += '</li>';
    });
  } else{
    htmlTrames = htmlTrames += '<li>';
      htmlTrames += 'Ce comté ne contient aucune trame scénaristiques.';
      htmlTrames += '</li>';;
  }
  $('#comteTrames').html(htmlTrames);

  $('a.trame').click(function(e){
    e.preventDefault();
    var target = $(this).attr('data-toggle');

    if( $('#' + target).hasClass('opened') == false ){
      $('.trame__description.opened').slideToggle().removeClass('opened');

      $('#' + target).slideToggle().addClass('opened');
    }

    //$('#' + target).slideToggle().addClass('opened');

  });

  $('.map__data__loading').fadeOut(150);
  $('.map__data__container').fadeIn(150);
}

