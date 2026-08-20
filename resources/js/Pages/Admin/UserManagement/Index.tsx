import { InertiaPageProps, User, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate } from '@/Utils/formatters';
import { router, Link } from '@inertiajs/react';
import { UserPlus, Shield, ShieldOff, Edit2, Trash2 } from 'lucide-react';

interface UserManagementIndexProps extends InertiaPageProps {
    users: PaginatedResponse<User>;
}

export default function UserManagementIndex({ users }: UserManagementIndexProps) {
    const columns = [
        { key: 'name', header: 'Name', className: 'font-medium' },
        { key: 'email', header: 'Email' },
        { key: 'role', header: 'Role', render: (user: User) => <span className="badge-medium">{user.role?.name || 'N/A'}</span> },
        { key: 'is_active', header: 'Status', render: (user: User) => <span className={user.is_active ? 'text-success' : 'text-danger'}>{user.is_active ? 'Active' : 'Inactive'}</span> },
        { key: 'login_attempts', header: 'Login Attempts' },
        { key: 'created_at', header: 'Created', render: (user: User) => formatDate(user.created_at) },
        { key: 'actions', header: 'Actions', render: (user: User) => (
            <div className="flex items-center gap-2">
                <Link href={`/admin/user-management/${user.id}/edit`} className="p-1.5 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 transition-colors">
                    <Edit2 className="w-4 h-4 text-primary" />
                </Link>
                <button onClick={() => router.patch(`/admin/users/${user.id}/toggle-status`)} className="p-1.5 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 transition-colors">
                    {user.is_active ? <ShieldOff className="w-4 h-4 text-danger" /> : <Shield className="w-4 h-4 text-success" />}
                </button>
                <button onClick={() => { if (confirm('Delete this user?')) router.delete(`/admin/user-management/${user.id}`); }} className="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <Trash2 className="w-4 h-4 text-danger" />
                </button>
            </div>
        )},
    ];

    return (
        <AuthenticatedLayout title="User Management">
            <div className="mb-4">
                <Link href="/admin/user-management/create" className="btn-primary inline-flex items-center gap-2">
                    <UserPlus className="w-4 h-4" />
                    Create User
                </Link>
            </div>
            <DataTable columns={columns} data={users.data} />
            <Pagination {...users} onPageChange={(page) => router.get(`/admin/user-management?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
