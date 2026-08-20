import { InertiaPageProps, BlockedIp, PaginatedResponse } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DataTable from '@/Components/DataTable';
import Pagination from '@/Components/Pagination';
import { formatDate } from '@/Utils/formatters';
import { router } from '@inertiajs/react';
import { ShieldAlert, ShieldBan, Clock, Timer } from 'lucide-react';

interface BlockedIpsProps extends InertiaPageProps {
    blockedIps: PaginatedResponse<BlockedIp>;
}

function ExpiresCell({ expiresAt, isPermanent }: { expiresAt: string | null; isPermanent: boolean }) {
    if (isPermanent || !expiresAt) {
        return <span className="text-danger font-semibold">Permanent</span>;
    }
    const remaining = new Date(expiresAt).getTime() - Date.now();
    if (remaining <= 0) return <span className="text-success font-semibold">Expired</span>;
    const mins = Math.floor(remaining / 60000);
    const secs = Math.floor((remaining % 60000) / 1000);
    return (
        <span className="font-mono font-bold text-warning flex items-center gap-1.5">
            <Timer className="w-3.5 h-3.5" />
            {String(mins).padStart(2, '0')}:{String(secs).padStart(2, '0')}
        </span>
    );
}

export default function BlockedIps({ blockedIps }: BlockedIpsProps) {
    const columns = [
        { key: 'ip_address', header: 'IP Address', render: (ip: BlockedIp) => (
            <span className="font-mono font-bold text-base text-secondary dark:text-light">{ip.ip_address}</span>
        )},
        { key: 'reason', header: 'Reason', className: 'max-w-xs' },
        { key: 'is_permanent', header: 'Type', render: (ip: BlockedIp) => ip.is_permanent
            ? <span className="flex items-center gap-1.5 text-danger font-semibold"><ShieldBan className="w-4 h-4" />Permanent</span>
            : <span className="flex items-center gap-1.5 text-warning font-semibold"><Clock className="w-4 h-4" />Temporary</span>
        },
        { key: 'attempts', header: 'Attempts', render: (ip: BlockedIp) => (
            <span className="font-bold text-lg">{ip.attempts}</span>
        )},
        { key: 'expires_at', header: 'Countdown', render: (ip: BlockedIp) => (
            <ExpiresCell expiresAt={ip.expires_at} isPermanent={ip.is_permanent} />
        )},
        { key: 'created_at', header: 'Blocked', render: (ip: BlockedIp) => (
            <span className="text-sm text-dark-4 dark:text-light-3">{formatDate(ip.created_at)}</span>
        )},
        { key: 'actions', header: '', render: (ip: BlockedIp) => (
            <button
                onClick={() => router.delete(`/blocked-ips/${ip.id}`)}
                className="px-3 py-1.5 text-xs font-medium text-danger border border-danger/30 dark:border-danger/50 rounded-lg hover:bg-danger/10 dark:hover:bg-danger/20 transition-colors"
            >
                Unblock
            </button>
        )},
    ];

    return (
        <AuthenticatedLayout title="Blocked IPs">
            <div className="flex items-center gap-3 mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl border border-orange-200 dark:border-orange-800">
                <ShieldAlert className="w-5 h-5 text-warning flex-shrink-0" />
                <p className="text-sm text-orange-800 dark:text-orange-300">IPs are automatically blocked for 3 minutes when threat detection rules trigger. Permanent blocks require manual review.</p>
            </div>
            <DataTable columns={columns} data={blockedIps.data} />
            <Pagination {...blockedIps} onPageChange={(page) => router.get(`/admin/blocked-ips?page=${page}`)} />
        </AuthenticatedLayout>
    );
}
