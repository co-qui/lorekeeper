@extends('admin.layout')

@section('admin-title') Handbook Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Handbook Categories' => 'admin/handbooks/categories', ($category->id ? 'Edit' : 'Create').' Category' => $category->id ? 'admin/handbooks/categories/edit/'.$category->id : 'admin/handbooks/categories/create']) !!}

<h1>{{ $category->id ? 'Edit' : 'Create' }} Category
    @if($category->id)
        <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
    @endif
</h1>

{!! Form::open(['url' => $category->id ? 'admin/handbooks/categories/edit/'.$category->id : 'admin/handbooks/categories/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Category Image (Optional)') !!} {!! add_help('This image is used on the handbook index.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: 200px x 200px</div>
    @if($category->image_name)
    <div class="row p-5">
        <div class="form-check col">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
        <div class="col">
            <img src="{{ $category->categoryImageUrl }}" style="max-width:300px;">
        </div>
    </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $category->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {    
    $('.delete-category-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/handbooks/categories/delete') }}/{{ $category->id }}", 'Delete Category');
    });
});
    
</script>
@endsection