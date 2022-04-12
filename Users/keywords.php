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
				<td width="300"> <!--COL 1 -->
      
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
							$kwgrp = $result['kwgrp'];
							$kwgrp = str_replace('"',"'",$kwgrp);
							if(count($_SESSION['kwgrp'])==1)
							{
								if($kwgrp==$_SESSION['kwgrp'][0])
								{
									$bgcolor="yellow";
								}
								else
									$bgcolor = "";
							}
							else
							{
								if(in_array($kwgrp,$_SESSION['kwgrp']))
									$checked = 'checked';
								else
									$checked = '';
							}
							
							echo '<tr>';
								echo '<td bgcolor="'.$bgcolor.'">';
								?>
								<input type="checkbox" <?php echo $checked;?>  name="all_kwgrp[]" class="checkbox_kwgrp" value="<?php echo $kwgrp;?>" id=<?php echo 'kwgrp_'.$j;?> >
								<a href='#' onclick='kwgrp_click_href("<?php echo htmlspecialchars($kwgrp, ENT_QUOTES);?>");'>
								<?php echo $kwgrp.'</a></td>';
								echo '<td>'.number_format($result['sumvol']).'</td>';
								echo '<td>'.$result['kwcnt'].'</td>';
							echo '</tr>';
							$j++;
						}
						?>
      				</table>
				</td>

				<td width="300"> <!--COL 2 -->
      
					<table border="0" width="100%" style="font-size:14px;">
						<tr align="left">
							<th>Keyword</th>
							<th>Volume</th>
						</tr>
						<?php
						foreach($kwarr as $kw)
						{
							$keyword = $kw['Keyword'];
							$keyword = str_replace('"',"'",$keyword);
							echo '<tr>';
								echo '<td>';
								?>
								<a href='#' onclick='keyword_click_href("<?php echo htmlspecialchars($keyword, ENT_QUOTES);?>")';><?php echo $keyword;?></a></td>
								<?php
								echo '<td>'.number_format($kw['Vol (US)']).'</td>';
							echo '</tr>';
								
						}
					    ?>
					 
					</table>
				</td>

				<td width="500"> <!--COL 3 -->
      
					<table border="0" width="100%" style="font-size:14px;">
						<tr align="left">
							<th>Articles</th>
							<th>Save</th>
						</tr>
						<?php
						if(count($artarr)>0)
						{
							foreach($artarr as $art)
							{
								echo '<tr>';
									echo '<td>'.$art['Article'].'</td>';
									echo '<td><a href="">save</a></td>';
								echo '</tr>';
								
							}
						}
						?>
					</table>
				</td>

			</tr>
		</table>
		<input type="hidden" name="kwgrp_val" id="kwgrp_val">
		<br>
		<input type='submit' value='Submit'>
	<?php echo $this->Form->end(); ?>
	
	<?php
		echo $this->Form->create(null,
						['name'=>'frmkeyword','id'=>'frmkeyword',
						 'url' => [
									'controller' => '',
									'action' => 'articles'
								  ]
						]); 
	?>
		<input type="hidden" name="keyword" id="keyword">
	<?php echo $this->Form->end(); ?>
	
</body>
</html>