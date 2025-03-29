<div class="login-container">
    <div class="login-card">
        <div class="header">
            <h2>Welcome Back</h2>
            <p>Please sign in to continue</p>
        </div>

        <form wire:submit.prevent="authenticate">
            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    placeholder="Enter your email"
                    class="form-input"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    wire:model="password"
                    placeholder="Enter your password"
                    class="form-input"
                    required>
            </div>

            <div class="options">
                <label class="remember-me">
                    <input type="checkbox">
                    Remember me
                </label>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <div class="separator">
            <span>Or continue with</span>
        </div>

        <a href="{{ route('auth.redirect') }}" class="zoho-btn">
            <svg class="zoho-logo" viewBox="0 0 24 24" fill="currentColor">
                <!-- Zoho Logo SVG Path -->
                <path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm0 22.5C6.21 22.5 1.5 17.79 1.5 12S6.21 1.5 12 1.5 22.5 6.21 22.5 12 17.79 22.5 12 22.5z"/>
                <path d="M12 6.75c-2.89 0-5.25 2.36-5.25 5.25S9.11 17.25 12 17.25 17.25 14.89 17.25 12 14.89 6.75 12 6.75zm0 9c-2.07 0-3.75-1.68-3.75-3.75S9.93 8.25 12 8.25s3.75 1.68 3.75 3.75-1.68 3.75-3.75 3.75z"/>
            </svg>
            Sign in with Zoho
        </a>
    </div>
</div>

<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
    }

    .login-card {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .header h2 {
        color: #1a1a1a;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .header p {
        color: #666;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
        font-size: 14px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #444;
    }

    .forgot-password {
        color: #3b82f6;
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .submit-btn:hover {
        background: #2563eb;
    }

    .separator {
        position: relative;
        margin: 25px 0;
        text-align: center;
        color: #666;
    }

    .separator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
        z-index: 1;
    }

    .separator span {
        position: relative;
        padding: 0 15px;
        background: white;
        z-index: 2;
    }

    .zoho-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 12px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .zoho-btn:hover {
        background: #f8f9fa;
        border-color: #ccc;
    }

    .zoho-logo {
        width: 20px;
        height: 20px;
    }
</style>
