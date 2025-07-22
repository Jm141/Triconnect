@extends('adminlte::page')

@section('title', 'Subscription List')

@section('content_header')
    <h1>Subscription Plans</h1>
@endsection

@section('content')
    <!-- Button to trigger create modal -->
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubscriptionModal">
            Add Subscription Plan
        </button>
    </div>

    <!-- Subscription Plans Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subscription Plans List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="subscriptionPlansTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Base Amount</th>
                        <th>Additional Multiplier</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptionPlans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->base_amount }}</td>
                            <td>{{ $plan->additional_multiplier }}</td>
                            <td>{{ $plan->created_at->format('F j, Y g:i A') }}</td>
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-warning update-btn"
                                    data-id="{{ $plan->id }}"
                                    data-name="{{ $plan->name }}"
                                    data-base_amount="{{ $plan->base_amount }}"
                                    data-additional_multiplier="{{ $plan->additional_multiplier }}"
                                    data-toggle="modal" 
                                    data-target="#updateSubscriptionModal">
                                    Update
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Subscription Modal -->
    <div class="modal fade" id="addSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <form action="/subscription/" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubscriptionModalLabel">Add New Subscription Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Plan Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter plan name" required>
                    </div>
                    <div class="form-group">
                        <label for="base_amount">Base Amount</label>
                        <input type="number" step="0.01" class="form-control" name="base_amount" id="base_amount" placeholder="Enter base amount" required>
                    </div>
                    <div class="form-group">
                        <label for="additional_multiplier">Additional Multiplier</label>
                        <input type="number" step="0.01" class="form-control" name="additional_multiplier" id="additional_multiplier" placeholder="Enter additional multiplier (e.g., 0.75)" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Subscription</button>
                </div>
            </form>
         </div>
      </div>
    </div>

    <!-- Update Subscription Modal -->
    <div class="modal fade" id="updateSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="updateSubscriptionModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <form id="updateSubscriptionForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSubscriptionModalLabel">Update Subscription Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="update_name">Plan Name</label>
                        <input type="text" class="form-control" name="name" id="update_name" placeholder="Enter plan name" required>
                    </div>
                    <div class="form-group">
                        <label for="update_base_amount">Base Amount</label>
                        <input type="number" step="0.01" class="form-control" name="base_amount" id="update_base_amount" placeholder="Enter base amount" required>
                    </div>
                    <div class="form-group">
                        <label for="update_additional_multiplier">Additional Multiplier</label>
                        <input type="number" step="0.01" class="form-control" name="additional_multiplier" id="update_additional_multiplier" placeholder="Enter additional multiplier (e.g., 0.75)" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Subscription</button>
                </div>
            </form>
         </div>
      </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#subscriptionPlansTable').DataTable({
                "responsive": true,
                "autoWidth": false,
            });

            $(document).on('click', '.update-btn', function(){
                console.log('Update button clicked');

                var id = $(this).data('id');
                var name = $(this).data('name');
                var base_amount = $(this).data('base_amount');
                var additional_multiplier = $(this).data('additional_multiplier');

                console.log({ id, name, base_amount, additional_multiplier });

                var url = '/subscription/' + id;
                $('#updateSubscriptionForm').attr('action', url);

                $('#update_name').val(name);
                $('#update_base_amount').val(base_amount);
                $('#update_additional_multiplier').val(additional_multiplier);
            });
        });
    </script>
@endsection