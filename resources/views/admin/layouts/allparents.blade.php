@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row g-3 justify-content-end" style="align-content: center;">
            <div class="col-12 col-md-2">
                <input type="text" class="form-control mb-3 p-1" style="border-radius: 20px; font-size:small;"
                    placeholder="Search Roll Number">
            </div>
            <div class="col-12 col-md-2">
                <input type="text" class="form-control mb-3 p-1" style="border-radius: 20px; font-size:small;"
                    placeholder="Search Section">
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-primary btn-sm" style="border-radius: 20px;">Search Results</button>
            </div>
        </div>

        <!-- Responsive table container -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="font">Parent ID</th>
                        <th class="font">Father's Name</th>
                        <th class="font">Mother's Name</th>
                        <th class="font">Father's Occupation</th>
                        <th class="font">Mother's Name Occupation</th>
                        <th class="font">Email</th>
                        <th class="font">Phone Number</th>
                        <th class="font">Present Address</th>
                        <th class="font">Permanent Address</th>
                        <th class="font">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parents as $parent)
                        <tr class="font">
                            <td>{{ $parent->id }}</td>
                            <td>{{ $parent->father_name }}</td>
                            <td>{{ $parent->mother_name }}</td>
                            <td>{{ $parent->father_occupation }}</td>
                            <td>{{ $parent->mother_occupation }}</td>
                            <td>{{ $parent->parent_email }}</td>
                            <td>{{ $parent->phone_number }}</td>
                            <td>{{ $parent->present_address }}</td>
                            <td>{{ $parent->permanent_address }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- Button that triggers the edit modal -->
                                    <a href="#" class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editParentModal-{{ $parent->id }}">
                                        <i class="fa-solid fa-pen-to-square" style="color: #FFD43B;"></i>
                                    </a>

                                    <!-- Delete form -->
                                    <form action="{{ route('allparents.destroy', $parent->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this parent?')">
                                            <i class="fa-solid fa-trash" style="color: #ff0000;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $parents->links() }}
        </div>

        <!-- Include the modal partial -->
        @foreach ($parents as $parent)
            @include('admin.layouts.modal.parentsedit', ['parent' => $parent])
        @endforeach
    @endsection
