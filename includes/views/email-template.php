<?php
/**
 * Email Template.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->subject; ?></title>

	<style>
	@media only screen and (min-device-width: 601px) {
		.content {
			width: 600px !important;
		}
	}
	</style>

</head>
<body style="background: #f9f9f9;margin: 0;padding: 40px 0;text-align: center;color: #999999;">

<!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
<![endif]-->

	<table class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px; background: #ffffff;text-align: left;">
		{mailerglue_content_tag}
		<tr><td height="20" style="background: #f9f9f9;">&nbsp;</td></tr>
	</table>

<!--[if (gte mso 9)|(IE)]>
		</td>
	</tr>
</table>
<![endif]-->

</body>
</html>