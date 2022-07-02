@component('mail::message')
# Introduction

Ciao admin il {{$postSlug}} è stato modificato!

@component('mail::button', ['url' => $postUrl])
Review Post
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
