import { InertiaPageProps, SecurityLog, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import SeverityBadge from '@/Components/SeverityBadge';
import { formatDate, timeAgo } from '@/Utils/formatters';
import { router } from '@inertiajs/react';

interface SecurityLogsProps extends InertiaPageProps {
    logs: PaginatedResponse<SecurityLog>;
}

export default function SecurityLogs({ logs }: SecurityLogsProps) {
    const columns = [
        { key: 'severity', header: 'Severity', render: (log: SecurityLog) => <SeverityBadge severity={log.severity === 'danger' ? 'high' : log.severity === 'critical' ? 'critical' : log.severity === 'warning' ? 'medium' : 'low'} /> },
        { key: 'type', header: 'Type' },
        { key: 'message', header: 'Message', className: 'max-w-md truncate' },
        { key: 'source_ip', header: 'IP', render: (log: SecurityLog) => <span className="font-mono text-xs">{log.source_ip}</span> },
        { key: 'user', header: 'User', render: (log: SecurityLog) => log.user?.name || 'System' },
        { key: 'created_at', header: 'Time', render: (log: SecurityLog) => (
            <span title={formatDate(log.created_at)}>{timeAgo(log.created_at)}</span>
        )},
    ];

    return (
        <AuthenticatedLayout title="Security Logs">
            <DataTable columns={columns} data={logs.data} />
            <Pagination {...logs} onPageChange={(page) => router.get(`/admin/security-logs?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
