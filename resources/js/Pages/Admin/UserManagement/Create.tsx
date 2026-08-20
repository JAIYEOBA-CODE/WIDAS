import { InertiaPageProps, Role } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';

interface CreateUserProps extends InertiaPageProps {
    roles: Role[];
}

export default function CreateUser({ roles }: CreateUserProps) {
    const { data, setData, post, processing, errors } = useForm({
        name: '', email: '', password: '', password_confirmation: '',
        role_id: roles[0]?.id || '', is_active: true,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/user-management');
    };

    return (
        <AuthenticatedLayout title="Create User">
            <div className="max-w-2xl">
                <form onSubmit={submit} className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 p-6 space-y-5">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Name</label>
                            <input type="text" value={data.name} onChange={e => setData('name', e.target.value)} className="input-field" required />
                            {errors.name && <p className="text-xs text-danger mt-1">{errors.name}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Email</label>
                            <input type="email" value={data.email} onChange={e => setData('email', e.target.value)} className="input-field" required />
                            {errors.email && <p className="text-xs text-danger mt-1">{errors.email}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Password</label>
                            <input type="password" value={data.password} onChange={e => setData('password', e.target.value)} className="input-field" required />
                            {errors.password && <p className="text-xs text-danger mt-1">{errors.password}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Confirm Password</label>
                            <input type="password" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} className="input-field" required />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Role</label>
                            <select value={data.role_id} onChange={e => setData('role_id', Number(e.target.value))} className="input-field">
                                {roles.map(role => <option key={role.id} value={role.id}>{role.name}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Status</label>
                            <div className="flex items-center gap-3 mt-2">
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" checked={data.is_active} onChange={() => setData('is_active', true)} className="text-primary" />
                                    <span className="text-sm text-dark-4 dark:text-light-3">Active</span>
                                </label>
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" checked={!data.is_active} onChange={() => setData('is_active', false)} className="text-danger" />
                                    <span className="text-sm text-dark-4 dark:text-light-3">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-3 pt-4 border-t border-light-2 dark:border-dark-3">
                        <button type="submit" disabled={processing} className="btn-primary flex items-center gap-2">
                            {processing && <Loader2 className="w-4 h-4 animate-spin" />}
                            {processing ? 'Creating...' : 'Create User'}
                        </button>
                        <a href="/admin/user-management" className="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
