{{ $list->name }}:
@foreach($list->items as $item)
* {{ $item->name}}
@endforeach
