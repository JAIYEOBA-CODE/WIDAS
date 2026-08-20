import { InertiaPageProps, IntrusionEvent, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import IntrusionsTable from '@/Components/IntrusionsTable';
import Pagination from '@/Components/Pagination';
import { router } from '@inertiajs/react';

interface IncidentsProps extends InertiaPageProps {
    incidents: PaginatedResponse<IntrusionEvent>;
}

export default function Incidents({ incidents }: IncidentsProps) {
    return (
        <AuthenticatedLayout title="Incidents">
            <IntrusionsTable intrusions={incidents.data} />
            <Pagination {...incidents} onPageChange={(page) => router.get(`/analyst/incidents?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
