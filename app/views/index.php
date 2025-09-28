<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #121212;
      color: #e5e5e5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
      font-weight: 600;
      color: #00ff88;
    }

    .card {
      border-radius: 1rem;
      overflow: hidden;
      background: #1e1e1e;
      border: none;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
    }

    .table thead {
      background: #00ff88;
      color: #121212;
    }

    .table tbody tr {
      background: #1e1e1e;
      color: #e5e5e5;
    }

    .table tbody tr:hover {
      background: #2a2a2a;
    }

    .btn-success {
      border-radius: 30px;
      padding: 6px 18px;
      background: #00ff88;
      border: none;
      color: #121212;
      font-weight: 600;
    }

    .btn-success:hover {
      background: #00cc6a;
      color: #fff;
    }

    .btn-primary {
      border-radius: 25px;
      background: #007bff;
      border: none;
    }

    .btn-danger {
      border-radius: 25px;
      background: #dc3545;
      border: none;
    }

    .btn-secondary {
      border-radius: 25px;
      background: #444;
      border: none;
    }

    /* Badges styled as pill buttons */
    .badge {
      cursor: pointer;
      font-size: 0.85rem;
      padding: 8px 14px;
      border-radius: 30px;
    }

    /* Pagination */
    .pagination .page-link {
      color: #00ff88;
      background: transparent;
      border-radius: 50% !important;
      margin: 0 4px;
      border: 1px solid #00ff88;
    }
    .pagination .page-link:hover {
      background-color: #00ff88;
      color: #121212;
    }
    .pagination .active .page-link {
      background-color: #00ff88;
      border-color: #00ff88;
      color: #121212;
    }

    /* Modal styling */
    .modal-content {
      border-radius: 1rem;
      border: none;
      background: #1e1e1e;
      color: #e5e5e5;
    }
    .modal-header {
      border-bottom: none;
    }
    .modal-footer {
      border-top: none;
    }

    .form-control {
      background: #2a2a2a;
      border: 1px solid #444;
      color: #e5e5e5;
    }

    .form-control:focus {
      background: #2a2a2a;
      border-color: #00ff88;
      box-shadow: 0 0 0 0.25rem rgba(0, 255, 136, 0.25);
      color: #fff;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4 text-center">📚 Student Records</h2>

  <!-- Error & Message Section -->
  <div class="mb-3">
    <?php getErrors(); ?>
    <?php getMessage(); ?>
  </div>

  <!-- Search + Add Student -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <form action="<?= site_url('/'); ?>" method="get" class="d-flex col-sm-12 col-md-6 mb-2 mb-md-0">
      <?php $q = isset($_GET['q']) ? $_GET['q'] : ''; ?>
      <input 
        class="form-control me-2" 
        name="q" 
        type="text" 
        placeholder="🔍 Search student..." 
        value="<?= html_escape($q); ?>">
      <button type="submit" class="btn btn-success">Search</button>
    </form>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Student</button>
  </div>

  <!-- Student Table -->
  <div class="card shadow">
    <div class="card-body">
      <table class="table table-hover text-center align-middle">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Course</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($all)): ?>
            <?php foreach ($all as $student): ?>
              <tr>
                <td><?= htmlspecialchars($student['student_id']); ?></td>
                <td><?= htmlspecialchars($student['first_name']); ?></td>
                <td><?= htmlspecialchars($student['last_name']); ?></td>
                <td><?= htmlspecialchars($student['course']); ?></td>
                <td>
                  <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $student['id']; ?>">✏ Edit</span>
                  <span class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $student['id']; ?>">🗑 Delete</span>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $student['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="/update-user/<?= $student['id']; ?>" method="POST">
                      <div class="modal-header">
                        <h5 class="modal-title text-success">✏ Edit Student</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">
                        <div class="mb-3">
                          <label class="form-label">Student ID</label>
                          <input type="text" name="student_id" class="form-control" value="<?= $student['student_id']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">First Name</label>
                          <input type="text" name="first_name" class="form-control" value="<?= $student['first_name']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Last Name</label>
                          <input type="text" name="last_name" class="form-control" value="<?= $student['last_name']; ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Course</label>
                          <input type="text" name="course" class="form-control" value="<?= $student['course']; ?>" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Delete Modal -->
              <div class="modal fade" id="deleteModal<?= $student['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="/delete-user/<?= $student['id']; ?>" method="POST">
                      <div class="modal-header">
                        <h5 class="modal-title text-danger">⚠ Delete Student</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete <strong><?= $student['first_name'] . " " . $student['last_name']; ?></strong>?</p>
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-muted">No students found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        <?= $page; ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/create-user" method="POST">
        <div class="modal-header">
          <h5 class="modal-title text-success">➕ Add Student</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input type="text" name="student_id" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Course</label>
            <input type="text" name="course" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL; ?>/public/js/alert.js"></script>
</body>
</html>
