 <!doctype html>
<?php 
  $min = 15;
  $max = 278;

  $priceSelectedMin = explode('-', $_REQUEST["price"])[0];
  $priceSelectedMax = end(explode('-', $_REQUEST["price"]));
  if($priceSelectedMin == ''){
    $priceSelectedMin = $min;
  }
  if($priceSelectedMax == ''){
    $priceSelectedMax = $max;
  }
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Slider - Range slider</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: <?php echo $min; ?>,
      max: <?php echo $max; ?>,
      values: [ <?php echo $priceSelectedMin; ?>, <?php echo $priceSelectedMax; ?> ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
  } );
  </script>
</head>
<body>
 
<p>
  <label for="amount">Price range:</label>
  <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
 
<div id="slider-range"></div>
 
 
</body>
</html>