import { Head, Link } from '@inertiajs/react';
import { route } from '@/lib/route';

export default function AuthenticatedLayout({ auth, children, header }) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    return (
        <>
            <Head title={header || 'TaskFlow'} />
            <div className="min-h-screen bg-gray-50">
                {/* Navigation */}
                <nav className="bg-white border-b border-gray-200 shadow-sm">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex">
                                <div className="shrink-0 flex items-center">
                                    <Link href={route('dashboard')} className="font-bold text-xl text-primary">
                                        TaskFlow
                                    </Link>
                                </div>
                                <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <Link
                                        href={route('dashboard')}
                                        className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Dashboard
                                    </Link>
                                    <Link
                                        href={route('projects.index')}
                                        className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Projets
                                    </Link>
                                    <Link
                                        href={route('teams.index')}
                                        className="inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Équipes
                                    </Link>
                                </div>
                            </div>
                            <div className="flex items-center">
                                <Link
                                    href={route('profile')}
                                    className="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                                >
                                    Profil
                                </Link>
                                <form method="POST" action={route('logout')}>
                                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')} />
                                    <button
                                        type="submit"
                                        className="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                                    >
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Page Content */}
                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {children}
                </main>
            </div>
        </>
    );
}
