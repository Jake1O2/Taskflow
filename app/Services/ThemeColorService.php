<?php

namespace App\Services;

use App\Models\User;

class ThemeColorService
{
    /**
     * Generate custom CSS for a specific user based on their preferences.
     *
     * @param int|string $userId
     * @return string
     */
    public function generateCustomCSS($userId): string
    {
        $user = User::find($userId);
        
        // Assuming user has a 'preferences' relationship or json column
        // This is a placeholder implementation
        $primaryColor = $user->preferences['primary_color'] ?? null;

        if (!$primaryColor) {
            return '';
        }

        return ":root {
    --primary: {$primaryColor};
}";
    }
}