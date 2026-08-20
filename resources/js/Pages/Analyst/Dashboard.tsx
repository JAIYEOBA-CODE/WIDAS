import { InertiaPageProps, IntrusionEvent, Alert, ChartDataPoint, ThreatDistribution, DashboardStats } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatCard from '@/Components/StatCard';
import { AlertTriangle, Siren, Bell, CheckCircle } from 'lucide-react';
import ThreatChart from '@/Components/ThreatChart';
import PieChart from '@/Components/PieChart';
import IntrusionsTable from '@/Components/IntrusionsTable';
import AlertsTable from '@/Components/AlertsTable';

interface AnalystDashboardProps extends InertiaPageProps {
    stats: DashboardStats;
    incidentQueue: IntrusionEvent[];
    recentAlerts: Alert[];
    threatTrend: ChartDataPoint[];
    threatTypes: ThreatDistribution;
    overallThreatScore: number;
}

export default function AnalystDashboard({ stats, incidentQueue, recentAlerts, threatTrend, threatTypes }: AnalystDashboardProps) {
    const threatTypeData = Object.entries(threatTypes).map(([name, value]) => ({
        name: name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
        value,
    }));

    return (
        <AuthenticatedLayout title="Analyst Dashboard">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard title="Incident Queue" value={stats.total_incidents ?? 0} icon={Siren} color="danger" />
                <StatCard title="Critical Incidents" value={stats.critical_incidents ?? 0} icon={AlertTriangle} color="danger" />
                <StatCard title="Pending Alerts" value={stats.pending_alerts ?? 0} icon={Bell} color="warning" />
                <StatCard title="Resolved Today" value={stats.resolved_today ?? 0} icon={CheckCircle} color="success" />
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <ThreatChart data={threatTrend} />
                {threatTypeData.length > 0 && <PieChart data={threatTypeData} title="Threat Distribution" />}
            </div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-lg font-semibold text-secondary dark:text-light">Incident Queue</h3>
                        <span className="badge-critical text-xs">{stats.total_incidents} pending</span>
                    </div>
                    <IntrusionsTable intrusions={incidentQueue} />
                </div>
                <div>
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="text-lg font-semibold text-secondary dark:text-light">Recent Alerts</h3>
                        <span className="badge-high text-xs">{stats.pending_alerts} unread</span>
                    </div>
                    <AlertsTable alerts={recentAlerts} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
