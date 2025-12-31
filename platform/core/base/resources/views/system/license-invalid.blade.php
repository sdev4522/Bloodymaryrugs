@php
$manageLicense = auth()
->user()
->hasPermission('core.manage.license');
@endphp

<x-core::alert
    type="warning"
    :important="true"
    @class(['alert-license alert-sticky small bg-warning text-white', 'vertical-wrapper'=> AdminAppearance::isVerticalLayout()])
    icon=""
    @style(['display: none' => $hidden ?? true])
    data-bb-toggle="license-reminder"
    >
    <div class="{{ AdminAppearance::getContainerWidth() }}">


    </div>
</x-core::alert>