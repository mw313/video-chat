<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src='https://m2.iki.ac.ir/external_api.js'></script>
<script src='https://m2.iki.ac.ir/libs/lib-jitsi-meet.min.js'></script>

<style>
body {
	margin:0px;
}
</style>

</head>
<body>
<div id="meet"></div>

<script>        
    var displayName = '<?=$name ?>';
    var domain = 'm2.iki.ac.ir';
    const options = {
            roomName: '_<?=$room ?>_',
            userInfo: '',
            parentNode: document.querySelector('#meet')	,
        
            interfaceConfigOverwrite: {
                LANG_DETECTION: true
            },
            configOverwrite: {
                startWithAudioMuted: true , // قطع بودن صدا به صورت پیش فرض
                startWithVideoMuted: true // قطع بودن تصویر به صورت پیش فرض
            },
        };

    var api = new JitsiMeetExternalAPI(domain, options);
    api.executeCommand('displayName', displayName);
    api.executeCommand('subject', '  ');

</script>
</body>
</html>