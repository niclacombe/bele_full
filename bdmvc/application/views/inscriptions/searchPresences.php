<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-header">Consulter les présences</h1>
            </div>        
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
		
        <div class="row">
        	
            <h3>Choisir l'activité</h3>            
			
			<?php echo form_open('inscriptions/getPresences'); ?>

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

        

        <?php if (isset($results)): ?>
        	<br><br>
        	<div class="row">
        		<div class="col-xs-12">
		        	<a href="<?php echo site_url('inscriptions/downloadPresencesList' .'/' .$results[0]->IdActivite); ?>">
	    	    		<button class="btn-primary btn-lg">Télécharger la liste  (.csv) <span class="fa fa-file-excel-o"></span></button>
	        		</a>
        		</div>
        	</div>	
        	<div class="row">
	        	<div class="col-md-6 col-xs-12">
		        	<div class="col-xs-6 text-left">
			        	<h3><?php echo count($results) ?> résultats correspondants</h3>
		        	</div>
		        	<?php if($_SESSION['infoUser']->NiveauAcces >= 6): ?>
			        	<div class="col-xs-6 text-right">
			        		<h3>Montant total : <?php echo number_format($total,2); ?> $</h3>
			        	</div>
		        	<?php endif; ?>
	        	</div>
        	</div>
	        <div class="row">
				<div class="col-md-6 col-xs-12">
					<table class="table table-striped table-responsive">
						<tr>
							<td><strong>Nom du joueur</strong></td>
							<td><strong>Reçu</strong></td>
						</tr>
						<?php foreach ($results as $result) : ?>
							<tr>
								<td><?php echo $result->NomIndivComplet .'<em> (' .$result->Compte .')</em>'; ?></td>
								<td><?php echo $result->Recu; ?></td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
	        </div>
        

        <?php endif; ?>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->