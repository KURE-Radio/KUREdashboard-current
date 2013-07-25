<?php
session_start();
if(!isset($_SESSION['name']))
{
header('location: dashboard.php');
}

include("connect2db.php");
include("header.php");
?>
<script type="text/javascript">


function hidediv() 
{ 
	if (document.getElementById) 
	{ // DOM3 = IE5, NS6 
	document.getElementById('hideShow').style.visibility = 'hidden'; 
	} 
	else 
	{ 
		if (document.layers) 
		{ // Netscape 4 
		document.hideShow.visibility = 'hidden'; 
		} 
		else 
		{ // IE 4 
		document.all.hideShow.style.visibility = 'hidden'; 
		}
	}

	if (document.getElementById) 
	{ // DOM3 = IE5, NS6 
	document.getElementById('showHide').style.visibility = 'visible'; 
	} 
	else 
	{ 
		if (document.layers) 
		{ // Netscape 4 
		document.showHide.visibility = 'visible'; 
		} 
		else 
		{ // IE 4 
		document.all.showHide.style.visibility = 'visible'; 
		} 
	}
 
}

function addElement() {
  var ni = document.getElementById('myDiv');
  var numi = document.getElementById('theValue');
  var num = (document.getElementById('theValue').value -1)+ 2;
  numi.value = num;
  var newdiv = document.createElement('div');
  var divIdName = 'my'+num+'Div';
  newdiv.setAttribute('id',divIdName);
  newdiv.innerHTML = 'Element Number '+num+' has been added! <a href=\'#\' onclick=\'removeElement('+divIdName+')\'>Remove the div "'+divIdName+'"</a>';
  ni.appendChild(newdiv);
}
</script>

<div width="100%" align="center">
<h3>Giga Spin List v1.1</h3>
<h4><a href="#csv">Got it typed out already?</a></h4>

<form action="playlistsubmit.php" method="post">
<?php
echo
'<input type="hidden" name="showname" value="' . $_SESSION['show'] . '"/>
<input type="hidden" name="djname" value="' . $_SESSION['dj'] . '"/>
<input type="hidden" name="showtime" value="' . $_SESSION['showtime'] .'"/>'
?>

<table>
<div id="addline">
<tr><td>#</td><td>Song Name</td><td>Artist Name</td><td>Album Name</td></tr>
<tr><td>1</td><td><input size="40" type="text" name="song1" /></td><td><input size="40" type="text" name="artist1" /></td><td><input size="40" type="text" name="album1" /></td></tr>
<tr><td>2</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song2" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist2" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album2" /></td></tr>
<tr><td>3</td><td><input size="40" type="text" name="song3" /></td><td><input size="40" type="text" name="artist3" /></td><td><input size="40" type="text" name="album3" /></td></tr>
<tr><td>4</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song4" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist4" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album4" /></td></tr>
<tr><td>5</td><td><input size="40" type="text" name="song5" /></td><td><input size="40" type="text" name="artist5" /></td><td><input size="40" type="text" name="album5" /></td></tr>
<tr><td>6</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song6" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist6" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album6" /></td></tr>
<tr><td>7</td><td><input size="40" type="text" name="song7" /></td><td><input size="40" type="text" name="artist7" /></td><td><input size="40" type="text" name="album7" /></td></tr>
<tr><td>8</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song8" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist8" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album8" /></td></tr>
<tr><td>9</td><td><input size="40" type="text" name="song9" /></td><td><input size="40" type="text" name="artist9" /></td><td><input size="40" type="text" name="album9" /></td></tr>
<tr><td>10</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song10" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist10" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album10" /></td></tr>
<tr><td>11</td><td><input size="40" type="text" name="song11" /></td><td><input size="40" type="text" name="artist11" /></td><td><input size="40" type="text" name="album11" /></td></tr>
<tr><td>12</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song12" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist12" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album12" /></td></tr>
<tr><td>13</td><td><input size="40" type="text" name="song13" /></td><td><input size="40" type="text" name="artist13" /></td><td><input size="40" type="text" name="album13" /></td></tr>
<tr><td>14</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song14" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist14" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album14" /></td></tr>
<tr><td>15</td><td><input size="40" type="text" name="song15" /></td><td><input size="40" type="text" name="artist15" /></td><td><input size="40" type="text" name="album15" /></td></tr>
<tr><td>16</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song16" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist16" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album16" /></td></tr>
<tr><td>17</td><td><input size="40" type="text" name="song17" /></td><td><input size="40" type="text" name="artist17" /></td><td><input size="40" type="text" name="album17" /></td></tr>
<tr><td>18</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song18" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist18" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album18" /></td></tr>
<tr><td>19</td><td><input size="40" type="text" name="song19" /></td><td><input size="40" type="text" name="artist19" /></td><td><input size="40" type="text" name="album19" /></td></tr>
<tr><td>20</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song20" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist20" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album20" /></td></tr>
<tr><td>21</td><td><input size="40" type="text" name="song21" /></td><td><input size="40" type="text" name="artist21" /></td><td><input size="40" type="text" name="album21" /></td></tr>
<tr><td>22</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song22" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist22" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album22" /></td></tr>
<tr><td>23</td><td><input size="40" type="text" name="song23" /></td><td><input size="40" type="text" name="artist23" /></td><td><input size="40" type="text" name="album23" /></td></tr>
<tr><td>24</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song24" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist24" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album24" /></td></tr>
<tr><td>25</td><td><input size="40" type="text" name="song25" /></td><td><input size="40" type="text" name="artist25" /></td><td><input size="40" type="text" name="album25" /></td></tr>
<tr><td>26</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song26" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist26" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album26" /></td></tr>
<tr><td>27</td><td><input size="40" type="text" name="song27" /></td><td><input size="40" type="text" name="artist27" /></td><td><input size="40" type="text" name="album27" /></td></tr>
<tr><td>28</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song28" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist28" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album28" /></td></tr>
<tr><td>29</td><td><input size="40" type="text" name="song29" /></td><td><input size="40" type="text" name="artist29" /></td><td><input size="40" type="text" name="album29" /></td></tr>
<tr><td>30</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song30" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist30" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album30" /></td></tr>
<tr><td>31</td><td><input size="40" type="text" name="song31" /></td><td><input size="40" type="text" name="artist31" /></td><td><input size="40" type="text" name="album31" /></td></tr>
<tr><td>32</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song32" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist32" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album32" /></td></tr>
<tr><td>33</td><td><input size="40" type="text" name="song33" /></td><td><input size="40" type="text" name="artist33" /></td><td><input size="40" type="text" name="album33" /></td></tr>
<tr><td>34</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song34" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist34" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album34" /></td></tr>
<tr><td>35</td><td><input size="40" type="text" name="song35" /></td><td><input size="40" type="text" name="artist35" /></td><td><input size="40" type="text" name="album35" /></td></tr>
<tr><td>36</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song36" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist36" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album36" /></td></tr>
<tr><td>37</td><td><input size="40" type="text" name="song37" /></td><td><input size="40" type="text" name="artist37" /></td><td><input size="40" type="text" name="album37" /></td></tr>
<tr><td>38</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song38" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist38" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album38" /></td></tr>
<tr><td>39</td><td><input size="40" type="text" name="song39" /></td><td><input size="40" type="text" name="artist39" /></td><td><input size="40" type="text" name="album39" /></td></tr>
<tr><td>40</td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="song40" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="artist40" /></td><td><input style="background-color:#CCCCCC;" size="40" type="text" name="album40" /></td></tr>

</div>
</table><br/>
<div>
<span id="hideShow">
<input type="submit" style="width: 400px" onclick="hidediv()">
</span><br/>
<span id="showHide" style="visibility:hidden;">
Loading<marquee width="40px" height="5px" direction="right">. . . </marquee>
</span>
</div>
<a name="csv"><h3>Copy/Paste your pre-typed playlist here:</h3></a>
<table width="60%" align="center">
<tr><td>Do you have a:</td><td><select name="type"><option selected value="none">&nbsp;</option><option value=",">Comma Separated Format</option><option value="t">Tab Separated Format (iTunes playlist)</option><option value="-">Hyphen Separated Format</option><option value=";">Semicolon Separated Format</option></td></tr>
<tr><td>What format?</td><td><select name="format"><option value="tr_ar_al">Track - Artist - Album</option><option value="tr_al_ar">Track - Album - Artist</option><option value="ar_tr_al">Artist - Track - Album</option><option value="ar_al_tr">Artist - Album - Track</option><option value="al_ar_tr">Album - Artist - Track</option><option value="al_tr_ar">Album - Track - Artist</option></select></td></tr>
<tr><td colspan="3"><textarea resize="none" cols="90" rows="20" name="massplaylist"></textarea></td></tr>

</table>
<br/>
</form>


</div>

<?php
include("footer.php");
?>
