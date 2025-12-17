@extends('layouts.app')

@section('title', 'Login - Document Management and Letter System')

@section('content')
<div class="login-container" role="main">
  <section class="login-box" aria-labelledby="login-title" role="form">
    <h1 id="login-title" class="login-title">Login</h1>

    @if ($errors->any())
      <div class="alert alert-danger" role="alert" aria-live="assertive">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form" novalidate>
      @csrf

      <div class="form-group">
        <label for="email">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="you@example.com"
          required
          autocomplete="email"
          autofocus
          value="{{ old('email') }}"
          aria-describedby="emailHelp"
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="••••••••"
          required
          autocomplete="current-password"
        />
      </div>

      <button type="submit" class="btn-submit" aria-label="Submit login form">Login</button>

      <div class="form-links">
        @if (Route::has('password.request'))
          <a class="form-link" href="{{ route('password.request') }}">
            Forgot your password?
          </a>
        @endif

        @if (Route::has('register'))
          <a class="form-link" href="{{ route('register') }}">
            Don't have an account? Register
          </a>
        @endif
      </div>
    </form>
  </section>
</div>

<style>
  body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; 
    margin: 0;
    background-color: #f0f2f5; 
  }

  .login-container {
    align-items: center;
    width: 100%;
    max-width: 800px; 
    padding: 20px;
    background-color: white; 
    border-radius: 80px; 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
  }

  .login-title {
    text-align: center;
    margin-bottom: 20px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
  }

  .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  .btn-submit {
    width: 100%;
    padding: 10px;
    background-color: #1877f2;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
  }

  .btn-submit:hover {
    background-color: #165eab; 
  }

  .form-links {
    text-align: center;
    margin-top: 15px;
  }

  .form-link {
    display: block;
    margin-top: 5px;
    color: #1877f2;
    text-decoration: none;
  }

  .form-link:hover {
    text-decoration: underline;
  }
</style>
@endsection
