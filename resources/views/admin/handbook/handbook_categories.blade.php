@extends('admin.layout')

@section('admin-title')
    Handbook Categories
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Handbooks' => 'admin/handbooks', 'Handbook Categories' => 'admin/handbooks/categories']) !!}

    <h1>Handbook Categories</h1>

    <p>This is a list of categories that you can organize handbooks under.</p>
    <p>The sorting order reflects the order in which categories will be displayed on the handbook index page.</p>

    <div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/handbooks/categories/create') }}"><i class="fas fa-plus"></i> Create New Category</a></div>
    @if (!count($categories))
        <p>No categories found.</p>
    @else
        <table class="table table-sm category-table">
            <tbody id="sortable" class="sortable">
                @foreach ($categories as $category)
                    <tr class="sort-item" data-id="{{ $category->id }}">
                        <td>
                            <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                            {!! $category->displayName !!}
                        </td>
                        <td class="text-right">
                            <a href="{{ url('admin/handbooks/categories/edit/' . $category->id) }}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="mb-4">
            {!! Form::open(['url' => 'admin/handbooks/categories/sort']) !!}
            {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
            {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    @endif

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.handle').on('click', function(e) {
                e.preventDefault();
            });
            $("#sortable").sortable({
                items: '.sort-item',
                handle: ".handle",
                placeholder: "sortable-placeholder",
                stop: function(event, ui) {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                },
                create: function() {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@endsection
