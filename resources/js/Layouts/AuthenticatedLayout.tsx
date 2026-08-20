import { useState } from 'react';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import Sidebar from './Sidebar';
import Navbar from './Navbar';

interface AuthenticatedLayoutProps {
    children: React.ReactNode;
    title?: string;
}

export default function AuthenticatedLayout({ children, title }: AuthenticatedLayoutProps) {
    const [mobileOpen, setMobileOpen] = useState(false);

    return (
        <div className="min-h-screen bg-light dark:bg-secondary">
            <Head title={title ? `${title} | WIDAS` : 'WIDAS'} />

            <Sidebar
                isMobileOpen={mobileOpen}
                onMobileClose={() => setMobileOpen(false)}
            />

            <div className="lg:pl-64 transition-all duration-300 min-h-screen">
                <Navbar onMenuClick={() => setMobileOpen(true)} />

                <main className="p-3 sm:p-4 lg:p-6">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.3 }}
                    >
                        {title && (
                            <h1 className="text-2xl font-bold text-secondary dark:text-light mb-6">
                                {title}
                            </h1>
                        )}
                        {children}
                    </motion.div>
                </main>
            </div>
        </div>
    );
}
