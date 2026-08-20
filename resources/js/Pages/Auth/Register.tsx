import { useForm } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';
import GuestLayout from '@/Layouts/GuestLayout';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <GuestLayout title="Register">
            <form onSubmit={submit} className="space-y-5">
                <div>
                    <h2 className="text-xl font-bold text-secondary dark:text-light">Create Account</h2>
                    <p className="text-sm text-dark-4 dark:text-light-3 mt-1">Register for a new account</p>
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Name</label>
                    <input type="text" value={data.name} onChange={e => setData('name', e.target.value)} className="input-field" placeholder="Your full name" required />
                    {errors.name && <p className="text-xs text-danger mt-1">{errors.name}</p>}
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Email</label>
                    <input type="email" value={data.email} onChange={e => setData('email', e.target.value)} className="input-field" placeholder="your@email.com" required />
                    {errors.email && <p className="text-xs text-danger mt-1">{errors.email}</p>}
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Password</label>
                    <input type="password" value={data.password} onChange={e => setData('password', e.target.value)} className="input-field" placeholder="Create a password" required />
                    {errors.password && <p className="text-xs text-danger mt-1">{errors.password}</p>}
                </div>

                <div>
                    <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Confirm Password</label>
                    <input type="password" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} className="input-field" placeholder="Confirm your password" required />
                </div>

                <button type="submit" disabled={processing} className="btn-primary w-full flex items-center justify-center gap-2">
                    {processing && <Loader2 className="w-4 h-4 animate-spin" />}
                    {processing ? 'Creating account...' : 'Create Account'}
                </button>

                <p className="text-center text-sm text-dark-4 dark:text-light-3 mt-4">
                    Already have an account?{' '}
                    <a href="/login" className="text-primary hover:underline font-medium">Sign in</a>
                </p>
            </form>
        </GuestLayout>
    );
}
