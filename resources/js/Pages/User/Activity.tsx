import { InertiaPageProps, ActivityLog, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate, timeAgo } from '@/Utils/formatters';
import { router } from '@inertiajs/react';

interface ActivityProps extends InertiaPageProps {
    activities: PaginatedResponse<ActivityLog>;
}

export default function Activity({ activities }: ActivityProps) {
    const columns = [
        { key: 'action', header: 'Action' },
        { key: 'module', header: 'Module' },
        { key: 'description', header: 'Description', className: 'max-w-md truncate' },
        { key: 'ip_address', header: 'IP', render: (log: ActivityLog) => <span className="font-mono text-xs">{log.ip_address}</span> },
        { key: 'created_at', header: 'Time', render: (log: ActivityLog) => <span title={formatDate(log.created_at)}>{timeAgo(log.created_at)}</span> },
    ];

    return (
        <AuthenticatedLayout title="Activity History">
            <div className="bg-white dark:bg-dark-2 rounded-xl p-4 border border-light-2 dark:border-dark-3 mb-6">
                <p className="text-sm text-dark-4 dark:text-light-3">Showing your recent activity across the platform.</p>
            </div>
            <DataTable columns={columns} data={activities.data} />
            <Pagination {...activities} onPageChange={(page) => router.get(`/user/activity?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
