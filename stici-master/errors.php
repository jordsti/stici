<?php
if($page->errorsExists())
{
?>
<div class="container">
	<div class="panel panel-danger">
		<div class="panel-heading">
		Error(s)
		</div>
		<div class="panel-body">
		<ul>
			<?php 
			foreach($page->errors as $e)
			{
			?>
				<li><?php echo $e; ?></li>
			<?php
			}
			?>
			</ul>
		</div>
	</div>
</div>

<?php
}
