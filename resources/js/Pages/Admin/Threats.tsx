import { InertiaPageProps, IntrusionEvent, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import IntrusionsTable from '@/Components/IntrusionsTable';
import Pagination from '@/Components/Pagination';
import { router } from '@inertiajs/react';

interface ThreatsProps extends InertiaPageProps {
    threats: PaginatedResponse<IntrusionEvent>;
}

export default function Threats({ threats }: ThreatsProps) {
    return (
        <AuthenticatedLayout title="Threats">
            <IntrusionsTable intrusions={threats.data} />
            <Pagination
                currentPage={threats.current_page}
                lastPage={threats.last_page}
                total={threats.total}
                from={threats.from}
                to={threats.to}
                onPageChange={(page) => router.get(`/admin/threats?page=${page}`)}
            />
        </AuthenticatedLayout>
    );
}
