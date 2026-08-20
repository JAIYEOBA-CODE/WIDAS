import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    Shield,
    LayoutDashboard,
    Users,
    AlertTriangle,
    Siren,
    Ban,
    FileText,
    Settings,
    LogOut,
    ChevronLeft,
    ChevronRight,
    Activity,
    ScrollText,
    UserCheck,
    Menu,
    X,
} from 'lucide-react';
import { cn } from '@/Utils/cn';
import { InertiaPageProps } from '@/Types';

interface SidebarProps {
    isMobileOpen: boolean;
    onMobileClose: () => void;
}

export default function Sidebar({ isMobileOpen, onMobileClose }: SidebarProps) {
    const { url } = usePage();
    const [collapsed, setCollapsed] = useState(false);
    const { auth } = usePage<InertiaPageProps>().props;

    const isActive = (path: string) => url.startsWith(path);

    const adminLinks = [
        { path: '/admin/dashboard', label: 'Dashboard', icon: LayoutDashboard },
        { path: '/admin/users', label: 'Users', icon: Users },
        { path: '/admin/user-management', label: 'User Management', icon: UserCheck },
        { path: '/admin/alerts', label: 'Alerts', icon: AlertTriangle },
        { path: '/admin/threats', label: 'Threats', icon: Siren },
        { path: '/admin/threat-rules', label: 'Threat Rules', icon: ScrollText },
        { path: '/admin/blocked-ips', label: 'Blocked IPs', icon: Ban },
        { path: '/admin/security-logs', label: 'Security Logs', icon: Activity },
        { path: '/admin/activity-logs', label: 'Activity Logs', icon: Activity },
        { path: '/admin/audit-logs', label: 'Audit Logs', icon: ScrollText },
        { path: '/admin/reports', label: 'Reports', icon: FileText },
        { path: '/admin/settings', label: 'Settings', icon: Settings },
    ];

    const analystLinks = [
        { path: '/analyst/dashboard', label: 'Dashboard', icon: LayoutDashboard },
        { path: '/analyst/incidents', label: 'Incidents', icon: Siren },
        { path: '/analyst/alerts', label: 'Alerts', icon: AlertTriangle },
    ];

    const userLinks = [
        { path: '/user/dashboard', label: 'Dashboard', icon: LayoutDashboard },
        { path: '/user/profile', label: 'Profile', icon: Users },
        { path: '/user/activity', label: 'Activity', icon: Activity },
    ];

    let links = userLinks;
    if (auth?.user?.is_admin) links = adminLinks;
    else if (auth?.user?.is_analyst) links = analystLinks;

    return (
        <>
            {isMobileOpen && (
                <div
                    className="fixed inset-0 bg-black/50 z-40 lg:hidden"
                    onClick={onMobileClose}
                />
            )}

            <aside
                className={cn(
                    'fixed top-0 left-0 z-50 h-full max-h-screen bg-white dark:bg-dark-2 border-r border-light-2 dark:border-dark-3 transition-all duration-300 flex flex-col overflow-hidden',
                    collapsed ? 'w-20' : 'w-72 sm:w-64',
                    isMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
                )}
            >
                <div className="flex items-center gap-3 px-4 h-16 border-b border-light-2 dark:border-dark-3">
                    <div className="w-10 h-10 rounded-lg bg-primary flex items-center justify-center flex-shrink-0">
                        <Shield className="w-6 h-6 text-white" />
                    </div>
                    {!collapsed && (
                        <div className="flex flex-col">
                            <span className="text-sm font-bold text-secondary dark:text-light">WIDAS</span>
                            <span className="text-xs text-dark-4 dark:text-light-3">Security Platform</span>
                        </div>
                    )}
                </div>

                <nav className="flex-1 overflow-y-auto overscroll-contain p-2 sm:p-3 space-y-0.5 sm:space-y-1">
                    {links.map((link) => {
                        const Icon = link.icon;
                        return (
                            <Link
                                key={link.path}
                                href={link.path}
                                className={isActive(link.path) ? 'sidebar-link-active' : 'sidebar-link'}
                                onClick={onMobileClose}
                            >
                                <Icon className="w-5 h-5 flex-shrink-0" />
                                {!collapsed && <span>{link.label}</span>}
                            </Link>
                        );
                    })}
                </nav>

                <div className="p-2 sm:p-3 border-t border-light-2 dark:border-dark-3 space-y-1">
                    <button
                        onClick={() => setCollapsed(!collapsed)}
                        className="sidebar-link w-full hidden lg:flex"
                    >
                        {collapsed ? <ChevronRight className="w-5 h-5" /> : <ChevronLeft className="w-5 h-5" />}
                        {!collapsed && <span>Collapse</span>}
                    </button>

                    <Link href="/logout" method="post" className="sidebar-link w-full text-danger hover:bg-red-50 dark:hover:bg-red-900/20">
                        <LogOut className="w-5 h-5" />
                        {!collapsed && <span>Logout</span>}
                    </Link>
                </div>
            </aside>
        </>
    );
}
