<?php
include 'config.php';

 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Exercice Tutorial</title>
</head>
<body>
<form action="index.php" method="get" >
<table>
<tr>
<td>
<label>Assignee</label>
</td>
<td>
<input type="text" name="assignee"  />
</td>
</tr>
<tr>
<td>
<label>Status</label>
</td>
<td>
<select name="status">
<option></option>
<?php 
	$stmt1 = $PDO->prepare('SELECT DISTINCT status_label FROM leads;');
	$stmt1->execute();
	
	while($row = $stmt1->fetch(PDO::FETCH_ASSOC ))
		{
		?>
		<option name="status" value="<?php echo $row['status_label'] ; ?>"><?php echo $row['status_label'] ; ?></option>
<?php    
		}
?>
</select>
</td>
</tr>
</table>


<input type="submit" value="Search" />

</form>
<?php
  
//1. Query for Both:  
if((!empty($_GET["assignee"])) &&(!empty($_GET["status"])) )
{
	$assignee = $_GET["assignee"];
	$status = $_GET["status"];
	$stmt = $PDO->prepare('select 
	field_value as Assignee, 
	status_label as Status, 
	count(status_label) as Count 
	from leadsmanagement.leads l
	inner join 
	leadsmanagement.lead_custom lc
	on l.id  = lc.lead_id
	where field_value like ?
	and status_label like ?
	group by  status_label');
	$params = array("%$assignee%", "%$status%");
	$stmt->execute($params);
}
//2. Query for Assignee:
else if(!empty($_GET["assignee"])) 
{
	$assignee = $_GET["assignee"];
	$stmt = $PDO->prepare('SELECT 
	field_value AS Assignee, 
	status_label AS Status,
	COUNT(status_label) AS Count
	FROM leadsmanagement.leads l
	INNER JOIN leadsmanagement.lead_custom lc ON l.id = lc.lead_id
	WHERE field_value like ?
	GROUP BY field_value, status_label');
	$params = array("%$assignee%");
	$stmt->execute($params);
}
//3. Query for Status:
else if(!empty($_GET["status"])) 
{
	$status = $_GET["status"];
	$stmt = $PDO->prepare('select 
	field_value as Assignee,
	status_label as Status, 
	count(status_label) as Count from leadsmanagement.leads l
	inner join leadsmanagement.lead_custom lc
	on l.id  = lc.lead_id
	where status_label = :status
	group by  status_label');
	$stmt->bindValue('status', $status);
	$stmt->execute();
}
//4. Query for None:
else
{
	$stmt = $PDO->prepare('select 
	field_value as Assignee, 
	status_label as Status, 
	count(status_label) as Count from leadsmanagement.leads l
	inner join leadsmanagement.lead_custom lc
	on l.id  = lc.lead_id
	group by  status_label');
	$stmt->execute();
	
}
    
	
?>


</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
<table border="1" style="width: 50%;text-align: center;">
<tr>
<td>Assignee</td>
<td>Status</td>
<td>Count</td>
</tr>
<?php 
while($row = $stmt->fetch(PDO::FETCH_ASSOC ))
{
?>
<tr>
<td><?php echo $row['Assignee'] ; ?></td>
<td><?php echo $row['Status'] ; ?></td>	
<td><?php echo $row['Count'] ; ?></td>	
</tr>
<?php
}
?>	
</table>
</body>
</html>