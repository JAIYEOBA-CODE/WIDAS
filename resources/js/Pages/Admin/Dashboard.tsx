import { InertiaPageProps, ChartDataPoint, ThreatDistribution, SeverityDistribution } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatCard from '@/Components/StatCard';
import { Shield, AlertTriangle, Siren, Ban, Users, Activity } from 'lucide-react';
import ThreatChart from '@/Components/ThreatChart';
import LoginActivityChart from '@/Components/LoginActivityChart';
import PieChart from '@/Components/PieChart';
import AlertsTable from '@/Components/AlertsTable';
import IntrusionsTable from '@/Components/IntrusionsTable';

interface AdminDashboardProps extends InertiaPageProps {
    stats: {
        total_threats: number;
        active_threats: number;
        critical_alerts: number;
        blocked_ips: number;
        active_users: number;
        total_alerts: number;
        resolved_threats: number;
    };
    threatTrend: ChartDataPoint[];
    threatTypes: ThreatDistribution;
    severityDistribution: SeverityDistribution;
    loginActivity: { date: string; successful: number; failed: number }[];
    recentAlerts: any[];
    recentIntrusions: any[];
    overallThreatScore: number;
}

export default function AdminDashboard(props: AdminDashboardProps) {
    const { stats, threatTrend, threatTypes, severityDistribution, loginActivity, recentAlerts, recentIntrusions, overallThreatScore } = props;

    const threatTypeData = Object.entries(threatTypes).map(([name, value]) => ({
        name: name.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
        value,
    }));

    const severityData = Object.entries(severityDistribution).map(([name, value]) => ({
        name: name.charAt(0).toUpperCase() + name.slice(1),
        value,
    }));

    return (
        <AuthenticatedLayout title="Admin Dashboard">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
                <StatCard title="Threat Score" value={`${overallThreatScore}%`} icon={Shield} color={overallThreatScore > 60 ? 'danger' : overallThreatScore > 30 ? 'warning' : 'success'} />
                <StatCard title="Total Threats" value={stats.total_threats} icon={Siren} color="danger" />
                <StatCard title="Active Threats" value={stats.active_threats} icon={AlertTriangle} color="accent" />
                <StatCard title="Critical Alerts" value={stats.critical_alerts} icon={AlertTriangle} color="danger" />
                <StatCard title="Blocked IPs" value={stats.blocked_ips} icon={Ban} color="warning" />
                <StatCard title="Active Users" value={stats.active_users} icon={Users} color="success" />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <ThreatChart data={threatTrend} />
                <LoginActivityChart data={loginActivity} />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                {threatTypeData.length > 0 && (
                    <PieChart data={threatTypeData} title="Threat Types" />
                )}
                {severityData.length > 0 && (
                    <PieChart
                        data={severityData}
                        title="Severity Distribution"
                        colors={['#EF4444', '#F97316', '#EAB308', '#22C55E']}
                    />
                )}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 className="text-lg font-semibold text-secondary dark:text-light mb-4">Recent Alerts</h3>
                    <AlertsTable alerts={recentAlerts} />
                </div>
                <div>
                    <h3 className="text-lg font-semibold text-secondary dark:text-light mb-4">Recent Intrusions</h3>
                    <IntrusionsTable intrusions={recentIntrusions} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
