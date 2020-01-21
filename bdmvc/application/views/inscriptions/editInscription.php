<div id="page-wrapper">
    <div class="container-fluid">
   	

	    	<div class="row">
	    		<div class="col-xs-12">
	    			<h2>Éditer la présence de <?php echo $inscription->Prenom .' ' . $inscription->Nom; ?></h2>
	    			<h2>Pour <?php echo $inscription->NomActiv; ?></h2> 
	    		</div>
	    	</div>
		<?php if(!isset($inscription->NomPersonnage) && $hasPaid == null ): ?>
	    	<div class="row">
	    		<?php echo form_open('inscriptions/addInscription/' .$inscription->Id .'/' .$inscription->IdActiv, array('class' => 'col-md-8 col-xs-12')); ?>

				
					<h3>Choisir quel personnage inscrire</h3>
					<select name="idPerso" id="" class="form-control">
						<?php foreach ($personnages as $personnage) : ?>
							<option value="<?php echo $personnage->Id; ?>"><?php echo $personnage->NomComplet; ?></option>
						<?php endforeach; ?>
					</select>
					<br><hr><br>
					<button class="btn btn-primary btn-block">Inscrire le joueur <span class="fa fa-check"></span></button>

				</div>


	    		<?php echo form_close(); ?>
	    	</div>
	    <?php elseif(($hasPaid == null && isset($inscription->NomPersonnage)) && $isLocation == false ): ?>
	    	<div class="row">
	    		<div class="col-xs-12 col-md-8 col-md-offset-2">
	    			<h3><?php echo $inscription->Prenom .' ' . $inscription->Nom; ?> est inscrit<?php if($inscription->Sexe == 'F'): echo 'e'; endif; ?> avec son personnage :</h3>
	    			<h3><?php echo $inscription->NomPersonnage; ?></h3>
	    		</div>
	    	</div>
	    	<?php if($hasDebts[0]->Montant != null): ?>
    		<div class="row">
    			<div class="col-xs-12 col-md-8 col-md-offset-2">
	    			<h4 style="color:red;">    				
	    				Ce joueur a une dette ! ( <?php echo $hasDebts[0]->Montant; ?> $ ) - 
	    				<a href="<?php echo site_url('administration/getCreditsAndDebts/' .$inscription->IdIndividu); ?>" target="_blank"><button class="btn btn-primary">Consulter</button>
	    				</a>
	    				<form style="display: inline-block;">
							<button type="button" class="btn btn-primary" onClick="history.go(0)">
								<span class="fa fa-refresh"></span>
							</button>
						</form>
	    			</h4>	
				</div>
    		</div>
    		<?php endif; ?>
	    	<div class="row">
	    		<?php echo form_open('inscriptions/addPresence/' .$inscription->Id .'/' .$inscription->IdActiv .'/' .$inscription->IdPersonnage, array('class' => 'col-md-8 col-xs-12')); ?>	    			

		    		<div class="col-xs-12 col-md-8 col-md-offset-2">
		    			<h4>À payer : </h4>
		    			<input type="text" class="form-control" name="montant" value="<?php echo $inscription->PrixInscrit; ?>">

		    			<input type="hidden" name="idActiv" value="<?php echo $inscription->IdActiv; ?>">
		    			<br><br>

		    			<?php if($inscription->ActivitesGratuites > 0) :?>
		    				<a href="<?php echo site_url('inscriptions/addFreePresence') .'/' .$inscription->Id .'/' .$inscription->IdActiv.'/' .$inscription->IdPersonnage; ?>">
		    					<button type="button" class="btn btn-success btn-block">Utiliser une Activité Gratuite !</button>
	    					</a>
		    				<br><br>
	    				<?php endif; ?>

		    			<button class="btn btn-primary btn-block">Ajouter la présence <span class="fa fa-check"></span></button>			    			
		    		</div>			

	    		<?php echo form_close(); ?>
	    	</div>
    	<?php else: ?>
    		<div class="row">
    			<div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
    				<h2><?php echo $inscription->Prenom .' ' . $inscription->Nom; ?></h2>
    				<h3>est inscrit<?php if($inscription->Sexe == 'F'): echo 'e'; endif; ?> avec son personnage :</h3>
	    			<h2><?php echo $inscription->NomPersonnage; ?>.</h2>
	    			<h3>Son inscription est maintenant payée et sa présence notée.</h3>
	    			<h2>Bon GN !</h2>

	    			<a href="<?php echo site_url('inscriptions') . '/index/' . $inscription->IdActiv; ?>">
	    				<button class="btn-primary btn-lg n-block">
	    					<span class="fa fa-chevron-right"></span>
	    					Inscrire un autre joueur
	    					<span class="fa fa-chevron-left"></span>
    					</button>
	    			</a>

    			</div>
    		</div>

    	<?php endif; ?>
        
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

