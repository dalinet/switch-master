<HTML>
    <head>
        <TITLE>Video CC</TITLE>
		<style>
			* {
				margin: 0;
				padding:  0;
			}

			.contVid {
				position: fixed;
				width: 100%;
				height: 100%;
			}
			.contVid video {
			    width: 100vw;
			    height: 56.25vw;
			    min-height: 100vh;
			    min-width: 177.77vh;
			    position: absolute;
			    top: 50%;
			    left: 50%;
			    -webkit-transform: translate(-50%,-50%);
			    -ms-transform: translate(-50%,-50%);
			    transform: translate(-50%,-50%);
			}
		</style>
    </head>
    <body>

	<div class="contVid">
		<video width="100%" height="100%" controls autoplay="">
			<source src="http://{{  $url["host"] }}/{{  $url["urlVideo"] }}" type="video/ogg">
			Your browser does not support the video tag.
		</video>
	</div>

    </body>
</HTML>
