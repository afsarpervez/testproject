@if ($model instanceof App\Models\Question)
    @php
        $name = 'question';
        $firstURISegment = 'questions'
    @endphp
    @elseif($model instanceof App\Models\Answer)
        @php
        $name = 'answer';
        $firstURISegment = 'answers'
    @endphp
@endif

@php
    $formId = $name."-".$model->id;
    $formAction = "/".$firstURISegment."/".$model->id."/vote";
    
@endphp

<div class="d-flex flex-column vote-controls mr-3">
    <a title="{{$name}} Useful"
        class="vote-up {{ Auth::guest() ? 'off' : ''}}"
        onclick="event.preventDefault(); document.getElementById('up-vote-{{ $formId }}').submit();"
    >
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    <form id="up-vote-{{ $formId }}" action="{{ $formAction }}" method="POST">
        @csrf
        <input type="hidden" name="vote" value="1">                           
    </form>
    <span class="votes-count">{{$model->votes_count}}</span>

    <a title="{{$name}} not Useful"
        class="vote-down {{ Auth::guest() ? 'off' : ''}}"
        onclick="event.preventDefault(); document.getElementById('down-vote-{{ $formId }}').submit();"
    >
        <i class="fas fa-caret-down fa-3x"></i>
    </a>

    <form id="down-vote-{{ $formId }}" action="{{ $formAction }}" method="POST">
        @csrf
        <input type="hidden" name="vote" value="-1">                           
    </form>

    {{-- Favorite Section --}}
    @if ($model instanceof App\Models\Question)
        <favorite :question="{{$model}}"></favorite>
        

    @elseif ($model instanceof App\Models\Answer)
        <accept :answer="{{$model}}"></accept>
    @endif
    
</div>