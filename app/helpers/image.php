<?php

function getExtension(string $name)
{
    return pathinfo($name, PATHINFO_EXTENSION);
}

function getFunctionToCreateFrom(string $extension)
{
    return match ($extension) {
        'png' => 'imagecreatefrompng',
        'jpeg','jpg' => 'imagecreatefromjpeg',
        'gif' => 'imagecreatefromgif'
    };
}

function isFileToUpload($fieldName)
{
    if (!isset($_FILES[$fieldName]) || !isset($_FILES[$fieldName]['name']) || $_FILES[$fieldName]['name'] === '') {
        throw new Exception("Campo {$fieldName} não existe ou não foi escolhida nenhuma foto");
    }
}

function getNewWidthAndHeight(int|float $width, int|float $height, int|float $newWidth, int|float $newHeight):array
{
    $ratio = $width/$height;

    if ($newWidth/$newHeight > $ratio) {
        $newWidth = $newHeight*$ratio;
        $newHeight = $newHeight;
    } else {
        $newHeight = $newWidth/$ratio;
        $newWidth = $newWidth;
    }

    return [$newWidth,$newHeight];
}


function isImage($extension)
{
    $imagesExtensions = ['jpg','jpeg','png','gif'];

    if (!in_array($extension, $imagesExtensions)) {
        throw new Exception("Extension not allowed");
    }
}

function resize(int $newWidth, int $newHeight, string $destination)
{
    $file = $_FILES['file'];

    $extension = getExtension($file['name']);

    isImage($extension);

    [$width, $height] = getimagesize($file['tmp_name']);
    [$newWidth, $newHeight] = getNewWidthAndHeight($width, $height, $newWidth, $newHeight);

    $functionToCreateFrom = getFunctionToCreateFrom($extension);
    $src = $functionToCreateFrom($file['tmp_name']);

    // $src = imagecreatefromgif($file['tmp_name']);
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled(
        $dst,
        $src,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    $path = $destination.DIRECTORY_SEPARATOR.rand().'.'.$extension;

    imagepng($dst, $path, 0);

    return $path;
}
