<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Tailwind via CDN. Kalau nanti pakai CLI, ganti dengan:
       <link rel="stylesheet" href="../../assets/css/output.css"> -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100">

  <main class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

      <h1 class="text-2xl font-bold text-gray-800 text-center">Masuk</h1>
      <p class="text-sm text-gray-500 text-center mt-1 mb-6">
        Login untuk mengelola tenant kamu
      </p>

      <form action="process/login.php" method="POST" class="space-y-4">

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" id="email" name="email" required placeholder="nama@email.com"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password" required placeholder="Masukkan password"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit"
          class="w-full rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 text-sm transition-colors">
          Login
        </button>

      </form>

      <p class="text-sm text-gray-500 text-center mt-6">
        Belum punya akun?
        <a href="register.php" class="text-blue-600 hover:underline font-medium">Daftar</a>
      </p>

    </div>
  </main>

</body>

</html>