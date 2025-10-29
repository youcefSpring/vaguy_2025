@extends($activeTemplate.'layouts.frontend')
@section('content')

@include($activeTemplate.'sections.banner')

{{-- <br>
<div class="h-100 d-flex align-items-center justify-content-center">
    <div style="">
        <iframe width="500" height="345" src="https://www.youtube.com/embed/wGiqAIGIeUI">

        </iframe>
    </div>
  </div> --}}


@if($sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif

@endsection

