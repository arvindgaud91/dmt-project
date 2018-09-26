<html>
<head>
<title>Merchant Check Out Page</title>
</head>
<body>
	<?php
	header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
	$paramList["MID"] = $MID;
$paramList["ORDER_ID"] = $ORDER_ID;
$paramList["CUST_ID"] = $CUST_ID;
$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
$paramList["CHANNEL_ID"] = $CHANNEL_ID;
$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
$paramList["WEBSITE"] = $WEBSITE;
$paramList["CALLBACK_URL"] = $CALLBACK_URL;

	?>
	<center><h1>Please do not refresh this page...</h1></center>
		<form method="post" action="https://secure.paytm.in/oltp-web/processTransaction" name="f1">

		<table border="1">
			<tbody>
			<?php
			foreach($paramList as $name => $value) {
				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			?>
			<input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
			</tbody>
		</table>
		<script type="text/javascript">
			document.f1.submit();
		</script>
	</form>
</body>
</html>