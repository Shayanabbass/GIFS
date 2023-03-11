<script type="text/javascript">
	setREVStartSize({c: 'rev_slider_1_1',rl:[1240,1024,778,480],el:[500],gw:[1024],gh:[500],type:'standard',justify:'',layout:'fullwidth',mh:"0"});
	var	revapi1,
	tpj;
	function revinit_revslider11() {
	jQuery(function() {
	tpj = jQuery;
	revapi1 = tpj("#rev_slider_1_1");
	if(revapi1==undefined || revapi1.revolution == undefined){
		revslider_showDoubleJqueryError("rev_slider_1_1");
	}else{
		revapi1.revolution({
			visibilityLevels:"1240,1024,778,480",
			gridwidth:1024,
			gridheight:500,
			perspective:600,
			perspectiveType:"local",
			editorheight:"500,650,750,800",
			responsiveLevels:"1240,1024,778,480",
			progressBar:{disableProgressBar:true},
			navigation: {
				mouseScrollNavigation:false,
				onHoverStop:false,
				bullets: {
					enable:true,
					tmp:"",
					style:"hesperiden",
					hide_onmobile:true,
					hide_under:"600px",
					h_offset:-1,
					v_offset:31,
					space:20
				}
			},
			fallbacks: {
				allowHTML5AutoPlayOnAndroid:true
			},
		});
	}
	
	});
	} // End of RevInitScript
	var once_revslider11 = false;
	if (document.readyState === "loading") {document.addEventListener('readystatechange',function() { if((document.readyState === "interactive" || document.readyState === "complete") && !once_revslider11 ) { once_revslider11 = true; revinit_revslider11();}});} else {once_revslider11 = true; revinit_revslider11();}
</script>
<script>
	var htmlDivCss = unescape("%23rev_slider_1_1_wrapper%20.hesperiden.tp-bullets%20%7B%0A%7D%0A%23rev_slider_1_1_wrapper%20.hesperiden.tp-bullets%3Abefore%20%7B%0A%09content%3A%27%20%27%3B%0A%09position%3Aabsolute%3B%0A%09width%3A100%25%3B%0A%09height%3A100%25%3B%0A%09background%3Atransparent%3B%0A%09padding%3A10px%3B%0A%09margin-left%3A-10px%3Bmargin-top%3A-10px%3B%0A%09box-sizing%3Acontent-box%3B%0A%20%20%20border-radius%3A8px%3B%0A%20%20%0A%7D%0A%23rev_slider_1_1_wrapper%20.hesperiden%20.tp-bullet%20%7B%0A%09width%3A9px%3B%0A%09height%3A9px%3B%0A%09position%3Aabsolute%3B%0A%09background%3A%20%23ffffff%3B%20%2F%2A%20old%20browsers%20%2A%2F%0A%20%20%20%20background%3A%20-moz-linear-gradient%28top%2C%20%20%23ffffff%200%25%2C%20%23ffffff%20100%25%29%3B%20%2F%2A%20ff3.6%2B%20%2A%2F%0A%20%20%20%20background%3A%20-webkit-linear-gradient%28top%2C%20%20%23ffffff%200%25%2C%23ffffff%20100%25%29%3B%20%2F%2A%20chrome10%2B%2Csafari5.1%2B%20%2A%2F%0A%20%20%20%20background%3A%20-o-linear-gradient%28top%2C%20%20%23ffffff%200%25%2C%23ffffff%20100%25%29%3B%20%2F%2A%20opera%2011.10%2B%20%2A%2F%0A%20%20%20%20background%3A%20-ms-linear-gradient%28top%2C%20%20%23ffffff%200%25%2C%23ffffff%20100%25%29%3B%20%2F%2A%20ie10%2B%20%2A%2F%0A%20%20%20%20background%3A%20linear-gradient%28to%20bottom%2C%20%20%23ffffff%200%25%2C%23ffffff%20100%25%29%3B%20%2F%2A%20w3c%20%2A%2F%0A%20%20%20%20filter%3A%20progid%3Adximagetransform.microsoft.gradient%28%20%0A%20%20%20%20startcolorstr%3D%27%23ffffff%27%2C%20endcolorstr%3D%27%23ffffff%27%2Cgradienttype%3D0%20%29%3B%20%2F%2A%20ie6-9%20%2A%2F%0A%09border%3A3px%20solid%20transparent%3B%0A%09border-radius%3A50%25%3B%0A%09cursor%3A%20pointer%3B%0A%09box-sizing%3Acontent-box%3B%0A%7D%0A%23rev_slider_1_1_wrapper%20.hesperiden%20.tp-bullet%3Ahover%2C%0A%23rev_slider_1_1_wrapper%20.hesperiden%20.tp-bullet.selected%20%7B%0A%09background%3A%2300a651%3B%0A%7D%0A%23rev_slider_1_1_wrapper%20.hesperiden%20.tp-bullet-image%20%7B%0A%7D%0A%23rev_slider_1_1_wrapper%20.hesperiden%20.tp-bullet-title%20%7B%0A%7D%0A%0A");
	var htmlDiv = document.getElementById('rs-plugin-settings-inline-css');
	if(htmlDiv) {
	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
	}else{
	var htmlDiv = document.createElement('div');
	htmlDiv.innerHTML = '<style>' + htmlDivCss + '</style>';
	document.getElementsByTagName('head')[0].appendChild(htmlDiv.childNodes[0]);
	}
</script>
<script>
	var htmlDivCss = unescape("%0A%0A");
	var htmlDiv = document.getElementById('rs-plugin-settings-inline-css');
	if(htmlDiv) {
	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
	}else{
	var htmlDiv = document.createElement('div');
	htmlDiv.innerHTML = '<style>' + htmlDivCss + '</style>';
	document.getElementsByTagName('head')[0].appendChild(htmlDiv.childNodes[0]);
	}
</script>