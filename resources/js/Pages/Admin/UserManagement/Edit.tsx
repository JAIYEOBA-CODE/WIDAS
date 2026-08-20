import { InertiaPageProps, User, Role } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';

interface EditUserProps extends InertiaPageProps {
    user: User;
    roles: Role[];
}

export default function EditUser({ user, roles }: EditUserProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: user.name, email: user.email, password: '', password_confirmation: '',
        role_id: user.role_id || roles[0]?.id || '', is_active: user.is_active,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/admin/user-management/${user.id}`);
    };

    return (
        <AuthenticatedLayout title={`Edit User - ${user.name}`}>
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
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Password (leave blank to keep)</label>
                            <input type="password" value={data.password} onChange={e => setData('password', e.target.value)} className="input-field" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">Confirm Password</label>
                            <input type="password" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} className="input-field" />
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
                                    <span className="text-sm">Active</span>
                                </label>
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" checked={!data.is_active} onChange={() => setData('is_active', false)} className="text-danger" />
                                    <span className="text-sm">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-3 pt-4 border-t border-light-2 dark:border-dark-3">
                        <button type="submit" disabled={processing} className="btn-primary flex items-center gap-2">
                            {processing && <Loader2 className="w-4 h-4 animate-spin" />}
                            {processing ? 'Updating...' : 'Update User'}
                        </button>
                        <a href="/admin/user-management" className="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
