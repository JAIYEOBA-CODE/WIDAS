import { InertiaPageProps, SystemSetting } from '@/Types';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm } from '@inertiajs/react';
import { Save } from 'lucide-react';

interface SettingsProps extends InertiaPageProps {
    settings: Record<string, SystemSetting[]>;
}

export default function Settings({ settings }: SettingsProps) {
    return (
        <AuthenticatedLayout title="System Settings">
            <div className="space-y-6">
                {Object.entries(settings).map(([group, items]) => (
                    <div key={group} className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 overflow-hidden">
                        <div className="px-6 py-4 bg-dark-2 dark:bg-dark-3">
                            <h3 className="text-sm font-semibold text-white dark:text-light uppercase tracking-wider">{group}</h3>
                        </div>
                        <div className="divide-y divide-light-2 dark:divide-dark-3">
                            {items.map((setting) => (
                                <SettingRow key={setting.id} setting={setting} />
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        </AuthenticatedLayout>
    );
}

function SettingRow({ setting }: { setting: SystemSetting }) {
    const { data, setData, put, processing } = useForm({ value: setting.value });

    const save = () => {
        put(`/settings/${setting.id}`);
    };

    return (
        <div className="px-6 py-4 flex items-center justify-between gap-4">
            <div className="flex-1">
                <p className="text-sm font-medium text-secondary dark:text-light">
                    {setting.key.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')}
                </p>
                {setting.description && (
                    <p className="text-xs text-dark-4 dark:text-light-3 mt-0.5">{setting.description}</p>
                )}
            </div>
            <div className="flex items-center gap-2">
                <input
                    type="text"
                    value={data.value}
                    onChange={e => setData('value', e.target.value)}
                    className="input-field text-sm py-1.5 px-3 w-48"
                    disabled={!setting.is_editable}
                />
                {setting.is_editable && (
                    <button onClick={save} disabled={processing} className="btn-primary text-sm py-1.5 px-3">
                        <Save className="w-4 h-4" />
                    </button>
                )}
            </div>
        </div>
    );
}
