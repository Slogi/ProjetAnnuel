<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?> </title>
    <script src="<?= asset_url().'assets/jquery/jquery-3.3.1.min.js';?>"></script>
    <script src="<?= asset_url().'assets/jquery/jquery-ui.min.js';?>"></script>
    <link href="<?= asset_url().'assets/jquery/jquery-ui.min.css';?>" rel="stylesheet">
    <script src="<?= asset_url().'assets/bootstrap/js/bootstrap.min.js';?>"></script>
    <link href="<?= asset_url().'assets/bootstrap/css/bootstrap.min.css';?>" rel="stylesheet">
    <link href="<?= asset_url().'assets/css/headerfooter.css';?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
<body>
<?php if(isset($message)) echo $message ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-info" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url();?>">Market Monitor</a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar">
            &#9776;
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <?php if ( $pseudo != null ) { ?>
                    <li class="nav-item"><a href="<?= base_url().'produitSuivi'?>" class="nav-link">Liste des articles suivis</a></li>
                <?php } ?>
                <li class="nav-item search-bar">
                    <div class="form-inline my-2 my-lg-0">
                        <input id="search_product" name="term" class="form-control mr-sm-2"
                               type="search" placeholder="Chercher un produit"
                               aria-label="Search">
                        <button id="recherche" class="btn btn-outline-secondary
                     text-white my-2 my-sm-0"><i class="fas fa-search"></i></button>
                    </div>
                </li>
            </ul>
            <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
                <li class="dropdown order-1">
                    <?php if ( $pseudo == null ) { ?>
                        <button type="button" id="sinscrire" data-target="#modalInscription" data-toggle="modal" class="btn btn-outline-secondary
                     text-white">S'inscrire <span class="caret"></span></button>
                        <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-secondary
                    dropdown-toggle text-white">Se connecter <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right mt-2">
                            <li class="px-3 py-2">
                                <?php $this->load->view('form_connexion.php'); ?>
                            </li>
                        </ul>
                    <?php } else { ?>
                        <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-secondary
                    dropdown-toggle text-white"><?= $pseudo ?><span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right mt-2">
                            <li>
                                <a href="<?= base_url().'accueil/deconnexion'; ?>">
                                    <button id="deconnexion" class="btn btn-info">Deconnexion</button>
                                </a>
                            </li>
                        </ul>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div id="modalInscription" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Inscrivez-vous</h3>
                <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <?= form_open('formConnexion/inscription'); ?>
                <div class="form-group">
                    <?php echo form_error('email'); ?>
                    <input required name="email" value="<?php echo set_value('email'); ?>" placeholder="Email" class="form-control form-control-sm" type="text" size="30">
                </div>
                <div class="form-group">
                    <?php echo form_error('pseudo'); ?>
                    <input required name="pseudo" type="text" value="<?php echo set_value('pseudo'); ?>" placeholder="pseudo" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <?php echo form_error('mdp'); ?>
                    <input required name="mpd" type="password" value="<?php echo set_value('password'); ?>" placeholder="Mot de passe" class="form-control form-control-sm">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block bg-info">S'inscrire</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div id="content">
    <h1><?= $h1; ?></h1>
    <script>
        $(document).ready(function(){

            $('#search_product').autocomplete({
                source : "<?= base_url().'accueil/autocompletion'; ?>"
            });

            $('#search_product').on('keyup', function(event){
                if ( event.keyCode === 13){
                    event.preventDefault();
                    $('#recherche').click();
                }
            });

            $('#recherche').on('click', function(event){
                if ( $('#search_product').val().trim() !== '' ) {

                    window.location.replace("<?= base_url()."accueil/recherche/"; ?>"+encodeURI($('#search_product').val().trim()));
                }
            });
        });
    </script>

