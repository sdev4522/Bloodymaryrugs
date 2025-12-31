@php
$manageLicense = auth()
->user()
->hasPermission('core.manage.license');
@endphp