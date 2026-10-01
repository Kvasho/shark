<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>ადმინ პანელი — შესვლა</title>

    @vite(['resources/css/admin/login.css'])
</head>
<body class="admin-login">
    <main class="admin-login__card">
        <div class="admin-login__head">
            <span class="admin-login__badge">Shark Admin</span>
            <h1 class="admin-login__title">შესვლა</h1>
            <p class="admin-login__subtitle">შეიყვანეთ მომხმარებლის სახელი და პაროლი</p>
        </div>

        <form method="POST" action="{{ route('admin.login.attempt') }}" class="admin-login__form" novalidate>
            @csrf

            @error('username')
                <div class="admin-login__alert" role="alert">{{ $message }}</div>
            @enderror

            <label class="admin-login__field">
                <span class="admin-login__label">მომხმარებლის სახელი</span>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    autocomplete="username"
                    autofocus
                    required
                    @class(['admin-login__input', 'is-invalid' => $errors->has('username')])
                >
            </label>

            <label class="admin-login__field">
                <span class="admin-login__label">პაროლი</span>
                <input
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    @class(['admin-login__input', 'is-invalid' => $errors->has('password')])
                >
                @error('password')
                    <span class="admin-login__error">{{ $message }}</span>
                @enderror
            </label>

            <label class="admin-login__remember">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span>დამიმახსოვრე</span>
            </label>

            <button type="submit" class="admin-login__submit">შესვლა</button>
        </form>
    </main>
</body>
</html>
