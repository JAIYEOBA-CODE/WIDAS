import { InertiaPageProps, ActivityLog, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate, timeAgo } from '@/Utils/formatters';
import { router } from '@inertiajs/react';

interface ActivityLogsProps extends InertiaPageProps {
    logs: PaginatedResponse<ActivityLog>;
}

export default function ActivityLogs({ logs }: ActivityLogsProps) {
    const columns = [
        { key: 'user', header: 'User', render: (log: ActivityLog) => log.user?.name || 'System' },
        { key: 'action', header: 'Action' },
        { key: 'module', header: 'Module' },
        { key: 'description', header: 'Description', className: 'max-w-md truncate' },
        { key: 'ip_address', header: 'IP', render: (log: ActivityLog) => <span className="font-mono text-xs">{log.ip_address}</span> },
        { key: 'created_at', header: 'Time', render: (log: ActivityLog) => (
            <span title={formatDate(log.created_at)}>{timeAgo(log.created_at)}</span>
        )},
    ];

    return (
        <AuthenticatedLayout title="Activity Logs">
            <DataTable columns={columns} data={logs.data} />
            <Pagination {...logs} onPageChange={(page) => router.get(`/admin/activity-logs?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
