<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<html>
	<body>
		<span class='link'>
			<?php echo $this->Html->link('Menu',['controller'=>'','action'=>'menu']);?>
		</span>
		<?php
			echo $this->Form->create(null,
			['name'=>'import_csv','id'=>'import_csv',
			'type' => 'file',
			'url' => [
			  'controller' => '',
			  'action' => 'import_master'
			]
			]); 
		?>

			<table>
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
						<?php
							echo $this->Html->link('Addnew',['controller'=>'','action'=>'addcategory']);
						?>
					</td>
				</tr>
				<tr>
					<td>
						Import:
					</td>
					<td>
						<input type='file' name='data' id='data'>
						<?php echo $this->Html->link('Download template',['controller'=>'users','action'=>'master_template']);?>
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
	</body>
</html>