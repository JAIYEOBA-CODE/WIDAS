import { InertiaPageProps, AuditLog, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate, timeAgo } from '@/Utils/formatters';
import { router } from '@inertiajs/react';

interface AuditLogsProps extends InertiaPageProps {
    logs: PaginatedResponse<AuditLog>;
}

export default function AuditLogs({ logs }: AuditLogsProps) {
    const columns = [
        { key: 'user', header: 'User', render: (log: AuditLog) => log.user?.name || 'System' },
        { key: 'event', header: 'Event' },
        { key: 'auditable_type', header: 'Resource', render: (log: AuditLog) => {
            const parts = log.auditable_type.split('\\');
            return parts[parts.length - 1];
        }},
        { key: 'auditable_id', header: 'Resource ID' },
        { key: 'created_at', header: 'Time', render: (log: AuditLog) => (
            <span title={formatDate(log.created_at)}>{timeAgo(log.created_at)}</span>
        )},
    ];

    return (
        <AuthenticatedLayout title="Audit Logs">
            <DataTable columns={columns} data={logs.data} />
            <Pagination {...logs} onPageChange={(page) => router.get(`/admin/audit-logs?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
