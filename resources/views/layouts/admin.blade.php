<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - POS API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; background: #2c3e50; color: white; padding-top: 20px; }
        .sidebar a { color: #bdc3c7; text-decoration: none; padding: 15px 25px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; }
        .main-content { background: #f4f7f9; min-height: 100vh; padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <h4 class="text-center mb-4">POS ADMIN</h4>
                <a href="{{ route('barangs.index') }}" class="{{ request()->is('admin/barangs*') ? 'active' : '' }}">📦 Produk Catalog</a>
                <a href="#">📊 Laporan (Coming Soon)</a>
                
                <form action="{{ route('logout') }}" method="POST" class="mt-5 px-4">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">LOGOUT</button>
                </form>
            </div>
            <div class="col-md-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>