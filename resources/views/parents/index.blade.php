@extends('adminlte::page')

@section('title', 'Parents')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content_header')
    <h1>Parent List</h1>
@endsection

@section('content')
<div class="mt-3 text-center">
    <a href="{{ route('parents.create') }}" class="btn btn-primary">Add Family</a>
</div> 

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

<div class="card mt-4">
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover" id="parents">
            <thead class="thead-dark">
                <tr>
                    <th>Parent Name</th>
                    <th>Parent Number</th>
                    <th>Email</th>
                    <th>Children(s)</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($families as $parent)
                <tr>
                    <td>{{ $parent->fname }} {{ $parent->lname }}</td>
                    <td>{{ $parent->number }}</td>
                    <td>{{ $parent->email }}</td>
                    <td>
                        <ul>
                            @foreach ($parent->students as $student)
                                <li>{{ $student->firstname }} {{ $student->lastname }} ( {{ $student->grade_level }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($parent->family && $parent->family->address)
                            {{ $parent->family->address }}
                        @else
                            No Address Available
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm give-access-btn" 
                            data-family-code="{{ $parent->family_code }}" 
                            data-email="{{ $parent->email}}" 
                            data-name="{{ $parent->fname }} {{ $parent->lname }}">
                            Give Access
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="accessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accessModalLabel">Give Access to <span id="parentName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="giveAccessForm">
                    @csrf
                    <input type="hidden" id="family_code" name="family_code">
                    <input type="hidden" id="emailP" name="emailP">
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Select Role</label>
                        <select id="role" name="role" class="form-control">
                            <option value="parent">Parent</option>
                            <option value="guardian">Guardian</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="canupdate" name="canupdate">
                        <label class="form-check-label" for="canupdate">Can Update</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Give Access</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let accessModal = new bootstrap.Modal(document.getElementById("accessModal"));

    document.querySelectorAll(".give-access-btn").forEach(button => {
        button.addEventListener("click", function () {
            let familyCode = this.getAttribute("data-family-code");
            let parentName = this.getAttribute("data-name");
            let email = this.getAttribute("data-email");

            document.getElementById("family_code").value = familyCode;
            document.getElementById("parentName").textContent = parentName;
            document.getElementById("emailP").value = email;
            accessModal.show();
        });
    });

    document.getElementById("giveAccessForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('give.access') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire("Success", data.message, "success");
                accessModal.hide();
            } else {
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire("Error", "Something went wrong!", "error");
        });
    });
});
</script>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection
@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#parents').DataTable({
                "responsive": true,
                "autoWidth": false,
            });

        });
    </script>
@endsection