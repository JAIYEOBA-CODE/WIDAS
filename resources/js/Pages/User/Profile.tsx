import { InertiaPageProps, User } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Shield, Mail, Calendar, User as UserIcon, CheckCircle, XCircle, Pencil, Loader2, Eye, EyeOff } from 'lucide-react';
import { formatDate } from '@/Utils/formatters';
import { usePage, useForm } from '@inertiajs/react';
import { useState } from 'react';

interface ProfileProps extends InertiaPageProps {
    user: User;
}

export default function Profile({ user }: ProfileProps) {
    const { flash } = usePage<InertiaPageProps>().props;

    const nameForm = useForm({ name: user.name });
    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const [editingName, setEditingName] = useState(false);
    const [editingPassword, setEditingPassword] = useState(false);
    const [showCurrent, setShowCurrent] = useState(false);
    const [showNew, setShowNew] = useState(false);
    const [showConfirm, setShowConfirm] = useState(false);

    const updateName = (e: React.FormEvent) => {
        e.preventDefault();
        nameForm.patch('/user/profile', {
            preserveScroll: true,
            onSuccess: () => setEditingName(false),
        });
    };

    const updatePassword = (e: React.FormEvent) => {
        e.preventDefault();
        passwordForm.patch('/user/profile', {
            preserveScroll: true,
            onSuccess: () => {
                setEditingPassword(false);
                passwordForm.reset();
            },
        });
    };

    return (
        <AuthenticatedLayout title="Profile">
            <div className="max-w-2xl mx-auto space-y-6">
                {flash?.success && (
                    <div className="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg text-sm">
                        {flash.success}
                    </div>
                )}

                <div className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 overflow-hidden">
                    <div className="bg-gradient-to-r from-primary to-accent p-6">
                        <div className="flex items-center gap-4">
                            <div className="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                                <span className="text-2xl font-bold text-white">{user.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <div className="text-white">
                                <h2 className="text-xl font-bold">{user.name}</h2>
                                <p className="text-white/80 text-sm">{user.email}</p>
                            </div>
                        </div>
                    </div>
                    <div className="p-6 space-y-4">
                        <div className="flex items-center gap-3 p-3 rounded-lg bg-light dark:bg-dark-3">
                            <UserIcon className="w-5 h-5 text-primary" />
                            <div>
                                <p className="text-xs text-dark-4 dark:text-light-3">Role</p>
                                <p className="text-sm font-medium text-secondary dark:text-light">{user.role?.name || 'N/A'}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 p-3 rounded-lg bg-light dark:bg-dark-3">
                            <Mail className="w-5 h-5 text-primary" />
                            <div>
                                <p className="text-xs text-dark-4 dark:text-light-3">Email</p>
                                <p className="text-sm font-medium text-secondary dark:text-light">{user.email}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 p-3 rounded-lg bg-light dark:bg-dark-3">
                            {user.is_active ? <CheckCircle className="w-5 h-5 text-success" /> : <XCircle className="w-5 h-5 text-danger" />}
                            <div>
                                <p className="text-xs text-dark-4 dark:text-light-3">Status</p>
                                <p className="text-sm font-medium text-secondary dark:text-light">{user.is_active ? 'Active' : 'Inactive'}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 p-3 rounded-lg bg-light dark:bg-dark-3">
                            <Calendar className="w-5 h-5 text-primary" />
                            <div>
                                <p className="text-xs text-dark-4 dark:text-light-3">Member Since</p>
                                <p className="text-sm font-medium text-secondary dark:text-light">{formatDate(user.created_at)}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 p-6">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-lg font-semibold text-secondary dark:text-light">Edit Name</h3>
                        <button
                            onClick={() => setEditingName(!editingName)}
                            className="text-primary hover:text-primary-dark dark:hover:text-primary/80 text-sm font-medium"
                        >
                            {editingName ? 'Cancel' : 'Change'}
                        </button>
                    </div>
                    {editingName ? (
                        <form onSubmit={updateName} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                                    Full Name
                                </label>
                                <input
                                    type="text"
                                    value={nameForm.data.name}
                                    onChange={(e) => nameForm.setData('name', e.target.value)}
                                    className="input-field"
                                    required
                                />
                                {nameForm.errors.name && (
                                    <p className="text-xs text-danger mt-1">{nameForm.errors.name}</p>
                                )}
                            </div>
                            <div className="flex gap-2">
                                <button
                                    type="submit"
                                    disabled={nameForm.processing}
                                    className="btn-primary flex items-center gap-2"
                                >
                                    {nameForm.processing && <Loader2 className="w-4 h-4 animate-spin" />}
                                    Save Name
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setEditingName(false)}
                                    className="btn-secondary"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    ) : (
                        <p className="text-dark-4 dark:text-light-3">{user.name}</p>
                    )}
                </div>

                <div className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 p-6">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-lg font-semibold text-secondary dark:text-light">Change Password</h3>
                        <button
                            onClick={() => setEditingPassword(!editingPassword)}
                            className="text-primary hover:text-primary-dark dark:hover:text-primary/80 text-sm font-medium"
                        >
                            {editingPassword ? 'Cancel' : 'Change'}
                        </button>
                    </div>
                    {editingPassword ? (
                        <form onSubmit={updatePassword} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                                    Current Password
                                </label>
                                <div className="relative">
                                    <input
                                        type={showCurrent ? 'text' : 'password'}
                                        value={passwordForm.data.current_password}
                                        onChange={(e) => passwordForm.setData('current_password', e.target.value)}
                                        className="input-field pr-10"
                                        required
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowCurrent(!showCurrent)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-dark-4 dark:text-light-3"
                                    >
                                        {showCurrent ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                    </button>
                                </div>
                                {passwordForm.errors.current_password && (
                                    <p className="text-xs text-danger mt-1">{passwordForm.errors.current_password}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                                    New Password
                                </label>
                                <div className="relative">
                                    <input
                                        type={showNew ? 'text' : 'password'}
                                        value={passwordForm.data.password}
                                        onChange={(e) => passwordForm.setData('password', e.target.value)}
                                        className="input-field pr-10"
                                        required
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowNew(!showNew)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-dark-4 dark:text-light-3"
                                    >
                                        {showNew ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                    </button>
                                </div>
                                {passwordForm.errors.password && (
                                    <p className="text-xs text-danger mt-1">{passwordForm.errors.password}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-secondary dark:text-light mb-1.5">
                                    Confirm New Password
                                </label>
                                <div className="relative">
                                    <input
                                        type={showConfirm ? 'text' : 'password'}
                                        value={passwordForm.data.password_confirmation}
                                        onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)}
                                        className="input-field pr-10"
                                        required
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowConfirm(!showConfirm)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-dark-4 dark:text-light-3"
                                    >
                                        {showConfirm ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                    </button>
                                </div>
                            </div>
                            <div className="flex gap-2">
                                <button
                                    type="submit"
                                    disabled={passwordForm.processing}
                                    className="btn-primary flex items-center gap-2"
                                >
                                    {passwordForm.processing && <Loader2 className="w-4 h-4 animate-spin" />}
                                    Update Password
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setEditingPassword(false)}
                                    className="btn-secondary"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    ) : (
                        <p className="text-dark-4 dark:text-light-3">••••••••</p>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
