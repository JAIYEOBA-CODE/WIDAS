import { InertiaPageProps, User, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate } from '@/Utils/formatters';
import { router } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import { UserPlus, Shield, ShieldOff } from 'lucide-react';

interface UsersProps extends InertiaPageProps {
    users: PaginatedResponse<User>;
}

export default function Users({ users }: UsersProps) {
    const columns = [
        { key: 'name', header: 'Name', sortable: true, className: 'font-medium' },
        { key: 'email', header: 'Email' },
        { key: 'role', header: 'Role', render: (user: User) => user.role?.name || 'N/A' },
        { key: 'is_active', header: 'Status', render: (user: User) => (
            <span className={user.is_active ? 'text-success' : 'text-danger'}>
                {user.is_active ? 'Active' : 'Inactive'}
            </span>
        )},
        { key: 'login_attempts', header: 'Login Attempts' },
        { key: 'created_at', header: 'Created', render: (user: User) => formatDate(user.created_at) },
        { key: 'actions', header: 'Actions', render: (user: User) => (
            <div className="flex items-center gap-2">
                <Link href={`/admin/user-management/${user.id}/edit`} className="text-primary hover:underline text-sm">Edit</Link>
                <button
                    onClick={() => router.patch(`/admin/users/${user.id}/toggle-status`)}
                    className="text-sm hover:underline"
                >
                    {user.is_active ? <ShieldOff className="w-4 h-4 text-danger" /> : <Shield className="w-4 h-4 text-success" />}
                </button>
            </div>
        )},
    ];

    return (
        <AuthenticatedLayout title="Users">
            <div className="mb-4">
                <Link href="/admin/user-management/create" className="btn-primary inline-flex items-center gap-2">
                    <UserPlus className="w-4 h-4" />
                    Create User
                </Link>
            </div>
            <DataTable columns={columns} data={users.data} />
            <Pagination
                currentPage={users.current_page}
                lastPage={users.last_page}
                total={users.total}
                from={users.from}
                to={users.to}
                onPageChange={(page) => router.get(`/admin/users?page=${page}`)}
            />
        </AuthenticatedLayout>
    );
}
