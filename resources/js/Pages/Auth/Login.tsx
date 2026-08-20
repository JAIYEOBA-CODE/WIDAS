import { useForm } from '@inertiajs/react';
import { Eye, EyeOff, Loader2 } from 'lucide-react';
import { useState } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <GuestLayout title="Login">
            <form onSubmit={submit} className="space-y-5">
                <div>
                    <h2 className="text-xl font-bold text-secondary dark:text-light">Welcome Back</h2>
                    <p className="text-sm text-dark-4 dark:text-light-3 mt-1">Sign in to your security dashboard</p>
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                        Email Address
                    </label>
                    <input
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="input-field"
                        placeholder="Enter your email"
                        required
                    />
                    {errors.email && (
                        <p className="text-xs text-danger mt-1">{errors.email}</p>
                    )}
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                        Password
                    </label>
                    <div className="relative">
                        <input
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="input-field pr-10"
                            placeholder="Enter your password"
                            required
                        />
                        <button
                            type="button"
                            onClick={() => setShowPassword(!showPassword)}
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-dark-4 dark:text-light-3 hover:text-primary"
                        >
                            {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                        </button>
                    </div>
                    {errors.password && (
                        <p className="text-xs text-danger mt-1">{errors.password}</p>
                    )}
                </div>

                <div className="flex items-center justify-between">
                    <label className="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="w-4 h-4 rounded border-light-3 dark:border-dark-3 text-primary focus:ring-primary/20"
                        />
                        <span className="text-sm text-dark-4 dark:text-light-3">Remember me</span>
                    </label>
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="btn-primary w-full flex items-center justify-center gap-2"
                >
                    {processing && <Loader2 className="w-4 h-4 animate-spin" />}
                    {processing ? 'Signing in...' : 'Sign In'}
                </button>

                <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-light-2 dark:border-dark-3" />
                    </div>
                    <div className="relative flex justify-center text-xs">
                        <span className="bg-white dark:bg-dark-2 px-2 text-dark-4 dark:text-light-3">
                            Demo Credentials
                        </span>
                    </div>
                </div>

                <div className="space-y-2 text-xs text-dark-4 dark:text-light-3">
                    <p><strong className="text-secondary dark:text-light">Admin:</strong> admin@widas.test / password</p>
                    <p><strong className="text-secondary dark:text-light">Analyst:</strong> analyst@widas.test / password</p>
                    <p><strong className="text-secondary dark:text-light">User:</strong> user@widas.test / password</p>
                </div>

                <p className="text-center text-sm text-dark-4 dark:text-light-3 mt-6">
                    Don&apos;t have an account?{' '}
                    <a href="/register" className="text-primary hover:underline font-medium">Register</a>
                </p>
            </form>
        </GuestLayout>
    );
}
