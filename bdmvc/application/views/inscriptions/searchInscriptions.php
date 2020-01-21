<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-header">Annuler une inscription</h1>
            </div>        
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
		
        <div class="row">
        	
            <h3>Choisir l'activité</h3> 
			
			<?php echo form_open('inscriptions/getInscriptions'); ?>

			<div class="col-md-4 col-xs-12">
	    		<select id="selectIdActiv" name="idActiv" class="form-control">
					<?php foreach ($activites as $activite) : ?>
						<option <?php if(isset($results) && $results[0]->IdActivite == $activite->Id): echo 'selected="selected"'; endif; ?> value="<?php echo $activite->Id; ?>"><?php echo $activite->Nom; ?></option>
					<?php endforeach; ?>
	    		</select>
	    	</div>
         </div>

        <div class="row">
	        <div class="col-xs-6">
	        	<button class="btn btn-primary btn-lg">Rechercher</button>
	        	<?php echo form_close(); ?>  
    		</div>
        </div>

        <?php if( isset($noResults) ):?>
        	<div class="row">
        		<h3>Il ne reste aucune inscription pour cette activité.</h3>
        	</div>
    	<?php endif; ?>

        

        <?php if (isset($results)): ?>
        	<h3><?php echo count($results); ?> résultats correspondants</h3>
        	<?php if($_SESSION['infoUser']->NiveauAcces >= 7 && count($results) != 0 ): ?>
	        	<div class="row">
	        		<div class="col-md-8 col-xs-12 btn-lg text-right">
	        			<a href="<?php echo site_url('inscriptions/deleteAllInscriptions'.'/' .$results[0]->IdActivite); ?>">
	        				<button class="btn btn-primary">Supprimer les inscriptions des absents</button>
	        			</a>
	        		</div>
	        	</div>
        	<?php endif; ?>
        	<?php if( count($results) != 0 ):?>
		        <div class="row">
					<div class="col-md-8 col-xs-12">
						<table class="table table-striped table-responsive">
							<tr>
								<td><strong>Nom du joueur</strong></td>
								<td><strong>Nom de personnage</strong></td>
								<td><strong>Coût d'Inscription</strong></td>
								<td><strong></strong></td>
							</tr>
							<?php foreach ($results as $key => $result) : ?>
								<tr>
									<td><?php echo $result->NomIndivComplet; ?></td>
									<td><?php echo $result->NomPersonnage; ?></td>
									<td><?php echo $result->PrixInscrit; ?></td>
									<td>
										<a href="<?php echo site_url('inscriptions/deleteInscription') .'/' .$result->IdActivite .'/' .$result->IdIndividu .'/' .$result->IdPersonnage; ?>">
											<button class="btn btn-danger"><span class="fa fa-close"></span></button>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
		        </div>
	        <?php endif; ?>
        

        <?php endif; ?>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<script>
	$('#selectIdActiv').on('change', function(){
		console.log($('#selectIdActiv'));
		$('.idActiv').val( $('#selectIdActiv :selected').val() );
	})
</script>