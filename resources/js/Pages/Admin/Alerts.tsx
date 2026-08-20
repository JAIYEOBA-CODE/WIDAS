import { InertiaPageProps, Alert, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import AlertsTable from '@/Components/AlertsTable';
import Pagination from '@/Components/Pagination';
import { router } from '@inertiajs/react';

interface AlertsProps extends InertiaPageProps {
    alerts: PaginatedResponse<Alert>;
}

export default function Alerts({ alerts }: AlertsProps) {
    return (
        <AuthenticatedLayout title="Alerts">
            <AlertsTable alerts={alerts.data} />
            <Pagination
                currentPage={alerts.current_page}
                lastPage={alerts.last_page}
                total={alerts.total}
                from={alerts.from}
                to={alerts.to}
                onPageChange={(page) => router.get(`/admin/alerts?page=${page}`)}
            />
        </AuthenticatedLayout>
    );
}
