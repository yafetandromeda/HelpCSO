<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php
			foreach ($list_report_eskalasi as $p):
				 echo "<tr>";
				 echo "<td>".$p->cat_id."</td>";
				 echo "<td>".$p->catname."</td>";
				 echo "<td>".$p->total_new_ticket."</td>";
				 echo "<td>".$p->total_handled_ticket."</td>";
				 echo "<td>".$p->total_solved_ticket."</td>";
				 echo "<td>".$p->total_closed_ticket."</td>";
				 echo "</tr>";
			  endforeach;
		?>
<body>
</body>
</html>