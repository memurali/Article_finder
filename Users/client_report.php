<?php
	echo $this->Html->css('report.css');
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
	echo $this->Html->script('common.js');
?>
<script>
$(document).ready(function(){
	$('#tblclreport_master th').click(function(){
		var table = $(this).parents('table').eq(0)
		var rows = table.find('tr:gt(1)').toArray().sort(comparer($(this).index()))
		this.asc = !this.asc
		if (!this.asc){rows = rows.reverse()}
		for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	});
});
function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
		return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text().replace(/,/g,'') }
</script>
<div class='row_client'>
	<div class='col_cl_master' style="background-color:#eee;">
		<?php
		echo '<h2>'.$kwgrp.' Group</h2>';
		echo '<h3>KEYWORDS</h3>';
		if(count($masterkw)>0)
		{
		?>
			<table class='kwtbl' id='tblclreport_master'>
				<thead>
					<tr>
						<th><a class='thsort'>Keyword</a></th>
						<th><a class='thsort'>Search Vol</a></th>
					</tr>
					<tr>
						<td class='tdkw' onMouseOver="this.style.cursor='pointer';" onclick="accordion_clreport('acc_master','tblclreport_master');"><b><span id='acc_master'>+</span><?php echo $kwgrp;?></b></td>
						<td class='tdvol'><b><?php echo number_format(array_sum(array_column($masterkw,'Volume')));?></b></td>
					</tr>
				</thead>
				<tbody id='tbdyclreport_master'>
					<?php
													
					foreach ($masterkw as $kw)
					{
						if(in_array($kw['Keyword'],array_column($clientkw,'Ranking_keyword')))
						{
							$color = 'limegreen';
							$onlcick = 'onclick = check_keyword(this.textContent)';
							$title_attr = '';
						}
						else
						{
							$color = '';
							$onlcick = '';
							$title_attr = 'No ranking match';
						}
						echo '<tr class=hide_tr>';
							echo '<td onMouseOver=this.style.cursor="pointer"; title="'.$title_attr.'" style="color:'.$color.'" class="tdkw"  '.$onlcick.'>'.$kw['Keyword'].'</td>';
							echo "<td class='tdvol'>".number_format($kw['Volume'])."</td>";
							//echo "<td class='tdvol'>".$kw['Volume']."</td>";
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		<?php	
		}
		else
			echo 'No master keywords found';
		?>
	</div>
	<div class='col_cl_kw' style="background-color:#bbb5b56e;">
		<?php
		echo '<h2><a href="//'.$site_domain.'" target="_blank" >'.$site_domain.'</a></h2>';
		echo '<h3>RANKING KEYWORDS</h3>';
		if(count($clientkw)>0)
		{
		?>
			<table class='kwtbl' id='tblclreport_kw'>
				<tr>
					<th><a class='thsort' onclick="sortTable('tbdyclreport_kw',0)">Keyword</a></th>
					<th><a class='thsort' onclick="sortTable('tbdyclreport_kw',1,'number')">Search Vol</a></th>
					<th><a class='thsort' onclick="sortTable('tbdyclreport_kw',2,'number')">Rank</a></th>
				</tr>
				<tr>
					<td class='tdkw' onMouseOver="this.style.cursor='pointer';" onclick="accordion_clreport('acc_kw','tblclreport_kw');"><b><span id='acc_kw'>+</span><?php echo $kwgrp;?></b></td>
					<td class='tdvol'><b><?php echo number_format(array_sum(array_column($clientkw,'Volume')));?></b></td>
					<td class='tdcnt'><b><?php echo number_format((array_sum(array_column($clientkw,'avg_pos'))/count($clientkw)),0);?></b></td>
				</tr>
				<tbody id='tbdyclreport_kw'>
					<?php
					foreach ($clientkw as $kw)
					{
						echo '<tr class=hide_tr>';
							echo "<td class='tdkw'>".$kw['Ranking_keyword']."</td>";
							if($kw['Volume']==NULL)
								$kwvol = 0;
							else
								$kwvol = $kw['Volume'];
							
							echo "<td class='tdvol'>".number_format($kwvol)."</td>";
							echo "<td class='tdcnt'>".number_format($kw['avg_pos'],0)."</td>";
						echo '</tr>';
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
	<div class='col_cl_page' style="background-color:#bbb5b59e;">
		<span class='link'>
			<?php echo $this->Html->link('Menu',['controller'=>'','action'=>'menu']);?>
		</span>
		<?php
		echo '<h2>'.$this->Html->link('Back',['controller'=>'','action'=>'master-report']).'</h2>';
		echo '<h3>RANKING PAGES</h3>';
		if(count($clientpage)>0)
		{
		?>
			<table class='kwtbl'>
				<thead>
					<tr>
						<th class='tdurl'>URL</th>
						<th class='tdcnt'>Content Quality</th>	
						<th class='tdcnt'><a class='thsort' onclick="sortTable('tbdy_clreport_page',2,'number')">Internal Links</a></th>	
						<th class='tdvol'>Action</th>
					</tr>
					<tr>
						<td class='tdurl'><b>Ranking Page</b></td>
						<td class='tdcnt'>
							<select name='content_qlty' id='content_qlty'>
								<!--<option value="all">--select--</option>
								<option value='low'>low</option>
								<option value='medium'>medium</option>
								<option value='high'>high</option>--->
							</select>
						</td>
						<td class='tdcnt'>
							<b><?php echo number_format((array_sum(array_column($clientpage,'Internal_links'))/count($clientpage)),0);?></b>
						</td>
						<td class='tdvol'>
							<select name='action_filter' id='action_filter'>
								<?php
								if(count($actionarr)>0)
								{
									echo '<option value="all">--select--</option>';
									foreach($actionarr as $action)
									{
										echo '<option value="'.$action['Action'].'">'.$action['Action'].'</option>';
									}
								}
								?>
							</select>
						</td>
					</tr>
				</thead>
				<tbody id='tbdy_clreport_page'>
				<?php
				foreach ($clientpage as $page)
				{
					if($page['Ranking_position']==0)
						$class= 'no_rank';
					else
						$class= '';
					
					echo '<tr class='.$class.'>';
						echo '<td class="tdurl">'.$page['Url'].'</td>';
						echo "<td class='tdcnt'>".$page['Quality_score']."</td>";
						echo "<td class='tdcnt'>".$page['Internal_links']."</td>";
						echo "<td class='tdvol'>".$page['Action']."</td>";
					echo '</tr>';
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
</div>