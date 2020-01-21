<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Module de gestion</title>

    <link href="/bdmvc/assets/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="/bdmvc/assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/bdmvc/assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/bdmvc/assets/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/bdmvc/assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- bPopup CSS -->
    <link href="/bdmvc/assets/css/bpopup.css" rel="stylesheet" type="text/css"></script>

    <link href="/bdmvc/assets/css/bdmvc.css" rel="stylesheet" type="text/css"></script>

    <!-- jQuery -->
    <script src="/bdmvc/assets/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/bdmvc/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- include summernote css/js-->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

    <!-- bPopup -->
    <script src="/bdmvc/assets/js/bpopup.js"></script>

    <script src="/bdmvc/assets/js/beleScript.js"></script>

    <script src="/bdmvc/assets/bower_components/raphael/raphael-min.js"></script>

    <!-- Morris -->
    <script src="/bdmvc/assets/bower_components/morrisjs/morris.min.js"></script>
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">
    <?php if(isset($_SESSION['infoUser'])): ?>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>?id=<?php echo $_SESSION['infoUser']->Id; ?>">Gestion Bélé</a>
            </div>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="#"><i class="fa fa-check fa-fw"></i>Approbations<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo site_url('approbations/approbationBG'); ?>">Histoires</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('approbations/plansDeCours'); ?>">Plans de cours</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('approbations/approbRaces'); ?>">Races</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-hand-o-right fa-fw"></i>Inscriptions<span class="fa arrow"></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?php echo site_url('inscriptions'); ?>">Inscrire un joueur</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('inscriptions/searchInscriptions'); ?>">Annuler une inscription</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('inscriptions/searchPresences'); ?>">Consulter les présences</a>
                                </li> 
                                <li>
                                    <a href="<?php echo site_url('personnages'); ?>">Gérer les personnages</a>
                                </li>
                                <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                                    <li>
                                        <a href="<?php echo site_url('noblesse/listTitres') ?>">Titres</a>
                                    </li>
                                <?php endif; ?>                               
                            </ul>
                            
                        </li>
                        <li>
                            <a href="#"><span class="fa fa-gavel fa-reverse"></span> Administration<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">

                                <li><a href="<?php echo site_url('Administration/avertissements'); ?>">Avertissements</a></li>
                                <li><a href="<?php echo site_url('Administration/creditsEtDettes'); ?>">Crédits et Dettes</a></li>                                

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                                    <li><a href="<?php echo site_url('activites'); ?>">Gérer les activités</a></li>
                                <?php endif; ?>

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                                    <li><a href="<?php echo site_url('passes'); ?>">Gérer les passes</a></li>
                                <?php endif; ?> 

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                                    <li><a href="<?php echo site_url('passes/passesParJoueur'); ?>">Gérer les Passes par Joueur</a></li>
                                <?php endif; ?>

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 6): ?>
                                    <li><a href="<?php echo site_url('Mecenat'); ?>">Mécénat</a></li>
                                <?php endif; ?> 

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                                    <li><a href="<?php echo site_url('Administration/acces'); ?>">Modifier Niveau Accès</a></li>
                                <?php endif; ?>

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 6): ?>
                                    <li><a href="<?php echo site_url('Courriel'); ?>">Envoyer un courriel <span class="fa fa-envelope"></span></a></li>
                                <?php endif; ?>

                                <?php if($_SESSION['infoUser']->NiveauAcces >= 2): ?>
                                    <li><a href="<?php echo site_url('individus/arcanns'); ?>">Arcanns <span class="fa fa-money"></span></a></li>
                                <?php endif; ?>                                
                                                            
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <?php if($_SESSION['infoUser']->NiveauAcces >= 6): ?>
                        <li>
                            <a href="#"><span class="fa fa-book fa-reverse"></span> Histoire<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">

                                <li><a href="<?php echo site_url('Histoire/trames'); ?>">Trames <span class="fa fa-bookmark"></span></a></li>

                                <li><a href="<?php echo site_url('Histoire/carte'); ?>">Carte Interactive <span class="fa fa-map"></span></a></li>

                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php endif; ?>

                        <?php if($_SESSION['infoUser']->NiveauAcces >= 5 || $_SESSION['infoUser']->NiveauAcces == 3): ?>
                        <li>
                          <a href="#"><span class="fa fa-compass fa-reverse"></span> Quêtes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                              <li><a href="<?php echo site_url('Quetes/viewQuests'); ?>">Voir les Quêtes</a></li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    <?php endif; ?>
                        <?php if($_SESSION['infoUser']->NiveauAcces >= 5): ?>
                            <li>
                                <a href="#"><span class="fa fa-users"></span> Groupes <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?php echo site_url('groupes'); ?>">Consulter les groupes</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('groupes/viewActions'); ?>">Consulter les actions</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="#"><i class="fa fa-eye fa-fw"></i>Consultations<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="/PERSONNAGES/main.php" target="_blank">Personnages</a></li>
                                <li><a href="/GROUPES/main.php" target="_blank">Groupes</a></li>                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="/BD" target="_blank"><span class="fa fa-home fa-reverse"></span>Retour à la BD</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
    <?php endif; ?>