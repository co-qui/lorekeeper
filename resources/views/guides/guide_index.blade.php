@extends('layouts.app')

@section('title') Guides @endsection

@section('content')
{!! breadcrumbs(['Guides' => 'guides']) !!}
<h1>Guides</h1>
<p>Unsure of how to play the ARPG, or how specific features of the site work? You've come to the right place!</p>


@foreach($categories as $category)

@if($category->guides->count() > 0)
<div class="card mt-3">
    <h5 class="card-header">{{ $category->name }}</h5>
    <div class="card-body row">
        @if($category->image_name)
        <div class="col-md-3">
            <img class="mw-100" src="{{ $category->categoryImageUrl }}" />
        </div>
        @endif
        <div class="{{ $category->image_name ? 'col-md-9' : 'col-12' }}">
            <ul class="list-group">
                @foreach($category->guides as $guide)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a class="h5 m-0" href="{{ $guide->url }}">{{ $guide->title }}</a>
                    <i class="text-muted">{!! format_date($guide->updated_at) !!} </i>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@endforeach

<div class="card mt-3">
    <h5 class="card-header">Misc</h5>
    <div class="card-body">
        <ul class="p-0">
            @foreach($guidesWithoutCategory as $guide)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a class="h5 m-0" href="{{ $guide->url }}">{{ $guide->title }}</a>
                <i class="text-muted">{!! format_date($guide->updated_at) !!} </i>
            </li>
            @endforeach
        </ul>
    </div>
</div>

@endsection