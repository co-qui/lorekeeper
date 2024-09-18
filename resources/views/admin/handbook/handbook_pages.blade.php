@extends('admin.layout')

@section('admin-title') Handbooks @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Handbooks' => 'admin/handbooks']) !!}

<h1>Handbooks</h1>

<p>Here, you can create handbook pages that will be shown on the handbook index.</p>

<div class="text-right mb-3"></div>

<div class="text-right mb-3">
    <a class="btn btn-primary" href="{{ url('admin/handbooks/categories') }}"><i class="fas fa-folder"></i> Handbook Categories</a>
    <a class="btn btn-primary" href="{{ url('admin/handbooks/create') }}"><i class="fas fa-plus"></i> Create New Handbook</a>
  </div>

@if(!count($pages))
    <p>No handbooks found.</p>
@else 
    <table class="table table-sm handbook-table">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Last Edited</th>
        </tr>
      </thead>
        <tbody id="sortable" class="sortable">
          @foreach($pages as $page)
                <tr class="sort-item" data-id="{{ $page->id }}">
                    <td>
                        <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                        {{ $page->title }}
                    </td>
                    <td>
                    {{ $page->category->name ?? '' }}
                    </td>
                    <td>
                    {!! pretty_date($page->updated_at) !!}
                    </td>
                    <td class="text-right">
                    <a href="{{ url('admin/handbooks/edit/'.$page->id) }}" class="btn btn-primary py-0 px-2">Edit</a>
                    </td>

                </tr>
          @endforeach
        </tbody>

    </table>
    <div class="mb-4">
        {!! Form::open(['url' => 'admin/handbooks/sort']) !!}
        {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
        {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
    <div class="text-center mt-4 small text-muted">{{ $pages->count() }} result{{ $pages->count() == 1 ? '' : 's' }} found.</div>

@endif


@endsection

@section('scripts')
@parent
<script>

$( document ).ready(function() {
    $('.handle').on('click', function(e) {
        e.preventDefault();
    });
    $( "#sortable" ).sortable({
        items: '.sort-item',
        handle: ".handle",
        placeholder: "sortable-placeholder",
        stop: function( event, ui ) {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        },
        create: function() {
            $('#sortableOrder').val($(this).sortable("toArray", {attribute:"data-id"}));
        }
    });
    $( "#sortable" ).disableSelection();
});
</script>
@endsection
