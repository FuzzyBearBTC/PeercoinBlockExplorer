<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
	<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
	<script>
		$(function () {
		    startRefresh();
		});

		function startRefresh() {
		    setTimeout(startRefresh, 10000);
                    var murl = 'http://coinmarketcap.northpole.ro/api/v4/ppc.json';
                    $.getJSON('http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20json%20where%20url%3D%22' + encodeURIComponent(murl) + '%22&format=json&callback=?', function(data) {
			    jQuery('#ticker').html(data.query.results.json.price.usd);
			    jQuery('#ticker').append(' USD');
                jQuery('#tickerbtc').html(data.query.results.json.price.btc);
			    jQuery('#tickerbtc').append(' BTC');
	    jQuery('#marketcap').html(data.query.results.json.marketCap.usd);
			    jQuery('#marketcap').append(' USD');
                        
                        
		    });
		}
	</script>
</head>
<body>

	<div id="site_head">

		<div id="site_head_logo" align="center">

			<a href="./" title="Start Page">
				<img src="imgs/PeercoinBlockExplorerHeader.png">
			</a>

		</div>

	</div>
	
	<div id="page_wrap">