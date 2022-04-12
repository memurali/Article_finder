<div>
	<?php
	  echo $this->Form->create(null,['data-abide'=>'','novalidate',
		'name'=>'art_search','id'=>'art_search',
		'url' => [
		  'controller' => '',
		  'action' => ''
		]
	  ]); 
	?>
		<div id='tag_div'>
			<?php
			if(count($tagarr)>0)
			{
				echo '<br>';
				if(count($_SESSION['tagarr'])==0)
					$check_all = 'checked';
				else
					$check_all = '';
				
				echo '<input type=checkbox name=select_all id=select_all  '.$check_all.' > Select All';//onclick="select_all_tag();"
				echo '<table class=tbltag>';
				$i=1;
				echo '<tr>';
				
				foreach($tagarr as $tag)
				{
					
					if(in_array($tag,$_SESSION['tagarr']))
						$checked = '';
					else
						$checked = 'checked';		
					
					echo "<td class=tdtag><input type=checkbox name=tag[] class=checkbox_tag id=tag_check".$i." value='".$tag."' ".$checked.">".$tag."</td>";//onclick='tagcheck(this.value,this.id);'
					if ($i % 8 == 0)
					{
						echo '<tr>';
						echo '</tr>';
					}
					$i++;
				}
				echo '</tr>';
				echo '</table>';
			}
			?>
		</div>
	<?php echo $this->Form->end(); ?>
	<div id='result_div'>
		<span><h2>Results</h2></span>
		<?php
			$tagcount_arr = $count_arr[0];
			if(count($tagcount_arr)>0)
			{
				echo '<table>';
					echo '<tr>';
						echo '<th>Tag</th>';
						echo '<th>Matches<br>(all sets)</th>';
						echo '<th>Matches % <br>(all sets)</th>';
					echo '</tr>';
					arsort($tagcount_arr);
					$all_tag = array_column($all_tag,'tag');
					foreach($tagcount_arr as $tag=>$tagcount)
					{
						$match_per = round(($tagcount/$dbcount)*100,2);
						if($match_per>1 && $match_per<=25)
							$color = '#F9EBEA';
						if($match_per>25 && $match_per<=50)
							$color = '#FEF9E7';
						if($match_per>50 && $match_per<=75)
							$color = '#EAFAF1';
						if($match_per>75 && $match_per<=100)
							$color = '#D4EFDF';
						
						/*if($match_per<1)
							$color = '';*/
						echo '<tr>';
							echo '<td style=background-color:'.$color.';>'.$tag.'</td>';
							echo '<td>'.$tagcount.'</td>';
							echo '<td>'.$match_per.'%</td>';
						echo '</tr>';
					}
					$zero_tagarr = array_values(array_diff( $all_tag , array_keys($tagcount_arr)));
					foreach($zero_tagarr as $zero_tag)
					{
						echo '<tr>';
							echo '<td>'.$zero_tag.'</td>';
							echo '<td>0</td>';
							echo '<td>0%</td>';
						echo '</tr>';
					}
				echo '</table>';
			}
		?>
		<span><h4>Log Data</h4></span>
		<?php
		    unset($count_arr[0]);
			$cntarr = call_user_func_array('array_merge', $count_arr);
			arsort($cntarr);
			$totsumval = array_sum($cntarr);
			echo '<table>';
				echo '<tr>';
					echo '<th>Keyword</th>';
					echo '<th>Matches</th>';
					echo '<th>Match %</th>';
				echo '</tr>';
				foreach($cntarr as $token=>$sumval)
				{
					echo '<tr>';
						echo '<td>'.$token.'</td>';
						echo '<td>'.$sumval.'</td>';
						echo '<td>'.round(($sumval/$totsumval)*100,2).'%</td>';
					echo '</tr>';
				}
			echo '</table>';
			echo '<br>';
			$totsumval_match = array_sum($matchcount_arr);
			echo '<table>';
				echo '<tr>';
					echo '<th>Match Types</th>';
					echo '<th>Matches</th>';
					echo '<th>Match %</th>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Single</td>';
					echo '<td>'.$matchcount_arr[1].'</td>';
					echo '<td>'.round(($matchcount_arr[1]/$totsumval_match)*100,2).'%</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Double</td>';
					echo '<td>'.$matchcount_arr[2].'</td>';
					echo '<td>'.round(($matchcount_arr[2]/$totsumval_match)*100,2).'%</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>Triple</td>';
					echo '<td>'.$matchcount_arr[3].'</td>';
					echo '<td>'.round(($matchcount_arr[3]/$totsumval_match)*100,2).'%</td>';
				echo '</tr>';
			echo '</table>';
				
		?>
	</div>
</div>


