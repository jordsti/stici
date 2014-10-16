<?php
	require_once("actions/EditBuildAction.php");
	$page = new EditBuildAction();
	$page->execute();
	require_once("header.php");
	
	if(count($page->getErrors()) > 0)
	{
		$errors = $page->getErrors();
		foreach($errors as $e)
		{
			echo $e;
		}
	}
	else
	{
	
	require_once("job_sidemenu.php");
?>

		<div class="container">
			<div class="col-md-10 col-md-offset-1">
			
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title">
							<strong>Build configuration</strong>
						</div>
					</div>
				</div>

				<div class="panel-body">
				<form role="form" method="post" action="editbuild.php?job_id=<?php echo $page->getJob()->getId();?>&save">
					<div id="envs">
					<h4>Environnements variables</h4>
					
					<?php
					$envs = $page->getEnvs();
					$ie = 0;
					foreach($envs as $e)
					{
					?>
						<div class="form-group" id="env_<?php echo $ie; ?>">
								<label for="env_name_<?php echo $ie; ?>">Name</label>
								<input type="text" class="form-control" id="env_name_<?php echo $ie; ?>" name="env_name_<?php echo $ie; ?>" value="<?php echo $e->getName(); ?>">
								
								<label for="env_value_<?php echo $ie; ?>">Value</label>
								<input type="text" class="form-control" id="env_value_<?php echo $ie; ?>" name="env_value_<?php echo $ie; ?>" value="<?php echo $e->getValue(); ?>">
								
								<input type="hidden" name="env_id_<?php echo $ie; ?>" id="env_id_<?php echo $ie; ?>" value="<?php echo $e->getId(); ?>">
								<button type="button" class="btn btn-default" onclick="delete_env(<?php echo $ie; ?>);">Delete</button>
						</div>
					<?php
						$ie++;
					}
					
					?>
					<button type="button" id="env_add_btn" class="btn btn-default" onclick="btn_add_new_env();">Add a new variable</button>
					</div>
					
					<div id="steps">
					<h4>Build Steps</h4>
										
					<?php
					$steps = $page->getSteps();
					$ie = 0;
					foreach($steps as $s)
					{
					?>
						<div class="form-group" id="step_<?php echo $ie; ?>">
								<label for="step_exe_<?php echo $ie; ?>">Executable</label>
								<input type="text" class="form-control" id="step_exe_<?php echo $ie; ?>" name="step_exe_<?php echo $ie; ?>" value="<?php echo $s->getExecutable(); ?>">
								
								<label for="step_args_<?php echo $ie; ?>">Arguments</label>
								<input type="text" class="form-control" id="step_args_<?php echo $ie; ?>" name="step_args_<?php echo $ie; ?>" value="<?php echo $s->getArgs(); ?>">
								
								<label for="step_order_<?php echo $ie; ?>">Order</label>
								<input type="text" class="form-control" id="step_order_<?php echo $ie; ?>" name="step_order_<?php echo $ie; ?>" value="<?php echo $s->getOrder(); ?>">
								
								<div class="checkbox">
									<label>
										<?php
										if($s->testFlags(BuildStep::$IgnoreReturn))
										{
										?>
											<input type="checkbox" value="true" id="step_flags_ignore_return_<?php echo $ie; ?>" name="step_flags_ignore_return_<?php echo $ie; ?>" checked>
										<?php
										}
										else
										{
										?>
											<input type="checkbox" value="true" id="step_flags_ignore_return_<?php echo $ie; ?>" name="step_flags_ignore_return_<?php echo $ie; ?>">
										<?php
										}
										?>
										Ignore Return Value
									</label>
								</div>
								
								<input type="hidden" name="step_id_<?php echo $ie; ?>" id="step_id_<?php echo $ie; ?>" value="<?php echo $s->getId(); ?>">
								<button type="button" class="btn btn-default" onclick="delete_step(<?php echo $ie; ?>);">Delete</button>
						</div>
					<?php
						$ie++;
					}
					
					?>
					<button type="button" id="step_add_btn" class="btn btn-default" onclick="btn_add_new_step();">Add a new step</button>
					
					</div>
					
					<input type="hidden" name="delete_envs" value="" id="delete_envs">
					<input type="hidden" name="delete_steps" value="" id="delete_steps">
					
				<br />
				<button type="submit" class="btn btn-default">Save</button>
				</form>
				</div>
				

			</div>
		
		</div>

<?php
	}

	require_once("footer.php");