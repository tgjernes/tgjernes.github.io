<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Weight and Balance</title>
<style type="text/css">
<!--
@media print{
  .genbody {
	width: 100%;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	margin-right: .25in;
  }
  .spinput {
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #333333;
	font-size: 14px;
	font-weight: bold;
  }
  .sxinput {border: none;}
  .sonly {visibility: hidden;}
}
@media screen{
  .genbody {
	width: 800px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
  }
  .spinput {
	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #333333;
	font-size: 14px;
	font-weight: bold;
  }
  .sxinput {border: 1px solid #999999; background-color: #FFFFCC;}
  .sonly {visibility: visible;}
} 
.spinput1 {	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #333333;
	font-size: 14px;
	font-weight: bold;
}
.spinput1 {	border-bottom-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-bottom-color: #333333;
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<?php
  $school = $_REQUEST["school"];
  if ($school == "") $school = "Sensei Learning Systems";
  $actype = $_REQUEST["actype"];
  if ($actype == "") {echo "Missing Aircraft Type</body></html>"; exit;}
  $acfile = "typedata/" . strtolower($actype) . ".typ"; 
  if (!file_exists($acfile)) {echo "Unknown Aircraft Type</body></html>"; exit;}
  // read the typedata array
  $typedata = file($acfile);
  foreach ($typedata as $typeentry) {
    $tpair = explode("=", $typeentry);
	$td[trim($tpair[0])] = trim($tpair[1]);
  } // foreach	
?>
<script>
  actype = "c172";
  empty_weight = <?php echo $_REQUEST["emwt"]; ?>;
  empty_cg = <?php echo $_REQUEST["emcg"]; ?>;
  empty_moment = empty_weight * empty_cg;
  front_seat_wt = 0;
  front_seat_cg = <?php echo $td["seatrow1arm"]; ?>;
  rear_seat_wt = 0;
  rear_seat_moment = 0;
  rear_seat_cg = 0;
  seat_rows = <?php echo $td["seatrows"]; ?>;
  if (seat_rows < 2) rear_seat_cg = 0; else rear_seat_cg = <?php echo $td["seatrow2arm"]; ?>;
  cargo1_max = <?php echo $td["bag1max"]; ?>;
  cargo2_max = <?php echo $td["bag2max"]; ?>;
  cargo1_wt = 0;
  cargo2_wt = 0;
  cargo1_cg = <?php echo $td["bag1arm"]; ?>;
  if (cargo2_max > 0) cargo2_cg = <?php echo $td["bag2arm"]; ?>;
  cargo1_moment = 0;
  cargo2_moment = 0;
  fuel_wt = 0;
  fuel_max = <?php echo $td["fuelmax"]; ?>;
  fuel_cg = <?php echo $td["fuelarm"]; ?>;
  fuel_moment = 0;
  
  iwt_min = <?php echo $td["iminweight"]; ?>;
  iwt_max = <?php echo $td["imaxweight"]; ?>;
  icg_min = <?php echo $td["imincg"]; ?>;
  icg_max = <?php echo $td["imaxcg"]; ?>;
 
  function initialize(){
    document.getElementById("par01").innerHTML = empty_moment.toFixed(0);
  
  
  } // initialize()
  
  function clearAll(){ // clear all calculations when changes are made
    document.getElementById("par01").innerHTML = "&nbsp;";
    document.getElementById("par02").innerHTML = "&nbsp;";
    document.getElementById("par03").innerHTML = "&nbsp;";
    document.getElementById("par04").innerHTML = "&nbsp;";
    document.getElementById("par05").innerHTML = "&nbsp;";
    document.getElementById("par06").innerHTML = "&nbsp;";
    document.getElementById("par07").innerHTML = "&nbsp;";
    document.getElementById("par08").innerHTML = "&nbsp;";
    document.getElementById("par09").innerHTML = "&nbsp;";
    document.getElementById("par10").innerHTML = "&nbsp;";
    document.getElementById("par11").innerHTML = "&nbsp;";
    document.getElementById("par12").innerHTML = "&nbsp;";
    document.getElementById("par13").innerHTML = "&nbsp;";
    document.getElementById("par14").innerHTML = "&nbsp;";
    document.getElementById("par15").innerHTML = "&nbsp;";
    document.getElementById("par16").innerHTML = "&nbsp;";
    document.getElementById("dred").style.visibility = "hidden";
    document.getElementById("dgreen").style.visibility = "hidden";
    document.getElementById("dblue").style.visibility = "hidden";
  } // clearAll()
  
  function CalculateWB(){
  
  
    // load the parameters
    empty_weight = 1 * document.getElementById("acewt").value;
    empty_cg = 1 * document.getElementById("acecg").value;
    front_seat_wt = 1 * document.getElementById("sfront").value;
	if (seat_rows > 1) rear_seat_wt = 1 * document.getElementById("srear").value;
	cargo1_wt = 1 * document.getElementById("cargo1").value;
	if (cargo2_max > 0) cargo2_wt = 1 * document.getElementById("cargo2").value;
	fuel_wt = 1 * document.getElementById("fuelto").value;
	fuel_ld = 1 * document.getElementById("fuelld").value;

    // report any errors
    if (empty_weight < 100) {alert("Invalid Aircraft Empty Weight"); return; }
    if (empty_cg < 1) {alert("Invalid Aircraft Empty CG (Arm)"); return; }
	if (front_seat_wt < 1) {alert("At least One Front Seat Must be Occupied"); return; }
	if (cargo1_wt > cargo1_max) {alert("Cargo Area 1 Maximum Exceeded"); return; }
	if (cargo2_wt > cargo2_max) {alert("Cargo Area 2 Maximum Exceeded"); return; }
	if (fuel_wt < 1) {alert("Please Takeoff with SOME Fuel"); return; }
	if (fuel_wt > fuel_max) {alert("Maximum Fuel Load Exceeded.  Please correct."); return; }
	
	// do the calculations
	if (isNaN(empty_weight)) empty_weight = 0;
	if (isNaN(empty_cg)) empty_cg = 0;
	empty_moment = empty_weight * empty_cg;
	document.getElementById("par01").innerHTML = empty_moment.toFixed(0);
	if (isNaN(front_seat_wt)) front_seat_wt = 0;
	if (isNaN(front_seat_cg)) front_seat_cg = 0;
	front_seat_moment = front_seat_wt * front_seat_cg;
	document.getElementById("par02").innerHTML = front_seat_moment.toFixed(0);
	if (seat_rows > 1){
	  if (isNaN(rear_seat_wt)) rear_seat_wt = 0;
	  if (isNaN(rear_seat_cg)) rear_seat_cg = 0;
	  rear_seat_moment = rear_seat_wt * rear_seat_cg;
	  document.getElementById("par03").innerHTML = rear_seat_moment.toFixed(0);
	} // if seat_rows
	if (isNaN(cargo1_wt)) cargo1_wt = 0;
	if (isNaN(cargo1_cg)) cargo1_cg = 0;
	cargo1_moment = cargo1_wt * cargo1_cg;
	document.getElementById("par04").innerHTML = cargo1_moment.toFixed(0);
	if (cargo2_max > 0) {
	  if (isNaN(cargo2_wt)) cargo2_wt = 0;
	  if (isNaN(cargo2_cg)) cargo2_cg = 0;
	  cargo2_moment = cargo2_wt * cargo2_cg;
	  document.getElementById("par05").innerHTML = cargo2_moment.toFixed(0);
	} // if cargo2
	total_empty_moment = parseFloat(empty_moment) + parseFloat(front_seat_moment) + parseFloat(rear_seat_moment) + parseFloat(cargo1_moment) + parseFloat(cargo2_moment);
	total_empty_weight = parseFloat(empty_weight) + parseFloat(front_seat_wt) + parseFloat(rear_seat_wt) + parseFloat(cargo1_wt) + parseFloat(cargo2_wt);
	total_empty_cg = 1 * total_empty_moment / total_empty_weight.toFixed(0);
	document.getElementById("par06").innerHTML = total_empty_weight.toFixed(0);
	document.getElementById("par07").innerHTML = total_empty_cg.toFixed(1);
	document.getElementById("par08").innerHTML = total_empty_moment.toFixed(0);
	if (isNaN(fuel_wt)) fuel_wt = 0;
	if (isNaN(fuel_cg)) fuel_cg = 0;
	fuel_moment = fuel_wt * fuel_cg;
	document.getElementById("par09").innerHTML = fuel_moment.toFixed(0);
	total_to_moment = total_empty_moment + fuel_moment;
	total_to_weight = total_empty_weight + fuel_wt;
	total_to_cg = total_to_moment / total_to_weight;
	document.getElementById("par10").innerHTML = total_to_weight.toFixed(0);
	document.getElementById("par11").innerHTML = total_to_cg.toFixed(1);
	document.getElementById("par12").innerHTML = total_to_moment.toFixed(0);
	if (isNaN(fuel_ld)) fuel_ld = 0;
	fuel_moment_ld = fuel_ld * fuel_cg;
	document.getElementById("par13").innerHTML = fuel_moment_ld.toFixed(0);
	total_ld_moment = total_empty_moment + fuel_moment_ld;
	total_ld_weight = total_empty_weight + fuel_ld;
	total_ld_cg = total_ld_moment / total_ld_weight;
	document.getElementById("par14").innerHTML = total_ld_weight.toFixed(0);
	document.getElementById("par15").innerHTML = total_ld_cg.toFixed(1);
	document.getElementById("par16").innerHTML = total_ld_moment.toFixed(0);
	
	if (document.getElementById("plotit").checked){
	  // empty plot
	  yval = 100 - ((100.0 * (total_empty_weight - iwt_min)) / (iwt_max - iwt_min));
	  yval = yval.toFixed(1) + "%";
	  document.getElementById("dred").style.top = yval;
	  xval = ((1.0 * (total_empty_cg - icg_min)) / (icg_max - icg_min)) * 100.0;
	  xval = xval.toFixed(1) + "%";
	  document.getElementById("dred").style.left = xval;
	  document.getElementById("dred").style.visibility = "visible";
	  // takeoff plot
	  yval = 100 - ((100.0 * (total_to_weight - iwt_min)) / (iwt_max - iwt_min));
	  yval = yval.toFixed(1) + "%";
	  document.getElementById("dgreen").style.top = yval;
	  xval = ((1.0 * (total_to_cg - icg_min)) / (icg_max - icg_min)) * 100.0;
	  xval = xval.toFixed(1) + "%";
	  document.getElementById("dgreen").style.left = xval;
	  document.getElementById("dgreen").style.visibility = "visible";
	  // landing plot
	  yval = 100 - ((100.0 * (total_ld_weight - iwt_min)) / (iwt_max - iwt_min));
	  yval = yval.toFixed(1) + "%";
	  document.getElementById("dblue").style.top = yval;
	  xval = ((1.0 * (total_ld_cg - icg_min)) / (icg_max - icg_min)) * 100.0;
	  xval = xval.toFixed(1) + "%";
	  document.getElementById("dblue").style.left = xval;
	  document.getElementById("dblue").style.visibility = "visible";
	  
	} // if plotting
	
  } // CalculateWB

</script>

<div class="genbody">

<div align="right" style="float: right; font-size: 14px; font-weight: 600; color: #003399;"><?php echo $td["typename"]; ?> Weight and Balance&nbsp;</div>
<b style="color: #003399;"><i><?php echo strtoupper($school); ?></i></b>
<hr size="1" noshade>
<br>&nbsp;<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
  <tr>
    <td width="33%" colspan="1" align="left" valign="top">Name: <input class="spinput"></td>
    <td width="34%" colspan="1" align="left" valign="top">Date: <input class="spinput"></td>
    <td width="33%" colspan="1" align="left" valign="top">A/C Ident: <input name="" type="text" class="spinput" style="font-size: 16px; font-weight: 600;" value="<?php echo strtoupper($_REQUEST["acid"]); ?>" size="10" maxlength="8"></td>
  </tr>
</table>
<br>&nbsp;<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #333333; font-size: 12px;">
  <tr bgcolor="#333333">
    <td width="34%" colspan="1" align="left" valign="top" style="border-bottom: 2px solid #000000; color: #FFFFFF; font-size: 14px; font-weight: 600;">AC TYPE</td>
    <td width="22%" colspan="1" align="left" valign="top" style="border-bottom: 2px solid #000000; color: #FFFFFF; font-size: 14px; font-weight: 600;">WEIGHT</td>
    <td width="22%" colspan="1" align="left" valign="top" style="border-bottom: 2px solid #000000; color: #FFFFFF; font-size: 14px; font-weight: 600;">ARM</td>
    <td width="22%" colspan="1" align="left" valign="top" style="border-bottom: 2px solid #000000; color: #FFFFFF; font-size: 14px; font-weight: 600;">MOMENT</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Empty Weight</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input name="" type="text" class="sxinput" id="acewt" onChange="clearAll();" value="<?php echo $_REQUEST["emwt"]; ?>"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input id="acecg" class="sxinput" onChange="clearAll();" value="<?php echo $_REQUEST["emcg"]; ?>"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par01">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Front Seats</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="sfront" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["seatrow1arm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par02">&nbsp;</td>
  </tr>
   <?php
    if ($td["seatrows"] == "1") echo '<tr bgcolor="#FFFFFF" style="visibility: hidden; position: absolute;">'; else echo '<tr bgcolor="#FFFFFF">';
   ?>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Rear Seats</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="srear" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["seatrow2arm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par03">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Cargo Area 1 (max <?php echo $td["bag1max"]; ?>)</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="cargo1" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["bag1arm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par04">&nbsp;</td>
  </tr>
  <?php
    if ($td["bag2max"] == "0") echo '<tr bgcolor="#FFFFFF" style="visibility: hidden; position: absolute;">'; else echo '<tr bgcolor="#FFFFFF">';
  ?>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Cargo Area 2 (max <?php echo $td["bag2max"]; ?>)</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="cargo2" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["bag2arm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par05">&nbsp;</td>
  </tr>
  <tr bgcolor="#CCFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><b>Zero Fuel Totals</b></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par06">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par07">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par08">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Fuel at Takeoff (max <?php echo $td["fuelmax"]; ?>)</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="fuelto" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["fuelarm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par09">&nbsp;</td>
  </tr>
  <tr bgcolor="#CCFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><b>Takeoff Totals</b></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par10">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par11">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par12">&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;">Fuel at Landing</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><input onChange="clearAll();" id="fuelld" class="sxinput"></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><?php echo $td["fuelarm"]; ?></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par13">&nbsp;</td>
  </tr>
  <tr bgcolor="#CCFFFF">
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;"><b>Landing Totals</b></td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par14">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par15">&nbsp;</td>
    <td colspan="1" align="left" valign="top" style="border-bottom: 1px solid #333333;" id="par16">&nbsp;</td>
  </tr>
</table>
<div align="right" class="sonly"><input id="plotit" onChange="clearAll();" type="checkbox" value="yes"> Plot Results&nbsp;&nbsp;&nbsp;&nbsp;<input name="" type="button" onClick="CalculateWB();" value="Calculate"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="70%" colspan="1" align="left" valign="top">
	  <div style="position: relative; top: -4px; left: -4px;">
	    <img src="<?php echo "typedata/" . strtolower($td["imagecode"]) . ".jpg"; ?>" style="width: 100%; position: relative; top: 4px; left: 4px;">
	    <img id="dred" src="dred.gif" width="7" height="7" hspace="0" vspace="0" border="0" style="position: absolute; top: 50%; left: 25%; visibility: hidden;">
	    <img id="dgreen" src="dgreen.gif" width="7" height="7" hspace="0" vspace="0" border="0" style="position: absolute; top: 50%; left: 25%; visibility: hidden;">
	    <img id="dblue" src="dblue.gif" width="7" height="7" hspace="0" vspace="0" border="0" style="position: absolute; top: 50%; left: 25%; visibility: hidden;">
	  </div>
	  <br>&nbsp;<br><b>Passenger Manifest</b><br>
	  <table width="100%" border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #333333; font-size: 12px;">
	    <tr bgcolor="#333333">
		  <td width="70%" colspan="2" align="center" valign="top" style="color: #FFFFFF;"><b>NAME</b></td>
		  <td width="30%" colspan="1" align="center" valign="top" style="color: #FFFFFF;"><b>CONTACT #</b></td>
		</tr>
	    <tr bgcolor="#FFFFFF">
		  <td width="30%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;">Pilot in Command</td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnm1" class="sxinput"></td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnb1" class="sxinput"></td>
		</tr>
	    <tr bgcolor="#FFFFFF">
		  <td width="30%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;">Student / Front Px</td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnm2" class="sxinput"></td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnb2" class="sxinput"></td>
		</tr>
   <?php
    if ($td["seatrows"] == "1") echo '<tr bgcolor="#FFFFFF" style="visibility: hidden; position: absolute;">'; else echo '<tr bgcolor="#FFFFFF">';
   ?>
		  <td width="30%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;">Passenger</td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnm3" class="sxinput"></td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnb3" class="sxinput"></td>
		</tr>
   <?php
    if ($td["seatrows"] == "1") echo '<tr bgcolor="#FFFFFF" style="visibility: hidden; position: absolute;">'; else echo '<tr bgcolor="#FFFFFF">';
   ?>
		  <td width="30%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;">Passenger</td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnm4" class="sxinput"></td>
		  <td width="35%" colspan="1" align="left" valign="top" style="border-top: 1px solid #333333;"><input id="pxnb4" class="sxinput"></td>
		</tr>
	  </table>
	</td>
  </tr>
</table>
<hr size="1" noshade><b>Air Time</b>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="33%" colspan="1" align="left" valign="top">Current:<br><input class="spinput"></td>
    <td width="34%" colspan="1" align="left" valign="top">Next Inspection:<br><input class="spinput"></td>
    <td width="33%" colspan="1" align="left" valign="top">Time Remaining:<br><input class="spinput"></td>
  </tr>
</table>
</div>
<script>initialize();</script>
</body></html>
