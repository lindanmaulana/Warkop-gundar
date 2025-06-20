@props(['name', 'class' => 'w-5 h-5'])

@php
    $path = resource_path("views/icons/{$name}.svg");
@endphp

@if (file_exists($path))
    {!! str_replace('<svg', '<svg class="' . $class . '"', file_get_contents($path)) !!}
@else
    <span class="text-red-500">[Icon missing]</span>
@endif