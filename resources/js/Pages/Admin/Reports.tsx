import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { FileText, Download } from 'lucide-react';

interface ReportsProps {
    daily: any;
    weekly: any;
    monthly: any;
}

export default function Reports({ daily, weekly, monthly }: ReportsProps) {
    const reports = [
        { label: 'Daily Security Report', period: 'daily', data: daily },
        { label: 'Weekly Security Report', period: 'weekly', data: weekly },
        { label: 'Monthly Security Report', period: 'monthly', data: monthly },
    ];

    return (
        <AuthenticatedLayout title="Reports">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {reports.map((report) => (
                    <div key={report.period} className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 p-6">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary/20 flex items-center justify-center">
                                <FileText className="w-5 h-5 text-primary" />
                            </div>
                            <div>
                                <h3 className="text-sm font-semibold text-secondary dark:text-light">{report.label}</h3>
                                <p className="text-xs text-dark-4 dark:text-light-3 capitalize">{report.period} report</p>
                            </div>
                        </div>
                        <div className="space-y-2 mb-4">
                            <div className="flex justify-between text-sm">
                                <span className="text-dark-4 dark:text-light-3">Total Threats</span>
                                <span className="font-semibold text-secondary dark:text-light">{report.data?.summary?.total_threats || 0}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-dark-4 dark:text-light-3">Critical Alerts</span>
                                <span className="font-semibold text-danger">{report.data?.summary?.critical_alerts || 0}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-dark-4 dark:text-light-3">Blocked IPs</span>
                                <span className="font-semibold text-warning">{report.data?.summary?.blocked_ips || 0}</span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-dark-4 dark:text-light-3">Failed Logins</span>
                                <span className="font-semibold text-accent">{report.data?.summary?.failed_logins || 0}</span>
                            </div>
                        </div>
                        <a href={`/admin/reports/${report.period}`} className="btn-primary w-full flex items-center justify-center gap-2 text-sm">
                            <Download className="w-4 h-4" />
                            Download PDF
                        </a>
                    </div>
                ))}
            </div>
        </AuthenticatedLayout>
    );
}
