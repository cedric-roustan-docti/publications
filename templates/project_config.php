<?php
require __DIR__.'/common/header.php';
require __DIR__.'/common/top_nav.php';
?>

<h1 class="text-center">
	<?php $current_project ? 'Configuration du projet : '.htmlentities($current_project->get_name()) : 'Creation d\'un projet'; ?>
</h1>

<form action="" method="post" class="form-horizontal col-lg-8 col-lg-offset-2">
	<fieldset>
		<legend>Général</legend>

		<div class="form-group<?php echo isset($errors['name']) ? ' has-error' : ''; ?>">
			<label for="name" class="col-lg-2 control-label">Nom du projet</label>

			<div class="col-lg-10">
				<input type="text" class="form-control"
					   id="name" name="name"
					   value="<?php echo isset($name) ? htmlentities($name) : ''; ?>">
				<?php if (isset($errors['name'])) { ?>
					<span class="help-block"><?php echo $errors['name']; ?></span>
				<?php } ?>
			</div>
		</div>

		<div class="form-group<?php echo isset($errors['description']) ? ' has-error' : ''; ?>">
			<label for="description" class="col-lg-2 control-label">Description (texte libre - peut être du HTML)</label>

			<div class="col-lg-10">
				<textarea class="form-control"
					   id="description" name="description"
					   ><?php echo isset($description) ? htmlentities($description) : ''; ?></textarea>
				<?php if (isset($errors['description'])) { ?>
					<span class="help-block"><?php echo $errors['description']; ?></span>
				<?php } ?>
			</div>
		</div>

		<div class="form-group<?php echo isset($errors['visible']) ? ' has-error' : ''; ?>">
			<label for="visible" class="col-lg-2 control-label">Afficher le projet dans les onglets</label>

			<div class="col-lg-10">
				<input type="checkbox" class="form-control"
					   id="visible" name="visible"
					   <?php echo !empty($visible) ? 'checked="checked"' : ''; ?>">
				<?php if (isset($errors['visible'])) { ?>
					<span class="help-block"><?php echo $errors['visible']; ?></span>
				<?php } ?>
			</div>
		</div>

		<div class="form-group<?php echo isset($errors['has_prod']) ? ' has-error' : ''; ?>">
			<label for="has_prod" class="col-lg-2 control-label">Le projet peut avoir des publications</label>

			<div class="col-lg-10">
				<input type="checkbox" class="form-control"
					   id="has_prod" name="has_prod"
					   <?php echo !empty($has_prod) ? 'checked="checked"' : ''; ?>">
				<?php if (isset($errors['has_prod'])) { ?>
					<span class="help-block"><?php echo $errors['has_prod']; ?></span>
				<?php } ?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Repository</legend>

		<div class="form-group<?php echo isset($errors['vcs_base']) ? ' has-error' : ''; ?>">
			<label for="vcs_base" class="col-lg-2 control-label">Base du repository</label>

			<div class="col-lg-10">
				<input type="text" class="form-control"
					   id="vcs_base" name="vcs_base"
					   value="<?php echo isset($vcs_base) ? htmlentities($vcs_base) : ''; ?>">
				<?php if (isset($errors['vcs_base'])) { ?>
					<span class="help-block">
						Ce qui se met après l'url de base des repository mais avant le path spécifique du projet. Il sera commun aux branches mergées avec ce projet.
						<?php echo '<br />'.$errors['vcs_base']; ?>
					</span>
				<?php } ?>
			</div>
		</div>

		<div class="form-group<?php echo isset($errors['vcs_path']) ? ' has-error' : ''; ?>">
			<label for="vcs_path" class="col-lg-2 control-label">Chemin du projet dans le repository</label>

			<div class="col-lg-10">
				<input type="text" class="form-control"
					   id="vcs_path" name="vcs_path"
					   value="<?php echo isset($vcs_path) ? htmlentities($vcs_path) : ''; ?>">
				<?php if (isset($errors['vcs_path'])) { ?>
					<span class="help-block">
						Sera rajouté après l'url de base du repository et le champ "Base du repository".
						<?php echo '<br />'.$errors['vcs_path']; ?>
					</span>
				<?php } ?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Dépendances (external)</legend>

		<div class="form-group<?php echo isset($errors['externals']) ? ' has-error' : ''; ?>">
			<div class="col-lg-10 col-lg-offset-2">
				<?php
					if ($current_project) {
						foreach ($current_project->get_externals() as $external) {
							echo '<select name="externals[]" class="form-control">';
								echo '<option value="0">--</option>';
								foreach ($project_shared->get_projects() as $project) {
									echo '<option value="'.$project->get_id().'"'.
										 ($project == $external ? 'selected="selected"' : '')
										 .'>'
										 .htmlentities($project->get_name())
										.'</option>';
								}
							echo '</select><br />';
						}
					}

					echo '<select name="externals[]" id="new_external" class="form-control">';
						echo '<option value="0">--</option>';
						foreach ($project_shared->get_projects() as $project) {
							echo '<option value="'.$project->get_id().'">'
								 .htmlentities($project->get_name())
								 .'</option>';
						}
					echo '</select><br />';
				?>
				<a href="#" id="add_external">Ajouter</a><br />
				<?php if (isset($errors['externals'])) { ?>
					<span class="help-block">
						Liste des autres projets (branches) dont dépend ce projet.
						<?php echo '<br />'.$errors['externals']; ?>
					</span>
				<?php } ?>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>Destinataire des publications</legend>

		<div class="form-group<?php echo isset($errors['recipients']) ? ' has-error' : ''; ?>">
			<div class="col-lg-10 col-lg-offset-2">
				<?php
					$this_recipients = !empty($recipients) ? $recipients : ($current_project ? $current_project->get_recipients() : array());
					foreach ($this_recipients as $recipient) {
						echo '<input type="email" class="form-control" name="recipients[]" value="'.htmlentities($recipient).'" /><br />';
					}
				?>
				<input type="email" class="form-control" name="recipients[]" id="new_recipient" value="" /><br />

				<a href="#" id="add_recipient">Ajouter</a><br />
				<?php if (isset($errors['recipients'])) { ?>
					<span class="help-block">
						Liste des personnes destinées à recevoir le mail de publication.
						<?php echo '<br />'.$errors['recipients']; ?>
					</span>
				<?php } ?>
			</div>
		</div>
	</fieldset>

	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<input type="submit" value="Enregistrer"/>
		</div>
	</div>
</form>

<script type="text/javascript">
	$('#add_external').click(function() {
		$('#new_external')
			.clone()
				.attr('id', '')
				.insertBefore($('#add_external'))
				.after('<br />');
		return false;
	});

	$('#add_recipient').click(function() {
		$('#new_recipient')
			.clone()
				.val('')
				.attr('id', '')
				.insertBefore($('#add_recipient'))
				.after('<br />');
		return false;
	});
</script>

<?php
require __DIR__.'/common/footer.php';
?>