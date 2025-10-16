<meta charset="utf-8" />
<meta name="description" content="{{ getApplicationSetting('general_application_description') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta property="og:locale" content="{{ app()->getLocale() }}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ getApplicationSetting('general_application_name', env('APP_NAME')) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="{{ getApplicationSetting('general_application_name', env('APP_NAME')) }}" />
