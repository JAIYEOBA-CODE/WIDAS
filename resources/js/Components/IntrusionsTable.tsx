import { IntrusionEvent } from '@/Types';
import DataTable from './DataTable';
import SeverityBadge from './SeverityBadge';
import { formatDate, timeAgo, threatCategoryLabel } from '@/Utils/formatters';

interface IntrusionsTableProps {
    intrusions: IntrusionEvent[];
    loading?: boolean;
}

export default function IntrusionsTable({ intrusions, loading }: IntrusionsTableProps) {
    const columns = [
        { key: 'severity', header: 'Severity', render: (event: IntrusionEvent) => <SeverityBadge severity={event.severity} /> },
        { key: 'type', header: 'Type', render: (event: IntrusionEvent) => threatCategoryLabel(event.type) },
        { key: 'threat_score', header: 'Score', render: (event: IntrusionEvent) => (
            <div className="flex items-center gap-2">
                <div className="w-16 h-2 rounded-full bg-light-2 dark:bg-dark-3 overflow-hidden">
                    <div
                        className="h-full rounded-full transition-all duration-500"
                        style={{
                            width: `${event.threat_score}%`,
                            backgroundColor: event.threat_score >= 80 ? '#EF4444' : event.threat_score >= 60 ? '#F97316' : event.threat_score >= 40 ? '#EAB308' : '#22C55E',
                        }}
                    />
                </div>
                <span className="text-xs font-medium">{event.threat_score}</span>
            </div>
        )},
        { key: 'source_ip', header: 'Source IP', render: (event: IntrusionEvent) => (
            <span className="font-mono text-xs">{event.source_ip}</span>
        )},
        { key: 'description', header: 'Description', render: (event: IntrusionEvent) => (
            <span className="text-dark-4 dark:text-light-3 truncate max-w-xs block">{event.description}</span>
        )},
        { key: 'created_at', header: 'Time', render: (event: IntrusionEvent) => (
            <span className="text-dark-4 dark:text-light-3" title={formatDate(event.created_at)}>
                {timeAgo(event.created_at)}
            </span>
        )},
        { key: 'is_resolved', header: 'Status', render: (event: IntrusionEvent) => (
            event.is_resolved
                ? <span className="text-success">Resolved</span>
                : <span className="text-danger">Active</span>
        )},
    ];

    return <DataTable columns={columns} data={intrusions} loading={loading} />;
}
