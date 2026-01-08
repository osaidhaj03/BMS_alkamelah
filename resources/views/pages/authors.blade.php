@extends('layouts.app')

@section('title', 'جميع المؤلفين')

@section('content')
    <!-- Header -->
    @include('components.layout.header')

    <div class="min-h-screen bg-[#fafafa] relative overflow-hidden">
        <!-- Section Background Pattern -->
        <div class="absolute inset-0 pointer-events-none"
            style="background-image: url('{{ asset('assets/Frame 1321314420.png') }}'); background-repeat: repeat; background-size: 800px; background-attachment: fixed;">
        </div>

        <section class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20" dir="rtl">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center">
                        <img src="{{ asset('images/group0.svg') }}" alt="Icon" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-[#1a3a2a]">جميع المؤلفين</h2>
                </div>
            </div>

            <!-- Authors Table with Search -->
            @livewire('authors-table', ['showSearch' => true, 'showFilters' => true, 'perPage' => 20])
        </section>
    </div>

    <!-- Footer -->
    @include('components.layout.footer')
@endsection