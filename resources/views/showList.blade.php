{{ $list->name }}:\n\n
{{ collect($list->items)->map( function($item) {
    return "* $item->name" . ' \n';
})->implode("") }}
