@if (auth()->user())
    @php($oneSignalConfig = config('onesignal'))
    <script type="text/javascript">
        window.OneSignal = window.OneSignal || [];
        window.OneSignal.push(function(){
            OneSignal.on('subscriptionChange', function (isSubscribed) {
                OneSignal.getUserId().then(function(userId) {
                    $.ajax({
                        url: '/OneSignalStatus',
                        method: 'post',
                        data: {
                            'csrf_token': "{{ csrf_token() }}",
                            'one_signal_id': userId,
                            'status': isSubscribed ? 1 : 0
                        }
                    });
                });
            });
        });
        window.OneSignal.push(["init", {
            appId: "{{ $oneSignalConfig['app_id'] }}",
            autoRegister: true,
@if (!empty($oneSignalConfig['sub_domain']))
            subdomainName: "{{ $oneSignalConfig['sub_domain'] }}",
@endif
            welcomeNotification: {disable: true},
            notifyButton: {enable: true, size: 'small'}
        }]);
    </script>
    <script src="//cdn.onesignal.com/sdks/OneSignalSDK.js" async="async"></script>
@endif
