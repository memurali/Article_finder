<?php
	echo $this->Form->create(null,
	['name'=>'import_csv','id'=>'import_csv',
	'type' => 'file',
	'url' => [
	  'controller' => 'users',
	  'action' => 'import'
	]
	]); 
?>

	Import:<input type='file' name='data' id='data'><br><br>
	<input type="radio" name="mode" value="article" required="required">Article
	<input type="radio" name="mode" value="keyword">Keyword
	<br><br>
	<input type="submit" value="submit">
	
<?php echo $this->Form->end(); ?>