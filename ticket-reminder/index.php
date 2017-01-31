<!DOCTYPE html>
<html lang="sv-SE">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Påminnelse att boka SJ-biljetter 90 dagar i förväg</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/flick/jquery-ui.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
  <script>
	  $(function() {
$.datepicker.setDefaults( $.datepicker.regional[ "sv" ] );
	  
	    $("#datepicker").datepicker({dateFormat: 'yy-mm-dd', defaultDate:+90, altField: '#datestart', altFormat: '@'}).change(function(){
		    $("#datestart").val(
		    	$("#datestart").datepicker().val() / 1000
		    	- (90 * 24 * 60 * 60)
		    	+ (11 * 60 * 60)
		    );
		    $("#summary").val(
		    	'Boka tågbiljetter för ' + $("#datepicker").val()
		    );
		    if ( $("#datepicker").val() != '' ) {
			    $("#submit").prop('disabled', false);		    
		    }
	    });
	  });
  </script>
  
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-3878005-1', 'psu.se');
	  ga('send', 'pageview');
	</script>  
</head>
<body>

<form action="ical.php" method="GET">
	<label for="datepicker">Datum för tågresan:</label><br />
	<input type="text" id="datepicker" /><br />

	<input type="hidden" name="datestart" id="datestart" />
	<input type="hidden" name="summary" id="summary" />
	<input type="hidden" name="filename" value="sj.ics" />
	<input type="hidden" name="sj" value="1" />
	<input type="submit" id="submit" value="Påmin mig 90 dagar tidigare" disabled="true" />
</form>

</body>
</html>