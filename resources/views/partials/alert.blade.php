<!-- resources/views/partials/alert.blade.php -->

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function() {
            var alertElement = document.querySelector('.alert.alert-success');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 2000); // Close after 2 seconds
    </script>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function() {
            var alertElement = document.querySelector('.alert.alert-danger');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 2000); // Close after 2 seconds
    </script>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <strong>Warning!</strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function() {
            var alertElement = document.querySelector('.alert.alert-warning');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 2000); // Close after 2 seconds
    </script>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <strong>Info!</strong> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function() {
            var alertElement = document.querySelector('.alert.alert-info');
            if (alertElement) {
                var alert = new bootstrap.Alert(alertElement);
                alert.close();
            }
        }, 2000); // Close after 2 seconds
    </script>
@endif
