<?php
require_once("actions/SettingsAction.php");
$page = new SettingsAction();
$page->execute();

require_once("header.php");
require_once("settings_sidemenu.php");
?>

<div class="container">

	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
		<h4>General settings</h4>
		<form role="form" method="post" action="settings.php?save">
		<?php 
			$settings = $page->settings();
			
			foreach($settings as $n => $v)
			{
			?>
				<div class="form-group">
					<label for="<?php echo $n; ?>" class="col-sm-3 control-label">
						<?php echo $n; ?>
					</label>
					
					<div class="col-sm-9">
						<input type="text" class="form-control" name="<?php echo $n; ?>" id="<?php echo $n; ?>" value="<?php echo $v; ?>">
					</div>
				
				</div>
			<?php 
			}
		
		
		?>
			<div class="form-group col-sm-6 col-sm-2-offset">
				<button type="submit" class="btn btn-default">Save</button>
			
			</div>
		</form>
		
		</div>
	</div>

</div>

<?php
require_once("footer.php");