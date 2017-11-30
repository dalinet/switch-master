<!DOCTYPE html>
<html>
<head>
</head>
<body style="background-color: #fbfbfb;">
	<div id="wrapper" style="width: 600px; height: 100%; margin: 0 auto; border: 1px solid #ebebeb; background-color: #ffffff;">
		<img id="footer" src="{{ url('/img/email-header.jpg') }}" style="width: 100%;">
		
		<div id="container" style="margin: 0 auto; padding: 15px;">
			<div>
				<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
					Ha sido creada una nueva campaña en Switch con la siguiente información:
				</p>
				<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
					<strong>Cliente: </strong> {{ $costumer_name }}
				</p>
				<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
					<strong>Campaña: </strong> {{ $campaign_name }}
				</p>
				<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
					<strong>Fecha de creación: </strong> {{ $current_date }}
				</p>
			</div>
		</div>

		<img id="footer" src="{{ url('/img/email-footer.jpg') }}" style="width: 100%;">
		<div style="padding: 15px;vertical-align: top;padding-left: 15px;padding-right: 10px;word-break: break-word;word-wrap: break-word;text-align: left;font-size: 12px;line-height: 20px;color: #999;font-family: Georgia,serif">
			Clear Channel M&#233;xico<br>
			www.clearchannel.com.mx
		</div>
	</div>
</body>
</html>