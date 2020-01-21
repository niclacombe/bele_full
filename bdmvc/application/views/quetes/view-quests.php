<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="page-header">Voir les quêtes</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
        	<div class="col-xs-12">
        		<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#actif">En cours <span class="badge badge-light"><?php echo count($quetes['actif']); ?></span></a></li>
				    <li><a data-toggle="tab" href="#dem">Demandes <span class="badge badge-light"><?php echo count($quetes['dem']); ?></span></a></li>
				</ul>

				<div class="tab-content">
					<div id="actif" class="tab-pane fade in active">
						<h3>Quêtes actives</h3>
						<table class="table-responsive table-striped table">
							<tr>
								<th>Nom du personnage</th>
								<th>Objet</th>
								<th>Suggestion</th>
								<th>Parties</th>
								<th>État</th>
								<th>Responsable</th>
								<th></th>
							</tr>
						<?php foreach ($quetes['actif'] as $quete) : ?>
		        			<tr>
		        				<td><?php echo $quete->nomPerso; ?></td>
		        				<td><?php echo $quete->Objet; ?></td>
		        				<td><?php echo $quete->Suggestions; ?></td>
		        				<td><?php echo $quete->Parties; ?></td>
		        				<td><?php echo $quete->CodeEtat; ?></td>
		        				<td><?php echo $quete->nomRespo; ?></td>
		        				<td><a class="detailQuete" data-id-quete="<?php echo $quete->Id; ?>" href="#"><button class="btn btn-primary"><span class="fa fa-eye"></span></button></a></td>
		        			</tr>
	        			<?php endforeach; ?>
	        			</table>
					</div>
					<div id="dem" class="tab-pane fade">
						<h3>Demandes de quêtes</h3>
						<table class="table-responsive table-striped table">
							<tr>
								<th>Nom du personnage</th>
								<th>Objet</th>
								<th>Suggestion</th>
								<th>Parties</th>
								<th>État</th>
								<th>Responsable</th>
								<th></th>
							</tr>
						<?php foreach ($quetes['actif'] as $quete) : ?>
		        			<tr>
		        				<td><?php echo $quete->nomPerso; ?></td>
		        				<td><?php echo $quete->Objet; ?></td>
		        				<td><?php echo $quete->Suggestions; ?></td>
		        				<td><?php echo $quete->Parties; ?></td>
		        				<td><?php echo $quete->CodeEtat; ?></td>
		        				<td><?php echo $quete->nomRespo; ?></td>
		        				<td><a href="#"><button class="btn btn-primary"><span class="fa fa-eye"></span></button></a></td>
		        			</tr>
	        			<?php endforeach; ?>
	        			</table>
					</div>
				</div>
	        </div>
        </div>
    </div>
</div>