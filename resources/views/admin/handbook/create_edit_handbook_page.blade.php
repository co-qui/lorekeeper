@extends('admin.layout')

@section('admin-title') Handbooks @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Handbooks' => 'admin/handbooks', ($page->id ? 'Edit' : 'Create').' Handbook' => $page->id ? 'admin/handbooks/edit/'.$page->id : 'admin/handbooks/create']) !!}

<h1>{{ $page->id ? 'Edit' : 'Create' }} Handbook

    @if($page->id)
        <a href="#" class="btn btn-danger float-right delete-page-button">Delete Page</a>
        <a href="{{ $page->url }}" class="btn btn-info float-right mr-md-2">View Page</a>
    @endif
</h1>

{!! Form::open(['url' => $page->id ? 'admin/handbooks/edit/'.$page->id : 'admin/handbooks/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', $page->title, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Category') !!}
            {!! Form::select('category_id', $categories, $page->category_id, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<div class="form-group">
    {!! Form::label('Page Content') !!}
    {!! Form::textarea('text', $page->text, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::checkbox('is_visible', 1, $page->id ? $page->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_visible', 'Is Viewable', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If this is turned off, users will not be able to view the page even if they have the link to it.') !!}
        </div>
    </div>
</div>

<div class="text-right">
    {!! Form::submit($page->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {    
    $('.delete-page-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/handbooks/delete') }}/{{ $page->id }}", 'Delete Handbook');
    });
});
    
</script>
@endsection
