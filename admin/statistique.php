<?php

include "../config.php";
include "version.php";
include "../engine/class.statistique-visiteur.php";

$class_statistique_visiteur = new Statistique_Visiteur();

@session_start();

$SQL = "SELECT COUNT(*) FROM pas_admin_user WHERE username = '".$_SESSION['admin_username']."' AND password = '".$_SESSION['admin_password']."'";
$reponse = $pdo->query($SQL);
$req = $reponse->fetch();

if($req[0] == 0)
{
	header("Location: index.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Administration PAS Script v<?php echo $version; ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="morris/morris.min.js"></script>
	<link rel="stylesheet" href="morris/morris.css">
</head>
<body>
<style>
.stats-box
{
	float: left;
	padding: 20px;
	background-color: #96d2ff;
	border-radius: 5px;
	margin-right: 10px;
	width: 150px;
	text-align: center;
}

.number-box
{
	font-size: 28px;
}

.calendar
{
	width:239px;
	overflow: auto;
	background-color:#666;
}

.title-calendar
{
	background-color:#c2bebe;
	font-size: 11px;
	text-align: center;
	padding-top: 5px;
	padding-bottom: 5px;
}

.calendar-selected
{
	background-color: #96d2ff !important;
}

.calendar-box
{
	float: left;
	font-size: 11px;
	width: 14.28%;
	background-color:#fff;
	padding-top: 5px;
	padding-bottom: 5px;
	text-align: center;
	font-weight: bold;
	border: 1px solid #000;
	box-sizing: border-box;
	cursor: pointer;
}

.left-btn-calendar
{
	float: left;
	margin-left: 10px;
	text-decoration: none;
	color:#000;
	font-weight: bold;
}

.right-btn-calendar
{
	float: right;
	margin-right: 10px;
	text-decoration: none;
	color:#000;
	font-weight: bold;
}

.daycolor
{
	background-color: #ffdfb5 !important;
}
</style>
	<?php
	
	include "header.php";
	include "sidebar.php";
	
	if(isset($_REQUEST['annee']))
	{
		$annee = $_REQUEST['annee'];
	}
	else
	{
		$annee = date('Y');
	}
	
	if(isset($_REQUEST['mois']))
	{
		$mois = $_REQUEST['mois'];
	}
	else
	{
		$mois = date('m');
	}
	
	if(isset($_REQUEST['jour']))
	{
		$jour = $_REQUEST['jour'];
	}
	else
	{
		$jour = date('d');
	}
	
	?>
	<div class="container">
		<H1>Statistique de visite</H1>
		<div class="info">
		Retrouver une vue globale des visites sur votre site internet
		</div>
		<div style="float:right;margin-top: -120px;">
			<input type="text" style="height: 30px;margin-right: 5px;padding-left: 5px;border: 1px solid #aaaaaa;" placeholder="JJ/MM/AAAA" id="datestat" onclick="showCalendar();" value="<?php echo "$jour/$mois/$annee"; ?>"><button class="btn" onclick="updateDate();">Rechercher</button>
			<div class="calendar" id="calendar"></div>
		</div>
		<script>
		function showCalendar()
		{
			$.post("calendar.php?day=<?php echo $jour; ?>&month=<?php echo $mois; ?>&year=<?php echo $annee; ?>", function( data ) 
			{
			  $("#calendar").html(data);
			});
		}
		
		function nextMonth(month,year)
		{
			month = parseInt(month) + 1;
			if(month == 13)
			{
				month = 1;
				year = parseInt(year) + 1;
			}
			$('#datestat').val('1/'+month+'/'+year);
			updateDate();
		}
		
		function backMonth(month,year)
		{
			month = parseInt(month) - 1;
			if(month == 0)
			{
				month = 12;
				year = parseInt(year) - 1;
			}
			$('#datestat').val('1/'+month+'/'+year);
			updateDate();
		}
		
		function updateCalendar(day,month,year)
		{
			$('#datestat').val(day+'/'+month+'/'+year);
			updateDate();
		}
		</script>
		<div style="overflow:auto;margin-bottom:20px;">
			<div class="stats-box">
				<div class="number-box">
				<i class="far fa-file"></i> <?php echo $class_statistique_visiteur->getPageVu($annee."-".$mois."-".$jour); ?>
				</div>
				<div class="title-box">
				Page vu(s)
				</div>
			</div>
			<div class="stats-box">
				<div class="number-box">
				<i class="fas fa-user"></i> <?php echo $class_statistique_visiteur->getVisiteur($annee."-".$mois."-".$jour); ?>
				</div>
				<div class="title-box">
				Visiteur(s)
				</div>
			</div>
			<div class="stats-box">
				<div class="number-box">
				<i class="fas fa-robot"></i> <?php echo $class_statistique_visiteur->getBot($annee."-".$mois."-".$jour); ?>
				</div>
				<div class="title-box">
				Robot(s)
				</div>
			</div>
		</div>
		<?php
		
		$mois_titre = $class_statistique_visiteur->getMoisFrancais($mois);
		
		?>
		<H3>Visite unique du mois de <?php echo $mois_titre." ".$annee; ?></H3>
		<div id="myfirstchart" style="height: 250px;"></div>
		<?php	  
		$number = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
		?>
		<H3>Les 20 dernières visites sur votre site internet</H3>
		<table class="table">
		  <thead class="thead-dark">
			<tr>
			  <th scope="col">Date de la visite</th>
			  <th scope="col">Adresse IP</th>
			  <th scope="col">User-Agent</th>
			</tr>
		  </thead>
		  <tbody>
			<?php
			
			$SQL = "SELECT * FROM statistique_visiteur ORDER BY id DESC LIMIT 20";
			$reponse = $pdo->query($SQL);
			while($req = $reponse->fetch())
			{
				?>
				<tr>
					<td><?php echo $req['date_de_la_visite']; ?></td>
					<td><?php echo $req['ip']; ?></td>
					<td><?php echo $req['user_agent']; ?></td>
				</tr>
				<?php
			}
			?>
		  </tbody>
		</table>
	</div>
	<script>
	new Morris.Line({
	  // ID of the element in which to draw the chart.
	  element: 'myfirstchart',
	  // Chart data records -- each entry in this array corresponds to a point on
	  // the chart.
	  data: [
		<?php
		for($x=1;$x<$number+1;$x++)
		{
			if($x==$number)
			{
				if($n < 9)
				{
					$n = "0".$x;
				}
				else
				{
					$n = $x;
				}
				?>
				{ jour: '<?php echo $x.' '.$mois_titre; ?>', value: <?php echo $class_statistique_visiteur->getVisiteur($annee."-".$mois."-".$n); ?> }
				<?php
			}
			else
			{
				if($n < 9)
				{
					$n = "0".$x;
				}
				else
				{
					$n = $x;
				}
				?>
				{ jour: '<?php echo $x.' '.$mois_titre; ?>', value: <?php echo $class_statistique_visiteur->getVisiteur($annee."-".$mois."-".$n); ?> },
				<?php
			}
		}
		?>
	  ],
	  // The name of the data record attribute that contains x-values.
	  xkey: 'jour',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['value'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: ['Visite unique']
	});
	</script>
	<script>
	function updateDate()
	{
		var date = $('#datestat').val();
		var d = date.split('/');
		var jour = d[0];
		var mois = d[1];
		var annee = d[2];
		
		window.location.href = 'statistique.php?jour='+jour+'&mois='+mois+'&annee='+annee;
	}
	</script>
</body>
</html>