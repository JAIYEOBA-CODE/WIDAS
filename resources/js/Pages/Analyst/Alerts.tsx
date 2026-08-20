import { InertiaPageProps, Alert, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import AlertsTable from '@/Components/AlertsTable';
import Pagination from '@/Components/Pagination';
import { router } from '@inertiajs/react';

interface AnalystAlertsProps extends InertiaPageProps {
    alerts: PaginatedResponse<Alert>;
}

export default function Alerts({ alerts }: AnalystAlertsProps) {
    return (
        <AuthenticatedLayout title="Alert Review">
            <AlertsTable alerts={alerts.data} />
            <Pagination {...alerts} onPageChange={(page) => router.get(`/analyst/alerts?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
