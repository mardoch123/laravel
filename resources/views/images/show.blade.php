@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-center">
        <img src="{{ $image->image_path }}" alt="Image" class="max-w-full h-auto rounded-lg shadow-lg">
    </div>
</div>
@endsection
