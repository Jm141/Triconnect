@extends('adminlte::page')


@section('title', 'Subscription Plans - Triconnect')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-credit-card"></i> Subscription Plans Management
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPlanModal">
                            <i class="fa fa-plus"></i> Add New Plan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Original content from the subscription index page --}}
                    @if(isset($subscriptionPlans) && count($subscriptionPlans) > 0)
                        <div class="table-responsive">
                            <table id="subscriptionsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Plan Name</th>
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
                                            <td>₱{{ number_format($plan->base_amount, 2) }}</td>
                                            <td>{{ $plan->additional_multiplier }}</td>
                                            <td>{{ $plan->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info edit-plan" data-id="{{ $plan->id }}" data-name="{{ $plan->name }}" data-base-amount="{{ $plan->base_amount }}" data-multiplier="{{ $plan->additional_multiplier }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('subscription.destroy', $plan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No subscription plans found. 
                            <button type="button" class="alert-link" data-toggle="modal" data-target="#addPlanModal">Add your first plan</button>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Subscription Plans Overview --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Total Plans</h4>
                            <h2 class="mb-0">{{ $subscriptionPlans->count() ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-credit-card fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Total Base Amount</h4>
                            <h2 class="mb-0">₱{{ number_format($subscriptionPlans->sum('base_amount'), 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-money fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Average Multiplier</h4>
                            <h2 class="mb-0">{{ number_format($subscriptionPlans->avg('additional_multiplier'), 2) }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-percentage fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Plan Modal -->
    <div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog" aria-labelledby="addPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('subscription.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPlanModalLabel">Add New Subscription Plan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Plan Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="base_amount">Base Amount</label>
                            <input type="number" step="0.01" class="form-control" id="base_amount" name="base_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="additional_multiplier">Additional Multiplier</label>
                            <input type="number" step="0.01" class="form-control" id="additional_multiplier" name="additional_multiplier" value="0.75" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1" role="dialog" aria-labelledby="editPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editPlanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPlanModalLabel">Edit Subscription Plan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Plan Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_base_amount">Base Amount</label>
                            <input type="number" step="0.01" class="form-control" id="edit_base_amount" name="base_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_additional_multiplier">Additional Multiplier</label>
                            <input type="number" step="0.01" class="form-control" id="edit_additional_multiplier" name="additional_multiplier" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#subscriptionsTable').DataTable({
                responsive: true,
                language: {
                    search: "Search plans:",
                    lengthMenu: "Show _MENU_ plans per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ plans",
                    infoEmpty: "No plans available",
                    infoFiltered: "(filtered from _MAX_ total plans)"
                },
                pageLength: 10,
                order: [[0, 'desc']] // Sort by ID descending
            });

            // Edit plan functionality
            $('.edit-plan').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const baseAmount = $(this).data('base-amount');
                const multiplier = $(this).data('multiplier');

                $('#edit_name').val(name);
                $('#edit_base_amount').val(baseAmount);
                $('#edit_additional_multiplier').val(multiplier);
                $('#editPlanForm').attr('action', `/subscription/${id}`);
                
                $('#editPlanModal').modal('show');
            });
        });
    </script>
@endpush