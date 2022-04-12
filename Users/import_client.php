<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<span class='link'>
	<?php echo $this->Html->link('Menu',['controller'=>'','action'=>'menu']);?>
</span>
<?php
	echo $this->Form->create(null,
	['name'=>'import_csv','id'=>'import_csv',
	'type' => 'file',
	'url' => [
	  'controller' => '',
	  'action' => 'import_client'
	]
	]); 
?>
	<table>
		<tr>
			<td>
				Site:
			</td>
			<td>
				<select name='site' id='site' required>
					<option value=''>--select--</option>
					<?php
					if(count($sitearr))
					{
						foreach($sitearr as $siteval)
						{
							echo "<option value=".$siteval['Siteid'].">".$siteval['Site']."</option>";
						}
					}
					?>
				</select>
				<?php
					echo $this->Html->link('Addnew',['controller'=>'','action'=>'addsite']);
				?>
			</td>
		</tr>
		<tr>
			<td>
				Category:
			</td>
			<td>
				<select name='category' id='category' required>
					<option value=''>--select--</option>
					<?php
					if(count($catarr))
					{
						foreach($catarr as $catval)
						{
							echo "<option value=".$catval['Catid'].">".$catval['Category']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Keyword Import:
			</td>
			<td>
				<input type='file' name='data_kw' id='data_kw'>
				<?php echo $this->Html->link('Download template',['controller'=>'users','action'=>'client_template']);?>
			</td>
		</tr>
		<tr>
			<td>
				Page Import:
			</td>
			<td>
				<input type='file' name='data_page' id='data_page'>
				<?php echo $this->Html->link('Download template',['controller'=>'users','action'=>'client_template_page']);?>
			</td>
		</tr>
	</table>
	<input type="submit" value="submit">
	
<?php 
	echo $this->Form->end(); 
	if(count($message)>0)
	{
		echo $message[0].'<br>';
		if($message[1]!='')
			echo 'Error :  '.$message[1];
	}

?>
