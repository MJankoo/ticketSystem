<?php


namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    private const TARGET_DIRECTORY = 'images';
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function uploadFile(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new FileException('Could not move file temporary dir from to '.$this->getTargetDirectory().$fileName);
        }

        return $fileName;
    }

    public function removeFile($fileName) {
        $filesystem = new Filesystem();
        $fileAddress = $this->getTargetDirectory().$fileName;
        $filesystem->remove($fileAddress);
    }

    private function getTargetDirectory()
    {
        return self::TARGET_DIRECTORY;
    }
}