<?php


namespace App\Service;


use App\Entity\Participant;

class GenerateToken
{
public function getToken(Participant $participant): Participant{
    $val=  substr($participant->getNom(),-3);
    $val.= substr($participant->getPrenom(),-3);
    $val.= substr($participant->getEmail(),-5);
    $val.= substr($participant->getPassword(),7);
    $participant->setApiToken(md5($val));
    return $participant;
}
}
