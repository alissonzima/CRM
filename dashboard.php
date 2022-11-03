<!doctype html>
<?php include_once("conn.php"); ?>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="jquery/jquery-3.5.1.min.js"></script>
  <head>
	<?php $stid = oci_parse($conn, 'select count(c.idetapa) as qtd , initcap(e.descricao) as descricao, e.id as cod from cliente c 
																	inner join etapa e on c.idetapa = e.id group by e.descricao, e.id order by 3');
		oci_execute($stid);
	?>  
    <script type="text/javascript" src="charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Etapa', 'Quantidade'],
		  <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			  echo "['".$row['DESCRICAO']."', ".$row['QTD']."],";
		  }?>
        ]);

        var options = {
          title: 'Clientes por etapa',
          is3D: true,
		  pieSliceText: 'value',
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
</head>
<body>
	<?php $menu = 1; include_once("menu.php"); ?>
	<div id="piechart_3d" style="width: 900px; height: 500px;"></div>
</body>
</html>