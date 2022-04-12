<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ContentUp Demo</title>
  <?php
	echo $this->Html->script('jquery.js');
	echo $this->Html->script('common.js');
  ?>
</head>
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
}

</style>
<body>
	<?php echo $this->Html->link('ContentUp',['controller'=>'','action'=>'kwgrp']);?>
	<?php
		echo $this->Form->create(null,['name'=>'frmkwgrp','id'=>'frmkwgrp',
									 'url' => [
											  'controller' => '',
											  'action' => 'keywords'
											]
									  ]); 
	?>
		<table border="1">
			<tr>
				<td width="300">
		            <table border="0" width="100%" style="font-size:14px;">
						<tr>
							<?php
							if(count($resultarr)==count($_SESSION['kwgrp']))
								$check_all = 'checked';
							else
								$check_all = '';
							?>
							<th><input type="checkbox" <?php echo $check_all;?> name='select_all_kwgrp' id='select_all_kwgrp' onclick='check_all_kwgrp();'>KW Group</th>
							<th>Volume</th>
							<th>Keywords</th>
						</tr>
						<?php
						$j=0;
						foreach($resultarr as $result)
						{
							echo '<tr>';
								echo '<td>';
								?>
								<input type="checkbox" name="all_kwgrp[]" class="checkbox_kwgrp" value="<?php echo $result['kwgrp'];?>" id=<?php echo 'kwgrp_'.$j;?> >
								<a href='#' onclick='kwgrp_click_href("<?php echo $result['kwgrp'];?>");'>
								<?php echo $result['kwgrp'].'</a></td>';
								echo '<td>'.number_format($result['sumvol']).'</td>';
								echo '<td>'.$result['kwcnt'].'</td>';
							echo '</tr>';
							$j++;
						}
						?>
						<input type="hidden" name="kwgrp_val" id="kwgrp_val">
					</table>
				</td>
				<td width="1000"><<< Click on a KW group</td>
			</tr>
			
		</table><br>
		<input type='submit' value='Submit'>
	<?php echo $this->Form->end(); ?>
</body>
</html>