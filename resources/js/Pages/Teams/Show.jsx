import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, router } from '@inertiajs/react';
import { route } from '@/lib/route';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import { Badge } from '@/Components/ui/badge';
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/Components/ui/alert-dialog';
import { Settings, Users, Mail, Trash2, ArrowLeft } from 'lucide-react';

export default function Show({ auth, team, members, flash }) {
    const isOwner = auth.user.id === team.user_id;

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('teams.addMember', team.id), {
            onSuccess: () => reset(),
        });
    };

    const handleRemoveMember = (userId) => {
        router.delete(route('teams.removeMember', [team.id, userId]));
    };

    return (
        <AuthenticatedLayout auth={auth} header={team.name}>
            <div className="space-y-8">
                {/* Team Header */}
                <Card className="bg-gradient-to-br from-gray-900 to-indigo-950 text-white border-none shadow-2xl shadow-indigo-500/10">
                    <CardContent className="p-8">
                        <div className="flex flex-col md:flex-row justify-between items-center gap-6">
                            <div className="flex items-center gap-6 text-center md:text-left">
                                <div className="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-4xl font-bold shadow-soft">
                                    {team.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h1 className="text-3xl font-bold">{team.name}</h1>
                                    <div className="flex items-center gap-3 mt-2 text-indigo-200">
                                        <Badge variant="outline" className="bg-white/10 text-white border-white/20">
                                            {members.length} Membres
                                        </Badge>
                                        <span className="text-xs opacity-60">Créé par {team.owner?.name}</span>
                                    </div>
                                </div>
                            </div>
                            <div className="flex gap-2">
                                {isOwner && (
                                    <Link href={route('teams.invitations', team.id)}>
                                        <Button variant="outline" className="bg-white/10 hover:bg-white text-white hover:text-gray-900 border-white/20">
                                            <Users className="w-4 h-4 mr-2" />
                                            Invitations
                                        </Button>
                                    </Link>
                                )}
                                <Link href={route('teams.edit', team.id)}>
                                    <Button variant="outline" className="bg-white/10 hover:bg-white text-white hover:text-gray-900 border-white/20">
                                        <Settings className="w-4 h-4 mr-2" />
                                        Paramètres
                                    </Button>
                                </Link>
                                <Link href={route('teams.index')}>
                                    <Button variant="outline" className="bg-white/5 hover:bg-white/10 text-white border-white/10">
                                        <ArrowLeft className="w-4 h-4 mr-2" />
                                        Retour
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Members List */}
                    <div className="lg:col-span-2 space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>Membres de l'équipe</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Utilisateur</TableHead>
                                            <TableHead>Rôle</TableHead>
                                            <TableHead className="text-right">Actions</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {members && members.length > 0 ? (
                                            members.map((member) => {
                                                const isMemberOwner = member.user_id === team.user_id;
                                                return (
                                                    <TableRow key={member.id}>
                                                        <TableCell>
                                                            <div className="flex items-center gap-3">
                                                                <Avatar>
                                                                    <AvatarFallback>
                                                                        {(member.user?.name || '?').charAt(0).toUpperCase()}
                                                                    </AvatarFallback>
                                                                </Avatar>
                                                                <div className="flex flex-col">
                                                                    <span className="font-bold text-gray-900">{member.user?.name}</span>
                                                                    <span className="text-xs text-gray-400">{member.user?.email}</span>
                                                                </div>
                                                            </div>
                                                        </TableCell>
                                                        <TableCell>
                                                            {isMemberOwner ? (
                                                                <Badge variant="default">Propriétaire</Badge>
                                                            ) : (
                                                                <Badge variant="secondary">Membre</Badge>
                                                            )}
                                                        </TableCell>
                                                        <TableCell className="text-right">
                                                            {isOwner && !isMemberOwner && (
                                                                <AlertDialog>
                                                                    <AlertDialogTrigger asChild>
                                                                        <Button variant="ghost" size="icon" className="text-gray-400 hover:text-red-600">
                                                                            <Trash2 className="w-5 h-5" />
                                                                        </Button>
                                                                    </AlertDialogTrigger>
                                                                    <AlertDialogContent>
                                                                        <AlertDialogHeader>
                                                                            <AlertDialogTitle>Retirer ce membre ?</AlertDialogTitle>
                                                                            <AlertDialogDescription>
                                                                                Êtes-vous sûr de vouloir retirer {member.user?.name} de l'équipe ?
                                                                            </AlertDialogDescription>
                                                                        </AlertDialogHeader>
                                                                        <AlertDialogFooter>
                                                                            <AlertDialogCancel>Annuler</AlertDialogCancel>
                                                                            <AlertDialogAction onClick={() => handleRemoveMember(member.user_id)}>
                                                                                Retirer
                                                                            </AlertDialogAction>
                                                                        </AlertDialogFooter>
                                                                    </AlertDialogContent>
                                                                </AlertDialog>
                                                            )}
                                                        </TableCell>
                                                    </TableRow>
                                                );
                                            })
                                        ) : (
                                            <TableRow>
                                                <TableCell colSpan={3} className="text-center text-gray-400 italic py-12">
                                                    Aucun membre
                                                </TableCell>
                                            </TableRow>
                                        )}
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-6">
                        {isOwner && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Ajouter un membre</CardTitle>
                                    <CardDescription>Invitez un utilisateur à rejoindre votre équipe.</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <form onSubmit={submit} className="space-y-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="email">Email de l'utilisateur</Label>
                                            <Input
                                                id="email"
                                                type="email"
                                                value={data.email}
                                                onChange={(e) => setData('email', e.target.value)}
                                                placeholder="email@exemple.com"
                                                required
                                            />
                                            {errors.email && (
                                                <p className="text-red-500 text-xs mt-2 px-1 font-semibold">{errors.email}</p>
                                            )}
                                        </div>
                                        <Button type="submit" disabled={processing} className="w-full">
                                            <Mail className="w-4 h-4 mr-2" />
                                            {processing ? 'Ajout...' : 'Ajouter le membre'}
                                        </Button>
                                    </form>
                                </CardContent>
                            </Card>
                        )}

                        <Card className="bg-gray-50 border-gray-100">
                            <CardHeader>
                                <CardTitle className="text-base">Conseils d'équipe</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-sm text-gray-500 leading-relaxed">
                                    Collaborez plus efficacement en partageant vos projets avec les membres de votre équipe.
                                    Seul le propriétaire peut gérer les membres.
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
