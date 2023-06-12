<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        // Pour aller recuperer les parametre mis en palce dans notre fichier services.yaml
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // On donne un nouveau nom l'image 
        $fichier = md5(uniqid(rand(), true)) . '.webp'; 
        
        $picture_infos = getimagesize($picture);

        if($picture_infos === false){
            throw new Exception('format d\'image incorrect');
        }

        // On vérifie le format de l'image
        switch($picture_infos['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;

            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;

            case 'image/webp':   
                $picture_source = imagecreatefromwebp($picture);
                break;

            default:
            //  Si l'image est dans aucun de ces formats alort je veux ce message
                throw new Exception('format d\'image est incorrect');
        }
        // On recadre l'image
        // On recadre les dimasions
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        //  On vérifier l'orientation de limage
        switch ($imageWidth <=> $imageHeight){
            case -1: // Portrait
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = ($imageHeight - $squareSize) / 2;
                break;
            case 0: // Carré
                $squareSize = $imageWidth;
                $src_x = 0;
                $src_y = 0;
                break; 
            case 1: // Paysage
                $squareSize = $imageHeight;
                $src_x = ($imageWidth - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        // On créer une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory') . $folder;

        // On crée le dossier si il n'existe pas
        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/', 0755, true);
        }
        // On stocke l'image recadrée
        imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);

        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder ='', ?int $width = 250, ?int $height = 250 )
    {
        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;

            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if(file_exists($original)){
                unlink($original);
                $success = true;
            }

            return $success;
        }
        return false;
    }
}
