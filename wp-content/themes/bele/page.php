<?php get_header(); ?>

<div class="container">

  <?php 
  if(have_rows('page_row')) {
    $vSubnavItems = array();
    
    while(have_rows('page_row')) { 
        the_row();
        
        if(get_sub_field('general_subnav_bool')){
          $vSubnavItems[] = get_sub_field('general_nom');
        }
    }
  }
  ?>

  <?php 
    if(isset($vSubnavItems) && !empty($vSubnavItems)) :
  ?>
    <div id="subnav" class="ligne subnav">
      <ul>
  <?php
      foreach ($vSubnavItems as $item) :
  ?>
        <li><a class="easyscroll" href="<?php echo '#' .sanitize_title($item); ?>"><?php echo $item; ?></a></li>
  <?php
      endforeach;
  ?>
      </ul>
    </div>
  <?php
    endif;    
  ?>
    
  <?php 
  if(have_rows('page_row')) :
    $i = 0;
    while(have_rows('page_row')) : the_row();
      $layout = get_row_layout();
  ?>
    <div id="<?php echo sanitize_title(get_sub_field('general_nom')); ?>" class="ligne">
      <?php 
      if(have_rows('row_section')):
        $j = 1;
        $rowCount = count( get_field('page_row')[$i]['row_section'] );
        while(have_rows('row_section')): the_row();
          $bg_type = get_sub_field('bg_type');
          $content_type = get_sub_field('content_type');
          $style = "";

          if($bg_type == 'color'){
            $style = 'background-color:#' . get_sub_field('bg_color') .'; ';
            if(get_sub_field('bg_color') == '1D2326') : $style .= 'color: #FFFFFF; '; endif;
          } elseif($bg_type == 'image'){
            $style = 'background-image:url(' . get_sub_field('bg_image') . '); ';
            $style .= 'background-size: cover; background-position: center; ';
          }

          if($content_type == 'custom' || $content_type == 'event') : $style .= "color: #" .get_sub_field('content_wysiwyg_color') . '; '; endif;
      ?>
      <div class="<?php echo $layout; ?>" data-test="<?php echo $rowCount; ?>" style="<?php echo $style; ?>">
        <?php if(get_sub_field('content_dark_filter') == true ) : ?><div class="dark-filter"></div> <?php endif; ?>
        <?php 
        if($content_type == 'custom'){
          echo '<div>';
          echo get_sub_field('content_wysiwyg');
          echo '</div>';
        } elseif($content_type == 'event'){
          $event = get_sub_field('content_event');
          if($layout != 'bele-layout-1'):
        ?>
            <div>
              <a href="<?php echo get_permalink($event->ID); ?>">
                <h3><?php echo $event->post_title ?></h3>
                <h3><?php echo get_field('event_date_start', $event->ID); ?></h3>
              </a>
            </div>
        <?php
          else:
        ?>
          <div class="event">
            <h2 class="eventitre"><?php echo $event->post_title ?></h2>
            <h3><?php echo get_field('event_date_start', $event->ID); ?></h3>

            <a href="<?php echo get_permalink($event->ID); ?>" class="voirplus">Détails</a>
          </div>
        <?php
          endif;
        } elseif($content_type == 'image'){
          $img = get_sub_field('content_image');
          echo '<div class="image_only" style="background-image:url(' .$img['url'] .');">';

          if(get_sub_field('content_image_link') != ''){
            echo '<a href="' .get_sub_field('content_image_link') .'" target="_blank"></a>';
          }

          echo '</div>';
        }
        ?>

        <?php if(!empty($vSubnavItems) ) : ?>
          <div class="scrollTop <?php if( $j == $rowCount ): echo 'large';endif; ?>">
            <a href="#subnav">Haut de page&nbsp;&nbsp;<span class="fas fa-chevron-up"></span></a>
          </div>
        <?php endif; ?>

      </div>     
      <?php
          $j++;
        endwhile;
      endif;
      ?>
    </div>
  <?php
      $i++;
    endwhile;
  endif;
  ?>

</div><!-- /.container -->

<?php get_footer(); ?>