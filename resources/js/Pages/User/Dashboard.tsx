import { InertiaPageProps, DashboardStats, SecurityLog, Alert } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatCard from '@/Components/StatCard';
import { LogIn, AlertTriangle, Bell, ShieldAlert } from 'lucide-react';
import DataTable from '@/Components/DataTable';
import SeverityBadge from '@/Components/SeverityBadge';
import { formatDate, timeAgo } from '@/Utils/formatters';

interface UserDashboardProps extends InertiaPageProps {
    stats: DashboardStats;
    recentActivity: SecurityLog[];
    recentAlerts: Alert[];
}

export default function UserDashboard({ stats, recentActivity, recentAlerts }: UserDashboardProps) {
    const activityColumns = [
        { key: 'type', header: 'Type' },
        { key: 'message', header: 'Message', className: 'max-w-md truncate' },
        { key: 'source_ip', header: 'IP', render: (log: SecurityLog) => <span className="font-mono text-xs">{log.source_ip}</span> },
        { key: 'created_at', header: 'Time', render: (log: SecurityLog) => <span title={formatDate(log.created_at)}>{timeAgo(log.created_at)}</span> },
    ];

    const alertColumns = [
        { key: 'severity', header: 'Severity', render: (alert: Alert) => <SeverityBadge severity={alert.severity} /> },
        { key: 'title', header: 'Title', className: 'font-medium' },
        { key: 'message', header: 'Message', className: 'max-w-md truncate' },
        { key: 'created_at', header: 'Time', render: (alert: Alert) => timeAgo(alert.created_at) },
    ];

    return (
        <AuthenticatedLayout title="Dashboard">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard title="Total Logins" value={stats.total_logins ?? 0} icon={LogIn} color="success" />
                <StatCard title="Failed Attempts" value={stats.failed_attempts ?? 0} icon={ShieldAlert} color="danger" />
                <StatCard title="Alerts" value={stats.alerts ?? 0} icon={Bell} color="warning" />
                <StatCard title="Unread Alerts" value={stats.unread_alerts ?? 0} icon={AlertTriangle} color="accent" />
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 className="text-lg font-semibold text-secondary dark:text-light mb-4">Recent Activity</h3>
                    <DataTable columns={activityColumns} data={recentActivity} />
                </div>
                <div>
                    <h3 className="text-lg font-semibold text-secondary dark:text-light mb-4">Recent Alerts</h3>
                    <DataTable columns={alertColumns} data={recentAlerts} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
