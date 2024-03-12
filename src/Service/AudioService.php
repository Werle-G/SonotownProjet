<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AudioService
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $audio, ?string $folder = '')
    {
        // On donne un nouveau nom à l'audio, on utilise un unique id
        $fichier = md5(uniqid(rand(), true) . '.mp3');

        $path = $this->params->get('audios_directory') . $folder;

        // On vérifie si le dossier de destination existe, sinon, on le crée
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // On déplace le fichier audio vers le dossier de destination
        $audio->move($path . '/', $fichier);

        // On retourne le nom du fichier
        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '')
    {
        if ($fichier !== 'default.mp3') {
            $success = false;

            $path = $this->params->get('audios_directory') . $folder;

            // Chemin complet du fichier audio
            $original = $path . '/' . $fichier;

            // Vérifie si le fichier existe et le supprime
            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }

            return $success;
        }

        return false;
        // Impossibilité de supprimer le fichier car c'est le fichier default.mp3
    }

}