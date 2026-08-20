import { Bell, Moon, Sun, Menu } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { useTheme } from '@/Hooks/useTheme';
import { InertiaPageProps } from '@/Types';
import { cn } from '@/Utils/cn';

interface NavbarProps {
    onMenuClick: () => void;
}

export default function Navbar({ onMenuClick }: NavbarProps) {
    const { isDark, toggle } = useTheme();
    const { auth, unread_alerts_count, critical_alerts_count } = usePage<InertiaPageProps>().props;

    return (
        <header className="h-16 bg-white dark:bg-dark-2 border-b border-light-2 dark:border-dark-3 flex items-center justify-between px-4 lg:px-6">
            <div className="flex items-center gap-4">
                <button
                    onClick={onMenuClick}
                    className="lg:hidden p-2 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 transition-colors"
                >
                    <Menu className="w-5 h-5 text-dark-4 dark:text-light-3" />
                </button>

                <div className="flex items-center gap-2">
                    <span className="text-sm text-dark-4 dark:text-light-3">Welcome,</span>
                    <span className="text-sm font-semibold text-secondary dark:text-light">
                        {auth?.user?.name}
                    </span>
                </div>
            </div>

            <div className="flex items-center gap-3">
                <button
                    onClick={toggle}
                    className="p-2 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 transition-colors"
                >
                    {isDark ? (
                        <Sun className="w-5 h-5 text-warning" />
                    ) : (
                        <Moon className="w-5 h-5 text-dark-4" />
                    )}
                </button>

                <button className="relative p-2 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 transition-colors">
                    <Bell className="w-5 h-5 text-dark-4 dark:text-light-3" />
                    {unread_alerts_count > 0 && (
                        <span className={cn(
                            'absolute -top-0.5 -right-0.5 w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white',
                            critical_alerts_count > 0 ? 'bg-danger' : 'bg-primary'
                        )}>
                            {unread_alerts_count > 99 ? '99+' : unread_alerts_count}
                        </span>
                    )}
                </button>

                <div className="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                    <span className="text-xs font-bold text-white">
                        {auth?.user?.name?.charAt(0)?.toUpperCase()}
                    </span>
                </div>
            </div>
        </header>
    );
}
