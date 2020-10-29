<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src='./assets/jquery-3.5.1.min.js'></script>
<script src='https://m2.iki.ac.ir/external_api.js'></script>
<style>
body{
	margin:0px;
}
</style>

</head>
<body>
<div id="meet"></div>
<script>
    var displayName = '<?=$name ?>';
    var domain = 'm2.iki.ac.ir';
    var options = {
            roomName: '_<?=$room ?>_',
            userInfo: '',
            parentNode: document.querySelector('#meet')	,
			
			interfaceConfigOverwrite: {
                LANG_DETECTION: true,
                TOOLBAR_BUTTONS: [ 'microphone', 'camera', 'closedcaptions', 'desktop', 'embedmeeting', 'fullscreen',
         'fodeviceselection','localrecording', 'profile', 'chat', 'recording',
         'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
         'videoquality', 'filmstrip', 'stats', 'shortcuts',
         'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'   ]
            },
            configOverwrite: {
                startWithAudioMuted: false , // قطع بودن صدا به صورت پیش فرض
                startWithVideoMuted: false // قطع بودن تصویر به صورت پیش فرض
            },
			
			
        };
        
    var  api = new JitsiMeetExternalAPI(domain, options);
    api.executeCommand('displayName', displayName );
    api.executeCommand('subject', '  ');
    $('#recordStart').click();
</script>
</body>
</html>