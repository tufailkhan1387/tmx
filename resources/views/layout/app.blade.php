<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />

    @include('layout.style')

    <!-- one signal for notification -->
    {{-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" defer></script>
        <script>
            window.OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.init({
                    appId: "e9b83ff2-2902-42b7-8701-ebf07ea3d682",
                });

                var userId = '{{ auth()->user()->id }}';
                OneSignal.sendTag("userId", userId);
            });
        </script> --}}
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- Left Sidebar -->
    @include('layout.sidebar')

    <!-- Topbar -->
    @include('layout.navbar')

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">@yield('pageTitle')</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">@yield('breadcrumTitle')</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            @yield('content')
        </div>

    </div>

    @include('layout.script')

    @yield('script')
    @stack('scripts')
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyAUkLuVFDDnjRWeWwgPmyQ6X4s-agy8LNo",
            authDomain: "notification-3804c.firebaseapp.com",
            projectId: "notification-3804c",
            storageBucket: "notification-3804c.appspot.com",
            messagingSenderId: "201355403250",
            appId: "1:201355403250:web:1d9bcb3904843fa9d0156b",
            measurementId: "G-6YYXRPRGG9"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        messaging.onMessage(function(payload) {
            console.log('Message received. ', payload);
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });

        // Register service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    messaging.useServiceWorker(registration);
                })
                .catch(function(error) {

                });
        } else {

        }
    </script>
</body>

</html>
