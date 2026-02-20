import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { route } from '@/lib/route';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Info } from 'lucide-react';

export default function Create({ auth }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('teams.store'));
    };

    return (
        <AuthenticatedLayout auth={auth} header="Créer une équipe">
            <div className="max-w-3xl mx-auto space-y-8">
                <header>
                    <h1 className="text-3xl font-bold text-gray-900">Créer une équipe</h1>
                    <p className="text-gray-500 font-medium mt-1">Donnez un nom à votre nouvel espace de collaboration.</p>
                </header>

                <Card>
                    <form onSubmit={submit}>
                        <CardHeader>
                            <CardTitle>Informations de l'équipe</CardTitle>
                            <CardDescription>Remplissez les informations pour créer votre nouvelle équipe.</CardDescription>
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
                            <Link href={route('teams.index')}>
                                <Button type="button" variant="outline">
                                    Annuler
                                </Button>
                            </Link>
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Création...' : 'Créer l\'équipe'}
                            </Button>
                        </CardFooter>
                    </form>
                </Card>

                {/* Help Info */}
                <Alert>
                    <Info className="h-4 w-4" />
                    <AlertDescription>
                        Vous pourrez ajouter des membres à cette équipe une fois qu'elle sera créée en consultant les détails de l'équipe.
                    </AlertDescription>
                </Alert>
            </div>
        </AuthenticatedLayout>
    );
}
