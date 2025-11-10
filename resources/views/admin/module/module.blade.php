@extends('admin.layouts.master');
@section('title', 'Modules | Neeraj - Ecommerece');
@section('content')

    <div class="row" id="">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($modules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($modules as $module)
                                        <tr {{ $module->id }}>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($module->image)
                                                    <img src="{{ asset('storage/modules/' . $module->image) }}"
                                                        alt="{{ $module->name }}" width="60" height="60"
                                                        style="object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <span>NA</span>
                                                @endif
                                            </td>
                                            <td>{{ $module->name }}</th>
                                            <td>
                                                <button class="btn btn-sm btn-warning view-offcanvas" data-size="400px"
                                                    data-url="{{ route('module.edit', ['id' => $module->id]) }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger delete-module" data-id="{{ $module->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h5>No module found.</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.offcanvas.offcanvas')

@endsection


@push('script')
    <script src="{{ asset('admin-assets/js/offcanvas/offcanvas.js') }}"></script>
@endpush