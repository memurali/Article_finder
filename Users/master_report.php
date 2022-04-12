<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>

<div class="row">
	<div class="column" style="background-color:#eee;">
		<?php
			echo $this->Form->create(null,
			['name'=>'master_report','id'=>'master_report',
			 'url' => [
			  'controller' => '',
			  'action' => 'master_report'
			]
			]); 
		?>
		Select category:
		<select name='category' id='category' onchange='select_category(this.value);' required>	
			<option value=''>--select--</option>
			<?php
			if(count($catarr)>0)
			{
				if($_SESSION['catid']!='')
					$catid = $_SESSION['catid'];
				else
					$catid = '';
				foreach($catarr as $cat)
				{
				?>
					<option <?php echo $catid==$cat['Catid']?'selected':'';?> value='<?php echo $cat['Catid'];?>'><?php echo $cat['Category'];?></option>
				<?php
				}
			}

			?>
		</select><br><br>

		<?php 
			echo $this->Form->end(); 
		?>
		<?php
		if($_SESSION['category']!='')
		{
			if(count($kwarr_group)>0)
			{
				echo '<h2>'.$_SESSION['category'].'</h2>';
				echo '<h3>KW Groups</h3>';
				?>
				<table class='maintbl'>
					<tr>
						<th class='tdkw'><a class='thsort' onclick="sortTable('tblsort',1)">Group</a></th>
						<th class='tdcnt'><a class='thsort' onclick="sortTable('tblsort',2,'number')"># KWs</a></th>
						<th class='tdvol'><a class='thsort' onclick="sortTable('tblsort',3,'number')">Search Vol</a></th>
					</tr>
					
					<tbody id='tblsort'>
						<?php
						$i=0;
						foreach($kwarr_group as $kwgrp)
						{
						?>
						<tr>
							<td colspan="3">
								<table class='kwtbl' id='hide_row_<?php echo $i;?>'>
									<tr class="expandable">
										<td class='tdkw' onMouseOver="this.style.cursor='pointer';" onclick='show_kw("hide_row_<?php echo $i;?>","compress_<?php echo $i;?>","<?php echo $kwgrp['Kw_Group'];?>");'>
											<b>
												<span class='accordion' id='compress_<?php echo $i;?>'>+</span>
												<?php echo $kwgrp['Kw_Group'];?>
											</b>
										</td>
										<td class='tdcnt'><b><?php echo $kwgrp['kwcnt'];?></b></td>
										<td class='tdvol'><b><?php echo number_format($kwgrp['vol']);?></b></td>
									</tr>
								</table>
							</td>
						</tr>
						<?php
							$i++;
						}
						?>
					</tbody>
				</table>	
			<?php	
			}
			else
				echo 'No master keywords found';
		}
		?>
	</div>
	<div class="column" style="background-color:#bbb5b56e;">
		<span class='link'>
			<?php echo $this->Html->link('Menu',['controller'=>'','action'=>'menu']);?>
		</span>
		<?php
		if(count($sitearr)>0)
		{
		?>
			Select site:
			<select name='site' id='site' onchange='select_site_report(this.value);' required>	
				<option value=''>--select--</option>
				<?php
					if($_SESSION['siteid']!='')
						$siteid = $_SESSION['siteid'];
					else
						$siteid = '';
					foreach($sitearr as $site)
					{
					?>
						<option <?php echo $siteid==$site['Siteid']?'selected':'';?> value='<?php echo $site['Siteid'];?>'><?php echo $site['Site'];?></option>
					<?php
					}
				?>
			</select>
		<?php
		}
		?>
		<div class='tablerow' id='client_div'>
		
		</div>
	</div>
</div>

