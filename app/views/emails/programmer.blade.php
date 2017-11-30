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
					{{ Lang::get( 'programmerEmail.msg' ) }}
				</p>
			</div>

			@if( $info[ "msg" ] != false )
				<div>
					<h3 style="color: #565656; font-family: Georgia,serif; font-size: 18px;">Mensaje:</h3>
					<p style="color: #565656; font-family: Georgia,serif; font-size: 16px;">
						{{$info[ "msg" ]}}
					</p>
				</div>
			@endif

			<div>
				<h3 style="color: #565656; font-family: Georgia,serif; font-size: 18px;">Campaña: {{ $info[ "campaign" ][ "name" ] }}</h3>
			</div>
			<div>
				<h3 style="color: #565656; font-family: Georgia,serif; font-size: 18px;">URLs:</h3>
				<ol>
					@foreach ($info["urls"] as $key => $url)
						<li style="word-wrap: break-word; color: #565656; font-family: Georgia,serif; font-size: 16px;">
							{{ $url[ "url" ] }}
						</li> 
					@endforeach
				</ol>
			</div>
			<div>
				<h3 style="color: #565656; font-family: Georgia,serif; font-size: 18px;">Pantallas/URLs:</h3>
				@foreach ($info["urlsInScreen"] as $key => $screen)
					<div style="width: 100%; float: left; position: relative; min-height: 1px;" > <div style="min-height: 20px; padding: 15px; Margin-top: 10px; Margin-bottom: 10px; Margin-left: 10px; Margin-right: 10px;background-color: #f5f5f5; border: 1px solid #e3e3e3; border-radius: 4px; -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05); box-shadow: inset 0 1px 1px rgba(0,0,0,.05); color: #565656; font-family: Georgia,serif; font-size: 14px;">
							<h4 style="-webkit-margin-before: 0; -webkit-margin-after: 0;">
								{{ $screen[ "name" ] }} / {{ $screen[ "location" ] }}
							</h4>

							<ol>
							@foreach( $screen[ "screen_ur_l" ] as $url)
								<li>
									{{ $url[ "url" ] }}
								</li>
							@endforeach
							</ol>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<img id="footer" src="{{ url('/img/email-footer.jpg') }}" style="width: 100%;">
		<div style="padding: 0px;vertical-align: top;padding-left: 15px;padding-right: 10px;word-break: break-word;word-wrap: break-word;text-align: left;font-size: 12px;line-height: 20px;color: #444;font-family: Georgia,serif">
		    {{ Lang::get('programmerEmail.no-reply') }}
		</div>
		<div style="padding: 10px;vertical-align: top;padding-left: 15px;padding-right: 10px;word-break: break-word;word-wrap: break-word;text-align: left;font-size: 12px;line-height: 20px;color: #999;font-family: Georgia,serif">
			Clear Channel M&#233;xico<br>
			www.clearchannel.com.mx
		</div>
	</div>
</body>
</html>