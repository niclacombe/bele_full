<div class="col-xs-12">
	<h3>Modifier la recette <?php echo $recette->Nom ?></h3>
</div>

<?php echo form_open('administration/editRecette/' .$recette->Id);?>

	<div class="form-group col-xs-12">
		<label for="name">Nom</label>
		<input name="name" class="form-control" value="<?php echo $recette->Nom; ?>">
	</div>

	<div class="form-group col-xs-12">
		<button class="btn btn-primary">Modifier <span class="fa fa-edit"></span></button>
	</div>

<?php echo form_close(); ?>
