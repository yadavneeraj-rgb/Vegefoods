@extends('admin.layouts.master')
@section('title', 'Neeraj | Categories')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Categories</h4>
                <button data-url="{{ route('category.create') }}" class="btn btn-primary view-offcanvas">
                    <i class="mdi mdi-plus"></i> Add New Category
                </button>
            </div>
        </div>
    </div>

    @include('admin.layouts.offcanvas.offcanvas')
@endsection
@push('script')
    <script src="{{ asset('admin-assets/js/offcanvas/offcanvas.js') }}"></script>
@endpush