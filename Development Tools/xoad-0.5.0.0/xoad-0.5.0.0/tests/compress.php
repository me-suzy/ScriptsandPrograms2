<form action="compress.php" method="post">
<?php

	$data = '';
	
	if (array_key_exists('data', $_POST)) {
		
		$postData = explode("\n", trim($_POST['data']));
		
		foreach ($postData as $line) {

			$line = preg_replace('/\t+/i', ' ', $line);
			
			$line = trim($line);
			
			if (strlen($line) < 1) {
				
				continue;
			}
			
			$data .= $line . "\n";
		}
		
		$data = trim($data);
	}

?>
	<p>
		<textarea rows="25" cols="100" name="data"><?= htmlspecialchars($data) ?></textarea>
	</p>
	<p>
		<input type="submit" value="Compress" />
	</p>
</form>