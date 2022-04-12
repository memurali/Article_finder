<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<span class='link'>
	<?php echo $this->Html->link('Back',['controller'=>'','action'=>'import_master']);?>
</span>
<?php
	echo $this->Form->create(null,
	['name'=>'addcat','id'=>'addcat',
	 'url' => [
	  'controller' => '',
	  'action' => 'addcategory'
	]
	]); 
?>

	<table>
		<tr>
			<td>Category :</td>
			<td><input type='text' name='category' id='category' required></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' value='Submit'></td>
		</tr>
	</table>
	
	
	
<?php 
	echo $this->Form->end(); 
?>
<table class='kwtbl'>
	<tr>
		<th>S.No</th>
		<th>Category</th>
	</tr>
	<tbody>
		<?php
		for($i=0; $i<count($catarr); $i++)
		{
			echo '<tr>';
				$s_no = $i+1;
				echo '<td class=tdcnt>'.$s_no.'</td>';
				echo '<td class=tdkw>'.$catarr[$i]['Category'].'</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>