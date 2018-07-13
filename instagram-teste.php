<?php
error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/instagram/vendor/autoload.php';

$instagram = \InstagramScraper\Instagram::withCredentials('combosmart', 'Elemidia123@#');
$instagram->login();
$medias = $instagram->getMediasByTag('instafood', 20);

$media = $medias[0];
echo "Media info:\n";
echo "Id: {$media->getId()}\n";
echo "Shotrcode: {$media->getShortCode()}\n";
echo "Created at: {$media->getCreatedTime()}\n";
echo "Caption: {$media->getCaption()}\n";
echo "Number of comments: {$media->getCommentsCount()}\n";
echo "Number of likes: {$media->getLikesCount()}\n";
echo "Get link: {$media->getLink()}\n";
echo "High resolution image: {$media->getImageHighResolutionUrl()}\n";
echo "Media type (video or image): {$media->getType()}\n";

$account = $media->getOwner();
$user = $instagram->getAccountById($account->getId());

echo "Username: {$user->getUsername()}\n";

/*
echo "Account info:\n";
echo "Id: {$account->getId()}\n";
echo "Username: {$user->getUsername()}\n";
echo "Full name: {$account->getFullName()}\n";
echo "Profile pic url: {$account->getProfilePicUrl()}\n";
*/