<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<span class='link'>
	<?php echo $this->Html->link('Back',['controller'=>'','action'=>'import_client']);?>
</span>
<?php
	echo $this->Form->create(null,
	['name'=>'addsite','id'=>'addsite',
	 'url' => [
	  'controller' => '',
	  'action' => 'addsite'
	]
	]); 
?>

	<table>
		<tr>
			<td>Site name :</td>
			<td><input type='text' name='sitename' id='sitename' required></td>
		</tr>
		<tr>
			<td>Site domain :</td>
			<td><input type='text' name='sitedomain' id='sitedomain' required></td>
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
		<th>Site name</th>
		<th>Site domain</th>
	</tr>
	<tbody>
		<?php
		for($i=0; $i<count($sitearr); $i++)
		{
			echo '<tr>';
				$s_no = $i+1;
				echo '<td class=tdcnt>'.$s_no.'</td>';
				echo '<td class=tdkw>'.$sitearr[$i]['Site'].'</td>';
				echo '<td class=tdkw><a href="//'.$sitearr[$i]['Site_domain'].'" target="_blank">'.$sitearr[$i]['Site_domain'].'</a></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>