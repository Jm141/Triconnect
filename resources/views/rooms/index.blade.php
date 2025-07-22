@extends('adminlte::page')
@section('title', 'Room List')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

@section('content_header')
    <h1>Room List</h1>
@endsection

@section('content')

<form action="{{ route('rooms.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by room name" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

<a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">Add Room</a>
<table class="table table-bordered" id="roomsTable">
    <thead>
        <tr>
            <th>Room Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>
                    <button class="btn btn-info generate-qr" data-room-name="{{ $room->name }}" data-room-code="{{ $room->room_code }}">Generate QR</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content p-5">
            <div class="modal-header flex justify-between items-center border-b pb-2">
                <h5 class="modal-title text-xl font-semibold" id="qrModalLabel">QR Code</h5>
                <button type="button" class="close text-gray-500 hover:text-gray-700" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body flex flex-col items-center justify-center space-y-4 p-4">
                <h4 id="roomName" class="text-lg font-semibold"></h4>
                <div id="qrCodeContainer" class="flex justify-center items-center min-h-[200px]"></div> <!-- QR Code centered -->
            </div>
            <div class="modal-footer flex justify-end space-x-3 border-t pt-2">
                <button type="button" class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded" onclick="printQrCode()">Print</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // $('#roomsTable').DataTable();

        $('.generate-qr').click(function() {
            var roomName = $(this).data('room-name');
            var roomCode = $(this).data('room-code');
            $('#roomName').text(roomName);
            $('#qrCodeContainer').empty(); 
            new QRCode(document.getElementById('qrCodeContainer'), {
                text: roomCode,
                width: 200,
                height: 200
            });
            $('#qrModal').modal('show');
        });
    });

    function printQrCode() {
        var printContents = document.getElementById('qrCodeContainer').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection
