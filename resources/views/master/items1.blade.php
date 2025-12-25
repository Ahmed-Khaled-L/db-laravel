<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Master</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background: white;
        }

        /* Table Styling */
        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            vertical-align: middle;
        }

        /* The "Quick Add" Row - Distinct Blue Style */
        .quick-add-row {
            background-color: #e3f2fd !important;
        }

        .quick-add-row td {
            padding: 15px 10px !important;
            border-bottom: 2px solid #90caf9;
        }

        /* Inputs in the Quick Add Row */
        .clean-input {
            border: 2px solid #bbdefb;
            border-radius: 6px;
            padding: 6px 10px;
            width: 100%;
            transition: all 0.3s;
        }

        .clean-input:focus {
            border-color: #2196f3;
            outline: none;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
        }

        /* Action Buttons */
        .btn-icon {
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .btn-icon:hover {
            background-color: #f1f3f5;
        }

        /* Badge Styling */
        .badge-unit {
            background-color: #e9ecef;
            color: #495057;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="fa-solid fa-layer-group text-primary me-2"></i>Item Master
                </h2>
                <p class="text-muted mb-0">Manage system inventory definitions</p>
            </div>
        </div>

        <div class="card main-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="itemsTable" class="table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr class="quick-add-row">
                                <form action="{{ route('items.store') }}" method="POST">
                                    @csrf
                                    <th style="width: 35%; background-color: #e3f2fd;">
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent border-0 text-primary">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                            <input type="text" name="item_name" class="clean-input"
                                                placeholder="New Item Name..." required>
                                        </div>
                                    </th>
                                    <th style="width: 25%; background-color: #e3f2fd;">
                                        <input type="text" name="barcode" class="clean-input" placeholder="Barcode">
                                    </th>
                                    <th style="width: 20%; background-color: #e3f2fd;">
                                        <select name="unit" class="clean-input bg-white">
                                            <option value="pcs">Pcs</option>
                                            <option value="kg">Kg</option>
                                            <option value="box">Box</option>
                                            <option value="m">Meter</option>
                                        </select>
                                    </th>
                                    <th style="width: 20%; background-color: #e3f2fd;" class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                            ADD
                                        </button>
                                    </th>
                                </form>
                            </tr>
                            <tr>
                                <th class="ps-4">Item Name</th>
                                <th>Barcode</th>
                                <th>Unit</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $item->item_name }}</td>
                                    <td class="font-monospace text-primary">{{ $item->barcode ?? '--' }}</td>
                                    <td><span class="badge-unit">{{ $item->unit }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-icon text-primary me-1"
                                            onclick="openEditModal({{ $item }})" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="btn btn-icon text-danger"
                                            onclick="confirmDelete('{{ $item->id }}')" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('items.destroy', $item->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Item Name</label>
                            <input type="text" name="item_name" id="modal_name" class="form-control form-control-lg"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Barcode</label>
                                <input type="text" name="barcode" id="modal_barcode" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Unit</label>
                                <select name="unit" id="modal_unit" class="form-select">
                                    <option value="pcs">Pcs</option>
                                    <option value="kg">Kg</option>
                                    <option value="box">Box</option>
                                    <option value="m">Meter</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-link text-muted text-decoration-none"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#itemsTable').DataTable({
                // Disable sorting on the first column (Action buttons) or specific headers if needed
                "order": [],
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search items..."
                },
                "dom": '<"row p-3"<"col-md-6"l><"col-md-6"f>>rt<"row p-3"<"col-md-6"i><"col-md-6"p>>'
            });

            // SweetAlert Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: "Check inputs"
                });
            @endif
        });

        // Edit Modal Logic
        function openEditModal(item) {
            document.getElementById('modal_name').value = item.item_name;
            document.getElementById('modal_barcode').value = item.barcode;
            document.getElementById('modal_unit').value = item.unit;
            document.getElementById('editForm').action = '/items/' + item.id;

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        // Delete Logic
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Item?',
                text: "This cannot be undone",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>

</body>

</html>
