// Helper pour générer les URLs des routes Laravel
// Note: En production, vous devriez utiliser Ziggy pour une meilleure intégration
export function route(name, params = {}) {
    const routes = {
        'dashboard': '/dashboard',
        'profile': '/profile',
        'logout': '/logout',
        'projects.index': '/projects',
        'teams.index': '/teams',
        'teams.create': '/teams/create',
        'teams.show': (id) => `/teams/${id}`,
        'teams.edit': (id) => `/teams/${id}/edit`,
        'teams.destroy': (id) => `/teams/${id}`,
        'teams.update': (id) => `/teams/${id}`,
        'teams.addMember': (id) => `/teams/${id}/members`,
        'teams.removeMember': (teamId, userId) => `/teams/${teamId}/members/${userId}`,
        'teams.invitations': (id) => `/teams/${id}/invitations`,
    };

    const route = routes[name];
    
    if (typeof route === 'function') {
        if (Array.isArray(params)) {
            return route(...params);
        }
        return route(params);
    }
    
    return route || `#${name}`;
}

// Exposer globalement pour compatibilité
if (typeof window !== 'undefined') {
    window.route = route;
}
