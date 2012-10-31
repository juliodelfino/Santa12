
<table style="font-size: 9px; font-family: arial, sans-serif">
<?php 
    if (!empty($results)) {
    
        echo '<tr>';
        foreach ($fields as $field) {
            echo '<td>' . $field . '</td>';
        }
        echo '</tr>';
        
        foreach ($results as $row) {
            echo '<tr>';
            foreach ($row as $key=>$value) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
    }
?>
</table>
<br/>
<form method="post" action="">
	<input type="text" name="q" />
	<input type="submit" value="Go" />
</form>
