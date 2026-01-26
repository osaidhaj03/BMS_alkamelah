@extends('layouts.app')

@section('content')
    <!-- Header -->
    @include('components.layout.header')

    <!-- Hero Section -->
    @include('components.layout.hero')

    <!-- Categories Section -->
    @include('components.layout.category')

    <!-- Books Section -->
    @include('components.layout.books-section')

    <!-- Authors Section -->
    @include('components.layout.authors-section')

    <!-- Footer -->
    @include('components.layout.footer')
@endsection
