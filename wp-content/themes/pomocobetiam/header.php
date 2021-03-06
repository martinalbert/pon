<!DOCTYPE html>
<html class="js-off">
    <head>
        <meta charset="<?php bloginfo("charset"); ?>">
        <!-- <meta name="author" content="SUS Ostrava">
        <meta name="copyright" content="SUS Ostrava, <?php //date("Y") ?>">-->

        <meta name="keywords" content="" />
        <meta name="author" content=""/>
        <meta name="description" content=""/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- change when needed -->
        <meta name="robots" content="noindex, nofollow">

        <title>
          <?php 
          if(is_front_page() || is_home()){
            echo "Pomoc Obetiam Násilia";
          } else {
            echo wp_title('');
          }
          echo ' | Pomoc obetiam násilia'; ?>
        </title>

        <!-- START WP HEAD -->
        <?php wp_head(); ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!-- END WP HEAD -->

    </head>

    <body>
        <?php
        // CONSTANTS ACROSS PAGES
        define('LINKA_POMOCI', get_field('linka_pomoci', 43));
        define('LINKA_POMOCI_2', get_field('linka_pomoci_2', 43));
        $otvaracie_hodiny = array(
          "Pondelok" => get_field('po', 43), 
          "Utorok" => get_field('ut', 43), 
          "Streda" => get_field('st', 43), 
          "Štvrtok" => get_field('stv', 43), 
          "Piatok" => get_field('pi', 43), 
          "Sobota" => get_field('so', 43), 
          "Nedela" => get_field('ne', 43));
        define('OTV_HODINY', $otvaracie_hodiny);
        $adresy = explode("\n",get_field('adresa', 43));
        define('ADRESY', $adresy);
        $email_adresses = explode("\n",get_field('email', 43));
        $emails = array("Linka" => $email_adresses[0], "Info" => $email_adresses[1]);
        define('EMAILS', $emails);
        define('ICO', get_field('ico', 43));
        
        $current_uri = home_url(add_query_arg(NULL, NULL));
        $isHome = is_home();
        $isMedia = strpos($current_uri, "/media/") !== false;

        $headerClasses = array();
        if (!$isHome) {
            $headerClasses[] = "no-hp";
        }
        ?>
        <script type="text/javascript">
          document.getElementsByTagName("html")[0].classList.remove('js-off');
        </script>

        <!-- NAVIGATION BAR -->
        <header class="page-head container fixed-top">
          <nav class="page-head-nav navbar navbar-light navbar-expand-lg">
            <div class="page-head-nav-logo">
              <a class="navbar-brand" href="/">
                <img src="<?php echo get_template_directory_uri() ?>/images/logo.jpg" alt="POMOC OBETIAM LOGO" />
              </a>
              <div class="page-head-nav-headings">
                <h5>Pomoc Obetiam Násilia</h5>
                <p>Občianske združenie</p>
                <p>Victim Support Slovakia</p>
              </div>
            </div>

            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="page-head-nav-list navbar-nav">
                <?php
                $locations = get_nav_menu_locations();
                if ( isset( $locations['header']) ) {
                  $menu = get_term( $locations['header'], 'nav_menu');
                }
                if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
                  foreach ( $items as $item ) {
                    if ($item->menu_item_parent != 0) {
                      continue;
                    }
                    $curNavItemID = $item->ID;
                    $classList = implode(" ", $item->classes); 
                    $item_url = $item->url; ?>
                    
                    <li class="page-head-nav-list-item nav-item">
                      <a href="<?php echo $item_url ?>"><?php echo $item->title ?></a>

                    <?php if ( in_array('dropdown', $item->classes)) { ?>
                      
                      <ul class="page-head-nav-list-item-dropdown dropdown dropdown-menu <?php echo $item->classes[1]; ?>">
                        
                        <?php foreach($items as $subnav) {
                          $curSubItemID = $subnav->ID;
                          $item_url = $subnav->url;
                          if ( $subnav->menu_item_parent == $curNavItemID) { ?>
                              
                              <li class="dropdown-item">
                              <a href="<?php echo $subnav->url ?>"><?php echo $subnav->title; ?></a>
                          
                          <?php if ( in_array('dropdown-list', $subnav->classes)) {?>
                                <ul class="dropdown-list style-none">
                              <?php foreach($items as $subsubnav) { 
                                    if ( $subsubnav->menu_item_parent == $curSubItemID ) { ?>
                                      <li>
                                        <a href="<?php echo $subsubnav->url ?>">
                                          <div class="menu-nadpisy-h5"><?php echo $subsubnav->title ?></div>
                                          <?php echo mb_strimwidth($subsubnav->description, 0, 100, "...") ?>
                                        </a>
                                      </li>
                              <?php } elseif ($subsubnav == end($items)) { ?>
                                    <li><a href="<?php echo $item_url; ?>">Více ...</a>
                              <?php }
                                  }
                                  echo "</ul>";
                                }
                              echo "</li>";
                              }
                            }
                          echo "</ul>";
                          } 
                        ?>
                      </li>
                    <?php  
                    } 
                  } else {
                    echo "MENU NOT FOUND";   
                    var_dump($locations);
                    var_dump($menu);
                        }
                      ?>
              </ul>
            </div>
          </nav>
        </header>

        <!-- START OF PAGE -->
        <?php 
        global $post;
        $page_title_class = $post->post_name ?? 'homepage';

        ?>
        <main class="page-main container <?php echo $page_title_class; ?>">
            <?php 

          // NOT HOMEPAGE
          if(!is_front_page()) { ?>
            <section class="page-title-wrap">
              <div class="page-title col-8 offset-2">
                <h1 id="page-title">
                  <?php 
                  if ( 'custom-taxonomy' == get_query_var( 'taxonomy' )) {
                    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
                    echo $term->name;
                  } else {
                    // the_title();
                    get_template_part( 'components/content', 'header' );
                  } ?>
                </h1>
              </div>
            </section>

          <!--HOMEPAGE -->
        <?php } else { 
            // get_template_part('components/home', 'carousel');    
          } 
             
        // START OF LAYOUT ?>
              <?php
              if(is_page('podporte-nas')): ?>
              <section class="row block block-pink mt-0">

              <?php
              elseif(is_page('kontakt')): ?>
              <section class="row block block-transparent pt-0">

              <?php
              elseif (!is_front_page() && !is_page('podporte-nas')): ?>
              <section class="row block block-transparent pt-0">
                <div class="col-xs-12 col-md-10 offset-md-1">

              <?php
              else: ?>
              <section class="row mb-5">
                  <div class="col-xs-12 col-md-6 offset-md-3 text-center mb-4 ">
                
              <?php
              endif; ?>














