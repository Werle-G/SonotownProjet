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

        // On donne un nouveau nom à l'image , on utilise un unique id
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        //  On récupère les infos de l'image
        $audio_infos = getimagesize($audio);

        if($audio_infos === false){
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format de l'image

        switch($audio_infos['mime']){

            case 'audio/mp3':
                $audio_source = imagecreatefrompng($audio);
                break;
            // case 'image/jpeg':
            //     $audio_source = imagecreatefromjpeg($audio);
            //     break;
            // case 'image/webp':
            //     $audio_source = imagecreatefromwebp($audio);
            //     break;
            default:
                throw new Exception('Format d\'image incorrect'); 
        }

        // On recadre l'image 
        // On récupère les dimensions
        // $audio_infos va contenir la largeur et hauteur

        $imageWidth = $audio_infos[0];
        $imageHeight = $audio_infos[1];

        //  On vérifie l'orientation

        //  Revoir tuto sur la manipulation d'images de nouvelle techno

        switch ($imageWidth <=> $imageHeight){
            case -1: // portrait
                $squareSize = $imageWidth;
                $src_x = 0; // on va rester en pleine largeur
                $src_y = ($imageHeight - $squareSize) / 2; // on descend de la hauteur pour croper
                break;
            case 0: // carré
                $squareSize = $imageWidth;
                $src_x = 0; // on va rester en pleine largeur
                $src_y = 0; // on descend de la hauteur pour croper
                break;
            case 1: // paysage
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2; // on descend de la hauteur pour croper
                $src_y = 0; // on va rester en pleine largeur
                break;
        }

        //  On crée une nouvelle image "vièrge"
        // $resized_picture = imagecreatetruecolor($width, $height);

        // imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);


        $path = $this->params->get('audios_directory') . $folder;

        // On crée le dossier de destion si il n'existe pas

        // if(!file_exists($path . '/mini/')){
        //     mkdir($path . '/mini/', 0755, true);
        // }

        //  On stocke l'image recadré
        // imagewebp($resized_picture, $path . '/min/' . $width . 'x' .$height . '-' . $fichier);
        // imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
        //  ça va stocker dans l'image dans le dossier crée

        $audio->move($path . '/', $fichier);

        //  le / pour d'éventuels problème avec windows

        return $fichier;
        // $fichier pour récupérer son nom

    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250){

        if($fichier !== 'default.webp'){

            $success =false;

            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/min/' . $width . 'x' .$height . '-' . $fichier;

            //  verifier si fichier existe

            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }

            //  chemin original du fichier
            $original = $path . '/' . $fichier;

            if(file_exists($original)){
                unlink($mini);
                $success = true;
            }

            return $success;

        }
        return false;
        //  Impossibilité de supprimer le fichier car fichier default.webp
    }

}