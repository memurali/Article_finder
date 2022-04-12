<?php
if($render_action=='show_kw')
{
	if(count($kwarr)>0)
	{
		$k=0;
		foreach($kwarr as $kw)
		{
			if($k<5)
				$trclass= 'show_tr';
			else
				$trclass = 'hide_tr';
			echo '<tr class='.$trclass.'>';
				echo '<td class=tdkw>'.$kw['Keyword'].'</td>';
				echo '<td class=tdcnt>'.$kw['kwcnt'].'</td>';
				echo '<td class=tdvol>'.number_format($kw['Volume']).'</td>';
			echo '</tr>';
			if($k==4)
			{
				echo '<tr>';
					echo '<td class=tdkw><a class=more_kw onclick="show_more(this);">more</a></td>';
					echo '<td class=tdcnt></td>';
					echo '<td class=tdvol></td>';
				echo '<tr>';
			}
			$k++;
		}
		
	}
	
}
if($render_action=='client_report')
{
	
	echo '<h2><a href="//'.$site_arr[0]['Site_domain'].'" target="_blank" >'.$site_arr[0]['Site_domain'].'</a></h2>';
	echo "<div class='tablecolumn'>";
	if(count($kwgrp_arr)>0)
	{
		$totkwcount = array_sum(array_column($kwgrp_arr,'kwcnt'));
		echo '<h3>Keywords     '.$totkwcount.'</h3>';
		?>
		
		<table class='kwtbl_cl'>
			<tr>
				<th><a class='thsort' onclick="sortTable('tblrank',0)">Group</a></th>
				<th><a class='thsort' onclick="sortTable('tblrank',1,'number')">Matches</a></th>
			</tr>
			<tbody id='tblrank'>
				<?php
				foreach($kwgrp_arr as $kwgrp)
				{
				?>
					<tr>
						<td class='tdgrp'><a onclick='client_report("<?php echo $kwgrp['Kw_Group'];?>");'><?php echo $kwgrp['Kw_Group'];?></a></td>
						<td class='tdcnt'><?php echo $kwgrp['kwcnt'];?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		
	<?php
	}
	else
		echo 'No client keywords found';
	?>
	</div>
	<div class='tablecolumn'>
		<?php
		if(count($pagegrp_arr)>0)
		{
			$totkwcount = array_sum(array_column($pagegrp_arr,'kwcnt'));
			echo '<h3>Pages     '.$totkwcount.'</h3>';
			?>
			<table class='kwtbl_cl'>
				<tr>
					<th><a class='thsort' onclick="sortTable('tblpage',0)">Group</a></th>
					<th><a class='thsort' onclick="sortTable('tblpage',1,'number')">Matches</a></th>
				</tr>
				<tbody id='tblpage'>
					<?php
					foreach($pagegrp_arr as $kwgrp)
					{
					?>
						<tr>
							<td class='tdgrp'><a onclick='client_report("<?php echo $kwgrp['Kw_Group'];?>");'><?php echo $kwgrp['Kw_Group'];?></a></td>
							<td class='tdcnt'><?php echo $kwgrp['kwcnt'];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		<?php
		}
		else
			echo 'No client pages found';
		?>
	</div>
	<?php
	echo $this->Form->create(null,
	['name'=>'frmclreport','id'=>'frmclreport',
	 'url' => [
	  'controller' => '',
	  'action' => 'client_report'
	]
	]); 
	?>
		<input type='hidden' name='kwgrp_report' id='kwgrp_report'>
		<input type='hidden' name='siteid' id='siteid' value='<?php echo $siteid;?>'>
	<?php 
	echo $this->Form->end();
	
}
?>