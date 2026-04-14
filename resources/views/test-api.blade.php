<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Test Login</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: 0 auto; background: #f4f4f4; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background: #0056b3; }
        pre { background: #333; color: #00ff00; padding: 15px; border-radius: 4px; overflow-x: auto; min-height: 50px; }
        .label { font-weight: bold; margin-bottom: 5px; display: block; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Test API Login</h2>
        <p>Gunakan akun yang sudah dibuat di UserSeeder (admin@pos.com / admin123)</p>
        
        <form id="loginForm">
            <span class="label">Email:</span>
            <input type="email" id="email" value="admin@pos.com" required>
            
            <span class="label">Password:</span>
            <input type="password" id="password" value="admin123" required>
            
            <button type="submit">Login (POST /api/login)</button>
        </form>

        <h3>Hasil Response JSON:</h3>
        <pre id="response">Belum ada data...</pre>

        <h3>Token Kamu (Simpan ini untuk tes API lain):</h3>
        <pre id="token" style="background: #222; color: #fff; word-break: break-all;">-</pre>

        <hr>
        <h3>Test Authorized GET Request</h3>
        <p>Klik tombol di bawah untuk mengambil data barang menggunakan Token di atas.</p>
        <button id="btnGetBarangs" style="background: #28a745; margin-bottom: 10px;">Ambil Data Barang (GET /api/barangs)</button>
        <button id="btnLogout" style="background: #dc3545;">Logout (POST /api/logout)</button>
        
        <pre id="responseBarang" style="margin-top: 15px;">Data barang akan muncul di sini...</pre>
    </div>

    <script>
        let savedToken = "";

        // Fungsi Login
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const responseArea = document.getElementById('response');
            const tokenArea = document.getElementById('token');

            responseArea.innerText = "Sabar, lagi diproses...";
            
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                responseArea.innerText = JSON.stringify(data, null, 2);

                if (data.success && data.token) {
                    savedToken = data.token;
                    tokenArea.innerText = savedToken;
                }
            } catch (error) {
                responseArea.innerText = "Error: " + error.message;
            }
        });

        // FUNGSI BARU: Logout (Hapus Token)
        document.getElementById('btnLogout').addEventListener('click', async () => {
            const responseArea = document.getElementById('response');
            const tokenArea = document.getElementById('token');

            if (!savedToken) {
                alert("Login dulu bos!");
                return;
            }

            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + savedToken
                    }
                });

                const data = await response.json();
                alert(data.message);

                savedToken = "";
                tokenArea.innerText = "- (Token sudah hangus)";
                document.getElementById('responseBarang').innerText = "Token dihapus. Klik 'Ambil Data' lagi untuk tes (pasti error 401).";
            } catch (error) {
                alert("Error: " + error.message);
            }
        });

        // Fungsi GET Barang
        document.getElementById('btnGetBarangs').addEventListener('click', async () => {
            const responseBarangArea = document.getElementById('responseBarang');

            if (!savedToken) {
                alert("Login dulu bos!");
                return;
            }

            responseBarangArea.innerText = "Mengambil data dengan token...";

            try {
                const response = await fetch('/api/barangs', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + savedToken
                    }
                });

                const data = await response.json();
                responseBarangArea.innerText = JSON.stringify(data, null, 2);
            } catch (error) {
                responseBarangArea.innerText = "Error: " + error.message;
            }
        });
    </script>
</body>
</html>