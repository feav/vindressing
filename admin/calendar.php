<?php

if(isset($_REQUEST['day']))
{
	$day = $_REQUEST['day'];
}
else
{
	$day = date('d');
}

if(isset($_REQUEST['month']))
{
	$month = $_REQUEST['month'];
}
else
{
	$month = date('m');
}

if(isset($_REQUEST['year']))
{
	$year = $_REQUEST['year'];
}
else
{
	$year = date('Y');
}

$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$first_day_month = date("l", mktime(0, 0, 0, $month, 1, $year));

if($first_day_month == 'Monday')
{
	$decal = 0;
}
else if($first_day_month == 'Tuesday')
{
	$decal = 1;
}
else if($first_day_month == 'Wednesday')
{
	$decal = 2;
}
else if($first_day_month == 'Thursday')
{
	$decal = 3;
}
else if($first_day_month == 'Friday')
{
	$decal = 4;
}
else if($first_day_month == 'Saturday')
{
	$decal = 5;
}
else if($first_day_month == 'Sunday')
{
	$decal = 6;
}

if($month == '01')
{
	$month_letter = 'Janvier';
}
else if($month == '02')
{
	$month_letter = 'Février';
}
else if($month == '03')
{
	$month_letter = 'Mars';
}
else if($month == '04')
{
	$month_letter = 'Avril';
}
else if($month == '05')
{
	$month_letter = 'Mai';
}
else if($month == '06')
{
	$month_letter = 'Juin';
}
else if($month == '07')
{
	$month_letter = 'Juillet';
}
else if($month == '08')
{
	$month_letter = 'Août';
}
else if($month == '09')
{
	$month_letter = 'Septembre';
}
else if($month == '10')
{
	$month_letter = 'Octobre';
}
else if($month == '11')
{
	$month_letter = 'Novembre';
}
else if($month == '12')
{
	$month_letter = 'Décembre';
}

?>
<div class="title-calendar">
<a href="javascript:void(0);" onclick="backMonth('<?php echo $month; ?>','<?php echo $year; ?>');" class="left-btn-calendar" title="Mois précédent"><</a> <?php echo $month_letter; ?> <?php echo $year; ?> <a href="javascript:void(0);" onclick="nextMonth('<?php echo $month; ?>','<?php echo $year; ?>');" class="right-btn-calendar" title="Mois suivant">></a>
</div>
<div class="calendar-line">
	<div class="calendar-box daycolor">
	L
	</div>
	<div class="calendar-box daycolor">
	M
	</div>
	<div class="calendar-box daycolor">
	M
	</div>
	<div class="calendar-box daycolor">
	J
	</div>
	<div class="calendar-box daycolor">
	V
	</div>
	<div class="calendar-box daycolor">
	S
	</div>
	<div class="calendar-box daycolor">
	D
	</div>
</div>
<div class="calendar-line">
<?php

for($x=0;$x<$decal;$x++)
{
	?>
	<div class="calendar-box">
	&nbsp;
	</div>
	<?php
}

for($x=0;$x<$number_day;$x++)
{
	if(($x+1) == $day)
	{
		?>
		<div class="calendar-box calendar-selected" onclick="updateCalendar('<?php echo $x+1; ?>','<?php echo $month; ?>','<?php echo $year; ?>');">
		<?php echo $x+1; ?>
		</div>
		<?php
	}
	else
	{
		?>
		<div class="calendar-box" onclick="updateCalendar('<?php echo $x+1; ?>','<?php echo $month; ?>','<?php echo $year; ?>');">
		<?php echo $x+1; ?>
		</div>
		<?php
	}
}

?>
</div>