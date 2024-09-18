@extends('layouts.app')

@section('title') Handbooks @endsection

@section('content')
{!! breadcrumbs(['Handbooks' => 'handbooks']) !!}
<h1>Handbooks</h1>
<p>Unsure of how to play the ARPG, or how specific features of the site work? You've come to the right place!</p>


@foreach($categories as $category)

@if($category->handbooks->count() > 0)
<div class="card mt-3">
    <h5 class="card-header">{{ $category->name }}</h5>
    <div class="card-body row">
        @if($category->image_name)
        <div class="col-md-3">
            <img class="mw-100" src="{{ $category->categoryImageUrl }}" />
        </div>
        @endif
        <div class="{{ $category->image_name ? 'col-md-9' : 'col-12' }}">
            <div>{!! $category->parsed_description !!}</div>
            <ul class="list-group">
                @foreach($category->handbooks as $handbook)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="h5 m-0" href="{{ $handbook->url }}">{{ $handbook->title }}</a>
                    <i class="text-muted">{!! format_date($handbook->updated_at) !!} </i>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@endforeach

@if(count($handbooksWithoutCategory) > 0)
<div class="card mt-3">
    <h5 class="card-header">Misc</h5>
    <div class="card-body">
        <ul class="p-0">
            @foreach($handbooksWithoutCategory as $handbook)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a class="h5 m-0" href="{{ $handbook->url }}">{{ $handbook->title }}</a>
                <i class="text-muted">{!! format_date($handbook->updated_at) !!} </i>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@endsection