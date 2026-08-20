import { Head } from '@inertiajs/react';
import { Shield } from 'lucide-react';

interface GuestLayoutProps {
    children: React.ReactNode;
    title?: string;
}

export default function GuestLayout({ children, title }: GuestLayoutProps) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-secondary via-dark-2 to-secondary flex items-center justify-center p-4">
            <Head title={title ? `${title} | WIDAS` : 'WIDAS'} />

            <div className="w-full max-w-md">
                <div className="text-center mb-8">
                    <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary mb-4">
                        <Shield className="w-10 h-10 text-white" />
                    </div>
                    <h1 className="text-2xl font-bold text-white">WIDAS</h1>
                    <p className="text-light-3 mt-1">Web-Based Intrusion Detection & Alert System</p>
                </div>

                <div className="bg-white dark:bg-dark-2 rounded-2xl shadow-2xl p-6 sm:p-8">
                    {children}
                </div>
            </div>
        </div>
    );
}
