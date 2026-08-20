import { Alert } from '@/Types';
import DataTable from './DataTable';
import SeverityBadge from './SeverityBadge';
import { formatDate, timeAgo } from '@/Utils/formatters';

interface AlertsTableProps {
    alerts: Alert[];
    loading?: boolean;
}

export default function AlertsTable({ alerts, loading }: AlertsTableProps) {
    const columns = [
        { key: 'severity', header: 'Severity', render: (alert: Alert) => <SeverityBadge severity={alert.severity} /> },
        { key: 'title', header: 'Title', className: 'font-medium' },
        { key: 'type', header: 'Type' },
        { key: 'message', header: 'Message', render: (alert: Alert) => (
            <span className="text-dark-4 dark:text-light-3 truncate max-w-xs block">{alert.message}</span>
        )},
        { key: 'created_at', header: 'Time', render: (alert: Alert) => (
            <span className="text-dark-4 dark:text-light-3" title={formatDate(alert.created_at)}>
                {timeAgo(alert.created_at)}
            </span>
        )},
        { key: 'is_read', header: 'Status', render: (alert: Alert) => (
            !alert.is_read ? <span className="inline-flex items-center gap-1 text-primary"><span className="w-2 h-2 rounded-full bg-primary animate-pulse-dot" /> New</span> : 'Read'
        )},
    ];

    return <DataTable columns={columns} data={alerts} loading={loading} />;
}
