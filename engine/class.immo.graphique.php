<?php

/* Classe immobiliere Graphique par Shua-Creation 2019 */

class Graphique_Immo
{
	function __construct()
	{
	}
	
	function showGraphiqueGES($type)
	{
		$data = '<style>';
		$data .= '.classGESA
		{
			background-color: #fdeafe !important;
			width: 60px !important;
			color:#b415b9 !important;
		}
		
		.classGESB
		{
			background-color: #ffdefd !important;
			width: 70px !important;
			color: #b415b9 !important;
		}
		
		.classGESC
		{
			background-color: #f9befd !important;
			width: 80px !important;
		}
		
		.classGESD
		{
			background-color: #fb7eff !important;
			width: 90px !important;
		}
		
		.classGESE
		{
			background-color: #f652fc !important;
			width: 100px !important;
		}
		
		.classGESF
		{
			background-color: #e428f4 !important;
			width: 110px !important;
		}
		
		.classGESG
		{
			background-color: #b415b9 !important;
			width: 120px !important;
		}
		
		.info-range
		{
			float:left;
		}
		
		.info-letter
		{
			float:right;
		}
		
		.colorGESA
		{
			border-left-color: #fdeafe !important;
		}
		
		.colorGESB
		{
			border-left-color: #ffdefd !important;
		}
		
		.colorGESC
		{
			border-left-color: #f9befd !important;
		}
		
		.colorGESD
		{
			border-left-color: #fb7eff !important;
		}
		
		.colorGESE
		{
			border-left-color: #f652fc !important;
		}
		
		.colorGESF
		{
			border-left-color: #e428f4 !important;
		}
		
		.colorGESG
		{
			border-left-color: #b415b9 !important;
		}
		
		.graphique-wrapper
		{
			overflow:auto;
		}
		
		.graphique-immo-selector
		{
			float:left;
		}
		
		.selector
		{
			width: 30px;
			background-color: #000;
			color: #fff;
			text-align: center;
			padding: 4px;
			font-size: 12px;
			font-weight: bold;
			margin-bottom: 2px;
			float:right;
		}
		
		.info-complementaire
		{
			width: 320px;
			font-size: 13px;
		}
		
		.invisible
		{
			opacity:0;
		}
		</style>';
		$data .= '<div class="graphique-immo-energie">
			<div class="graphique-immo-first">
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESA">
						<div class="info-range">< 5</div>
						<div class="info-letter">A</div>
					</div>
					<div class="arrow colorGESA"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESB">
						<div class="info-range">6 à 10</div>
						<div class="info-letter">B</div>
					</div>
					<div class="arrow colorGESB"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESC">
						<div class="info-range">11 à 20</div>
						<div class="info-letter">C</div>
					</div>
					<div class="arrow colorGESC"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESD">
						<div class="info-range">21 à 35</div>
						<div class="info-letter">D</div>
					</div>
					<div class="arrow colorGESD"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESE">
						<div class="info-range">36 à 55</div>
						<div class="info-letter">E</div>
					</div>
					<div class="arrow colorGESE"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESF">
						<div class="info-range">56 à 80</div>
						<div class="info-letter">F</div>
					</div>
					<div class="arrow colorGESF"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classGESG">
						<div class="info-range">> 80</div>
						<div class="info-letter">G</div>
					</div>
					<div class="arrow colorGESG"></div>
				</div>
			</div>';
			$data .= '<div class="graphique-immo-selector">';
			if($type == 'a')
			{
				$data .= '<div class="graphique-wrapper">
						<div class="selector">
						A
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'b')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						B
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'c')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						C
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'd')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						D
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'e')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						E
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'f')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						F
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'g')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						G
						</div>
						<div class="arrowBlack"></div>
					</div>';
				}
				else
				{
					
				}
				
		$data .= '</div></div>';
		$data .= '<div class="info-complementaire">';
		$data .= 'Unité de mesure exprimé en kgeqCO2/m².an ';
		$data .= '</div>';
		return $data;
	}
	
	function showGraphiqueEnergie($type)
	{
		$data = '<style>';
		$data .= '.graphique-immo-energie';
		$data .= '{';
		$data .= '
			width: 320px;
			overflow: auto;
		}
		
		.graphique-immo-first
		{
			float: left;
			width: 170px;
		}
		
		.graphique-immo-item
		{
			background-color:#aaaaaa;
			padding: 3px;
			border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			font-size: 13px;
			font-weight: bold;
			color: #fff;
			width:160px;
			margin-bottom: 3px;
			float:left;
		}
		
		.classA
		{
			background-color: #0ddb9d;
			width: 60px;
		}
		
		.classB
		{
			background-color: #61f127;
			width: 70px;
		}
		
		.classC
		{
			background-color: #cafa02;
			width: 80px;
		}
		
		.classD
		{
			background-color: #f9dd17;
			width: 90px;
		}
		
		.classE
		{
			background-color: #fdad00;
			width: 100px;
		}
		
		.classF
		{
			background-color: #fc7130;
			width: 110px;
		}
		
		.classG
		{
			background-color: #d94654;
			width: 120px;
		}
		
		.info-range
		{
			float:left;
		}
		
		.info-letter
		{
			float:right;
		}
		
		.arrow
		{
			display: inline-block;
			width: 0;
			height: 0;
			border-top: 11px solid transparent;
			border-bottom: 11px solid transparent;
			vertical-align: middle;
			border-left: 11px solid;
			border-left-color: currentcolor;
			float: left;
		}
		
		.colorA
		{
			border-left-color: #0ddb9d;
		}
		
		.colorB
		{
			border-left-color: #61f127;
		}
		
		.colorC
		{
			border-left-color: #cafa02;
		}
		
		.colorD
		{
			border-left-color: #f9dd17;
		}
		
		.colorE
		{
			border-left-color: #fdad00;
		}
		
		.colorF
		{
			border-left-color: #fc7130;
		}
		
		.colorG
		{
			border-left-color: #d94654;
		}
		
		.graphique-wrapper
		{
			overflow:auto;
		}
		
		.graphique-immo-selector
		{
			float:left;
		}
		
		.selector
		{
			width: 30px;
			background-color: #000;
			color: #fff;
			text-align: center;
			padding: 4px;
			font-size: 12px;
			font-weight: bold;
			margin-bottom: 2px;
			float:right;
		}
		
		.arrowBlack
		{
			display: inline-block;
			width: 0;
			height: 0;
			border-top: 12px solid transparent;
			border-bottom: 10px solid transparent;
			vertical-align: middle;
			margin-top: 0px;
			border-right: 10px solid;
			border-right-color: #000000;
			float:right;
		}
		
		.info-complementaire
		{
			width: 320px;
			font-size: 13px;
		}
		
		.invisible
		{
			opacity:0;
		}
		</style>';
		$data .= '<div class="graphique-immo-energie">
			<div class="graphique-immo-first">
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classA">
						<div class="info-range">< 50</div>
						<div class="info-letter">A</div>
					</div>
					<div class="arrow colorA"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classB">
						<div class="info-range">51 à 90</div>
						<div class="info-letter">B</div>
					</div>
					<div class="arrow colorB"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classC">
						<div class="info-range">91 à 150</div>
						<div class="info-letter">C</div>
					</div>
					<div class="arrow colorC"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classD">
						<div class="info-range">151 à 230</div>
						<div class="info-letter">D</div>
					</div>
					<div class="arrow colorD"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classE">
						<div class="info-range">231 à 330</div>
						<div class="info-letter">E</div>
					</div>
					<div class="arrow colorE"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classF">
						<div class="info-range">331 à 450</div>
						<div class="info-letter">F</div>
					</div>
					<div class="arrow colorF"></div>
				</div>
				<div class="graphique-wrapper">
					<div class="graphique-immo-item classG">
						<div class="info-range">> 450</div>
						<div class="info-letter">G</div>
					</div>
					<div class="arrow colorG"></div>
				</div>
			</div>
			<div class="graphique-immo-selector">';
			if($type == 'a')
			{
				$data .= '<div class="graphique-wrapper">
						<div class="selector">
						A
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'b')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						B
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'c')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						C
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'd')
				{
					$data = '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						D
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'e')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						E
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'f')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						F
						</div>
						<div class="arrowBlack"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						G
						</div>
						<div class="arrowBlack invisible"></div>
					</div>';
				}
				else if($type == 'g')
				{
					$data .= '<div class="graphique-wrapper">
						<div class="selector invisible">
						A
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						B
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						C
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						D
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						E
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector invisible">
						F
						</div>
						<div class="arrowBlack invisible"></div>
					</div>
					<div class="graphique-wrapper">
						<div class="selector">
						G
						</div>
						<div class="arrowBlack"></div>
					</div>';
				}
				else
				{
					
				}
			$data .= '</div>
		</div>
		<div class="info-complementaire">
		Unité de mesure exprimé en kWhEP/m².an 
		</div>';
		
		return $data;
	}
}

?>