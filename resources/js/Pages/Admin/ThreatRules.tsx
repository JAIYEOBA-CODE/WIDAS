import { InertiaPageProps, ThreatRule, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import SeverityBadge from '@/Components/SeverityBadge';
import { threatCategoryLabel } from '@/Utils/formatters';
import { router } from '@inertiajs/react';

interface ThreatRulesProps extends InertiaPageProps {
    rules: PaginatedResponse<ThreatRule>;
}

export default function ThreatRules({ rules }: ThreatRulesProps) {
    const columns = [
        { key: 'name', header: 'Name', className: 'font-medium' },
        { key: 'category', header: 'Category', render: (rule: ThreatRule) => threatCategoryLabel(rule.category) },
        { key: 'severity', header: 'Severity', render: (rule: ThreatRule) => <SeverityBadge severity={rule.severity} /> },
        { key: 'threat_score', header: 'Score' },
        { key: 'action', header: 'Action' },
        { key: 'threshold', header: 'Threshold' },
        { key: 'is_active', header: 'Status', render: (rule: ThreatRule) => (
            <span className={rule.is_active ? 'text-success' : 'text-danger'}>
                {rule.is_active ? 'Active' : 'Inactive'}
            </span>
        )},
    ];

    return (
        <AuthenticatedLayout title="Threat Rules">
            <DataTable columns={columns} data={rules.data} />
            <Pagination {...rules} onPageChange={(page) => router.get(`/admin/threat-rules?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
