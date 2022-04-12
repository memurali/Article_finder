<?php
	ctype_cntrl ('cbd	CBD');
?>
<html>
	<head>
		<?php
			echo $this->Html->css('report.css');
			echo $this->Html->script('jquery.js');
			echo $this->Html->script('common.js');
		?>
	</head>
	<body>
		<span class='link'>
			<?php echo $this->Html->link('Menu',['controller'=>'','action'=>'menu']);?>
		</span>
		Select category:
		<select name='category' id='category' onchange='sel_cat_match(this.value);' required>	
			<option value=''>--select--</option>
			<?php
			if(count($catarr)>0)
			{
				foreach($catarr as $cat)
				{
				?>
					<option value='<?php echo $cat['Catid'];?>'><?php echo $cat['Category'];?></option>
				<?php
				}
			}

			?>
		</select><br><br>
		<label>Enter match:</label><br>
		<textarea placeholder="Keyword Search Phrase:Keyword Group" name="kwgrp_match" id="kwgrp_match" style="height: 10rem;width:30rem"></textarea><br><br>
		<input type='button' onclick='kwgrp_match_click();'  value="Submit">
	</body>
</html>