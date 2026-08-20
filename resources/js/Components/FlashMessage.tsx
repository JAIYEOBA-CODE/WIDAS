import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { CheckCircle, XCircle, AlertTriangle, X } from 'lucide-react';
import { cn } from '@/Utils/cn';

export default function FlashMessage() {
    const { flash } = usePage().props as any;
    const [visible, setVisible] = useState(false);
    const [message, setMessage] = useState('');
    const [type, setType] = useState<'success' | 'error' | 'warning'>('success');

    useEffect(() => {
        if (flash?.success) {
            setMessage(flash.success);
            setType('success');
            setVisible(true);
        } else if (flash?.error) {
            setMessage(flash.error);
            setType('error');
            setVisible(true);
        } else if (flash?.warning) {
            setMessage(flash.warning);
            setType('warning');
            setVisible(true);
        }
    }, [flash]);

    useEffect(() => {
        if (visible) {
            const timer = setTimeout(() => setVisible(false), 5000);
            return () => clearTimeout(timer);
        }
    }, [visible]);

    const icons = {
        success: CheckCircle,
        error: XCircle,
        warning: AlertTriangle,
    };

    const Icon = icons[type];

    return (
        <AnimatePresence>
            {visible && (
                <motion.div
                    initial={{ opacity: 0, y: -50, x: '-50%' }}
                    animate={{ opacity: 1, y: 0, x: '-50%' }}
                    exit={{ opacity: 0, y: -50, x: '-50%' }}
                    className={cn(
                        'fixed top-4 left-1/2 z-[100] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border',
                        type === 'success' && 'bg-success/10 border-success/20 text-success dark:bg-success/20 dark:border-success/30',
                        type === 'error' && 'bg-danger/10 border-danger/20 text-danger dark:bg-danger/20 dark:border-danger/30',
                        type === 'warning' && 'bg-warning/10 border-warning/20 text-warning dark:bg-warning/20 dark:border-warning/30',
                    )}
                >
                    <Icon className="w-5 h-5 flex-shrink-0" />
                    <span className="text-sm font-medium">{message}</span>
                    <button onClick={() => setVisible(false)} className="ml-2 hover:opacity-70">
                        <X className="w-4 h-4" />
                    </button>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
