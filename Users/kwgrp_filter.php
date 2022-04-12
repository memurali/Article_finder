<?php
if(count($kwarr)>0 || count($artarr)>0)
{
?>
<!-- Tab links -->
<div class="tab">
  <button class="tablinks" class='active' onclick="openCity(event, 'keyword')" id="defaultOpen"><h3>Keywords</h3></button>
  <button class="tablinks" onclick="openCity(event, 'article')"><h3>Articles</h3></button>
</div>

<!-- Tab content -->
<div id="keyword" class="tabcontent" style='display:block'>
  <h2>Keywords</h2>
  <?php
  if(count($kwarr)>0)
  {
	  echo '<table>';
		echo '<tr>';
			echo '<th>Keywords</th>';
			echo '<th>Volume</th>';
		echo '</tr>';
		foreach($kwarr as $kw)
		{
			echo '<tr>';
				echo '<td>'.$kw['Keyword'].'</td>';
				echo '<td>'.$kw['Vol_Abrv (US)'].'</td>';
			echo '</tr>';
			
		}
	  echo '</table>';
  }
  ?>
</div>

<div id="article" class="tabcontent">
  <h2>Articles</h2>
  <?php
  if(count($artarr)>0)
  {
	  echo '<table>';
		echo '<tr>';
			echo '<th>Article ideas</th>';
			echo '<th>Save to List</th>';
		echo '</tr>';
		foreach($artarr as $art)
		{
			echo '<tr>';
				echo '<td>'.$art['Article'].'</td>';
				echo '<td><a href="">save</a></td>';
			echo '</tr>';
			
		}
	  echo '</table>';
  }
  ?>
</div>
<?php
}
?>