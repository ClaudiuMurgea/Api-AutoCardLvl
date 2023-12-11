<?php

include "../../MOAPHP.php";

header('Access-Control-Allow-Origin: *');

$PID=$_GET["PID"];

$con = mysqli_connect($dhost, $dusername, $dpassword, $ddatabase) or die ("Cannot connect to the database");

$CardColor=GetSingleValue($con,"select CustomerColor from PlayerTracking.Customers where EntryID=$PID");
$Currency = GetSingleValue($con, "SELECT global_currency FROM lmi.global_settings order by global_config_id");

$CardColors =[];
//upgrade
	$res = Q($con,"select Color, Definition from PlayerTracking.Card_Color_Definition order by Color");

	while($data = mysqli_fetch_assoc($res))
	{
		$CardColors[(int)$data["Color"]] = $data["Definition"];
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Card Progress Page</title>
<!-- background-image: url('http://10.109.254.38/PlayerJPNou/CC/progress_bar/Arrow.png'); -->

<style>
.meter {
  box-sizing: content-box;
  height: 30px; /* Can be anything */
  position: relative;
  margin: 45px 0 0 0; /* Just for demo spacing */
  background: #555;
  border-radius: 25px;
  padding: 10px;
  box-shadow: inset 3px 3px 3px rgba(255, 255, 255, 0.3);
}
.meter > span {
  display: block;
  height: 100%;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  background-color: rgb(43, 194, 83);
  background-image: linear-gradient(
    center bottom,
    rgb(43, 194, 83) 37%,
    rgb(84, 240, 84) 69%
  );
  box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3),
    inset 0 -2px 6px rgba(0, 0, 0, 0.4);
  position: relative;
  overflow: hidden;
}
.meter > span:after,
.animate > span > span {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-image: linear-gradient(
    -45deg,
    rgba(255, 255, 255, 0.2) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255, 255, 255, 0.2) 50%,
    rgba(255, 255, 255, 0.2) 75%,
    transparent 75%,
    transparent
  );
  z-index: 1;
  background-size: 50px 50px;
  animation: move 5s linear infinite;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  overflow: hidden;
}
.animate > span:after {
  display: none;
}

@keyframes move {
  0% {
    background-position: 0 0;
  }
  100% {
    background-position: 50px 50px;
  }
}
.orange > span {
  background-image: linear-gradient(#f1a165, #f36d0a);
}
.red > span {
  background-image: linear-gradient(#f0a3a3, #f42323);
}
.nostripes > span > span,
.nostripes > span::after {
  background-image: none;
}
#page-wrap {
  width: 100%;
	min-height:100vh;
  margin: 0 auto;
  /* border:2px solid #abc7ff; */
  /* border-radius:20px; */
  position:relative;
  text-align: center;
  padding-top:25px;
  padding-left:30px;
  padding-right:30px;
  padding-bottom:30px;
  overflow: auto;
}
.title {
  display:inline-block;
  transform: translateY(33px);
	letter-spacing:2px;
  font-size:30px;
	text-shadow: 1px 1px #000;
}
.subtitle {
  font-size:22px;
  padding-bottom:12px;
}
.more_info {
	text-decoration:none;
	color:#fff;
	max-height:46px;
	text-shadow:1px 1px #000;
	font-size:22px;
	letter-spacing:1px;
	transform: translateY(30px);
	display:inline-block;
	border-radius:15px;
	margin-left:5px;
	box-shadow: inset 0 0 10px #b4d1ff;
	padding:10px 25px;
}
.wide_flex {
  display:flex;
  flex-direction:column;
  width:100%;
}
.between_flex {
	display:flex;
	width:100%;
	justify-content:space-between;
}
@keyframes animatedgradient {
	0% {
		background-position: 0% 50%;
	}
	50% {
		background-position: 100% 50%;
	}
	100% {
		background-position: 0% 50%;
	}
}
body {
  background: linear-gradient(60deg, #2c3e50, #000000, #2c3e50, #222);
  animation: animatedgradient 50s ease alternate infinite;
  background-size: 400% 400%;
  color: #eee;
  font-family: "Arial";
  min-height:100vh;
  overflow-y: auto;
  margin:0 !important;
}
h1 {
  font-size: 42px;
  font-weight: 600;
  margin: 0 0 30px;
}
pre {
  background: #000;
  text-align: left;
  padding: 20px;
  margin: 0 auto 30px;
}
* {
  box-sizing: border-box;
}
.left
{
  float: left;
}
.right
{
  float: right;
}
.center {
  text-align: center;
}
.transform_35 {
  transform:translateY(-35px);
}
/* Round counter progress - start */
circle {
    transition: all 1s linear;
}
#c1{
    transition: all 1s linear;
    stroke: #fff;
    stroke-width: 3;
    stroke-linecap: round;
    fill: transparent;
}
#c2 {
    transition: all 1s linear;
    stroke: #616161;
    stroke-width: 3;
    stroke-linecap: round;
    fill: transparent;
}
#counterText {
    -webkit-animation: heartBeat 1s infinite;
    animation: heartBeat 1s infinite;
}
.timer-text_wrapper {
    position:absolute;
    top:50px;
    right: 0;
    font-size:10px;
    font-weight:800;
    color:#fff;
    line-height:0;
}
/* Round counter progress - end */
.top-side_wrapper {
    width:100%;
    display:flex;
    justify-content:space-between;
}
.empty_element {
  min-width:100px;
}
.text_one {
  padding-right:56px;
}
.text_two {
  text-align:center;
  padding-right:56px;
}
.section_wrapper {
  font-size:18px;
  text-shadow:1px 1px #000;
  font-weight:600;
  display:flex;
  flex-direction:column;
  text-align:left;
  background:rgba(0,0,0,0.2) !important;
  padding:2rem;
  margin:10px 5px;
  border-radius:20px;
  box-shadow: inset 0 0 10px #b4d1ff;
	position:relative !important;
}
.flex_container {
  width:100%;
  display:flex;
  justify-content:left;
}
.flex_element {
  min-width:49%;
}
.flex_short_element {
  min-width:2%;
}
/* Custom scrollbar start */
.custom_scrollbar::-webkit-scrollbar {
    width: 16px;
}
.custom_scrollbar::-webkit-scrollbar-track {
    background: linear-gradient(0deg,transparent, rgba(152, 151, 169, 0.4),rgba(2, 2, 2, 0.3),rgba(0, 0, 0, 0.5), rgba(4, 4, 4, 0.4),rgba(4, 4, 4, 0.4),rgba(4, 4, 4, 0.4),rgba(0, 0, 0, 0.5), rgba(4, 4, 4, 0.4),rgba(4, 4, 4, 0.4),rgba(4, 4, 4, 0.4),rgba(0, 0, 0, 0.5), rgba(4, 4, 4, 0.4),rgba(4, 4, 4, 0.4), rgba(4, 4, 4, 0.4),rgba(2, 2, 2, 0.3), rgba(152, 151, 169, 0.4), transparent);
}
.custom_scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, rgba(0, 0, 0, 5), rgba(0, 0, 0, 0.8), rgba(255, 255, 255, 0.5), rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5));
    border-top: 1px solid rgba(0, 0, 0, 0.8);
    border-bottom: 1px solid rgba(0, 0, 0, 0.8);
}
.downgrade_grace {
	position:absolute;
	top:32px;
	left:0;
	display:flex;
	justify-content:center;
	width:100%;
	padding-right:5px;
}
.downgrade_grace-period {
	padding:3px 5px;
	box-shadow: inset 0 0 5px #b4d1ff;
	color:#fff;
	border-radius:10px;
	text-shadow:1px 0 #b4d1ff;
}
.downgrade_lock {
	position:absolute;
	top:45px;
	left:0;
	display:flex;
	justify-content:center;
	width:100%;
	padding-right:5px;
}
/* Custom Scrollbar end */
@media screen and (max-width: 1000px) {
	.downgrade_grace {
		padding-left:26px;
		top:7px;
	}
	.downgrade_grace-period {
		box-shadow: unset;
		padding: unset;
		text-decoration:underline;
	}
	.downgrade_lock {
		top:22px;
	}
	.lock {
		max-width:50px !important;
		max-height:50px !important;
	}
  .timer-text_wrapper {
  	position:absolute;
  	top:50px;
  	right:0;
  	font-size:10px;
  	font-weight:800;
  	color:#fff;
    line-height:0;
  }
  .flex_element {
    min-width:47%;
  }
  .flex_short_element {
    min-width:6%;
  }
	.title {
		font-size:30px;
		letter-spacing:1px;
	}
	.more_info {
		padding:7px 15px;
		letter-spacing:0;
		font-size:22px;
		height:41px;
	}
}
.wrapperz {
  display: flex;
  justify-content:center;
}
.wrapperz a {
  z-index:1;
  position:absolute;
  display: inline-block;
  text-align:center;
  text-decoration: none;
  padding: 10px 35px 50px 35px;
  width:400px;
  text-transform: uppercase;
  color: #fff;
  text-shadow:1px 1px #000;
  font-family: 'Roboto', sans-serif;
  top: 0;
}
.close {
    font-family: Arial, Helvetica, sans-serif;
    /* background: #f26d7d; */
    background: crimson;
    opacity:1;
    color: #fff;
    line-height: 25px;
    position: absolute;
    right: -12px;
    text-align: center;
    top: -10px;
    width: 34px;
    height: 34px;
    text-decoration: none;
    font-weight: bold;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    -moz-box-shadow: 1px 1px 3px #000;
    -webkit-box-shadow: 1px 1px 3px #000;
    box-shadow: 1px 1px 3px #000;
    padding-top: 5px;
}
.rotate {
	transform: rotate(180deg);
}
.faq-drawer {
	padding-top:5px;
}
.faq-drawer__content-wrapper {
  font-size: 18px;
  line-height: 1.4em;
  max-height: 0px;
  overflow: hidden;
  transition: 0.25s ease-in-out;
}
.faq-drawer__title {
  cursor: pointer;
  display: flex;
	justify-content:center;
  font-size: 18px;
  font-weight: 700;
  position: relative;
  margin-bottom: 0;
  transition: all 0.25s ease-out;
}
.faq-drawer__title::after {
  border-style: solid;
  border-width: 1px 1px 0 0;
  content: " ";
  display: inline-block;
  float: right;
  height: 10px;
  left: 2px;
  position: relative;
  right: 20px;
  top: 3px;
  transform: rotate(135deg);
  transition: 0.35s ease-in-out;
  vertical-align: top;
  width: 10px;
	margin-left:10px;
}
/* OPTIONAL HOVER STATE */
.faq-drawer__title:hover {
  color: #4E4B52  ;
}
.faq-drawer__trigger:checked
  + .faq-drawer__title
  + .faq-drawer__content-wrapper {
  max-height: 310px;
}
.faq-drawer__trigger:checked + .faq-drawer__title::after {
  transform: rotate(-45deg) translateY(6px) translateX(-5px);
  transition: 0.25s ease-in-out;
}
.test:after {
	background-image: unset !important;
}

input[type="checkbox"] {
  display: none;
}
.box
{
	max-width:150px;
  margin: 0 auto;
  padding: 2px;
  /* Single pixel data uri image http://jsfiddle.net/LPxrT/
  /* background-image: gold, gold, white */
  background-image: url('data:image/gif;base64,R0lGODlhAQABAPAAAOqrAP///yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='),  url('data:image/gif;base64,R0lGODlhAQABAPAAAOqrAP///yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='),
url('data:image/gif;base64,R0lGODlhAQABAPAAAP///////yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==');
  background-repeat: no-repeat;
  background-size: 0 2px, 0 100%, 0% 2px;
  background-position: top center, top center, bottom center;
  -webkit-animation: drawBorderFromCenter 4s ease-in-out alternate infinite;
}

/* Chrome, Safari, Opera */
@-webkit-keyframes drawBorderFromCenter {
    0% {
      background-size: 0 2px, 0 0, 100% 100%;
    }
    20% {
      background-size: 100% 2px, 100% 0, 100% 100%;
    }
    66%
    {
      background-size: 100% 2px, 100% 98%, 100% 100%;
    }
    99%
    {
      background-size: 100% 2px, 100% 98%, 0 2px;
    }
}
.content
{
	max-width:150px;
  background: white;
  text-align: center;
  text-transform: uppercase;
	margin-left:3px;
	margin-right:3px;
	background-color:orange !important;
}
/* https://codepen.io/nxworld/pen/oLdoWb Ribbon resource css */
.upgrade-ribbon-top-right {
  top: -10px;
  right: -10px;
}
.upgrade-ribbon-top-right::before,
.upgrade-ribbon-top-right::after {
  border-top-color: transparent;
  border-right-color: transparent;
}
.upgrade-ribbon-top-right::before {
  top: 0;
  left: 0;
}
.upgrade-ribbon-top-right::after {
  bottom: 0;
  right: 0;
}
.upgrade-ribbon-top-right span {
  left: -25px;
  top: 30px;
  transform: rotate(45deg);
}
.upgrade-ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
}
.upgrade-ribbon::before,
.upgrade-ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  border: 5px solid #02811b;
}
.upgrade-ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  background-color: #015612;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}
.downgrade-ribbon-top-right {
  top: -10px;
  right: -10px;
}
.downgrade-ribbon-top-right::before,
.downgrade-ribbon-top-right::after {
  border-top-color: transparent;
  border-right-color: transparent;
}
.downgrade-ribbon-top-right::before {
  top: 0;
  left: 0;
}
.downgrade-ribbon-top-right::after {
  bottom: 0;
  right: 0;
}
.downgrade-ribbon-top-right span {
  left: -25px;
  top: 30px;
  transform: rotate(45deg);
}
.downgrade-ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
}
.downgrade-ribbon::before,
.downgrade-ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  border: 5px solid #BC544B;
}
.downgrade-ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  background-color: #c61a09;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}
</style>
</head>
<body class="custom_scrollbar">
<div id="page-wrap">
  <div class="top-side_wrapper">
  	 <div style="display:flex;width:33%; justify-content:start;" class="empty_element">
				 <!-- <a href="#history" class="more_info">
					 More information
				 </a> -->
				 &nbsp;
  	 </div>
	   <div style="display:flex;width:33%; justify-content:center;">
	       <strong class="title"> Nivel Cont </strong>
     </div>
   	 <div style="display:flex;width:33%; justify-content:end;">
  	      <svg width="100px" height="100px" viewBox="0 0 42 42" class="donut">
          		<circle id="c1" cx="21" cy="21" r="15.91549430918954" stroke-dasharray="100 0" stroke-dashoffset="100"></circle>
          		<circle id="c2" cx="21" cy="21" r="15.91549430918954" stroke-dasharray="0 100" stroke-dashoffset="0"></circle>
          		<g class="chart-text">
          		    <text x="50%" y="68%" dominant-baseline="middle" text-anchor="middle" id="counterText" font-size="10" fill="white"></text>
          		    <div class="timer-text_wrapper">
          		        <p class="text_one">REFRESH</p>
          	    		  <p class="text_two">IN</p>
          		    </div>
          		</g>
  	      </svg>
      </div>
   </div>
	 <!-- <div class="author_bio_toggle_wrapper">
  <a href="#0" id="author_bio_wrap_toggle">Expand Author Details</a>
</div>
<div id="author_bio_wrap" style="display: none;">
	This should be clickable!!!
</div> -->

  <?php
	//upgrade
	$downgradeOrUbgrade = "promovare";
	$res = Q($con,"select * from MasterOnlyDB.AutoCardLvl where FromCardLvl>ToCardLvl and FromCardLvl=$CardColor order by FromCardLvl asc,ToCardLvl desc");
	$TitleShown=false;
  $upgradeMeterCounter = 0;

	while($data = mysqli_fetch_assoc($res))
	{
    $upgradeMeterCounter += 1;
		if (!$TitleShown)
		{
			//echo ("<h1>Card Upgrade Progress</h1>");
			//echo("<br>");
			//echo("<br>");
			//echo("<br>");
			//echo("<br>");
			//echo("<br>");
			//echo("<br>");
			$TitleShown=true;
		}
	?>
		<div class="section_wrapper">
			<div class="between_flex">
				<div class="subtitle flex_container">
						<div><?php echo ($CardColors[$CardColor]); ?>&nbsp;</div>
						<img style="transform:translateY(-2px)" src="res/rightarrow.webp" alt=”img” width="40" height="30">
						<div>&nbsp;<?php echo($CardColors[$data["ToCardLvl"]]); ?>&nbsp;</div>
				</div>
		<?php

    $Type=$data["Type"];

		$ID=$data["ID"];
    //echo $Type;
		$Operator=$data["Operator"];
		$Threshold=$data["Threshold"];

		//echo ("CheckType ".$data["CheckType"]);
		//if($data["CheckType"]=="Once a month")
		//    echo ("DayOfMonth ".$data["DayOfMonth"]);
		//echo ("CheckPeriod ".$data["CheckPeriod"]);
		//if($data["CheckPeriod"]=="In the last (days)")
		 //   echo("DaysToCheck ".$data["DaysToCheck"]);

		$CheckType=$data["CheckType"];
		$CheckPeriod=$data["CheckPeriod"];
		switch($data["DayOfMonth"]) {
			case 1:
			 	$sufix = "st";
				break;
			case 2:
				$sufix = "nd";
				break;
			case 3:
				$sufix = "rd";
				break;
			case 21:
				$sufix = "st";
				break;
			case 22:
				$sufix = "nd";
				break;
			case 23:
				$sufix = "rd";
				break;
			default:
				$sufix = "th";
		}
		$StartDay=" date(DATE_SUB(NOW(), INTERVAL (".$data["DaysToCheck"]."-1) DAY)) ";//-1 pentru ca 1 = azi, o sa fie executat la 23:00 de acum
		if($CheckPeriod=="In this month")
		    $StartDay=" DATE_FORMAT(NOW() ,'%Y-%m-01') ";
		if($CheckPeriod=="In this year")
		    $StartDay=" DATE_FORMAT(NOW() ,'%Y-01-01') ";

		$Que="";
		if($Type=="Collected points")
			$Que="select ifnull(sum(b1c)/1000,0) Col from PlayerTracking.benefits_history where date between $StartDay and date(NOW()) and player_id=$PID";
		else
			$Que="select ifnull(sum($Type)/100,0) Col from PlayerTracking.Session where Date between $StartDay and date(NOW()) and PlayerId=$PID";

		$Total=0;

		$resIP= Q($con,"select * from Mystery.MasterIP");
		while($dataIP = mysqli_fetch_assoc($resIP))
		{
			$ConServer = mysqli_connect($dataIP["IP"], $dusername, $dpassword, $ddatabase) or die("Cannot connect to the database");
			$Total+=GetSingleValue($ConServer,$Que);
      $Total = round($Total);
		}

    if($Total > $Threshold) {
      $Total = $Threshold;
    }

		//echo("<br>");
		$pointsNeeded = round($Threshold - $Total);
    if($pointsNeeded < 1) {
      // If Points have a negative value, we make give the value 0
      $pointsNeeded = 0;
    }

    if($Type == 'Collected points'){
      $collectOrspend   = "colectezi";
			$collectedOrSpent = "colectat";
      $pointsOrCurrency = "puncte";
    } else {
      $collectOrspend   = "pariezi";
			$collectedOrSpent = "pariat";
      $pointsOrCurrency = $Currency;
    }

		if($data["DaysToCheck"] == 1) {
			$dayOrDays = "ultima zi";
			$data["DaysToCheck"] = "";
		} else {
			$rocada = "ultimele ";
			$dayOrDays = "zile";
		}

		if($Total != 0) {
			$Procent=$Total*100/$Threshold;
			$Procent=number_format($Procent, 100);
			// $Procent=min($Procent,100);
			// $Procent=round($Procent);

			//$Procent= 100-$Procent;
			$subtraction = $Threshold - $Total;
		} else {
			$Procent = $Total;
		}

		if($Procent < 99) {
			$Procent=ceil($Procent);
		} else {
			$Procent=floor($Procent);
		}

		//??????
		$theRequired = "";
		$congrats = "0";
		if($Procent == 100) {
			$theRequired = "necesare ";
			$congrats = "Felicitari, ai indeplinit cu succes conditiile de promovare!";
		}
		?>
		<div>
			<?php
				if($Procent == 100) {
					?>
				<div class="upgrade-ribbon upgrade-ribbon-top-right"><span>Indeplinit</span></div>
				<?php
				} else {
					?>
					<img class="rotate" src="res/uparrow.webp" alt=”img” width="25" height="30">
					<?php
				}
			?>
		</div>
	</div>

	 <?php
    echo("<div class='left'> In " . $rocada .$data["DaysToCheck"]. " " .$dayOrDays." ai ".$collectedOrSpent." ".$theRequired.$Total." ". $pointsOrCurrency . ".</div>");

		//echo("<br>");
    $subtraction = $Threshold - $Total;
		if($subtraction == 1 && $Type == "Collected points") {
			$pointsOrCurrency = "more point";
		}
		// if ($Operator == ">" || $Operator == ">=")
		// {
			//echo("<div class='right'> $Total / $Threshold </div>");
			if ($data["CheckPeriod"] == "In the last (days)")
			{
				echo("<br>");
        ?>
        <div class="left">	<?php echo("Pentru nivelul " . $CardColors[$data["ToCardLvl"]] ." trebuie sa ".$collectOrspend ." ". $subtraction ." ". $pointsOrCurrency ."."); ?>  </div>
        <?php
			}
			else if ($data["CheckPeriod"] == "In this month")
				{
					$now = new DateTime();
					$month_end = DateTime::createFromFormat('U', strtotime('last day of this month', time()));
					$month_end->setTime(23, 59, 59);
					$interval = date_diff($now, $month_end);
					if($interval->format('%a') == 1) {
						$singleOrPlural = "zi";
					} else {
						$singleOrPlural =  "zile";
					}
					echo("<br>");
					echo("<div class='left'> In ".$interval->format('%a')." $singleOrPlural progresul pentru regula de ".$downgradeOrUbgrade." va fi resetat.</div>");
					//echo("<div class='right'> ".$interval->format('%a days %hh:%mm:%ss remaining')."</div>");
				}

    // These classes are active only if the the progress bar is on 100%
		  $AdditionalStyle="";
			$AddClass="";
			if($Procent=="100")
			{
					$AdditionalStyle="border-top-right-radius: 20px;border-bottom-right-radius: 20px;background-image: unset !important;";
					$AddClass="class='test'";
			}

				echo("<div class='wide_flex'><div class='meter'><span $AddClass style='$AdditionalStyle width: $Procent%'></span></div><div class='transform_35 center'>$Procent%</div></div>");

				$ruleType = "";
				if($CheckType == "Once a month") {
					$ruleType = "o data pe luna";
				} elseif($CheckType == "Everyday") {
					$ruleType = "in fiecare zi";
				} elseif($CheckType == "Once a year") {
					$ruleType = "o data pe an";
				}
			?>
				<div class="faq-drawer">
					<input class="faq-drawer__trigger" id="<?php echo "faq$ID";  ?>" type="checkbox" /><label style="font-size:1.25em" class="faq-drawer__title" for="<?php echo "faq$ID";  ?>"   > Detalii</label>
					<div class="faq-drawer__content-wrapper">
						<div class="faq-drawer__content">
							<!-- cand o sa fie  -->
								<!-- poate sa fie: Once a month / Everyday -->
							<!-- Next check in: timpu TODO -->
							<div style="display:flex;flex-direction:column;">
			 			    <div class="left" style="min-width:150px;max-width:150px;max-height:30px;text-shadow:1px 0 #bbb;letter-spacing:1px;color:steelblue;font-size:1.25em;">Criterii</div>
								<?php //echo "Regula se aplica " . $ruleType . "."; ?>
								<div>Verificarea indeplinirii conditiilor se efectueaza in data de <?php echo $data["DayOfMonth"];?> a lunii.</div>
							</div>
						   <!-- Info about checked period/period that is checked: -->
								<!-- poate sa fie: in the last (days) / in this year / in this month -->
							<div style="display:flex;flex-direction:column;">
							<!-- <div class="left" style="min-width:150px;max-width:150px;max-height:30px;text-shadow:1px 0 #bbb; letter-spacing:1px;color:steelblue;font-size:1.25em;">Criteria</div> -->
							<div>Sistemul verifica daca ai <?php echo "$collectedOrSpent $Threshold $pointsOrCurrency";  ?>
								<?php

								$decurs = " decursul a ";
								if($CheckPeriod == "In the last (days)")
								{
									$consecutiveDaysOrDay = " zile consecutive.";
									if(empty($data["DaysToCheck"])) {
											$consecutiveDaysOrDay = " zi.";
									}
									// if(empty($data["DaysToCheck"])) {
									// 	$data["DaysToCheck"] = "2";
									// }
									echo "in " . $decurs . $data["DaysToCheck"].$consecutiveDaysOrDay."</div>";

									//echo ucfirst($collectOrspend) ." a total of ".$subtraction. " ".$pointsOrCurrency. " during ".$data["DaysToCheck"].$consecutiveDaysOrDay."</div>";
									echo "</div>";
								}
								if($CheckPeriod == "In this month") //This Year sau This month
								{
									echo "in luna curenta.</div>";
									// echo "<div>You still need to " . $collectOrspend ." ". $subtraction ." ". $pointsOrCurrency . " in ";

									$now = new DateTime();
									$month_end = DateTime::createFromFormat('U', strtotime('last day of this month', time()));
									$month_end->setTime(23, 59, 59);
									$interval = date_diff($now, $month_end);
									//echo($interval->format('%a days %h hours.</div></div>'));
									echo "</div>";
								}
								if($CheckPeriod == "In this year") //This Year sau This month
								{
									echo "in anul curent.<br>";

									// echo "Time until next year: ";
									//
									// $now = new DateTime();
									// $month_end = DateTime::createFromFormat('U', strtotime('last day of this year', time()));//TODO test
									// $month_end->setTime(23, 59, 59);
									// $interval = date_diff($now, $month_end);
									// echo($interval->format('%a days %h hours.</div></div>'));
									echo ('</div></div>');
								}
								?>
						</div>
					</div>
				</div>
			<?php
		?>
		</div>
		<?php
	}
	//downgrade
	$downgradeOrUbgrade = "retrogradare";
	$DaysAgo = GetSingleValue($con, "select DATEDIFF(NOW(), Timestamp) DaysAgo from MasterOnlyDB.AutoCardLvlLog where PlayerId=$PID order by Timestamp desc");

	//echo("days ago $DaysAgo");
	$res = Q($con,"select * from MasterOnlyDB.AutoCardLvl where FromCardLvl<ToCardLvl and FromCardLvl=$CardColor order by FromCardLvl asc,ToCardLvl desc");
	$TitleShown=false;
  $downgradeMeterCounter = 0;

	while($data = mysqli_fetch_assoc($res))
	{
    $downgradeMeterCounter += 1;

    $DowngradeGracePeriod=(int)$data["DowngradeGracePeriod"];
		$Type = $data["Type"];
	// if ((int)$data["DowngradeGracePeriod"] > (int)$DaysAgo)
	// {
		if (!$TitleShown)
		{
			$TitleShown=true;
		}
		if($Type == 'Collected points'){
			$collectOrspend   = "colectezi";
			$collectedOrSpent = "ai colectat";
			$pointsOrCurrency = "puncte";
		} else {
			$collectOrspend   = "pariezi";
			$collectedOrSpent = "ai pariat";
			$pointsOrCurrency = $Currency;
		}
		$CheckType=$data["CheckType"];

		$ZileOk=$DowngradeGracePeriod - (int)$DaysAgo;

		$locked = false;
		if($ZileOk > 0)
		{
			$added = 'background:#000 !important;';
			$locked = true;
		}
		if($ZileOk == 1) {
			$dayOrDays2 = "zi";
		} else {
			$dayOrDays2 = "zile";
		}
    ?>
     <div style="position:relative; <?php echo $added; ?>"	class="section_wrapper">
			 <?php
				 if($ZileOk > 0)
				 {
					 // echo "mai ai $ZileOk pana cand se activeaza regula, pana atunci faci ce vrei nu conteaza, lacatel";
					 ?>
					 <div class="downgrade_grace">
							 <?php echo "<span class='downgrade_grace-period'>&nbsp;Regula intra in vigoare in $ZileOk $dayOrDays2 &nbsp;</span>"; ?>
					 </div>
					 <!-- <div class="downgrade_lock">
							 <img class="lock" style="z-index:999;" src="res/lock.webp" alt=”img” width="150" height="150">
					 </div> -->
					 <?php
				 }

			 ?>
			 <div class="between_flex">
         <div class="subtitle flex_container">
             <div><?php echo ($CardColors[$CardColor]); ?>&nbsp;</div>
             		<img style="transform:translateY(-2px)" src="res/rightarrow.webp" alt=”img” width="40" height="30">
             <div>&nbsp;<?php echo($CardColors[$data["ToCardLvl"]]); ?>&nbsp;</div>
         </div>

    <?php
		$Operator=$data["Operator"];
		$Threshold=$data["Threshold"];
		$CheckPeriod=$data["CheckPeriod"];

		$StartDay=" date(DATE_SUB(NOW(), INTERVAL (".$data["DaysToCheck"]."-1) DAY))";//-1 pentru ca 1 = azi, o sa fie executat la 23:00 de acum
		if($CheckPeriod=="In this month") {
      $StartDay=" DATE_FORMAT(NOW() ,'%Y-%m-01') ";
    }
		if($CheckPeriod=="In this year") {
      $StartDay=" DATE_FORMAT(NOW() ,'%Y-01-01') ";
    }

		$Que="";
		if($Type=="Collected points") {
      $Que="select ifnull(sum(b1c)/1000,0) Col from PlayerTracking.benefits_history where date between $StartDay and date(NOW()) and player_id=$PID";
    } else {
      $Que="select ifnull(sum($Type)/100,0) Col from PlayerTracking.Session where Date between $StartDay and date(NOW()) and PlayerId=$PID";
    }

		$resIP= Q($con,"select * from Mystery.MasterIP");
		while($dataIP = mysqli_fetch_assoc($resIP))
		{
			$ConServer = mysqli_connect($dataIP["IP"], $dusername, $dpassword, $ddatabase) or die("Cannot connect to the database");
			$Total+=GetSingleValue($ConServer,$Que);
      $Total = round($Total);
		}

    if($Total > $Threshold) {
      $Total = $Threshold;
    }

		if($Total >= 0) {
			$Procent=$Total*100/$Threshold;
			$Procent=number_format($Procent, 1);
			// $Procent=min($Procent,100);
			// $Procent=round($Procent);

			$Procent;
			$subtraction = $Threshold - $Total;
		} else {
			$Procent = 0;
		}

		if($Procent < 99) {
			$Procent=ceil($Procent);
		} else {
			$Procent=floor($Procent);
		}

		$pointsNeeded = round($Threshold -  $Total);

    if($Type == 'Collected points'){
      $thirdWord  = "colectezi";
      $fourthWord = "puncte";
    } else {
      $thirdWord  = "cheltui";
      $fourthWord = $Currency;
    }
    ?>
		<div>
			 <!-- <img class="rotate" src="res/downarrow.webp" alt=”img” width="25" height="30"> -->
			 <?php if($Procent == 100) { ?>
				 <div class="downgrade-ribbon downgrade-ribbon-top-right" style="letter-spacing:1px !important;"><span>&nbsp;&nbsp;Indeplinit</span></div>
			 <?php } else { ?>
				 <img class="rotate" src="res/downarrow.webp" alt=”img” width="25" height="30">
			 <?php } ?>
		</div>
	</div>
      <div class="left">	<?php echo("Pentru a pastra nivelul " . $CardColors[$CardColor] ." trebuie sa ".$thirdWord ." ". $pointsNeeded ." ". $fourthWord) ."."; ?>  </div>
    <?php
		echo("<br>");
		// if ($Operator == ">" || $Operator == ">=")
		// {
		// 	//echo("<div class='left'> $Total/$Threshold </div>");
		// 	if ($data["CheckPeriod"] == "In the last (days)")
		// 	{
		// 		echo("<div class='right'> Last ".$data["DaysToCheck"]." days </div>");
		// 	}
		// 	else if ($data["CheckPeriod"] == "In this month")
		// 		{
		// 			$now = new DateTime();
		// 			$month_end = DateTime::createFromFormat('U', strtotime('last day of this month', time()));
		// 			$month_end->setTime(23, 59, 59);
		// 			$interval = date_diff($now, $month_end);
		// 			echo("<div class='right'> ".$interval->format('%a days %hh:%mm:%ss remaining')."</div>");
		// 		}
		// }
		// else
		// 	{

				//echo("<div class='right section_wrapper'> $Total/$Threshold </div>");

				// if($Total != 0) {
				// 	$Procent=$Total*100/$Threshold;
				// 	$Procent=min($Procent,100);
				// 	$Procent=round($Procent);
				//
				// 	//$Procent= 100-$Procent;
				// 	$subtraction = $Threshold - $Total;
				// } else {
				// 	$Procent = 100;
				// }

				$AdditionalStyle="";
				$AddClass="";

				if($Procent==100)
				{
						$AdditionalStyle="border-top-right-radius: 20px;border-bottom-right-radius: 20px;";
						$AddClass="class='test'";
				}

				$statementFix1 = $data["DaysToCheck"];
				$statementFix2 = " zile";
				$statementFix3 = "ultimele ";
				if($data["DaysToCheck"] == 1) {
					$statementFix1 = " ";
					$statementFix2 = "zi";
					$statementFix3 = "ultima";
				}
				if ($data["CheckPeriod"] == "In the last (days)")
				{
					$subtraction = $Threshold - $Total;
					echo("<div class='right'> In  ". $statementFix3 . $statementFix1 . $statementFix2. " ".$collectedOrSpent. " ". $Total. " ". $pointsOrCurrency . ".</div>");
				}
				else if ($data["CheckPeriod"] == "In this month")
				{
          $month_end =  date("Y-m-d", strtotime('last day of this month', time()));
					$now = new DateTime();
					$month_end = DateTime::createFromFormat('U', strtotime('last day of this month', time()));
					$month_end->setTime(23, 59, 59);
					$interval = date_diff($now, $month_end);
					if($interval->format('%a') == 1) {
						$singleOrPlural = "zi";
					} else {
						$singleOrPlural =  "zile";
					}
					echo("<div class='left'> Progresul va fi verificat in " . $decurs . $interval->format('%a')." de $singleOrPlural.</div>");
				}

    // These classes are active only if the the progress bar is on 100%

		// switch($data["DayOfMonth"]) {
		// 	case 1:
		// 	 	$sufix = "st";
		// 		break;
		// 	case 2:
		// 		$sufix = "nd";
		// 		break;
		// 	case 3:
		// 		$sufix = "rd";
		// 		break;
		// 	case 21:
		// 		$sufix = "st";
		// 		break;
		// 	case 22:
		// 		$sufix = "nd";
		// 		break;
		// 	case 23:
		// 		$sufix = "rd";
		// 		break;
		// 	default:
		// 		$sufix = "th";
		// }

		$ruleType = "";
		if($CheckType == "Once a month") {
			$ruleType = "o data pe luna";
		} elseif($CheckType == "Everyday") {
			$ruleType = "in fiecare zi";
		} elseif($CheckType == "Once a year") {
			$ruleType = "o data pe an";
		}
			echo("<div class='wide_flex'><div class='meter red downgrade1'><span $AddClass style='$AdditionalStyle width: $Procent%'></span></div><div class='transform_35 center'>$Procent%</div></div>");
			$ID += 5;
		?>
			<div class="faq-drawer">
				<input class="faq-drawer__trigger" id="<?php echo "faq$ID";  ?>" type="checkbox" /><label style="font-size:1.25em" class="faq-drawer__title" for="<?php echo "faq$ID";  ?>"   > Detalii</label>
				<div class="faq-drawer__content-wrapper">
					<div class="faq-drawer__content">
						<div style="display:flex;flex-direction:column;">
							<div class="left" style="min-width:150px;max-width:150px;max-height:30px;text-shadow:1px 0 #bbb;letter-spacing:1px;color:steelblue;font-size:1.25em;">Criterii</div>
							<?php //echo "Aceasta regula se aplica " . $ruleType . "."; ?>
							<!-- <div>Regula verifica <?php //echo $data["DayOfMonth"]?> a fiecarei luni</div> -->
						</div>
						 <!-- Info about checked period/period that is checked: -->
						<div style="display:flex;flex-direction:column;">
						<!-- <div class="left" style="min-width:150px;max-width:150px;max-height:30px;text-shadow:1px 0 #bbb; letter-spacing:1px;color:steelblue;font-size:1.25em;">Criteria</div> -->
						<div>Sistemul verifica daca <?php echo "$collectedOrSpent $Threshold $pointsOrCurrency";  ?>
							<?php
							if($CheckPeriod == "In the last (days)")
							{
								$consecutiveDaysOrDay = " zile consecutive.";
								$statementFix = $data["DaysToCheck"];
								if($data['DaysToCheck'] == 1) {
									$consecutiveDaysOrDay = " zi.";
									$statementFix = "";
								}
								echo "in ultimele ".$statementFix. $consecutiveDaysOrDay. "</div></div>";
							}
							if($CheckPeriod == "In this month") //This Year sau This month
							{
								echo "in luna curenta.</div>";
								//echo "<div>You still need to " . $collectOrspend ." ". $subtraction ." ". $pointsOrCurrency . " in ";

								$now = new DateTime();
								$month_end = DateTime::createFromFormat('U', strtotime('last day of this month', time()));
								$month_end->setTime(23, 59, 59);
								$interval = date_diff($now, $month_end);
								//echo($interval->format('%a days %h hours.</div></div>'));
								echo "</div>";
							}
							if($CheckPeriod == "In this year") //This Year sau This month
							{
								echo "this year.<br>";
								// echo "Time until next year: ";

								// $now = new DateTime();
								// $month_end = DateTime::createFromFormat('U', strtotime('last day of this year', time()));//TODO test
								// $month_end->setTime(23, 59, 59);
								// $interval = date_diff($now, $month_end);
								// echo($interval->format('%a days %h hours.</div></div>'));
								echo ('</div></div>');
							}
							// if($locked) {
								// echo "<span style='text-decoration: underline;'>Aceasta regula de retrogradare se va aplica in ". $ZileOk ." ". $dayOrDays2 . ".</span>";
							// }
							?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
</div>

<script language="JavaScript" type="text/javascript" src="src/jquery-1-9-1.js"></script>
<script>
//Progress bar helper
$(".meter > span").each(function () {
  $(this)
    .data("origWidth", $(this).width())
    .width(0)
    .animate(
      {
        width: $(this).data("origWidth")
      },
      1200
    );
});
// Script 2 - Circle progress counter logic
    function startTimer(duration) {
	    var timeout = setTimeout(function () {
	    var time = duration;
	    var i = 1;
	    var k = ((i/duration) * 100);
	    var l = 100 - k;
	    i++;
	    document.getElementById("c1").style.strokeDasharray = [l,k];
	    document.getElementById("c2").style.strokeDasharray = [k,l];
	    document.getElementById("c1").style.strokeDashoffset = l;
	    document.getElementById("counterText").innerHTML = duration;
	    var interval = setInterval(function() {
		if (i > time) {
		    clearInterval(interval);
		    setTimeout(timeout);
		    return;
		}
		k = ((i/duration) * 100);
		l = 100 - k;
		document.getElementById("c1").style.strokeDasharray  = [l,k];
		document.getElementById("c2").style.strokeDasharray  = [k,l];
		document.getElementById("c1").style.strokeDashoffset = l;
		document.getElementById("counterText").innerHTML = (duration +1)-i;
		i++;
		if(i == duration + 1) {
		    i = 1;
		    location.reload();
		}
		}, 1000);
	    },0);
    }
    startTimer(60);
</script>
</body>
</html>
