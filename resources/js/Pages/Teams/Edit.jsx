import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { route } from '@/lib/route';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

export default function Edit({ auth, team }) {
    const { data, setData, put, processing, errors } = useForm({
        name: team.name || '',
        description: team.description || '',
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('teams.update', team.id));
    };

    return (
        <AuthenticatedLayout auth={auth} header={`Modifier ${team.name}`}>
            <div className="max-w-3xl mx-auto space-y-8">
                <header>
                    <h1 className="text-3xl font-bold text-gray-900">Modifier l'équipe</h1>
                    <p className="text-gray-500 font-medium mt-1">Mettez à jour les informations de votre équipe.</p>
                </header>

                <Card>
                    <form onSubmit={submit}>
                        <CardHeader>
                            <CardTitle>Informations de l'équipe</CardTitle>
                            <CardDescription>Modifiez les informations de votre équipe.</CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name">Nom de l'équipe</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Ex: Équipe Marketing, Studio créatif..."
                                    required
                                    autoFocus
                                />
                                {errors.name && (
                                    <p className="text-red-500 text-xs mt-2 px-1 font-semibold">{errors.name}</p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Description (optionnel)</Label>
                                <Input
                                    id="description"
                                    type="text"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Une brève description de votre équipe..."
                                />
                                {errors.description && (
                                    <p className="text-red-500 text-xs mt-2 px-1 font-semibold">{errors.description}</p>
                                )}
                            </div>
                        </CardContent>
                        <CardFooter className="flex items-center justify-end gap-4 pt-4 border-t border-gray-50">
                            <Link href={route('teams.show', team.id)}>
                                <Button type="button" variant="outline">
                                    Annuler
                                </Button>
                            </Link>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Enregistrement...' : 'Enregistrer les modifications'}
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </AuthenticatedLayout>
    );
}
