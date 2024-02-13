<ul class="text-center">
    <li class="sidebar-header" style="margin-bottom: 1px;"><a href="#" class="card-link" style="font-size: 11px;">Featured Member</a></li>
    <li class="sidebar-section p-2">
        @if (isset($featured_member) && $featured_member)
            <div>
                <a href="{{ $featured_member->url }}"><img src="{{ $featured_member->avatarUrl }}" class="img-thumbnail" /></a>
            </div>
            <div class="mt-1">
                <a href="{{ $featured_member->url }}" class="h5 mb-0">{{ $featured_member->name }}</a>
            </div>
        @else
            <p>There is no featured member!</p>
        @endif
    </li>
</ul>
