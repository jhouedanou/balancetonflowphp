<?php

namespace App\Helpers;

class YoutubeHelper
{
    /**
     * Extraire l'identifiant YouTube d'une URL
     *
     * @param string $url URL YouTube à parser
     * @return string L'identifiant YouTube ou chaîne vide si non trouvé
     */
    public static function getYoutubeId($url)
    {
        // Format livestream: youtube.com/live/ID
        if (preg_match('/youtube\.com\/live\/([^\/\?\&]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Format standard
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }

    /**
     * Vérifier si une URL est une URL YouTube
     *
     * @param string $url URL à vérifier
     * @return boolean True si c'est une URL YouTube, false sinon
     */
    public static function isYoutubeUrl($url)
    {
        return (bool) preg_match('/(youtube\.com|youtu\.be)/', $url);
    }

    /**
     * Générer l'URL d'intégration pour YouTube
     *
     * @param string $url URL de la vidéo YouTube
     * @return string URL formatée pour l'intégration
     */
    public static function getYoutubeEmbedUrl($url)
    {
        $videoId = self::getYoutubeId($url);
        if (!$videoId) {
            return '';
        }
        
        // Utiliser l'URL d'intégration officielle de YouTube
        return "https://www.youtube.com/embed/{$videoId}?autoplay=1&rel=0";
    }

    /**
     * Vérifier si une URL est une URL TikTok
     *
     * @param string $url URL à vérifier
     * @return boolean True si c'est une URL TikTok, false sinon
     */
    public static function isTikTokUrl($url)
    {
        return (bool) preg_match('/tiktok\.com\//', $url);
    }

    /**
     * Extraire l'ID d'une vidéo TikTok à partir de son URL
     *
     * @param string $url URL de la vidéo TikTok
     * @return string URL formatée pour l'intégration ou URL originale si non reconnue
     */
    public static function getTikTokEmbedUrl($url)
    {
        // Nettoyer l'URL
        $url = trim($url);
        
        // Vérifier si c'est une URL TikTok
        if (!self::isTikTokUrl($url)) {
            return '';
        }
        
        // Format: https://www.tiktok.com/@username/video/1234567890123456789
        if (preg_match('/tiktok\.com\/@([^\/]+)\/video\/(\d+)/', $url, $matches)) {
            return "https://www.tiktok.com/embed/v2/{$matches[2]}";
        }
        
        // Format: https://vm.tiktok.com/ABCDEF/
        if (preg_match('/vm\.tiktok\.com\/([^\/]+)/', $url)) {
            // Dans ce cas, on retournera l'URL complète car nous ne pouvons pas extraire l'ID directement
            return "https://www.tiktok.com/embed/v2/" . urlencode($url);
        }
        
        return '';
    }
}
