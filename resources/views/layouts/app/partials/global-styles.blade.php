<link rel="canonical" href="{{ asset('') }}" />
@if (getApplicationSetting('general_favicon', '', true))
    <link rel="shortcut icon" href="{{ getFileUrl(getApplicationSetting('general_favicon', '', true)) }}" />
@endif
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<link href="{{ asset('app/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('app/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('app/assets/plugins/global/plugins.bundle.js') }}"></script>
