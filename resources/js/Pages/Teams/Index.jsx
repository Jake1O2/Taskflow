import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { route } from '@/lib/route';
import { Card, CardContent } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { CheckCircle2, Plus, Users, Edit, Trash2 } from 'lucide-react';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/Components/ui/alert-dialog';
import { Badge } from '@/Components/ui/badge';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';

export default function Index({ auth, teams, flash }) {
    const teamGradients = [
        'from-blue-400 to-indigo-600',
        'from-violet-400 to-purple-600',
        'from-teal-400 to-cyan-600',
        'from-rose-400 to-pink-600',
        'from-amber-400 to-orange-600',
        'from-emerald-400 to-green-600',
    ];

    const memberColors = ['#0f4c81', '#7c3aed', '#059669', '#d97706', '#dc2626', '#0284c7'];

    const getTeamGradient = (name) => {
        const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
        return teamGradients[hash % teamGradients.length];
    };

    const handleDelete = (teamId) => {
        router.delete(route('teams.destroy', teamId));
    };

    return (
        <AuthenticatedLayout auth={auth} header="Équipes">
            <div className="space-y-8">
                {/* Header */}
                <header className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900 tracking-tight">Équipes</h1>
                        <p className="text-gray-400 font-medium mt-1">Gérez vos membres et collaborations.</p>
                    </div>
                    <Link href={route('teams.create')}>
                        <Button>
                            <Plus className="w-4 h-4" />
                            Nouvelle Équipe
                        </Button>
                    </Link>
                </header>

                {/* Success Message */}
                {flash?.success && (
                    <Alert variant="success">
                        <CheckCircle2 className="h-4 w-4" />
                        <AlertDescription className="font-semibold">{flash.success}</AlertDescription>
                    </Alert>
                )}

                {/* Teams Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {teams && teams.length > 0 ? (
                        teams.map((team) => {
                            const gradient = getTeamGradient(team.name);
                            const memberCount = team.members_count || 0;
                            const displayMembers = (team.members || []).slice(0, 3);
                            const extraCount = Math.max(0, memberCount - 3);

                            return (
                                <Card key={team.id} className="group relative flex flex-col">
                                    <CardContent className="p-6">
                                        {/* Top */}
                                        <div className="flex items-center gap-4 mb-5">
                                            <div
                                                className={`w-12 h-12 rounded-2xl bg-gradient-to-br ${gradient} text-white flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shrink-0`}
                                            >
                                                {team.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h3 className="text-base font-bold text-gray-900 truncate group-hover:text-primary transition-colors">
                                                    {team.name}
                                                </h3>
                                                {team.description && (
                                                    <p className="text-xs text-gray-400 truncate mt-0.5">{team.description}</p>
                                                )}
                                            </div>
                                        </div>

                                        {/* Owner Badge */}
                                        {team.owner && (
                                            <div className="flex items-center gap-2 mb-5">
                                                <Badge variant="warning">
                                                    <Users className="w-3 h-3 mr-1" />
                                                    {team.owner.name}
                                                </Badge>
                                            </div>
                                        )}

                                        {/* Member Avatars */}
                                        <div className="flex items-center gap-3 mb-5 flex-1">
                                            {displayMembers.length > 0 && (
                                                <div className="flex -space-x-2">
                                                    {displayMembers.map((member, i) => {
                                                        const initial = (member.user?.name || member.name || '?').charAt(0).toUpperCase();
                                                        const color = memberColors[i % memberColors.length];
                                                        return (
                                                            <Avatar
                                                                key={member.id || i}
                                                                className="border-2 border-white"
                                                                style={{ backgroundColor: color }}
                                                            >
                                                                <AvatarFallback className="text-white text-[11px]">{initial}</AvatarFallback>
                                                            </Avatar>
                                                        );
                                                    })}
                                                    {extraCount > 0 && (
                                                        <Avatar className="border-2 border-white bg-gray-100">
                                                            <AvatarFallback className="text-gray-500 text-[10px]">+{extraCount}</AvatarFallback>
                                                        </Avatar>
                                                    )}
                                                </div>
                                            )}
                                            <span className="text-xs font-semibold text-gray-400">
                                                {memberCount} membre{memberCount !== 1 ? 's' : ''}
                                            </span>
                                        </div>

                                        {/* Actions */}
                                        <div className="flex items-center gap-2 border-t border-gray-50 pt-5">
                                            <Link
                                                href={route('teams.show', team.id)}
                                                className="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-gray-50 text-gray-700 font-bold text-sm hover:bg-primary/8 hover:text-primary transition-all"
                                            >
                                                Voir détails
                                            </Link>
                                            <Link
                                                href={route('teams.edit', team.id)}
                                                className="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                                title="Modifier"
                                            >
                                                <Edit className="w-5 h-5" />
                                            </Link>
                                            <AlertDialog>
                                                <AlertDialogTrigger asChild>
                                                    <button
                                                        className="p-2.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                                        title="Supprimer"
                                                    >
                                                        <Trash2 className="w-5 h-5" />
                                                    </button>
                                                </AlertDialogTrigger>
                                                <AlertDialogContent>
                                                    <AlertDialogHeader>
                                                        <AlertDialogTitle>Confirmer la suppression</AlertDialogTitle>
                                                        <AlertDialogDescription>
                                                            Êtes-vous sûr de vouloir supprimer l'équipe "{team.name}" ? Cette action est irréversible.
                                                        </AlertDialogDescription>
                                                    </AlertDialogHeader>
                                                    <AlertDialogFooter>
                                                        <AlertDialogCancel>Annuler</AlertDialogCancel>
                                                        <AlertDialogAction onClick={() => handleDelete(team.id)}>
                                                            Supprimer
                                                        </AlertDialogAction>
                                                    </AlertDialogFooter>
                                                </AlertDialogContent>
                                            </AlertDialog>
                                        </div>
                                    </CardContent>
                                </Card>
                            );
                        })
                    ) : (
                        <div className="col-span-full">
                            <Card>
                                <CardContent className="p-12 text-center">
                                    <div className="w-16 h-16 rounded-3xl bg-violet-50 flex items-center justify-center mb-4 mx-auto text-violet-300">
                                        <Users className="w-8 h-8" />
                                    </div>
                                    <p className="text-gray-600 font-bold mb-1">Aucune équipe disponible</p>
                                    <p className="text-gray-400 text-sm mb-4">Commencez par en créer une pour collaborer.</p>
                                    <Link href={route('teams.create')}>
                                        <Button size="sm">Créer une équipe</Button>
                                    </Link>
                                </CardContent>
                            </Card>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
