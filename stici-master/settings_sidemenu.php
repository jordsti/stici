		<div class="sidemenu">
			<h4>Settings</h4>
			<?php 
			if($page->testGroupFlags(Group::$EditSettings))
			{
			?>
			<a class="btn btn-default" href="settings.php">General settings</a>
			<?php
			}
			?>
			
			<?php 
			if($page->testGroupFlags(Group::$AdminUsers))
			{
			?>
			<a class="btn btn-default" href="users.php">Users</a>
			<a class="btn btn-default" href="groups.php">Groups</a>
			<?php 
			}
			?>
			
			<?php 
			if($page->testGroupFlags(Group::$AdminWorkers))
			{
			?>
			<a class="btn btn-default" href="workers_key.php">Workers</a>
			<?php 
			}
			?>
		</div>