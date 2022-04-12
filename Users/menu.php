<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<html>
	<body>
		<center>
			<div style='width:100%;height:100%;'>
				<table class='menurow'>
					<tr>
						<td>
							<?php echo $this->Html->link('Import master',['controller'=>'','action'=>'import_master']);?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->Html->link('Import client',['controller'=>'','action'=>'import_client']);?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->Html->link('Auto Categorization',['controller'=>'','action'=>'kwgrping']);?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->Html->link('Reports',['controller'=>'','action'=>'master_report']);?>
						</td>
					</tr>
				</table>
			</div>
		</center>
	</body>
</html>
