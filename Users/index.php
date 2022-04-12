<html>
	<head>
		<style>
			#cat_div
			{
				display:none;
			}
			table, td, th {  
			  border: 1px solid #958282;
			  border-collapse: collapse;
			  text-align: left;
			  padding: 5px;
			}
			.tbltag,.tdtag
			{
				border: none;
			}
		</style>
		<?php
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('common.js');
		?>
		
	</head>
	<body>
		<div>
			<div style='float:right;'>
				<?php
					echo $this->Html->link('Keywords',['controller'=>'','action'=>'kwgrp']);
				?>
			</div>
			<label>Enter article title:</label><br>
			<textarea name='user_title' id='user_title' style="height: 5rem;width: 30rem;"></textarea><br><br>
			<input type='button' id='btn_search' value='Search'><br><br>
			<div id='cat_div'>
				Category: 
					<select name='category' id='category' onchange="category_select(this.value);">
						<option value=''>--select--</option>
						<?php
						foreach($catarr as $category)
						{
							echo '<option value='.$category['Catid'].'>'.$category['Category'].'</option>';
						}
						?>
						</option>
					</select>
			</div>
			<div id='result_div'>
			</div>
		</div>
		
	</body>
</html>